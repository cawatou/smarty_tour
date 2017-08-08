<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_User extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.user' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'user_id';

        $this->CMD      = '.adm.user';
        $this->CMD_LIST = '.user.list';
        $this->CMD_ADD  = '.user.add';
        $this->CMD_EDIT = '.user.edit';

        $this->FORM_ADD  = 'user_add';
        $this->FORM_EDIT = 'user_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_User';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_User';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_User';

        $this->TMPL_GROUP  = null;
        $this->TMPL_LIST   = 'backend/user_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/user_manage.tpl.php';

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

        /** @var DomainObjectQuery_Request $q */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var Form_Filter_Backend_Request $filter */
        $filter = DxFactory::getInstance('Form_Filter_Backend_Request', array('fr'));
        $filter->setContext($this->getContext());
        $filter->setUrl($this->getUrlList());

        /** @var DataList_Paginator $dl */
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

        $current_user = $this->getContext()->getCurrentUser();

        $parameters['currentUser'] = $current_user->getRole();

        if ($current_user->getRole() === 'DIRECTOR') {
            $parameters['s']['user_roles'] = array(
                'SELLER',
                'OPERATOR',
                'DIRECTOR',
            );

            $office_ids = array();

            foreach ($current_user->getSubdivisionOffices() as $office) {
                $office_ids[] = $office->getId();
            }

            if (!empty($office_ids)) {
                $parameters['s']['office_ids'] = $office_ids;
            } else {
                $parameters['s']['office_id'] = -1;
            }

            $parameters['s']['subdivision_id'] = $current_user->getSubdivisionId();
        }

        $dl->setParameters($parameters);

        $list  =& $dl->getRequestedPage();
        $state =  $dl->getState();

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'list'   => $list,
                'state'  => $state,
                'filter' => $filter,
            )
        );

        return $smarty->fetch($this->TMPL_LIST);
    }

    protected function opStatus()
    {
        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $m = $this->obtainRequestedModel();

        if ($m->getId() != $this->getContext()->getCurrentUser()->getId()) {
            $m->setStatus($m->getStatus() == 'ENABLED' ? 'DISABLED' : 'ENABLED');
            $this->getDomainObjectManager()->flush();
        }

        $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
        $this->getURL()->redirect($url);
    }
}