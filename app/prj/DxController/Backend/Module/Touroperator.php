<?php
DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Touroperator extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.touroperator' => 'index',
    );

    /**
     * @return null
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'touroperator_id';

        $this->CMD      = '.adm.touroperator';
        $this->CMD_LIST = '.touroperator.list';
        $this->CMD_ADD  = '.touroperator.add';
        $this->CMD_EDIT = '.touroperator.edit';

        $this->FORM_ADD  = 'touroperator_add';
        $this->FORM_EDIT = 'touroperator_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Touroperator';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Touroperator';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Touroperator';

        $this->TMPL_GROUP  = 'touroperator';
        $this->TMPL_LIST   = 'backend/touroperator_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/touroperator_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 30;
    }

    /**
     * @param bool $new
     * @return DomainObjectModel_Touroperator
     * @throws DxException
     */
    protected function obtainRequestedModel($new = false)
    {
        /** @var DomainObjectQuery_Touroperator $q */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if ($new) {
            /** @var DomainObjectModel_Touroperator $m */
            $m = DxFactory::getInstance($this->DOMAIN_OBJECT_MODEL);
        } else {
            $m = $q->findById(empty($_REQUEST[$this->REQUEST_ID]) ? 0 : $_REQUEST[$this->REQUEST_ID]);

            if (!$m) {
                throw new DxException('Invalid ' . $this->REQUEST_ID);
            }
        }

        return $m;
    }

    /**
     * @return string
     */
    protected function opList()
    {
        if (!$this->canView()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var DomainObjectQuery_Touroperator $q */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageName('page');
        $dl->setItemsPerPage($this->ITEMS_PER_PAGE);

        $parameters = array();

        $dl->setParameters($parameters);

        $list  =& $dl->getRequestedPage();
        $state =  $dl->getState();

        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'list'   => $list,
                'state'  => $state,
            )
        );

        return $smarty->fetch($this->TMPL_LIST);
    }
}