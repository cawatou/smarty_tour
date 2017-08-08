<?php

dxFactory::import('Form_Backend');

class Form_Backend_Menu extends Form_Backend
{
    /** @var DomainObjectModel_Menu */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Menu|null $form_model
     */
    public function setModel(DomainObjectModel_Menu $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Menu|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Menu|null
     */
    public function m()
    {
        return $this->getModel();
    }

    /**
     * @return bool
     */
    protected function process()
    {
        $data   = $this->getEnvData('_POST');
        $errors = array();

        $m = $this->getModel();
        if (is_null($m)) {
            return false;
        }

        $type = in_array($data['menu_type'], array('CMD', 'PAGE')) ? $data['menu_type'] : 'COMMON';

        $map = array(
            'parent_id' => array(
                'method' => 'setParentId',
                'value'  => empty($data['parent_id']) ? null : $data['parent_id'],
            ),		
            'menu_title'  => array(
                'method' => 'setTitle',
                'value'  => $data['menu_title'] ? $data['menu_title'] : null,
            ),
            'menu_alias'  => array(
                'method' => 'setAlias',
                'value'  => $data['menu_alias'] ? trim(mb_strtolower($data['menu_alias'])) : null,
            ),
            'menu_type'   => array(
                'method' => 'setType',
                'value'  => $data['menu_type'] ? $data['menu_type'] : null,
            ),
            'menu_value'  => array(
                'method' => 'setValue',
                'value'  => $data['menu_value'][$type] ? $data['menu_value'][$type] : null,
            ),
            'menu_cover'  => array(
                'method' => 'setCover',
                'value'  => empty($data['menu_cover']) ? null : $data['menu_cover'],
            ),
            'menu_decor'  => array(
                'method' => 'setDecor',
                'value'  => empty($data['menu_decor']) ? null : $data['menu_decor'],
            ),
            'menu_is_jump'  => array(
                'method' => 'setIsJump',
                'value'  => empty($data['menu_is_jump']) ? 0 : 1,
            ),
            'menu_status' => array(
                'method' => 'setStatus',
                'value'  => $data['menu_status'] ? $data['menu_status'] : null,
            ),
        );

        if ($data['menu_type'] == 'LINK' && strpos($data['menu_value'][$type], '://') === false) {
            $map['menu_value']['value']  = '/' . str_replace(' ', '', trim($data['menu_value'][$type], "/\\"));
        }		

        foreach ($map as $key => $val) {
            try {
                DxFactory::invoke($m, $val['method'], array($val['value']));
            } catch (DxException $e) {
                if ($e->getCode() == DomainObjectModel::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT) {
                    $errors[$key] = 'INVALID_FORMAT';
                } else {
                    $errors[$key] = 'NOT_VALID';
                }
            }
        }

        if ($m->getType() != 'MENU_ROOT' && !$m->getParent()) {
            $errors['menu_type'] = 'NOT_VALID';
        } elseif ($m->getType() == 'LINK' && empty($data['menu_value'])) {
            $errors['menu_value'] = 'NOT_VALID';
        }

        if (!empty($data['menu_cover'])) {
            try {
                DxFactory::invoke('DxFile_Image', 'createByPath', array(ROOT . $data['menu_cover']));
            } catch (DxException $e) {
                $img_errors = array(
                    DxFile_Image::ERROR_IMAGE_NOT_FOUND   => 'IMAGE_NOT_FOUND',
                    DxFile_Image::ERROR_IMAGE_LOAD        => 'IMAGE_NOT_LOAD',
                    DxFile_Image::ERROR_IMAGE_UNSUPPORTED => 'IMAGE_UNSUPPORTED',
                );

                $errors['menu_cover'] = array_key_exists($e->getCode(), $img_errors) ? $img_errors[$e->getCode()] : 'NOT_VALID';
            }
        }

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        $tree = DomainObjectModel_Menu::getTree();

        if ($m->getType() != 'MENU_ROOT') {
            $need_parent = $m->getParent();
            $m_node   = $tree->wrapNode($m);
            $parent_node = $m_node->getParent();

            if (!$parent_node || $parent_node->getId() != $need_parent->getId()) {
                if (!$m->getId()) {
                    $tree->wrapNode($need_parent)->addChild($m_node);
                } else {
                    $m_node->moveAsLastChildOf($tree->wrapNode($need_parent));
                }
            }
        }

        $this->getDomainObjectManager()->flush();

        if ($this->getId() == 'menu_add' && $m->getType() == 'MENU_ROOT') {
            $tree->createRoot($m);
        }

        return true;
    }

    /**
     * @return string
     */
    public function draw()
    {
        /** @var $q_menu DomainObjectQuery_Menu */
        $q_menu = DxFactory::getSingleton('DomainObjectQuery_Menu');

        /** @var $q_page DomainObjectQuery_Page */
        $q_page = DxFactory::getSingleton('DomainObjectQuery_Page');

        $this->smarty->assign(array(
            'menu_roots_tree' => $q_menu->getTree(),
            'pages_tree'      => $q_page->getTree(),
            'menu_types'      => DxFactory::invoke('DomainObjectModel_Menu', 'getTypes')
        ));

        return $this->smarty->fetch('backend/form/menu.tpl.php');
    }
}