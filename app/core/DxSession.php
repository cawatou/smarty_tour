<?php

abstract class DxSession
{
    const DX_SESSION_ERROR_BASE    = 800;
    const DX_SESSION_ERROR_CONFIG  = 801;
    const DX_SESSION_ERROR_START   = 802;
    const DX_SESSION_ERROR_PROCESS = 803;

    /** @var array */
    protected $params = array();

    /** @var array */
    protected $defaults = array(
        'name'       => 'SID',
        'lifetime'   => 86400,
        'gc_divisor' => 1000
    );

    /**
     * @abstract
     * @return void
     */
    abstract protected function start();

    /**
     * @param array $params
     * @return DxSession
     */
    public function __construct(array $params)
    {
        $this->setParams($params);
        $this->start();
    }

    /**
     * @param array $params
     * @return void
     * @throws DxException
     */
    protected function setParams(array $params)
    {
        $this->params               = $params;
        $this->params['gc_divisor'] = isset($params['gc_divisor']) && is_numeric($params['gc_divisor']) ? $params['gc_divisor'] : $this->defaults['gc_divisor'];
        $this->params['name']       = !empty($params['name']) ? $params['name'] : $this->defaults['name'];
        $this->params['lifetime']   = isset($params['lifetime']) && is_numeric($params['lifetime']) ? $params['lifetime'] : $this->defaults['lifetime'];

        // having all necessary values - setting them up        
        session_name($this->params['name']);
        session_set_cookie_params($this->params['lifetime'], $this->getPath(), $this->getDomain());
        if (ini_set('session.gc_maxlifetime', $this->params['lifetime']) === false) {
            throw new DxException('Problem while setting gc_maxlifetime session parameter', self::DX_SESSION_ERROR_CONFIG);
        }

        if (ini_set('session.gc_probability', 1) === false || ini_set('session.gc_divisor', $this->params['gc_divisor']) === false) {
            throw new DxException('Problem while setting gc_divisor session parameter', self::DX_SESSION_ERROR_CONFIG);
        }

        if (array_key_exists('p3p_string', $this->params) && !empty($this->params['p3p_string'])) {
            // setting p3p header if it is defined in configuration
            header("p3p: CP='{$this->params['p3p_string']}'");
        }
    }

    /**
     * @return mixed
     */
    protected function getDomain()
    {
        return parse_url('http://' . str_replace('http://', '', $_SERVER['HTTP_HOST']), PHP_URL_HOST);
    }

    /**
     * @return string
     */
    protected function getPath()
    {
        if (array_key_exists('path', $this->params)) {
            return $this->params['path'];
        }

        $res = null;
        preg_match('~^(.+?)[^\/]+$~', $_SERVER['SCRIPT_NAME'], $res);
        return $res[1];
    }
}