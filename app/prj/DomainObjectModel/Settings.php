<?php

/**
 * @method  int getId()
 * @method  string getTitle()
 * @method  string getKey()
 * @method  string getValString()
 * @method  int getValInt()
 * @method  int getValBool()
 * @method  string getValText()
 * @method  string getType()
 * @method  string getGroup()
 * @method  int getQnt()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 *
 * @method  setId(int $arg)
 * @method  setTitle(string $arg)
 * @method  setKey(string $arg)
 * @method  setValString(string $arg)
 * @method  setValInt(int $arg)
 * @method  setValBool(int $arg)
 * @method  setValText(string $arg)
 * @method  setType(string $arg)
 * @method  setGroup(string $arg)
 * @method  setQnt(int $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Settings extends DomainObjectModel_BaseSettings
{
    /** @var string */
    protected $field_prefix = 'settings';

    /** @var array */
    protected static $types = array(
        'COMMON'   => 'Общие настройки',
        'BANNER'   => 'Баннер',
        'SEO'      => 'SEO',
        'BUY_TOUR' => 'Покупка туров',
    );

    /**
     * @static
     * @return array
     */
    public static function getTypes()
    {
        return self::$types;
    }
}
