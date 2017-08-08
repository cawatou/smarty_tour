<?php

/**
 * @method  int getId()
 * @method  string getRequest()
 * @method  string getTitle()
 * @method  string getKeywords()
 * @method  string getDescription()
 * @method  string getStatus()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 * 
 * @method  setId(int $arg)
 * @method  setRequest(string $arg)
 * @method  setTitle(string $arg)
 * @method  setKeywords(string $arg)
 * @method  setDescription(string $arg)
 * @method  setStatus(string $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Seo extends DomainObjectModel_BaseSeo
{
    /** @var string */
    protected $field_prefix = 'seo';

    protected function validateField($field = null)
    {
        if (is_null($field) || $field == 'seo_request') {
            if (empty($this->seo_request)) {
                throw new DxException("Invalid 'seo_request'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }
}
