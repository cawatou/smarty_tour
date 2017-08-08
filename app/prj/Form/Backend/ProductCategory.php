<?php

dxFactory::import('Form_Backend');

class Form_Backend_ProductCategory extends Form_Backend
{
    /** @var DomainObjectModel_ProductCategory */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_ProductCategory|null $form_model
     */
    public function setModel(DomainObjectModel_ProductCategory $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_ProductCategory|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_ProductCategory|null
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

        $map = array(
            'parent_id' => array(
                'method' => 'setParentId',
                'value'  => empty($data['parent_id']) ? null : $data['parent_id'],
            ),
            'product_category_title' => array(
                'method' => 'setTitle',
                'value'  => $data['product_category_title'],
            ),
            'product_category_alias' => array(
                'method' => 'setAlias',
                'value'  => trim(mb_strtolower($data['product_category_alias'])),
            ),
            'product_category_keywords' => array(
                'method' => 'setKeywords',
                'value'  => empty($data['product_category_keywords']) ? null : $data['product_category_keywords'],
            ),
            'product_category_description' => array(
                'method' => 'setDescription',
                'value'  => empty($data['product_category_description']) ? null : $data['product_category_description'],
            ),
            'product_category_cover' => array(
                'method' => 'setCover',
                'value'  => $data['product_category_cover'] ? $data['product_category_cover'] : null,
            ),
            'product_category_contains_products' => array(
                'method' => 'setContainsProducts',
                'value'  => empty($data['product_category_contains_products']) ? 0 : 1,
            ),
            'product_category_status' => array(
                'method' => 'setStatus',
                'value'  => $data['product_category_status'],
            ),
        );
		
        if (empty($map['product_category_alias']['value']) && !empty($map['product_category_title']['value'])) {
            DxFactory::import('Utils_NameMaker');
            $map['product_category_alias']['value'] = Utils_NameMaker::cyrillicToLatin($map['product_category_title']['value'], true);
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

        if (empty($errors)) {
            if (!$m->isUnique('alias')) {
                $errors['product_category_alias'] = 'ALREADY_EXISTS';
            }
        }

        if (!empty($data['product_category_cover'])) {
            try {
                DxFactory::invoke('DxFile_Image', 'createByPath', array(ROOT . $data['product_category_cover']));
            } catch (DxException $e) {
                $img_errors = array(
                    DxFile_Image::ERROR_IMAGE_NOT_FOUND   => 'IMAGE_NOT_FOUND',
                    DxFile_Image::ERROR_IMAGE_LOAD        => 'IMAGE_NOT_LOAD',
                    DxFile_Image::ERROR_IMAGE_UNSUPPORTED => 'IMAGE_UNSUPPORTED',
                );

                $errors['product_category_cover'] = array_key_exists($e->getCode(), $img_errors) ? $img_errors[$e->getCode()] : 'NOT_VALID';
            }
        }

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();
            return false;
        }

        $tree = DomainObjectModel_ProductCategory::getTree();

        $need_parent           = $m->getParent();
        $m_node = $tree->wrapNode($m);
        $parent_node           = $m_node->getParent();

        if (!$parent_node || $parent_node->getId() != $need_parent->getId()) {
            if (!$m->getId()) {
                $tree->wrapNode($need_parent)->addChild($m_node);
            } else {
                $m_node->moveAsLastChildOf($tree->wrapNode($need_parent));
            }
        }

        $this->getDomainObjectManager()->flush();
        return true;
    }

    /**
     * @return string
     */
    public function draw()
    {
        /** @var $q DomainObjectQuery_ProductCategory */
        $q = DxFactory::getSingleton('DomainObjectQuery_ProductCategory');

        $this->smarty->assign(array(
            'product_categories_tree' => $q->getTree(),
        ));

        return $this->smarty->fetch('backend/form/product_category.tpl.php');
    }
}