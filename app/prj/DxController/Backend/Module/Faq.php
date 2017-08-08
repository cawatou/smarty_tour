<?php
DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Faq extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.faq' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'faq_id';

        $this->CMD      = '.adm.faq';
        $this->CMD_LIST = '.faq.list';
        $this->CMD_ADD  = '.faq.add';
        $this->CMD_EDIT = '.faq.edit';

        $this->FORM_ADD  = 'faq_add';
        $this->FORM_EDIT = 'faq_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Faq';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Faq';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Faq';

        $this->TMPL_GROUP  = null;
        $this->TMPL_LIST   = 'backend/faq_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/faq_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 30;
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

    /**
     * @return string
     */
    protected function opList()
    {
        if (!$this->canView()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var $q DomainObjectQuery_Faq */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageName('page');
        $dl->setItemsPerPage($this->ITEMS_PER_PAGE);

        $parameters = array();

        $dl->setPaginatorPageUrl($this->getUrlList('?page=%s'));

        $current_user = $this->getContext()->getCurrentUser();

        if ($current_user->getRole() === 'OPERATOR') {
            if ($current_user->getOfficeId() !== null) {
                $parameters['s']['office_id'] = $current_user->getOfficeId();
            } else {
                $parameters['s']['office_id'] = -1;
            }
        } elseif ($current_user->getRole() === 'DIRECTOR') {
            $offices = $current_user->getSubdivisionOffices();

            $office_ids = array();

            foreach ($offices as $office) {
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

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'list'   => $list,
            'state'  => $state,
        ));

        return $smarty->fetch($this->TMPL_LIST);
    }
}