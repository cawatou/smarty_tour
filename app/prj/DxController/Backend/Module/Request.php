<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Request extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.request' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'request_id';

        $this->CMD      = '.adm.request';
        $this->CMD_LIST = '.request.list';
        $this->CMD_ADD  = '.request.add';
        $this->CMD_EDIT = '.request.edit';

        $this->FORM_ADD  = 'request_add';
        $this->FORM_EDIT = 'request_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Request';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Request';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Request';

        $this->TMPL_GROUP  = null;
        $this->TMPL_LIST   = 'backend/request_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/request_manage.tpl.php';

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

        $req_types = DomainObjectModel_Request::getTypesByRole($this->getContext()->getCurrentUser()->getRole());

		
		
        $parameters['s']['request_type'] = array_keys($req_types);

        if ($filter->isProcessed() && $params_url = $filter->getParametersAsURL()) {
            $dl->setPaginatorPageUrl($this->getUrlList("?{$params_url}&page=%s"));
            $parameters = $filter->getParameters();
        } else {
            $dl->setPaginatorPageUrl($this->getUrlList('?page=%s'));
        }

        $current_user = $this->getContext()->getCurrentUser();
		
		

        if ($current_user->getRole() === 'OPERATOR') {
            $parameters['s']['office_id'] = $current_user->getOfficeId() ? $current_user->getOfficeId() : -1;
        } elseif ($current_user->getRole() === 'DIRECTOR') {
            $office_ids = array();

            foreach ($current_user->getSubdivisionOffices() as $office) {
                $office_ids[] = $office->getId();
            }

            if (!empty($office_ids)) {
                $parameters['s']['office_ids'] = $office_ids;
            } else {
                $parameters['s']['office_id'] = -1;
            }
        }
		
		

        $dl->setParameters($parameters);

		//throw new Exception (var_dump($dl->getRequestedPage()));
		
        $list  =& $dl->getRequestedPage();
		//throw new Exception(var_dump($list));
		
		
        $state =  $dl->getState();

		
		
        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'list'   => $list,
                'state'  => $state,
                'filter' => $filter,
                'types'  => $req_types,
            )
        );

        return $smarty->fetch($this->TMPL_LIST);
    }

    /**
     * @return void
     */
    protected function opAdd()
    {
        throw new DxException("Unknown operation 'add'");

        if (!$this->canCreate()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }
    }
}