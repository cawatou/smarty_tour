<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_ProductCategory extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.product.category' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'product_category_id';

        $this->CMD      = '.adm.product.category';
        $this->CMD_LIST = '.product.category.list';
        $this->CMD_ADD  = '.product.category.add';
        $this->CMD_EDIT = '.product.category.edit';

        $this->FORM_ADD  = 'product_category_add';
        $this->FORM_EDIT = 'product_category_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_ProductCategory';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_ProductCategory';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_ProductCategory';

        $this->TMPL_GROUP  = 'product_category';
        $this->TMPL_LIST   = 'backend/product_category_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/product_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 30;
    }

    /**
     * @param bool $new
     * @return DomainObjectModel
     * @throws DxException
     */
    protected function obtainRequestedModel($new = false)
    {
        /** @var $q DomainObjectQuery_Product */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if ($new) {
            /** @var $m DomainObjectModle_Product */
            $m = DxFactory::getInstance($this->DOMAIN_OBJECT_MODEL);

            if (!empty($_REQUEST['parent_id'])) {
                $m->setParentId($_REQUEST['parent_id']);
            }
        } else {
            $m = $q->findById(empty($_REQUEST[$this->REQUEST_ID]) ? 0 : $_REQUEST[$this->REQUEST_ID]);

            if (!$m) {
                throw new DxException('Invalid ' . $this->REQUEST_ID);
            }
        }

        return $m;
    }

    /**
     * @return void
     */
    protected function opStatus()
    {
        throw new DxException("Unknown operation 'status'");

        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }
    }

    /**
     * @return string
     */
    protected function opList()
    {
        if (!$this->canView()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var $q  DomainObjectQuery_ProductCategory */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'tree' => $q->getTree()
        ));

        return $smarty->fetch($this->TMPL_LIST);
    }

    /**
     * @return string
     */
    protected function opOrder()
    {
        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var $m DomainObjectModel_ProductCategory */
        $m = $this->obtainRequestedModel();
        $tree = DomainObjectModel_ProductCategory::getTree();
        $node = $tree->wrapNode($m);

        if (isset($_REQUEST['down']) && $node->hasNextSibling()) {
            $node->moveAsNextSiblingOf($node->getNextSibling());
        } elseif (isset($_REQUEST['up']) && $node->hasPrevSibling()) {
            $node->moveAsPrevSiblingOf($node->getPrevSibling());
        }

        $this->getDomainObjectManager()->flush();
        $this->getUrl()->redirect($this->getUrlList());
    }

    /**
     * @return void
     */
    protected function opDelete()
    {
        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var $m DomainObjectModel_ProductCategory */
        $m = $this->obtainRequestedModel();
        $tree = DomainObjectModel_ProductCategory::getTree();

        $tree->wrapNode($m)->delete();
        $this->getDomainObjectManager()->flush();
        $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
        $this->getURL()->redirect($url);
    }
}