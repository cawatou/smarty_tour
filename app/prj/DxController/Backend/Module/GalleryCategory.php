<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_GalleryCategory extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.gallery.category' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'gallery_id';

        $this->CMD      = '.adm.gallery.category';
        $this->CMD_LIST = '.gallery.category.list';
        $this->CMD_ADD  = '.gallery.category.add';
        $this->CMD_EDIT = '.gallery.category.edit';

        $this->FORM_ADD  = 'gallery_category_add';
        $this->FORM_EDIT = 'gallery_category_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_GalleryCategory';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Gallery';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Gallery';

        $this->TMPL_GROUP  = 'gallery_category';
        $this->TMPL_LIST   = 'backend/gallery_category.tpl.php';
        $this->TMPL_MANAGE = 'backend/gallery_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 50;
    }

    /**
     * @return string
     */
    protected function opList()
    {
        if (!$this->canView()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var $q  DomainObjectQuery_Gallery */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $filter Form_Filter_Backend_Gallery */
        $filter = DxFactory::getInstance('Form_Filter_Backend_GalleryCategory', array('fg'));
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
            'list'       => $list,
            'state'      => $state,
            'filter'     => $filter,
        ));

        return $smarty->fetch($this->TMPL_LIST);
    }
}