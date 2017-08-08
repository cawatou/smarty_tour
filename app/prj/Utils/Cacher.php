<?php

DxFactory::import('Utils');

class Utils_Cacher extends Utils
{
    const ERROR_BASE = 2000;
    const ERROR_INIT = 2001;

    protected $cache_alias      = null;
    protected $cache_expiration = null;
    protected $data_cache       = null;

    /**
     * @param $cache_alias
     * @param null $expiration
     */
    public function __construct($cache_alias, $expiration = null)
    {
        if (empty($cache_alias)) {
            throw DxException('Data cache alias is empty', self::ERROR_INIT);
        }

        $this->cache_alias = $cache_alias;
        if (!is_null($expiration)) {
            $this->cache_expiration = $expiration;
        }

        /** @var $q DomainObjectQuery_DataCache */
        $q = DxFactory::getInstance('DomainObjectQuery_DataCache');
        $this->data_cache = $q->findByAlias($this->cache_alias);
        if (is_null($this->data_cache)) {
            /** @var $dc DomainObjectModel_DataCache */
            $dc = DxFactory::getInstance('DomainObjectModel_DataCache');
            $dc->setAlias($this->cache_alias);
            $dc->save();
            $this->data_cache = $dc;
        }
    }

    /**
     * @param $info
     */
    public function setCache($info)
    {
        $info['__check'] = time();
        $this->data_cache->setInfo($info);
        $this->data_cache->save();
    }

    /**
     * @return null|string
     */
    public function getCache()
    {
        if (!is_null($this->cache_expiration)) {
            $now = DxFactory::getInstance('DxDateTime')->toUTC();
            $diff = $now->difference($this->data_cache->getUpdated());
            if ($diff > $this->cache_expiration) {
                return null;
            }
        }

        $info = $this->data_cache->getInfo();
        unset($info['__check']);
        return $info;
    }

    /**
     * @return void
     */
    public function destroyCache()
    {
        $this->data_cache->remove();
        $this->data_cache->save();
    }
}