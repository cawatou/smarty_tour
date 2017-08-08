<?php

/**
 * @method  int getId()
 * @method  string getTitle()
 * @method  string getAlias()
 * @method  string getCover()
 * @method  array getKeywords()
 * @method  string getDescription()
 * @method  string getStatus()
 * @method  int getRootId()
 * @method  int getLft()
 * @method  int getRgt()
 * @method  int getLevel()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 *
 * @method  setId(int $arg)
 * @method  setTitle(string $arg)
 * @method  setAlias(string $arg)
 * @method  setCover(string $arg)
 * @method  setKeywords(array &$arg = array())
 * @method  setDescription(string $arg)
 * @method  setStatus(string $arg)
 * @method  setRootId(int $arg)
 * @method  setLft(int $arg)
 * @method  setRgt(int $arg)
 * @method  setLevel(int $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_ProductCategory extends DomainObjectModel_BaseProductCategory
{
    /** @var string */
    protected $field_prefix = 'product_category';
	
    /** @var null|DomainObjectTree_NestedSet */
    protected static $tree = null;

    /** @var DomainObjectModel_ProductCategory|null */
    public $parent = null;	

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if (is_null($field) || $field == 'product_category_title') {
            if (empty($this->product_category_title)) {
                throw new DxException("Invalid 'product_category_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'product_category_alias') {
            if (empty($this->product_category_alias)) {
                throw new DxException("Invalid 'product_category_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            } elseif (preg_match('~[^a-z0-9_-]+~u', $this->product_category_alias)) {
                throw new DxException("Invalid 'product_category_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if (is_null($field) || $field == 'product_category_cover') {
            if (!is_null($this->product_category_cover) && empty($this->product_category_cover)) {
                throw new DxException("Invalid 'product_category_cover'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'product_category_keywords') {
            if (!is_null($this->product_category_keywords) && empty($this->product_category_keywords)) {
                throw new DxException("Invalid 'product_category_keywords'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'product_category_description') {
            if (!is_null($this->product_category_description) && empty($this->product_category_description)) {
                throw new DxException("Invalid 'product_category_description'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'product_category_status') {
            if (empty($this->product_category_status) || !in_array($this->product_category_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'product_category_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'root_id') {
            if (!is_numeric($this->root_id)) {
                throw new DxException("Invalid 'root_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'lft') {
            if (!is_numeric($this->lft)) {
                throw new DxException("Invalid 'lft'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'rgt') {
            if (!is_numeric($this->rgt)) {
                throw new DxException("Invalid 'rgt'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'level') {
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
			'rootColumnName' => 'root_id',
		));
        $this->getTable()
            ->getRelation('Product')
            ->offsetSet('cascade', array('delete'));
    }
	
    /**
     * @static
     * @return DomainObjectTree_NestedSet
     */
    public static function getTree()
    {
        if (is_null(self::$tree)) {
            self::$tree = DxFactory::getInstance('DomainObjectTree_NestedSet', array(
				'DomainObjectModel_ProductCategory',
				DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER)
			));
        }

        return self::$tree;
    }

    /**
     * @param string $field
     * @return bool
     */
    public function isUnique($field = 'title')
    {
        /** @var $q DomainObjectQuery_ProductCategory */
        $q = DxFactory::getSingleton('DomainObjectQuery_ProductCategory');
        $p = $q->findByTitleOrAlias($field == 'title' ? $this->getTitle() : $this->getAlias());

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
        /** @var $q DomainObjectQuery_ProductCategory */
        $q = DxFactory::getSingleton('DomainObjectQuery_ProductCategory');

        if (!($parent = $q->findById($parent_id))) {
            throw new DxException('Invalid parent');
        }

        $this->setParent($parent);
    }

    /**
     * @param DomainObjectModel_ProductCategory $page
     */
    public function setParent(DomainObjectModel_ProductCategory $page)
    {
        $this->parent = $page;
    }

    /**
     * @return DomainObjectModel_ProductCategory|null
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
}