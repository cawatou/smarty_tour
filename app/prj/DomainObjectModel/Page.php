<?php

/**
 * @method  int getId()
 * @method  string getAlias()
 * @method  string getPath()
 * @method  string getTitle()
 * @method  array getKeywords()
 * @method  string getDescription()
 * @method  string getContent()
 * @method  string getStatus()
 * @method  int getRootId()
 * @method  int getLft()
 * @method  int getRgt()
 * @method  int getLevel()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 *
 * @method  setId(int $arg)
 * @method  setAlias(string $arg)
 * @method  setPath(string $arg)
 * @method  setTitle(string $arg)
 * @method  setKeywords(array &$arg = array())
 * @method  setDescription(string $arg)
 * @method  setContent(string $arg)
 * @method  setStatus(string $arg)
 * @method  setRootId(int $arg)
 * @method  setLft(int $arg)
 * @method  setRgt(int $arg)
 * @method  setLevel(int $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Page extends DomainObjectModel_BasePage
{
    /** @var string */
    protected $field_prefix = 'page';

    /** @var null|DomainObjectTree_NestedSet */
    protected static $tree = null;

    /** @var DomainObjectModel_Page|null */
    public $parent = null;

    /** @var array */
    protected static $decorators = array(
        'CONTENT' => array(
            'title' => 'Контент',
            'cmd'   => '.content',
        ),

        'CONTENT_GALLERY' => array(
            'title' => 'Контент + галерея',
            'cmd'   => '.content.gallery',
        ),

        'CONTENT_SPECIAL_WEATHER' => array(
            'title' => 'Спец. контент - погода',
            'cmd'   => '.content.special.weather',
        ),
    );

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'page_alias') {
            if ($this->page_alias !== null) {
                if (empty($this->page_alias)) {
                    throw new DxException("Invalid 'page_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
                }

                if (preg_match('~[^a-z0-9_-]+~u', $this->page_alias)) {
                    throw new DxException("Invalid 'page_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
                }
            }
        }

        if ($field === null || $field === 'page_path') {
            if (!is_null($this->page_path) && empty($this->page_path)) {
                throw new DxException("Invalid 'page_path'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'page_cmd') {
            if (empty($this->page_cmd)) {
                throw new DxException("Invalid 'page_cmd'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'page_title') {
            if (empty($this->page_title)) {
                throw new DxException("Invalid 'page_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'page_keywords') {
            if (!is_null($this->page_keywords) && empty($this->page_keywords)) {
                throw new DxException("Invalid 'page_keywords'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'page_description') {
            if (!is_null($this->page_description) && empty($this->page_description)) {
                throw new DxException("Invalid 'page_description'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'page_content') {
            if (!is_null($this->page_content) && empty($this->page_content)) {
                throw new DxException("Invalid 'page_content'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'page_status') {
            if (empty($this->page_status) || !in_array($this->page_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'page_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'root_id') {
            if (!is_numeric($this->root_id)) {
                throw new DxException("Invalid 'root_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'lft') {
            if (!is_numeric($this->lft)) {
                throw new DxException("Invalid 'lft'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'rgt') {
            if (!is_numeric($this->rgt)) {
                throw new DxException("Invalid 'rgt'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'level') {
            if (!is_numeric($this->level)) {
                throw new DxException("Invalid 'level'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->actAs('NestedSet', array(
            'hasManyRoots'   => true,
            'rootColumnName' => 'root_id'
        ));
    }

    /**
     * @static
     * @return array
     */
    public static function getDecorators()
    {
        return self::$decorators;
    }

    /**
     * @static
     * @return DomainObjectTree_NestedSet
     */
    public static function getTree()
    {
        if (self::$tree !== null) {
            return self::$tree;
        }

        /** @var $tree DomainObjectTree_NestedSet */
        self::$tree = DxFactory::getInstance('DomainObjectTree_NestedSet', array(
            'DomainObjectModel_Page',
            DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER)
        ));

        return self::$tree;
    }

    /**
     * @return void
     */
    public function regenerateOwnPath()
    {
        $this->setPath("{$this->getParent()->getPath()}/{$this->getAlias()}");
    }

    /**
     * @param null $before_path
     * @return void
     */
    public function regenerateChildrensPath($before_path = null)
    {
        if (null === $before_path || $before_path == $this->getPath()) {
            return false;
        }

        /** @var $q DomainObjectQuery_Page */
        $q = DxFactory::getSingleton('DomainObjectQuery_Page');
        $q->replacePath($before_path, $this->getPath(), $this->getLft(), $this->getRgt());

        return true;
    }

    /**
     * @return bool
     */
    public function isUniquePath()
    {
        /** @var $q DomainObjectQuery_Page */
        $q = DxFactory::getSingleton('DomainObjectQuery_Page');
        $p = $q->findByPath($this->getPath());

        if (!$p) {
            return true;
        } else {
            if (!$this->getId()) {
                return false;
            } elseif ($this->getId() == $p->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $parent_id
     */
    public function setParentId($parent_id)
    {
        /** @var $q DomainObjectQuery_Page */
        $q = DxFactory::getSingleton('DomainObjectQuery_Page');

        $parent = $q->findById($parent_id);
        if (null === $parent) {
            throw new DxException('Invalid parent');
        }

        $this->setParent($parent);
    }

    /**
     * @param DomainObjectModel_Page $page
     */
    public function setParent(DomainObjectModel_Page $page)
    {
        $this->parent = $page;
    }

    /**
     * @return DomainObjectModel_Page|null
     */
    public function getParent()
    {
        if (is_null($this->parent)) {
            $tree = self::getTree();

            $parent_node = $tree->wrapNode($this)->getParent();

            if (is_null($parent_node)) {
                $this->setParent($tree->fetchRoot()->getModel());
            } else {
                $this->setParent($parent_node->getModel());
            }
        }

        return $this->parent;
    }

    /**
     * @static
     * @param $id
     * @return null|string
     */
    public static function getPathById($id)
    {
        /** @var $q DomainObjectQuery_Page */
        $q = DxFactory::getSingleton('DomainObjectQuery_Page');
        $p = $q->findById($id);

        return $p ? $p->getPath() : null;
    }
}