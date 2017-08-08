<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_StaffCategory extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.staff.category' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'staff_category_id';

        $this->CMD      = '.adm.staff.category';
        $this->CMD_LIST = '.staff.category.list';
        $this->CMD_ADD  = '.staff.category.add';
        $this->CMD_EDIT = '.staff.category.edit';

        $this->FORM_ADD  = 'staff_category_add';
        $this->FORM_EDIT = 'staff_category_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_StaffCategory';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_StaffCategory';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_StaffCategory';

        $this->TMPL_GROUP  = 'staff_category';
        $this->TMPL_LIST   = 'backend/staff_category.tpl.php';
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

        /** @var $q  DomainObjectQuery_Staff */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if (isset($_POST['__change'])) {
            if (!$this->canEdit()) {
                throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
            }

            foreach ($_POST['staff_category_qnt'] as $id => $val) {
                $m = $q->findById($id);
                if (!is_null($m)) {
                    $m->setQnt(empty($val) ? 0 : $val);
                }
            }

            $this->getDomainObjectManager()->flush();
            $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
            $this->getURL()->redirect($url);
        }

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageName('page');
        $dl->setItemsPerPage($this->ITEMS_PER_PAGE);
        $dl->setPaginatorPageUrl($this->getUrlList('?page=%s'));

        $parameters = array();
        $dl->setParameters($parameters);

        $list  =& $dl->getRequestedPage();
        $state = $dl->getState();

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'list'   => $list,
            'state'  => $state,
        ));

        return $smarty->fetch($this->TMPL_LIST);
    }
}