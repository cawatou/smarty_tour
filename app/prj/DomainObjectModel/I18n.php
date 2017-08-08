<?php

/**
 * @method  int getId()
 * @method  string getSourceString()
 * @method  string getSourceTag()
 * @method  string getSourceLocale()
 * @method  string getTargetString()
 * @method  string getTargetLocale()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 * 
 * @method  setId(int $arg)
 * @method  setSourceString(string $arg)
 * @method  setSourceTag(string $arg)
 * @method  setSourceLocale(string $arg)
 * @method  setTargetString(string $arg)
 * @method  setTargetLocale(string $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_I18n extends DomainObjectModel_BaseI18n
{
    /** @var string */
    protected $field_prefix = 'i18n';
}
