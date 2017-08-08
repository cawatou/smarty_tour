<?php

/**
 * @method  int getId()
 * @method  string getType()
 * @method  string getTitle()
 * @method  string getAlias()
 * @method  string getValue()
 * @method  array getExtendedData()
 * @method  int getRootId()
 * @method  int getLft()
 * @method  int getRgt()
 * @method  int getLevel()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 *
 * @method  setId(int $arg)
 * @method  setType(string $arg)
 * @method  setTitle(string $arg)
 * @method  setAlias(string $arg)
 * @method  setValue(string $arg)
 * @method  setExtendedData(array $arg)
 * @method  setRootId(int $arg)
 * @method  setLft(int $arg)
 * @method  setRgt(int $arg)
 * @method  setLevel(int $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Menu extends DomainObjectModel_BaseMenu
{
    /** @var string */
    protected $field_prefix = 'menu';

    /** @var null|DomainObjectTree_NestedSet */
    protected static $tree = null;

    /** @var null|array */
    protected static $menu = null;

    /** @var null|array */
    protected static $nmenu = null;

    /** @var DomainObjectModel_Menu|null */
    public $parent = null;

    /** @var array */
    protected static $types = array(
        'LINK'      => 'Ссылка',
        'EMPTY'     => 'Пусто',
        'MENU_ROOT' => 'Вершина меню',
        'PAGE'      => 'Страница',
        'CMD'       => 'Команда'
    );

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if (is_null($field) || $field == 'menu_title') {
            if (empty($this->menu_title) || !is_string($this->menu_title)) {
                throw new DxException('Invalid \'menu_title\'', self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'menu_alias') {
            if (!is_null($this->menu_alias)) {
                if (empty($this->menu_alias)) {
                    throw new DxException('Invalid \'menu_alias\'', self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
                } elseif (preg_match('~[^a-z0-9_-]+~u', $this->menu_alias)) {
                    throw new DxException('Invalid \'menu_alias\'', self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
                }
            }
        }

        if (is_null($field) || $field == 'menu_type') {
            if (empty($this->menu_type) || !in_array($this->menu_type, array_keys(self::getTypes()))) {
                throw new DxException('Invalid \'menu_type\'', self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'menu_extended_data') {
            if (!is_null($this->menu_extended_data) && !is_array($this->menu_extended_data)) {
                throw new DxException('Invalid \'menu_extended_data\'', self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'menu_value') {
            if (!is_null($this->menu_value) && empty($this->menu_value)) {
                throw new DxException('Invalid \'menu_value\'', self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'menu_status') {
            if (empty($this->menu_status) || !in_array($this->menu_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException('Invalid \'menu_status\'', self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'root_id') {
            if (!is_numeric($this->root_id)) {
                throw new DxException('Invalid \'root_id\'', self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'lft') {
            if (!is_numeric($this->lft)) {
                throw new DxException('Invalid \'lft\'', self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'rgt') {
            if (!is_numeric($this->rgt)) {
                throw new DxException('Invalid \'rgt\'', self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'level') {
            if (!is_numeric($this->level)) {
                throw new DxException('Invalid \'level\'', self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasColumn('menu_extended_data', 'array');
        $this->actAs('NestedSet',
            array(
                'hasManyRoots'   => true,
                'rootColumnName' => 'root_id'
            )
        );
    }

    /**
     * @static
     * @return array
     */
    public static function getTypes()
    {
        return self::$types;
    }

    /**
     * @static
     * @return DomainObjectTree_NestedSet
     */
    public static function getTree()
    {
        if (is_null(self::$tree)) {
            self::$tree = DxFactory::getInstance('DomainObjectTree_NestedSet', array(
                'DomainObjectModel_Menu',
                DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER),
            ));
        }

        return self::$tree;
    }

    /**
     * @param int $parent_id
     */
    public function setParentId($parent_id)
    {
        /** @var $q DomainObjectQuery_Menu */
        $q = DxFactory::getSingleton('DomainObjectQuery_Menu');

        if (!($parent = $q->findById($parent_id))) {
            throw new DxException('Invalid parent');
        }

        $this->setParent($parent);
    }

    /**
     * @param DomainObjectModel_Menu $menu
     */
    public function setParent(DomainObjectModel_Menu $menu)
    {
        $this->parent = $menu;
    }

    /**
     * @return DomainObjectModel_Menu|bool
     */
    public function getParent()
    {
        if (is_null($this->parent)) {
            $tree = self::getTree();

            $parent_node = $tree->wrapNode($this)->getParent();

            if (is_null($parent_node)) {
                $this->parent = false;
            } else {
                $this->setParent($parent_node->getModel());
            }
        }

        return $this->parent;
    }

    /**
     * @static
     * @param DxCommand $command
     * @param null $menu_alias
     * @param null $active_alias
     * @param null $active_page_id
     * @param bool $only_enabled
     * @return array
     */
    public static function getNestedMenu(DxCommand $command, $menu_alias, $active_alias = null, $active_page_id = null, $only_enabled = true)
    {
        /** @var $q DomainObjectQuery_Menu */
        $q = DxFactory::getInstance('DomainObjectQuery_Menu');

        $menu = $q->findByAlias($menu_alias);
        if (is_null($menu)) {
            return array();
        }

        $menu_id = $menu->getId();

        if (empty(self::$nmenu[$menu_id])) {
            $tree = self::getTree();
            $active_items = array();

            if (null !== $active_alias) {
                $active_alias = (array)$active_alias;
                foreach ($active_alias as $_active_alias) {
                    $item = $q->findByAlias($_active_alias);
                    if (!is_null($item)) {
                        $active_items[] = $item;
                    }
                }
            } elseif (null !== $active_page_id) {
                $active_items = $q->findByTypeAndValue('PAGE', $active_page_id);
            } elseif (null !== $command->getArguments('request')) {
                $active_items = $q->findByTypeAndValue('LINK', $command->getArguments('request'));
            }

            if (empty($active_items)) {
                $active_items = $q->findByTypeAndValue('CMD', $command->getCmd());
            }

            $branch = array();
            foreach ($active_items as $item) {
                $node = $tree->wrapNode($item);

                $branch[$item->getRootId()] = array(
                    $item->getId() => array(
                        'active'   => true,
                        'selected' => true
                    )
                );

                while (is_object($node = $node->getParent())) {
                    $branch[$item->getRootId()][$node->getId()] = array(
                        'active'   => true,
                        'selected' => false
                    );
                }
            }

            $raw_menu = $q->getTree(array('level >=' => 1, 'root_id' => $menu_id));
            foreach ($raw_menu as $root_id => $nodes) {
                $ids = isset($branch[$root_id]) ? array_keys($branch[$root_id]) : array();
                foreach ($nodes as $node) {
                    if (in_array($node['menu_id'], $ids)) {
                        $node['active']   = $branch[$root_id][$node['menu_id']]['active'];
                        $node['selected'] = $branch[$root_id][$node['menu_id']]['selected'];
                    } else {
                        $node['active']   = false;
                        $node['selected'] = false;
                    }
                    self::$nmenu[$root_id] = self::increaseNestedMenu(empty(self::$nmenu[$root_id]) ? array() : self::$nmenu[$root_id], $node);
                }
            }
        }

        $result = isset(self::$nmenu[$menu_id]) ? self::$nmenu[$menu_id] : array();


        if ($only_enabled) {
            $result = self::cleanNestedMenu($result);
        }

        return $result;
    }

    /**
     * Removes hidden nodes
     * @static
     * @param $tree
     * @return array
     */
    protected static function cleanNestedMenu($tree)
    {
        foreach ($tree as $k => $node) {
            if ($node['menu_status'] != 'ENABLED') {
                unset($tree[$k]);
            } elseif (!empty($node['submenu'])) {
                $tree[$k]['submenu'] = self::cleanNestedMenu($node['submenu']);
            }
        }
        return $tree;
    }

    /**
     * Generates a nested tree
     * @static
     * @param $tree
     * @param $node
     * @return array
     */
    protected static function increaseNestedMenu($tree, $node)
    {
        if ($node['level'] == 1) {
            $node['submenu'] = array();
            $tree[$node['menu_id']] = $node;
            return $tree;
        } elseif (!empty($node['parent_id'])) {
            foreach ($tree as $_node) {
                if ($_node['menu_id'] == $node['parent_id']) {
                    if (empty($tree[$_node['menu_id']]['submenu'])) {
                        $tree[$_node['menu_id']]['submenu'] = array();
                    }
                    $tree[$_node['menu_id']]['submenu'][$node['menu_id']] = $node;
                    return $tree;
                } elseif (!empty($tree[$_node['menu_id']]['submenu'])) {
                    $tree[$_node['menu_id']]['submenu'] = self::increaseNestedMenu($tree[$_node['menu_id']]['submenu'], $node);
                }
            }
        }
        return $tree;
    }

    /**
     * @static
     * @param DxCommand $command
     * @param $menu_alias
     * @param null $active_alias
     * @param null $active_page_id
     * @return array
     */
    public static function getBreadDots(DxCommand $command, $menu_alias, $active_alias = null, $active_page_id = null)
    {
        $menu = self::getNestedMenu($command, $menu_alias, $active_alias, $active_page_id, false);

        $dots = array();
        foreach ($menu as $item) {
            if ($item['active']) {
                $dots[] = $item;
                if (!empty($item['submenu'])) {
                    foreach ($item['submenu'] as $subitem) {
                        if ($subitem['active']) {
                            $dots[] = $subitem;
                        }
                    }
                }
                break;
            }
        }

        return $dots;
    }
}
