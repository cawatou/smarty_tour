<?php
DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_City extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.city' => 'index',
    );

    /**
     * @return null
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'city_id';

        $this->CMD      = '.adm.city';
        $this->CMD_LIST = '.city.list';
        $this->CMD_ADD  = '.city.add';
        $this->CMD_EDIT = '.city.edit';

        $this->FORM_ADD  = 'city_add';
        $this->FORM_EDIT = 'city_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_City';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_City';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_City';

        $this->TMPL_GROUP  = 'city';
        $this->TMPL_LIST   = 'backend/city_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/city_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 30;
    }

    /**
     * @param bool $new
     * @return DomainObjectModel
     * @throws DxException
     */
    protected function obtainRequestedModel($new = false)
    {
        /** @var DomainObjectQuery_City $q */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if ($new) {
            /** @var DomainObjectModel_City $m */
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

        /** @var DomainObjectQuery_City $q */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var Form_Filter_Backend_City $filter */
        $filter = DxFactory::getInstance('Form_Filter_Backend_City', array('fc', true));
        $filter->setUrl($this->getUrlList());

        /** @var DataList_Paginator $dl */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageName('page');
        $dl->setItemsPerPage($this->ITEMS_PER_PAGE);

        $parameters = array();

        $filter->setFormData(
            array(
                'city_status' => 'ENABLED',
            )
        );

        if ($filter->isProcessed() && $params_url = $filter->getParametersAsURL()) {
            $dl->setPaginatorPageUrl($this->getUrlList("?{$params_url}&page=%s"));

            $parameters = $filter->getParameters();
        } else {
            $dl->setPaginatorPageUrl($this->getUrlList('?page=%s'));

            $parameters = array(
                Form_Filter_Backend_City::FILTER_SEARCH_PARAMS => $filter->getFormData(),
            );
        }

        if ($this->getContext()->getCurrentUser()->getRole() == 'DIRECTOR') {
            $city_ids = array();

            foreach ($this->getContext()->getCurrentUser()->getSubdivisionCities() as $city) {
                $city_ids[] = $city->getId();
            }

            if (!empty($city_ids)) {
                $parameters['s']['city_ids'] = $city_ids;
            } else {
                $parameters['s']['city_id'] = -1;
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
}