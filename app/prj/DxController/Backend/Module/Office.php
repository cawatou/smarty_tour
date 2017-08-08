<?php
DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Office extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.office' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'office_id';

        $this->CMD      = '.adm.office';
        $this->CMD_LIST = '.office.list';
        $this->CMD_ADD  = '.office.add';
        $this->CMD_EDIT = '.office.edit';

        $this->FORM_ADD  = 'office_add';
        $this->FORM_EDIT = 'office_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Office';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Office';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Office';

        $this->TMPL_GROUP  = 'office';
        $this->TMPL_LIST   = 'backend/office_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/office_manage.tpl.php';

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

        /** @var $q  DomainObjectQuery_Office */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if (isset($_POST['__change'])) {
            if (!$this->canEdit()) {
                throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
            }

            foreach ($_POST['office_qnt'] as $id => $val) {
                $m = $q->findById($id);
                if ($m !== null) {
                    $m->setQnt(empty($val) ? 0 : $val);
                }
            }

            $this->getDomainObjectManager()->flush();
            // Remove all of the caches, so new data can be propogated into them
            $this->getSmarty()->clearAllCache();

            $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
            $this->getURL()->redirect($url);
        }

        /** @var Form_Filter_Backend_Office $filter */
        $filter = DxFactory::getInstance('Form_Filter_Backend_Office', array('fo'));
        $filter->setUrl($this->getUrlList());
        $filter->setContext($this->getContext());

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

        if ($this->getContext()->getCurrentUser()->getRole() == 'DIRECTOR') {
            $office_ids = array();

            foreach ($this->getContext()->getCurrentUser()->getSubdivisionOffices() as $office) {
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
        $state = $dl->getState();

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
        $m->setStatus($m->getStatus() == 'ENABLED' ? 'DISABLED' : 'ENABLED');
        $this->getDomainObjectManager()->flush();

        $this->getSmarty()->clearCache('frontend/include/sidebars/side_left_office.tpl.php', 'SIDEBAR_OFFICE_'. $m->getCity()->getId());

        $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
        $this->getUrl()->redirect($url);
    }
}