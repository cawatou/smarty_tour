<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_I18n extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.i18n' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'i18n_id';

        $this->CMD      = '.adm.i18n';
        $this->CMD_LIST = '.i18n.list';
        $this->CMD_ADD  = '.i18n.add';
        $this->CMD_EDIT = '.i18n.edit';

        $this->FORM_ADD  = 'i18n_add';
        $this->FORM_EDIT = 'i18n_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_I18n';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_I18n';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_I18n';

        $this->TMPL_GROUP  = null;
        $this->TMPL_LIST   = 'backend/i18n_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/i18n_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 30;
    }

    /**
     * @return string
     */
    protected function opList()
    {
        if (!$this->canView()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var $q  DomainObjectQuery_I18n */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $filter Form_Filter_Backend_I18n */
        $filter = DxFactory::getInstance('Form_Filter_Backend_I18n', array('fi', true));
        $filter->setUrl($this->getUrlList());

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageName('page');
        $dl->setItemsPerPage($this->ITEMS_PER_PAGE);

        $parameters = array();
        if ($filter->isProcessed() && $params_url = $filter->getParametersAsURL()) {
            $dl->setPaginatorPageUrl($this->getUrlList("?{$params_url}&page=%s"));
            $parameters = $filter->getParameters();
        } else {
            $dl->setPaginatorPageUrl($this->getUrlList('?page=%s'));
        }
        $dl->setParameters($parameters);

        $list =& $dl->getRequestedPage();
        $state = $dl->getState();

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'list'   => $list,
            'state'  => $state,
            'filter' => $filter,
        ));

        return $smarty->fetch($this->TMPL_LIST);
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
    protected function opSet()
    {
        if (!$this->canView()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var $i18n I18n_Project */
        $i18n = DxApp::getComponent(DxConstant_Project::ALIAS_I18N);
        $i18n->setBackendLocale(empty($_REQUEST['locale']) ? null : $_REQUEST['locale']);

        $url = empty($_SERVER['HTTP_REFERER']) ? $this->getURL()->adm() : $_SERVER['HTTP_REFERER'];
        $this->getURL()->redirect($url);
    }
}