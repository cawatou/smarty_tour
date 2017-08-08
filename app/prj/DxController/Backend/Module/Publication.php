<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Publication extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.publication' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'publication_id';

        $this->CMD      = '.adm.publication';
        $this->CMD_LIST = '.publication.list';
        $this->CMD_ADD  = '.publication.add';
        $this->CMD_EDIT = '.publication.edit';

        $this->FORM_ADD  = 'publication_add';
        $this->FORM_EDIT = 'publication_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Publication';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Publication';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Publication';

        $this->TMPL_GROUP  = 'publication';
        $this->TMPL_LIST   = 'backend/publication_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/publication_manage.tpl.php';

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

        /** @var $q  DomainObjectQuery_Publication */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $filter Form_Filter_Backend_Publication */
        $filter = DxFactory::getInstance('Form_Filter_Backend_Publication', array('fn'));
        $filter->setUrl($this->getUrlList());

        /** @var $dl DataList_Paginator */
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
        $dl->setParameters($parameters);

        $list =& $dl->getRequestedPage();
        $state = $dl->getState();

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
     * @param $mode
     * @param DomainObjectModel $m
     * @return string
     * @throws DxException
     */
    protected function manage($mode, DomainObjectModel $m)
    {
        /** @var $form Form_Backend_Publication */
        $form = DxFactory::getInstance($this->FORM_CONTROLLER, array($mode));
        $categories = $m->getCategories();

        switch ($mode) {
            case $this->FORM_ADD:
                $category = empty($_REQUEST['category']) ? key($categories) : $_REQUEST['category'];
                $form->setUrl($this->getUrlAdd("?category={$category}"));
                break;
            case $this->FORM_EDIT:
                $category = $m->getCategory();
                $form->setUrl($this->getUrlEdit($m->getId()));
                break;
            default:
                throw new DxException('Invalid manage command');
        }

        if (!array_key_exists($category, $categories)) {
            throw new DxException('Unknown category');
        }

        $m->setCategory($category);
        $form->setModel($m);
        $form->setTemplate($categories[$category]['form_tpl']);

        if ($form->isProcessed()) {
            switch ($mode) {
                case $this->FORM_ADD:
                    $url = $this->getUrlList();
                    break;
                case $this->FORM_EDIT:
                    $url = $form->getUrl();
                    $form->setSuccessful();
                    break;
            }

            $this->getURL()->redirect($url);
        }

        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'form_html' => $form->draw(),
        ));

        return $smarty->fetch($this->TMPL_MANAGE);
    }

    /**
     * @return void
     */
    protected function opDeleteImage()
    {
        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $id = !empty($_REQUEST['publication_image_id']) ? $_REQUEST['publication_image_id'] : 0;

        /** @var $q DomainObjectQuery_PublicationImage */
        $q = DxFactory::getSingleton('DomainObjectQuery_PublicationImage');

        $m = $q->findById($id);
        if (!$m) {
            throw new DxException('Invalid publication_image_id');
        }
        $publication_id = $m->getPublicationId();
        $m->remove();
        $this->getDomainObjectManager()->flush();
        $this->getUrl()->redirect($this->getUrlEdit($publication_id));
    }

    /**
     * @return void
     */
    protected function opCoverImage()
    {
        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $id = !empty($_REQUEST['publication_image_id']) ? $_REQUEST['publication_image_id'] : 0;

        /** @var $q DomainObjectQuery_PublicationImage */
        $q = DxFactory::getSingleton('DomainObjectQuery_PublicationImage');

        $m = $q->findById($id);
        if (!$m) {
            throw new DxException('Invalid publication_image_id');
        }

        $m->setIsCover(1);

        $cover = $q->findCurrentCover($m->getPublicationId());
        if (!is_null($cover)) {
            $cover->setIsCover(0);
        }

        $this->getDomainObjectManager()->flush();
        $this->getUrl()->redirect($this->getUrlEdit($m->getPublicationId()));
    }

    /**
     * @return void
     */
    protected function opShiftImage()
    {
        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $id = !empty($_REQUEST['publication_image_id']) ? $_REQUEST['publication_image_id'] : 0;

        /** @var $q DomainObjectQuery_PublicationImage */
        $q = DxFactory::getSingleton('DomainObjectQuery_PublicationImage');
        $m = $q->findById($id);
        if (!$m) {
            throw new DxException('Invalid publication_image_id');
        }

        if (!empty($_REQUEST['way']) && $_REQUEST['way'] == 'LEFT') {
            $shift_o = $q->findLeftImage($m->getQnt(), $m->getPublicationId());
        } else {
            $shift_o = $q->findRightImage($m->getQnt(), $m->getPublicationId());
        }

        if (!is_null($shift_o)) {
            $shift_qnt = $shift_o->getQnt();
            $qnt = $m->getQnt();
            $m->setQnt($shift_qnt);
            $shift_o->setQnt($qnt);
            $this->getDomainObjectManager()->flush();
        }
        $this->getUrl()->redirect($this->getUrlEdit($m->getPublicationId()));
    }
}