<?php

/**
 * @method  int getId()
 * @method  string getAlias()
 * @method  string getTitle()
 * @method  string getContent()
 * @method  int getIsWysiwyg()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 *
 * @method  setId(int $arg)
 * @method  setAlias(string $arg)
 * @method  setTitle(string $arg)
 * @method  setContent(string $arg)
 * @method  setIsWysiwyg(int $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Block extends DomainObjectModel_BaseBlock
{
    /** @var string */
    protected $field_prefix = 'block';

    /** @var array */
    static $cache = array();

    /** @var array */
    protected static $categories = array(
        'COMMON' => array(
            'title' => 'Общие',
        ),
        'MAIN' => array(
            'title' => 'Главная страница',
        ),
        'SIDEBAR_RIGHT' => array(
            'title' => 'Правая колонка',
        ),
    );

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if (is_null($field) || $field == 'block_name') {
            if (empty($this->block_name)) {
                throw new DxException("Invalid 'block_name'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'block_alias') {
            if (empty($this->block_alias) || preg_match('~[^a-z0-9_-]+~u', $this->block_alias)) {
                throw new DxException("Invalid 'block_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if (is_null($field) || $field == 'block_content') {
            if (empty($this->block_content)) {
                throw new DxException("Invalid 'block_content'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @static
     * @return array
     */
    public static function getCategories()
    {
        return self::$categories;
    }

    /**
     * @return null
     */
    public function getCategoryName()
    {
        return isset(self::$categories[$this->getCategory()]) ? self::$categories[$this->getCategory()]['title'] : null;
    }

    /**
     * @return bool
     */
    public function isUniqueAlias()
    {
        /** @var $q DomainObjectQuery_Block */
        $q = DxFactory::getSingleton('DomainObjectQuery_Block');
        $c = $q->findByAlias($this->getAlias());

        if (!$c) {
            return true;
        } else {
            if (!$this->getId()) {
                return false;
            } elseif ($this->getId() == $c->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @static
     * @param $alias
     * @param null $k
     * @return string|null
     */
    public static function getBlock($alias, $k = null)
    {
        if (empty(self::$cache[$alias])) {
            /** @var $q DomainObjectQuery_Block */
            $q = DxFactory::getSingleton('DomainObjectQuery_Block');
            $c = $q->findByAlias($alias);

            if (empty($c)) return null;
            self::$cache[$alias] = $c;
        }

        if ($k == 'TITLE') {
            return self::$cache[$alias]->getTitle();
        } elseif ($k == 'CONTENT') {
            return self::$cache[$alias]->getContent();
        }

        return null;
    }
}