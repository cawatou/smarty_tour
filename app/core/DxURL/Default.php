<?php

DxFactory::import('DxURL');

class DxURL_Default extends DxURL
{
    /**
     * @return array
     */
    protected function analysisRequest()
    {
        list($cmd, $args) = parent::analysisRequest();

        if (!empty($cmd)) {
            return array($cmd, $args);
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $request = preg_split('~\?~', $_SERVER['REQUEST_URI']);
            $request = $request[0];
        } elseif (!empty($_SERVER['PATH_INFO'])) {
            $request = $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['PHP_SELF'])) {
            $request = str_replace('index.php', '', $_SERVER['PHP_SELF']);
        } else {
            throw new DxException('Unable to detect the URI using REQUEST_URI, PATH_INFO, PHP_SELF', self::DX_URL_ERROR_PROCESS);
        }

        $params = DxApp::config('url');

        if (!empty($params['uri']['base'])) {
            $request = preg_replace('~^' . preg_quote($params['uri']['base']) . '~i', '', $request);
        }

        $request = trim($request, '/');
        $cmd  = null;
        $args = array();

        if (!empty($request) && !empty($params['routes'])) {
            $args['request'] = '/' . mb_strtolower(urldecode($request));
            foreach ($params['routes'] as $route) {
                $rule = '~^' . str_replace(array(':any', ':num'), array('[^,^.^/^?]', '[0-9]'), $route['rule']) . '$~i';
                if (preg_match($rule, $request, $matches)) {
                    if (count($matches) > 1) {
                        $arg_search = $arg_replace = array();
                        for ($i = 1; $i < count($matches); $i++) {
                            $arg_search[]  = '$' . $i;
                            $arg_replace[] = $matches[$i];
                        }
                    }

                    foreach ($route as $key => $value) {
                        if ($key == 'rule') continue;
                        if (count($matches) > 1) {
                            $args[$key] = str_replace($arg_search, $arg_replace, $value);
                        } else {
                            $args[$key] = $value;
                        }
                        $args[$key] = preg_replace('~(\$[0-9]+)~i', '', $args[$key]);
                    }
                    break;
                }
            }

            $cmd = !empty($args['cmd']) ? $args['cmd'] : null;
        }

        return array($cmd, $args);
    }

    /**
     * @return DxCommand
     */
    public function getRequestedCommand()
    {
        if (DxApp::isCli()) {
            list($cmd, $args) = $this->analysisCli();
        } else {
            list($cmd, $args) = $this->analysisRequest();
        }

        return DxFactory::getSingleton('DxCommand', array($cmd, $args));
    }
}