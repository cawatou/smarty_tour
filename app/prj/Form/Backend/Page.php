<?php

dxFactory::import('Form_Backend');

class Form_Backend_Page extends Form_Backend
{
    /** @var DomainObjectModel_Page */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Page|null $form_model
     */
    public function setModel(DomainObjectModel_Page $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Page|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Page|null
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

        $before_parent = $m->getParent();
        $before_path   = $m->getPath();

        $map = array(
            'parent_id' => array(
                'method' => 'setParentId',
                'value'  => empty($data['parent_id']) ? null : $data['parent_id'],
            ),
            'page_alias'       => array(
                'method' => 'setAlias',
                'value'  => empty($data['page_alias']) ? null : mb_strtolower($data['page_alias']),
            ),
            'page_cmd'        => array(
                'method' => 'setCmd',
                'value'  => empty($data['page_cmd']) ? null : $data['page_cmd'],
            ),
            'page_name'       => array(
                'method' => 'setName',
                'value'  => empty($data['page_name']) ? null : mb_substr($data['page_name'], 0, 255),
            ),
            'page_title'       => array(
                'method' => 'setTitle',
                'value'  => empty($data['page_title']) ? null : mb_substr($data['page_title'], 0, 255),
            ),
            'page_keywords'    => array(
                'method' => 'setKeywords',
                'value'  => empty($data['page_keywords']) ? null : $data['page_keywords'],
            ),
            'page_description' => array(
                'method' => 'setDescription',
                'value'  => empty($data['page_description']) ? null : $data['page_description'],
            ),
            'page_content'     => array(
                'method' => 'setContent',
                'value'  => empty($data['page_content']) ? null : $data['page_content'],
            ),
            'page_status'      => array(
                'method' => 'setStatus',
                'value'  => empty($data['page_status']) || $data['page_status'] == 'ENABLED' ? 'ENABLED' : $data['page_status'],
            ),
        );

        if (empty($map['page_alias']['value']) && !empty($map['page_title']['value'])) {
            DxFactory::import('Utils_NameMaker');
            $map['page_alias']['value'] = Utils_NameMaker::cyrillicToLatin($map['page_title']['value'], true);
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

        if (!empty($data['page_cover'])) {
            $cover = $data['page_cover'];

            try {
                DxFactory::invoke('DxFile_Image', 'createByPath', array(ROOT . $cover));
            } catch (DxException $e) {
                $img_errors = array(
                    DxFile_Image::ERROR_IMAGE_NOT_FOUND   => 'IMAGE_NOT_FOUND',
                    DxFile_Image::ERROR_IMAGE_LOAD        => 'IMAGE_NOT_LOAD',
                    DxFile_Image::ERROR_IMAGE_UNSUPPORTED => 'IMAGE_UNSUPPORTED',
                );

                $errors['page_cover'] = array_key_exists($e->getCode(), $img_errors) ? $img_errors[$e->getCode()] : 'NOT_VALID';
            }

            if (empty($errors['product_cover'])) {
                $m->setCover($cover);
            }
        }

        if (empty($errors) && !$m->isUniquePath()) {
            $errors['page_alias'] = 'ALIAS_ALREADY_EXISTS';
        }

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        $tree = DomainObjectModel_Page::getTree();

        $parent = $m->getParent();
        $node   = $tree->wrapNode($m);

        if ($this->getId() == 'page_add') {
            $tree->wrapNode($parent)->addChild($node);
            $m->regenerateOwnPath();
        } else {
            $m->regenerateOwnPath();
            $m->regenerateChildrensPath($before_path);
            if ($parent->getId() != $before_parent->getId()) {
                $node->moveAsLastChildOf($tree->wrapNode($parent));
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
        /** @var $q DomainObjectQuery_Page */
        $q = DxFactory::getSingleton('DomainObjectQuery_Page');

        $this->smarty->assign(array(
            'pages_tree'      => $q->getTree(),
        ));

        return $this->smarty->fetch('backend/form/page.tpl.php');
    }
}