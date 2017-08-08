<?php
DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Resort extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.resort' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'resort_id';

        $this->CMD      = '.adm.resort';
        $this->CMD_LIST = '.resort.list';
        $this->CMD_ADD  = '.resort.add';
        $this->CMD_EDIT = '.resort.edit';

        $this->FORM_ADD  = 'resort_add';
        $this->FORM_EDIT = 'resort_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Resort';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Resort';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Resort';

        $this->TMPL_GROUP  = 'resort';
        $this->TMPL_LIST   = 'backend/resort_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/resort_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 30;
    }

    /**
     * @param bool $new
     * @return DomainObjectModel
     * @throws DxException
     */
    protected function obtainRequestedModel($new = false)
    {
        /** @var $q DomainObjectQuery_Resort */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if ($new) {
            /** @var $m DomainObjectQuery_Resort */
            $m = DxFactory::getInstance($this->DOMAIN_OBJECT_MODEL);

            if (!empty($_REQUEST['gallery_id'])) {
                $m->setGalleryId($_REQUEST['gallery_id']);
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
     * @return string
     */
    protected function opList()
    {
        if (!$this->canView()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var $q  DomainObjectQuery_Product */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $filter Form_Filter_Backend_Resort */
        $filter = DxFactory::getInstance('Form_Filter_Backend_Resort', array('fr'));
        $filter->setUrl($this->getUrlList());

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageName('page');
        $dl->setItemsPerPage($this->ITEMS_PER_PAGE);

        $parameters = array();

        $filter->setFormData(
            array(
                'resort_status' => 'ENABLED',
            )
        );

        if ($filter->isProcessed() && $params_url = $filter->getParametersAsURL()) {
            $dl->setPaginatorPageUrl($this->getUrlList("?{$params_url}&page=%s"));

            $parameters = $filter->getParameters();
        } else {
            $dl->setPaginatorPageUrl($this->getUrlList('?page=%s'));

            $parameters = array(
                Form_Filter_Backend_Country::FILTER_SEARCH_PARAMS => $filter->getFormData(),
            );
        }

        $dl->setParameters($parameters);

        $list  =& $dl->getRequestedPage();
        $state =  $dl->getState();

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'list'   => $list,
            'state'  => $state,
            'filter' => $filter,
        ));

        return $smarty->fetch($this->TMPL_LIST);
    }
}