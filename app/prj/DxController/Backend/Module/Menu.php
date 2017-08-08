<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Menu extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.menu' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'menu_id';

        $this->CMD      = '.adm.menu';
        $this->CMD_LIST = '.menu.list';
        $this->CMD_ADD  = '.menu.add';
        $this->CMD_EDIT = '.menu.edit';

        $this->FORM_ADD  = 'menu_add';
        $this->FORM_EDIT = 'menu_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Menu';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Menu';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Menu';

        $this->TMPL_GROUP  = null;
        $this->TMPL_LIST   = 'backend/menu_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/menu_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 30;
    }

    /**
     * @param bool $new
     * @return DomainObjectModel_Menu
     * @throws DxException
     */
    protected function obtainRequestedModel($new = false)
    {
        /** @var $q DomainObjectQuery_Menu */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if ($new) {
            /** @var $m DomainObjectModel_Menu */
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

        if (!$this->getContext()->getCurrentUser()->isDeveloper() && $m->getType() == 'MENU_ROOT') {
            throw new DxException('Invalid ' . $this->REQUEST_ID);
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

        /** @var $q  DomainObjectQuery_Menu */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'trees' => $q->getTree()
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

        /** @var $m DomainObjectModel_Menu */
        $m = $this->obtainRequestedModel();
        $tree = DomainObjectModel_Menu::getTree();
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

        /** @var $m DomainObjectModel_Menu */
        $m = $this->obtainRequestedModel();
        $tree = DomainObjectModel_Menu::getTree();

        $tree->wrapNode($m)->delete();
        $this->getDomainObjectManager()->flush();
        $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
        $this->getURL()->redirect($url);
    }
}