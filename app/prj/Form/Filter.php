<?php

DxFactory::import('Form');

abstract class Form_Filter extends Form
{
    /** @var string */
    protected $scope_id = '__FILTER__';

    const FILTER_CLEAR         = '_clear';
    const FILTER_SEARCH_PARAMS = 's';
    const FILTER_ORDER_PARAMS  = 'o';

    /** @var null|array */
    protected $persistent_store = null;

    /** @var bool */
    protected $store_in_session = false;

    public function __construct($form_id = null, $store_in_session = false)
    {
        parent::__construct($form_id);

        $this->setStoreInSession($store_in_session);
        $this->initPersistentStore();
    }

    /**
     * @param string $filter_id
     * @param string $name
     * @return string
     */
    public static function encodeSearchName($filter_id, $name)
    {
        return self::encodeName($filter_id, self::FILTER_SEARCH_PARAMS) . "[{$name}]";
    }

    /**
     * @param string $filter_id
     * @param string $name
     * @return string
     */
    public function encodeOrderName($filter_id, $name)
    {
        return self::encodeName($filter_id, self::FILTER_ORDER_PARAMS) . "[{$name}]";
    }

    /**
     * @param string $name
     * @return string
     */
    public function encodeSearch($name)
    {
        return $this->encode(self::FILTER_SEARCH_PARAMS) . "[{$name}]";
    }

    /**
     * @param string $name
     * @return string
     */
    public function encodeOrder($name)
    {
        return $this->encode(self::FILTER_ORDER_PARAMS) . "[{$name}]";
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->getPersistentStore();
    }

    /**
     * @return string
     */
    public function getParametersAsURL()
    {
        $s1 = $this->getSearchParametersAsURL();
        $s2 = $this->getOrderParametersAsURL();

        $res = array();

        if ($s1) {
            $res[] = $s1;
        }

        if ($s2) {
            $res[] = $s2;
        }

        return implode('&', $res);
    }


    /**
     * @return string
     */
    public function getSearchParametersAsURL()
    {
        $ps  = $this->getPersistentStore();
        $res = array();

        if (!isset($ps[self::FILTER_SEARCH_PARAMS])) {
            return '';
        }

        foreach ($ps[self::FILTER_SEARCH_PARAMS] as $k => $v) {
            $res[$this->encodeSearch($k)] = $v;
        }

        return urldecode(http_build_query($res));
    }

    /**
     * @return string
     */
    public function getOrderParametersAsUrl()
    {
        $ps  = $this->getPersistentStore();
        $res = array();

        if (!isset($ps[self::FILTER_ORDER_PARAMS])) {
            return '';
        }

        foreach ($ps[self::FILTER_ORDER_PARAMS] as $k => $v) {
            $res[$this->encodeOrder($k)] = $v;
        }

        return urldecode(http_build_query($res));
    }

    /**
     * @return array
     */
    public function getSearchParameters()
    {
        $params = $this->getParameters();

        return $params[self::FILTER_SEARCH_PARAMS];
    }

    /**
     * @return array
     */
    public function getOrderParameters()
    {
        $params = $this->getParameters();

        return $params[self::FILTER_ORDER_PARAMS];
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        $params = $this->getParameters();

        if ($this->isSearch() ||
            $this->isOrder() ||
            $params[self::FILTER_SEARCH_PARAMS] != $this->getDefaultSearchParams() ||
            $params[self::FILTER_ORDER_PARAMS] != $this->getDefaultOrderParams()
            ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isSearch()
    {
        $data =& $this->env_data['_REQUEST'];
        if (array_key_exists(self::FILTER_SEARCH_PARAMS, $data) && $data[self::FILTER_SEARCH_PARAMS] != $this->getDefaultSearchParams()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isOrder()
    {
        $data =& $this->env_data['_REQUEST'];
        if (array_key_exists(self::FILTER_ORDER_PARAMS, $data) && $data[self::FILTER_ORDER_PARAMS] != $this->getDefaultOrderParams()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isClear()
    {
        return isset($this->env_data['_REQUEST'][self::FILTER_CLEAR]);
    }

    /**
     * @return array
     */
    protected function getDefaultSearchParams()
    {
        return array();
    }

    /**
     * @return array
     */
    protected function getDefaultOrderParams()
    {
        return array();
    }

    /**
     * @return bool
     */
    protected function process()
    {
        $ps =& $this->getPersistentStore();

        if ($this->isClear()) {
            $this->clearPersistentStore();
        }

        if ($this->isSearch()) {
            $ps[self::FILTER_SEARCH_PARAMS] = empty($this->env_data['_REQUEST'][self::FILTER_SEARCH_PARAMS]) ?
                $this->getDefaultSearchParams() : $this->env_data['_REQUEST'][self::FILTER_SEARCH_PARAMS];
        }

        if ($this->isOrder()) {
            $ps[self::FILTER_ORDER_PARAMS] = empty($this->env_data['_REQUEST'][self::FILTER_ORDER_PARAMS]) ?
                $this->getDefaultOrderParams() : $this->env_data['_REQUEST'][self::FILTER_ORDER_PARAMS];
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function isSubmited()
    {
        return true;
    }

    /**
     * @param bool $store_in_session
     */
    protected function setStoreInSession($store_in_session)
    {
        $this->store_in_session = $store_in_session;
    }

    /**
     * @return bool
     */
    protected function isStoreInSession()
    {
        return $this->store_in_session;
    }

    /**
     * @return string
     */
    protected function getScopeId()
    {
        return $this->scope_id;
    }

    /**
     * @return void
     */
    protected function clearPersistentStore()
    {
        $this->persistent_store = array();
        $this->initPersistentStore();

        DxURL::redirect($this->getUrl() ? $this->getUrl() : null);
    }

    /**
     * @return void
     */
    protected function initPersistentStore()
    {
        if (!$this->isStoreInSession()) {
            $this->persistent_store = array();
        } else {
            $scope_id = $this->getScopeId();

            if (!array_key_exists($scope_id, $_SESSION)) {
                $_SESSION[$scope_id] = array();
            }

            if (!array_key_exists($this->getId(), $_SESSION[$scope_id])) {
                $_SESSION[$scope_id][$this->getId()] = array();
            }

            $this->persistent_store =& $_SESSION[$scope_id][$this->getId()];
        }

        if (!array_key_exists(self::FILTER_SEARCH_PARAMS, $this->persistent_store)) {
            $this->persistent_store[self::FILTER_SEARCH_PARAMS] = $this->getDefaultSearchParams();
        }

        if (!array_key_exists(self::FILTER_ORDER_PARAMS, $this->persistent_store)) {
            $this->persistent_store[self::FILTER_ORDER_PARAMS] = $this->getDefaultOrderParams();
        }
    }


    /**
     * @return array
     */
    protected function &getPersistentStore()
    {
        return $this->persistent_store;
    }
}