<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Staff extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.staff' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'staff_id';

        $this->CMD      = '.adm.staff';
        $this->CMD_LIST = '.staff.list';
        $this->CMD_ADD  = '.staff.add';
        $this->CMD_EDIT = '.staff.edit';

        $this->FORM_ADD  = 'staff_add';
        $this->FORM_EDIT = 'staff_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Staff';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Staff';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Staff';

        $this->TMPL_GROUP  = 'staff';
        $this->TMPL_LIST   = 'backend/staff_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/staff_manage.tpl.php';

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

        /** @var DomainObjectQuery_Staff $q */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if (isset($_POST['__change'])) {
            if (!$this->canEdit()) {
                throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
            }

            foreach ($_POST['staff_qnt'] as $id => $val) {
                $m = $q->findById($id);
                if (!is_null($m)) {
                    $m->setQnt(empty($val) ? 0 : $val);
                }
            }

            $this->getDomainObjectManager()->flush();
            $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
            $this->getURL()->redirect($url);
        }

        /** @var DataList_Paginator $dl */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageName('page');
        $dl->setItemsPerPage($this->ITEMS_PER_PAGE);
        $dl->setPaginatorPageUrl($this->getUrlList('?page=%s'));

        $parameters = array();

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
                'list'  => $list,
                'state' => $state,
            )
        );

        return $smarty->fetch($this->TMPL_LIST);
    }
}