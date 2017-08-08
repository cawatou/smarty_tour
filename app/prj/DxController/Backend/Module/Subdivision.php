<?php
DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Subdivision extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.subdivision' => 'index',
    );

    /**
     * @return null
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'subdivision_id';

        $this->CMD      = '.adm.subdivision';
        $this->CMD_LIST = '.subdivision.list';
        $this->CMD_ADD  = '.subdivision.add';
        $this->CMD_EDIT = '.subdivision.edit';

        $this->FORM_ADD  = 'subdivision_add';
        $this->FORM_EDIT = 'subdivision_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Subdivision';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Subdivision';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Subdivision';

        $this->TMPL_GROUP  = 'subdivision';
        $this->TMPL_LIST   = 'backend/subdivision_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/subdivision_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 30;
    }

    /**
     * @param bool $new
     * @return DomainObjectModel
     * @throws DxException
     */
    protected function obtainRequestedModel($new = false)
    {
        /** @var $q DomainObjectQuery_Product */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if ($new) {
            /** @var $m DomainObjectModle_Product */
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
        $filter = DxFactory::getInstance('Form_Filter_Backend_Subdivision', array('fs', true));
        $filter->setUrl($this->getUrlList());

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageName('page');
        $dl->setItemsPerPage($this->ITEMS_PER_PAGE);

        $parameters = array();

        $filter->setFormData(
            array(
                'subdivision_status' => 'ENABLED',
            )
        );

        if ($filter->isProcessed() && $params_url = $filter->getParametersAsURL()) {
            $dl->setPaginatorPageUrl($this->getUrlList("?{$params_url}&page=%s"));

            $parameters = $filter->getParameters();
        } else {
            $dl->setPaginatorPageUrl($this->getUrlList('?page=%s'));

            $parameters = array(
                Form_Filter_Backend_Subdivision::FILTER_SEARCH_PARAMS => $filter->getFormData(),
            );
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
}