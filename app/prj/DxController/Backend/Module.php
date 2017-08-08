<?php

DxFactory::import('DxController_Backend');

abstract class DxController_Backend_Module extends DxController_Backend
{
    protected $REQUEST_ID = null;

    protected $CMD      = null;
    protected $CMD_LIST = null;
    protected $CMD_ADD  = null;
    protected $CMD_COPY = null;
    protected $CMD_EDIT = null;

    protected $FORM_ADD        = null;
    protected $FORM_COPY       = null;
    protected $FORM_EDIT       = null;
    protected $FORM_CONTROLLER = null;

    protected $DOMAIN_OBJECT_QUERY = null;
    protected $DOMAIN_OBJECT_MODEL = null;

    protected $TMPL_GROUP  = null;
    protected $TMPL_LIST   = null;
    protected $TMPL_MANAGE = null;

    protected $OP_DEFAULT = 'list';

    protected $ITEMS_PER_PAGE = 30;

    abstract protected function setEnvVar();

    /**
     * @param DxAppContext         $ctx
     * @param DxCommandHook|null   $hook
     */
    public function __construct(DxAppContext $ctx, DxCommandHook $hook = null)
    {
        parent::__construct($ctx, $hook);
        $this->setEnvVar();
    }

    /**
     * @param null $suffix
     * @return string
     */
    protected function getUrlList($suffix = null)
    {
        return $this->getUrl()->adm($this->CMD_LIST, $suffix);
    }

    /**
     * @param null $suffix
     * @return string
     */
    protected function getUrlAdd($suffix = null)
    {
        return $this->getUrl()->adm($this->CMD_ADD, $suffix);
    }
    /**
     * @param $request_id
     * @param null $suffix
     * @return string
     */
    protected function getUrlCopy($request_id, $suffix = null)
    {
        if (null === $suffix) {
            $suffix = '?' . $this->REQUEST_ID . "={$request_id}";
        } else {
            $suffix = $suffix . '&' . $this->REQUEST_ID . "={$request_id}";
        }

        return $this->getUrl()->adm($this->CMD_COPY, $suffix);
    }

    /**
     * @param $request_id
     * @param null $suffix
     * @return string
     */
    protected function getUrlEdit($request_id, $suffix = null)
    {
        if (null === $suffix) {
            $suffix = '?' . $this->REQUEST_ID . "={$request_id}";
        } else {
            $suffix = $suffix . '&' . $this->REQUEST_ID . "={$request_id}";
        }

        return $this->getUrl()->adm($this->CMD_EDIT, $suffix);
    }

    /**
     * @return string
     */
    protected function index()
    {
        $op = $this->getContext()->getCurrentCommand()->getArguments('op', $this->OP_DEFAULT);

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'op'    => $op,
            'group' => $this->TMPL_GROUP,
        ));

        $op = explode('_', $op);
        foreach ($op as $k => $v) {
            $op[$k] = ucwords($v);
        }

        $method = 'op' . implode('', $op);
        $html   = $this->$method();
        return $this->wrap($html);
    }

    /**
     * @param $name
     * @param $arguments
     * @throws DxException
     */
    public function __call($name, $arguments)
    {
        throw new DxException("Unknown operation '{$name}'");
    }

    /**
     * @return string
     */
    protected function opList()
    {
        /** @var $q  DomainObjectQuery */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageName('page');
        $dl->setPaginatorPageUrl($this->getUrlList('?page=%s'));
        $dl->setItemsPerPage($this->ITEMS_PER_PAGE);

        $list =& $dl->getRequestedPage();
        $state = $dl->getState();

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'list'  => $list,
            'state' => $state,
        ));
        return $smarty->fetch($this->TMPL_LIST);
    }

    /**
     * @return string
     */
    protected function opAdd()
    {
        if (!$this->canCreate()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        return $this->manage($this->FORM_ADD, $this->obtainRequestedModel(true));
    }

    /**
     * @return string
     */
    protected function opEdit()
    {
        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        return $this->manage($this->FORM_EDIT, $this->obtainRequestedModel());
    }

    /**
     * @throws DxException
     * @return string
     */
    protected function opCopy()
    {
        if (!$this->canCreate()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }
        return $this->manage($this->FORM_COPY, $this->obtainRequestedModel());
    }

    /**
     * @return void
     */
    protected function opDelete()
    {
        if (!$this->canDelete()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $this->obtainRequestedModel()->remove();
        $this->getDomainObjectManager()->flush();
        $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
        $this->getUrl()->redirect($url);
    }

    /**
     * @throws DxException
     * @return void
     */
    protected function opStatus()
    {
        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $m = $this->obtainRequestedModel();
        $m->setStatus($m->getStatus() == 'ENABLED' ? 'DISABLED' : 'ENABLED');
        $this->getDomainObjectManager()->flush();
        $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
        $this->getUrl()->redirect($url);
    }

    /**
     * @param bool $new
     * @return DomainObjectModel
     * @throws DxException
     */
    protected function obtainRequestedModel($new = false)
    {
        /** @var $q DomainObjectQuery */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if ($new) {
            /** @var $m DomainObjectModle */
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
     * @param string $mode
     * @param DomainObjectModel $m
     * @return string
     */
    protected function manage($mode, DomainObjectModel $m)
    {
        $form = $this->getForm($mode);
        $form->setModel($m);

        switch ($mode) {
            case $this->FORM_ADD:
                $form->setUrl($this->getUrlAdd());
                break;
            case $this->FORM_COPY:
                $form->setUrl($this->getUrlCopy($m->getId()));
                break;
            case $this->FORM_EDIT:
                $form->setUrl($this->getUrlEdit($m->getId()));
                break;
            default:
                throw new DxException('Invalid manage command');
        }

        if ($form->isProcessed()) {
            switch ($mode) {
                case $this->FORM_ADD:
                    $url = $this->getUrlList();
                    break;
                case $this->FORM_COPY:
                    $url = $this->getUrlList();
                    break;
                case $this->FORM_EDIT:
                    $url = $form->getUrl();
                    $form->setSuccessful();
                    break;
            }
            $this->getUrl()->redirect($url);
        }

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'form_html' => $form->draw(),
        ));

        return $smarty->fetch($this->TMPL_MANAGE);
    }

    /**
     * @param string $mode
     * @return Form_Backend
     */
    public function getForm($mode)
    {
        /** @var $form Form_Backend */
        return DxFactory::getInstance($this->FORM_CONTROLLER, array($mode));
    }

    /**
     * @throws DxException
     * @return bool
     */
    public function canCreate()
    {
        return $this->getContext()->getCurrentUser()->canCreate($this->CMD);
    }

    /**
     * @return bool
     */
    public function canView()
    {
        return $this->getContext()->getCurrentUser()->canView($this->CMD);
    }

    /**
     * @return bool
     */
    public function canEdit()
    {
        return $this->getContext()->getCurrentUser()->canEdit($this->CMD);
    }

    /**
     * @return bool
     */
    public function canDelete()
    {
        return $this->getContext()->getCurrentUser()->canDelete($this->CMD);
    }
}