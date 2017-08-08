<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Feedback extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.feedback' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'feedback_id';

        $this->CMD      = '.adm.feedback';
        $this->CMD_LIST = '.feedback.list';
        $this->CMD_ADD  = '.feedback.add';
        $this->CMD_EDIT = '.feedback.edit';

        $this->FORM_ADD  = 'feedback_add';
        $this->FORM_EDIT = 'feedback_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Feedback';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Feedback';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Feedback';

        $this->TMPL_GROUP  = null;
        $this->TMPL_LIST   = 'backend/feedback_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/feedback_manage.tpl.php';

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

        /** @var $q  DomainObjectQuery_Request */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $filter Form_Filter_Backend_Feedback */
        $filter = DxFactory::getInstance('Form_Filter_Backend_Feedback', array('ff'));
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

        $list  =& $dl->getRequestedPage();
        $state =  $dl->getState();

        /** @var Smarty $smarty */
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