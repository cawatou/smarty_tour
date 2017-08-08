<?php

class DxAppContext
{
    const DX_APPCONTEXT_ERROR_BASE = 400;

    /** @var string */
    private $content_type = 'text/html';

    /** @var string */
    private $charset = 'utf-8';

    /** @var array */
    private $headers = array();

    /** @var DxCommand */
    private $current_command;

    /** @var null|DxUser */
    private $current_user = null;

    /**
     * Magic salt to add to the cookie
     * @var string
     */
    private $cookie_salt = 'dx_cookie';

    /** @var array */
    protected $data = array();

    public function __construct(array $params)
    {
        empty($params['content_type']) ? false : $this->setContentType($params['content_type']);
        empty($params['charset']) ? false : $this->setCharset($params['charset']);
    }

    /**
     * @param string $content_type
     */
    public function setContentType($content_type)
    {
        $this->content_type = $content_type;
    }

    /**
     * @param string $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * @param DxCommand $current_command
     */
    public function setCurrentCommand(DxCommand $current_command)
    {
        $this->current_command = $current_command;
    }

    /**
     * @param null|DxUser $current_user
     */
    public function setCurrentUser(DxUser $current_user = null)
    {
        $this->current_user = $current_user;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param string $header
     * @return DxAppContext
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @return DxCommand
     */
    public function getCurrentCommand()
    {
        return $this->current_command;
    }

    /**
     * @return null|DxUser
     */
    public function getCurrentUser()
    {
        return $this->current_user;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return void
     */
    public function sendHeaders()
    {
        header(sprintf('Content-type: %s; charset=%s', $this->getContentType(), $this->getCharset()));

        foreach ($this->getHeaders() as $header) {
            header($header);
        }
    }

    /**
     * @param string|array $param
     * @param string|null  $value
     * @return DxAppContext
     */
    public function setData($param, $value = null)
    {
        if (is_array($param)) {
            foreach ($param as $key => $val) {
                $this->data[$key] = $val;
            }
        } else {
            $this->data[$param] = $value;
        }

        return $this;
    }

    /**
     * @param      $key
     * @param null $default
     * @return string|null
     */
    public function getData($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * @param $name
     * @param $value
     * @return string
     */
    public function saltCookie($name, $value)
    {
        // Determine the user agent
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : 'unknown';

        return sha1($agent . $name . $value . $this->cookie_salt);
    }

    /**
     * @param $name
     * @param $value
     * @param int $expiration
     * @return bool
     */
    public function setCookie($name, $value, $expiration = 0)
    {
        if ($expiration !== 0) {
            // The expiration is expected to be a UNIX timestamp
            $expiration += time();
        }

        /** @var $secure Only transmit cookies over secure connections  */
        $secure = false;
        /** @var $httponly Only transmit cookies over HTTP, disabling Javascript access  */
        $httponly = false;
        /** @var $path Restrict the path that the cookie is available to */
        $path = '/';
        /** @var $domain Restrict the domain that the cookie is available to */
        $domain = null;

        // Add the salt to the cookie value
        $value = $this->saltCookie($name, $value).'~'.$value;

        return setcookie($name, $value, $expiration, $path, $domain, $secure, $httponly);
    }

    /**
     * @param $key
     * @param null $default
     * @return null
     */
    public function getCookie($key, $default = null)
    {
        if (!isset($_COOKIE[$key])) {
            // The cookie does not exist
            return $default;
        }

        // Get the cookie value
        $cookie = $_COOKIE[$key];

        // Find the position of the split between salt and contents
        $split = strlen($this->saltCookie($key, null));

        if (isset($cookie[$split]) && $cookie[$split] === '~') {
            // Separate the salt and the value
            list($hash, $value) = explode('~', $cookie, 2);

            if ($this->saltCookie($key, $value) === $hash) {
                // Cookie signature is valid
                return $value;
            }

            // The cookie signature is invalid, delete it
            $this->deleteCookie($key);
        }

        return $default;
    }

    /**
     * @param $name
     * @return bool
     */
    public function deleteCookie($name)
    {
        unset($_COOKIE[$name]);
        return $this->setCookie($name, NULL, -86400);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}