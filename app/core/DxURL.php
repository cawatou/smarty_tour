<?php

DxFactory::import('DxCommand');

abstract class DxURL
{
    const DX_URL_ERROR_BASE    = 700;
    const DX_URL_ERROR_PROCESS = 701;

    /** @var array */
    protected $params = array();

    /** @var string */
    protected $host = '';

    /** @var string */
    protected $protocol = '';

    /** @var string */
    protected $base = '';

    /**
     * @abstract
     * @return DxCommand
     */
    public abstract function getRequestedCommand();

    /**
     * @param array $params
     * @return DxURL
     */
    public function __construct(array $params)
    {
        $this->params = $params;

        $this->host = empty($this->params['uri']['host']) ? '' : trim(str_replace(array('http://', 'https://'), '', $this->params['uri']['host']), '/');
        $this->base = empty($this->params['uri']['base']) ? '' : '/' . trim($this->params['uri']['base'], '/');
        if (DxApp::isCli()) {
            $this->protocol = 'http://';
        } else {
            $this->protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';

            if (!empty($this->params['uri']['auto_detect']) || empty($this->host)) {
                $this->host = $_SERVER['HTTP_HOST'];
            }
        }
    }

    /**
     * @static
     * @param null|string $url
     */
    public static function redirect($url = null)
    {
        if (is_null($url)) {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        header("HTTP/1.1 301 Moved Permanently");
        header("Location: {$url}");

        DxApp::terminate();
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param string $cmd
     * @return string
     */
    public function url($cmd)
    {
        return $this->protocol . $this->host . $this->base . "/?cmd={$cmd}";
    }

    /**
     * @return array
     */
    protected function analysisRequest()
    {
        return array(empty($_REQUEST['cmd']) ? '' : $_REQUEST['cmd'], null);
    }

    /**
     * @return array
     * @throws DxException
     */
    protected function analysisCli()
    {
        if (empty($_SERVER['argv'])) {
            throw new DxException('Controller not found', self::DX_URL_ERROR_PROCESS);
        }

        $arguments = array();
        foreach ($_SERVER['argv'] as $arg) {
            if (strpos($arg, '=') !== false) {
                $_arg                = explode('=', $arg);
                $arguments[$_arg[0]] = $_arg[1];
            }
        }

        $cmd = empty($arguments['cmd']) ? '' : $arguments['cmd'];

        return array($cmd, $arguments);
    }

    /**
     * @param string $path
     * @return string
     */
    public function js($path)
    {
        return $this->getStatic('js', $path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function css($path)
    {
        return $this->getStatic('css', $path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function img($path)
    {
        return $this->getStatic('img', $path);
    }

    /**
     * @param string $dir
     * @param string $path
     * @return string
     */
    protected function getStatic($dir, $path)
    {
        $path = trim($path, '/');
        $dir  = empty($this->params['static'][$dir]) ? '' : '/' . trim($this->params['static'][$dir], '/');

        //return $this->protocol . $this->host . $this->base . $dir . '/' . $path;
        $path = $this->host . $this->base . $dir . '/' . $path;
        while(preg_match('/\w+\/\.\.\//', $path)){
            $path = preg_replace('/\w+\/\.\.\//', '', $path);
        }
        return $this->protocol . $path;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}