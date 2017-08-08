<?php

/**
 * @method  int getId()
 * @method  string getAlias()
 * @method  string getInfo()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 * 
 * @method  setId(int $arg)
 * @method  setAlias(string $arg)
 * @method  setInfo(string $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_DataCache extends DomainObjectModel_BaseDataCache
{
    /** @var string */
    protected $field_prefix = 'data_cache';

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->hasColumn('data_cache_info', 'array');
    }
}
