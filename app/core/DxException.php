<?php

class DxException extends Exception
{
    protected $cause;
    private $trace;

    /**
     * Supported signatures:
     *  - DxException(string $message);
     *  - DxException(string $message, int $code);
     *  - DxException(string $message, Exception $cause);
     *  - DxException(string $message, int $code, Exception $cause);
     * @param string exception message
     * @param int|Exception|null exception cause or code or null
     * @param int|Exception|null exception cause or code or null
     */
    public function __construct($message, $p2 = null, $p3 = null)
    {
        $code     = null;
        $previous = null;

        if (is_object($p2) && $p2 instanceof Exception) {
            $previous = $p2;
            $code     = (is_int($p3) || is_null($p3)) ? $p3 : null;
        } elseif (is_int($p2) || is_null($p2)) {
            $code     = $p2;
            $previous = (is_object($p3) && $p3 instanceof Exception) ? $p3 : null;
        }

        $this->cause = $previous;

        parent::__construct($message, $code);
    }

    /**
     * Return specific error information that can be used for more detailed
     * error messages or translation.
     *
     * This method may be overridden in child exception classes in order
     * to add functionality not present in PEAR_Exception and is a placeholder
     * to define API
     *
     * The returned array must be an associative array of parameter => value like so:
     * <pre>
     * array('name' => $name, 'context' => array(...))
     * </pre>
     * @return array
     */
    public function getErrorData()
    {
        return array();
    }

    /**
     * Returns the exception that caused this exception to be thrown
     * @access public
     * @return Exception|array The context of the exception
     */
    public function getCause()
    {
        return $this->cause;
    }

    public function getOriginalCause()
    {
        $c = $this;
        while (isset($c->cause) && is_object($c->cause)) {
            $c = $c->cause;
        }
        return $c;
    }

    /**
     * Function must be public to call on caused exceptions
     * @param array
     */
    public function getCauseMessage(&$causes)
    {
        $trace = $this->getTraceSafe();
        $cause = array('class'   => get_class($this),
                       'message' => $this->message,
                       'code' => $this->getCode(),
                       'file' => 'unknown',
                       'line' => 'unknown');
        if (isset($trace[0])) {
            if (isset($trace[0]['file'])) {
                $cause['file'] = $trace[0]['file'];
                $cause['line'] = $trace[0]['line'];
            }
        }
        $causes[] = $cause;
        if ($this->cause instanceof DxException) {
            $this->cause->getCauseMessage($causes);
        } elseif ($this->cause instanceof Exception) {
            $causes[] = array('class'   => get_class($this->cause),
                              'message' => $this->cause->getMessage(),
                              'code' => $this->cause->getCode(),
                              'file' => $this->cause->getFile(),
                              'line' => $this->cause->getLine());
        }
    }

    public function getTraceSafe()
    {
        if (!isset($this->trace)) {
            $this->trace = $this->getTrace();
            if (empty($this->trace)) {
                $backtrace = debug_backtrace();
                $this->trace = array($backtrace[count($backtrace)-1]);
            }
        }

        return $this->trace;
    }

    public function getErrorClass()
    {
        $trace = $this->getTraceSafe();
        return $trace[0]['class'];
    }

    public function getErrorMethod()
    {
        $trace = $this->getTraceSafe();
        return $trace[0]['function'];
    }

    public function __toString()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            return $this->_wrapper($this->toHtml());
        }

        return $this->toText();
    }

    public function toHtml()
    {
        $causes = array();
        $this->getCauseMessage($causes);
        $causes = array_reverse($causes);

        $html = '<div class="error"><table border="0" cellspacing="0" cellpadding="3">';
        if (DxApp::getEnv() == DxApp::ENV_PRODUCTION) {
            foreach ($causes as $i => $cause) {
                $html .= '<tr><td><strong>Sorry, error</strong>: </td><td>[' . $cause['code'] . '] ' . htmlspecialchars($cause['message']) . "</td></tr>\n";
                break;
            }
        } else {
            foreach ($causes as $i => $cause) {
                $html .= '<tr><td>'
                    . str_repeat('-', $i) . ' <strong>' . $cause['class'] . '</strong>: '
                    . '</td><td>[' . $cause['code'] . '] ' . htmlspecialchars($cause['message']) . ' in <strong>' . $cause['file'] . '</strong> '
                    . 'on line <strong>' . $cause['line'] . '</strong>'
                    . "</td></tr>\n";
            }
        }
        $html .= '</table></div>';

        if (DxApp::getEnv() == DxApp::ENV_PRODUCTION) {
            return $html;
        }

        $original_cause = $this->getOriginalCause();
        if ($original_cause instanceof DxException) {
            $trace = $original_cause->getTraceSafe();
        } else {
            $trace = $original_cause->getTrace();
        }

        $html .=  '<h2>Exception trace</h2><table class="trace" cellspacing="0" cellpadding="3">';
        $html .= '<tr><th>#</th><th>Function</th><th>Location</th></tr>';
        foreach ($trace as $k => $v) {
            $html .= '<tr><td>' . $k . '</td>'
                   . '<td>';
            if (!empty($v['class'])) {
                $html .= $v['class'] . $v['type'];
            }
            $html .= $v['function'];
            $args = array();
            if (!empty($v['args'])) {
                foreach ($v['args'] as $arg) {
                    if (is_null($arg)) $args[] = 'null';
                    elseif (is_array($arg)) $args[] = 'Array';
                    elseif (is_object($arg)) $args[] = 'Object('.get_class($arg).')';
                    elseif (is_bool($arg)) $args[] = $arg ? 'true' : 'false';
                    elseif (is_int($arg) || is_double($arg)) $args[] = $arg;
                    else {
                        $arg = (string)$arg;
                        $str = htmlspecialchars(substr($arg, 0, 16));
                        if (strlen($arg) > 16) $str .= '&hellip;';
                        $args[] = "'" . $str . "'";
                    }
                }
            }
            $html .= '(' . implode(', ',$args) . ')'
                   . '</td>'
                   . '<td>' . (isset($v['file']) ? $v['file'] : 'unknown')
                   . ':' . (isset($v['line']) ? $v['line'] : 'unknown')
                   . '</td></tr>';
        }

        $html .= '</table>';
        return $html;
    }

    public function toText()
    {
        $causes = array();
        $this->getCauseMessage($causes);
        $causeMsg = '';
        foreach ($causes as $i => $cause) {
            $causeMsg .= str_repeat(' ', $i) . $cause['class'] . ': '
                   . $cause['message'] . ' in ' . $cause['file']
                   . ' on line ' . $cause['line'] . "\n";
        }
        return $causeMsg . $this->getTraceAsString();
    }

    protected function _wrapper($html = null)
    {
        $html =
            '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
            <head>
                <title>Internal Error</title>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

                <style type="text/css">
                    html, body, h1, h2 { margin: 0; padding: 0; }
                    body { font-family: Arial; font-size: 12px; color: #000; background: #FFF; }
                    h1 { background: #000; color: #FFF; padding: 10px; }
                    h2 { padding: 20px 0 15px 20px; }

                    .error { margin: 0 0 10px 0; padding: 10px; background: #FF9999; }
                    .trace { border-collapse: collapse; margin-left: 20px; }
                        .trace th { background: #CCC; }
                        .trace td, .trace th { border: 1px solid #505050; padding: 5px; }
                </style>
            </head>
            <body>
                <h1>DX: Internal Error</h1>' . $html . '
            </body>
            </html>';
        return $html;
    }
}