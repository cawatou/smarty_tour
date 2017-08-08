<?php
DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Hotel extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.hotel' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'hotel_id';

        $this->CMD      = '.adm.hotel';
        $this->CMD_LIST = '.hotel.list';
        $this->CMD_ADD  = '.hotel.add';
        $this->CMD_EDIT = '.hotel.edit';

        $this->FORM_ADD  = 'hotel_add';
        $this->FORM_EDIT = 'hotel_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Hotel';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Hotel';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Hotel';

        $this->TMPL_GROUP  = 'hotel';
        $this->TMPL_LIST   = 'backend/hotel_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/hotel_manage.tpl.php';

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

        /** @var $q  DomainObjectQuery_Hotel */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $filter Form_Filter_Backend_Hotel */
        $filter = DxFactory::getInstance('Form_Filter_Backend_Hotel', array('fp'));
        $filter->setUrl($this->getUrlList());

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageName('page');
        $dl->setItemsPerPage($this->ITEMS_PER_PAGE);

        $parameters = array();

        $filter->setFormData(
            array(
                'hotel_status' => 'ENABLED',
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

    /**
     * @param bool $new
     * @return DomainObjectModel
     * @throws DxException
     */
    protected function obtainRequestedModel($new = false)
    {
        /** @var $q DomainObjectQuery_Hotel */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if ($new) {
            /** @var $m DomainObjectModle_Hotel */
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
     * @return void
     */
    protected function opDeleteImage()
    {
        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $id = !empty($_REQUEST['hotel_image_id']) ? $_REQUEST['hotel_image_id'] : 0;

        /** @var $q DomainObjectQuery_HotelImage */
        $q = DxFactory::getSingleton('DomainObjectQuery_HotelImage');

        $pi = $q->findById($id);

        if (!$pi) {
            throw new DxException('Invalid `hotel_image_id`');
        }

        $pi->remove();

        $hotel_id = $pi->getHotelId();

        $this->getDomainObjectManager()->flush();
        $this->getUrl()->redirect($this->getUrlEdit($hotel_id));
    }

    protected function opCheckTitle()
    {
        if (!array_key_exists('ajax', $_REQUEST) || empty($_REQUEST['hotel_title'])) {
            return $this->jsonResponse(array('is_unique' => true));
        }

        /** @var DomainObjectQuery_Hotel $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_Hotel');

        $hotel = $q->findByTitle($_REQUEST['hotel_title']);

        if ($hotel !== null) {
            return $this->jsonResponse(array('is_unique' => false));
        }

        return $this->jsonResponse(array('is_unique' => true));
    }

    protected function jsonResponse($res)
    {
        $this->getContext()
            ->addHeader('Expires: 0')
            ->addHeader('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0')
            ->addHeader('Pragma: no-cache')
            ->addHeader('Content-type: application/json');

        echo json_encode($res);

        exit(1);
    }
}