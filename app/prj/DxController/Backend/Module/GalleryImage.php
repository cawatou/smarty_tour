<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_GalleryImage extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.gallery.image' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'gallery_image_id';

        $this->CMD      = '.adm.gallery.image';
        $this->CMD_LIST = '.gallery.image.list';
        $this->CMD_ADD  = '.gallery.image.add';
        $this->CMD_EDIT = '.gallery.image.edit';

        $this->FORM_ADD  = 'gallery_image_add';
        $this->FORM_EDIT = 'gallery_image_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_GalleryImage';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_GalleryImage';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_GalleryImage';

        $this->TMPL_GROUP  = 'gallery_image';
        $this->TMPL_LIST   = 'backend/gallery_image.tpl.php';
        $this->TMPL_MANAGE = 'backend/gallery_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 54;
    }

    /**
     * @return string
     */
    protected function opList()
    {
        if (!$this->canView()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var $q  DomainObjectQuery_GalleryImage */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $filter Form_Filter_Backend_GalleryImage */
        $filter = DxFactory::getInstance('Form_Filter_Backend_GalleryImage', array('fgi'));
        $filter->setUrl($this->getURL()->adm($this->CMD_LIST));

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageName('page');
        $dl->setItemsPerPage($this->ITEMS_PER_PAGE);

        $parameters = array();

        if ($filter->isProcessed() && $params_url = $filter->getParametersAsURL()) {
            $dl->setPaginatorPageUrl($this->getURL()->adm($this->CMD_LIST, "?{$params_url}&page=%s"));
            $parameters = $filter->getParameters();
        } else {
            $dl->setPaginatorPageUrl($this->getURL()->adm($this->CMD_LIST, '?page=%s'));
        }

        $dl->setParameters($parameters);

        $list =& $dl->getRequestedPage();
        $state  = $dl->getState();

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'list'   => $list,
            'state'  => $state,
            'filter' => $filter
        ));

        return $smarty->fetch($this->TMPL_LIST);
    }

    /**
     * @return void
     */
    protected function opShift()
    {
        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $m = $this->obtainRequestedModel();

        /** @var $q DomainObjectQuery_GalleryImage */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if (!empty($_REQUEST['way']) && $_REQUEST['way'] == 'LEFT') {
            $shift_o = $q->findLeftImage($m->getQnt(), $m->getGalleryId());
        } else {
            $shift_o = $q->findRightImage($m->getQnt(), $m->getGalleryId());
        }

        if ($shift_o !== null) {
            $shift_qnt = $shift_o->getQnt();
            $qnt = $m->getQnt();
            $m->setQnt($shift_qnt);
            $shift_o->setQnt($qnt);
            $this->getDomainObjectManager()->flush();
        }

        $url = empty($_SERVER['HTTP_REFERER']) ? $this->getURL()->adm($this->CMD_LIST) : $_SERVER['HTTP_REFERER'];
        $this->getURL()->redirect($url);
    }
}