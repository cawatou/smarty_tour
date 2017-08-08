<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Page extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.page' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'page_id';

        $this->CMD      = '.adm.page';
        $this->CMD_LIST = '.page.list';
        $this->CMD_ADD  = '.page.add';
        $this->CMD_EDIT = '.page.edit';

        $this->FORM_ADD  = 'page_add';
        $this->FORM_EDIT = 'page_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Page';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Page';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Page';

        $this->TMPL_GROUP  = null;
        $this->TMPL_LIST   = 'backend/page_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/page_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 30;
    }

    /**
     * @return string
     */
    protected function index()
    {
       /** @var $tree DomainObjectTree_NestedSet */
        $tree = DomainObjectModel_Page::getTree();
        $root = $tree->fetchRoot();

        if ($root === null) {
            /** @var $p DomainObjectModel_Page */
            $p = DxFactory::getInstance($this->DOMAIN_OBJECT_MODEL);
            $p->setTitle('Корень сайта');
            $p->setStatus('ENABLED');
            $p->setCmd(DxCommand::CMD_DEFAULT);

            $this->getDomainObjectManager()->flush();
            $tree->createRoot($p);
        }

        $op = $this->getContext()->getCurrentCommand()->getArguments('op', $this->OP_DEFAULT);

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'op'    => $op,
            'group' => $this->TMPL_GROUP,
        ));

        $op = explode('_', $op);
        foreach ($op as $k => $v) {
            $op[$k] = ucwords($v);
        }

        $method = 'op' . implode('', $op);
        $html   = $this->$method();
        return $this->wrap($html);
    }

    /**
     * @param bool $new
     * @return DomainObjectModel_Page
     * @throws DxException
     */
    protected function obtainRequestedModel($new = false)
    {
        /** @var $q DomainObjectQuery_Page */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if ($new) {
            /** @var $m DomainObjectModle_Page */
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
     * @throws DxException
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

        /** @var $q  DomainObjectQuery_Page */
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
        if (!$this->canOrder()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var $m DomainObjectModel_Page */
        $m = $this->obtainRequestedModel();
        $tree = DomainObjectModel_Page::getTree();
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

        /** @var $m DomainObjectModel_Page */
        $m = $this->obtainRequestedModel();
        $tree = DomainObjectModel_Page::getTree();

        $tree->wrapNode($m)->delete();
        $this->getDomainObjectManager()->flush();
        $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
        $this->getURL()->redirect($url);
    }
}