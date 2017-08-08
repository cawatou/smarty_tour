<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Product extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.product' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'product_id';

        $this->CMD      = '.adm.product';
        $this->CMD_LIST = '.product.list';
        $this->CMD_COPY = '.product.copy';
        $this->CMD_ADD  = '.product.add';
        $this->CMD_EDIT = '.product.edit';

        $this->FORM_ADD  = 'product_add';
        $this->FORM_COPY = 'product_copy';
        $this->FORM_EDIT = 'product_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Product';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Product';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Product';

        $this->TMPL_GROUP  = 'product';
        $this->TMPL_LIST   = 'backend/product_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/product_manage.tpl.php';

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

        /** @var $q  DomainObjectQuery_Product */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if (isset($_POST['__change'])) {
            if (!$this->canEdit()) {
                throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
            }

            foreach ($_POST['product_qnt'] as $id => $val) {
                $o = $q->findById($id);

                if ($o !== null) {
                    $o->setQnt(empty($val) ? 0 : $val);
                }
            }

            foreach ($_POST['product_is_highlight'] as $id => $val) {
                $o = $q->findById($id);

                if ($o !== null) {
                    $o->setIsHighlight(empty($val) ? 0 : $val);
                }
            }

            $this->getDomainObjectManager()->flush();

            // Remove all of the caches, so new data can be propogated into them
            $this->getSmarty()->clearAllCache();

            $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
            $this->getURL()->redirect($url);
        }

        /** @var Form_Filter_Backend_Product $filter */
        $filter = DxFactory::getInstance('Form_Filter_Backend_Product', array('fp'));
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

        $current_user = $this->getContext()->getCurrentUser();

        if ($current_user->getRole() === 'OPERATOR' || $current_user->getRole() === 'DIRECTOR') {
            $froms = array(
                0,
            );

            foreach ($current_user->getUser()->getFroms() as $from) {
                if (!empty($from['is_shown'])) {
                    $froms[] = $from['departure_id'];
                }
            }

            $parameters['s']['product_from_ids'] = $froms;
        }

        $parameters['s']['product_only_parent'] = true;

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

    /**
     * @param bool $new
     * @return DomainObjectModel
     * @throws DxException
     */
    protected function obtainRequestedModel($new = false)
    {
        /** @var $q DomainObjectQuery_Product */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        if (!$new) {
            $m = $q->findById(empty($_REQUEST[$this->REQUEST_ID]) ? 0 : $_REQUEST[$this->REQUEST_ID]);

            if (!$m) {
                throw new DxException('Invalid ' . $this->REQUEST_ID);
            }

            return $m;
        }

        /** @var $m DomainObjectModle_Product */
        $m = DxFactory::getInstance($this->DOMAIN_OBJECT_MODEL);

        if (!empty($_REQUEST['country_id'])) {
            $m->setCountryId($_REQUEST['country_id']);
        }

        if (!empty($_REQUEST['resort_id'])) {
            $m->setResortId($_REQUEST['resort_id']);
        }

        return $m;
    }

    /**
     * @throws DxException
     * @return void
     */
    protected function opCoverImage()
    {
        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $id = !empty($_REQUEST['product_image_id']) ? $_REQUEST['product_image_id'] : 0;

        /** @var $q DomainObjectQuery_ProductImage */
        $q = DxFactory::getSingleton('DomainObjectQuery_ProductImage');

        $pi = $q->findById($id);
        if (!$pi) {
            throw new DxException('Invalid product_image_id');
        }

        $cover = $q->findCurrentCover($pi->getProductId());
        if (!is_null($cover)) {
            $cover->setIsCover(0);
        }

        $pi->setIsCover(1);

        $this->getDomainObjectManager()->flush();
        $this->getUrl()->redirect($this->getUrlEdit($pi->getProductId()));
    }

    /**
     * @throws DxException
     * @return void
     */
    protected function opDeleteImage()
    {
        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $id = !empty($_REQUEST['product_image_id']) ? $_REQUEST['product_image_id'] : 0;

        /** @var $q DomainObjectQuery_ProductImage */
        $q = DxFactory::getSingleton('DomainObjectQuery_ProductImage');

        $pi = $q->findById($id);
        if (!$pi) {
            throw new DxException('Invalid product_image_id');
        }
        $pi->remove();

        $product_id = $pi->getProductId();

        $this->getDomainObjectManager()->flush();
        $this->getUrl()->redirect($this->getUrlEdit($product_id));
    }

    /**
     * @return Form_Backend
     */
    public function getForm($mode)
    {
        /** @var $form Form_Backend */
        $form = DxFactory::getInstance($this->FORM_CONTROLLER, array($mode));

        $form->setContext($this->getContext());

        return $form;
    }

    /**
     * @return void
     */
    protected function opDelete()
    {
        if (!$this->canDelete()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $model = $this->obtainRequestedModel();

        if ($this->getContext()->getCurrentUser()->isUserInRoles('OPERATOR')) {
            if ($model->getUserId() != $this->getContext()->getCurrentUser()->getUser()->getId()) {
                throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
            }
        }

        $model->remove();
        $this->getDomainObjectManager()->flush();
        $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
        $this->getUrl()->redirect($url);
    }

    /**
     * @return string
     */
    protected function opFroms()
    {
        if (!$this->getContext()->getCurrentUser()->canEdit('.adm.product.froms')) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $this->TMPL_GROUP = 'product_from';

        /** @var DomainObjectQuery_ProductFrom $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_ProductFrom');

        if (isset($_POST['__change'])) {
            foreach ($_POST['product_from'] as $id => $val) {
                $o = $q->findById($id);

                if ($o === null) {
                    $o = DxFactory::getInstance('DomainObjectModel_ProductFrom');

                    $o->setId($id);
                }

                if ($o !== null) {
                    try {
                        $o->setDate(empty($val['date']) ? null : new DxDateTime($val['date']));
                    } catch (Exception $e) {
                        $o->setDate(null);
                    }
                }
            }

            $smarty = DxApp::getComponent(DxConstant_Project::ALIAS_SMARTY);

            $smarty->clearCache('frontend/master_main.tpl.php');
            $smarty->clearCache('frontend/master_russia.tpl.php');

            $this->getDomainObjectManager()->flush();
            $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
            $this->getURL()->redirect($url);
        }

        /** @var DomainObjectModel_Product $product */
        $product = DxFactory::getSingleton('DomainObjectModel_Product');

        $product_froms   = $product->getFromAll();
        $_existing_froms = $q->findAll();
        $existing_froms  = array();

        $users_froms = null;

        if ($this->getContext()->getCurrentUser()->getRole() == 'OPERATOR') {
            $_users_froms = $this->getContext()->getCurrentUser()->getUser()->getFroms();

            $users_froms = array();

            foreach ($_users_froms as $from) {
                $users_froms[$from['departure_id']] = $from;
            }
        }

        foreach ($_existing_froms as $from) {
            if ($users_froms === null || !empty($users_froms[$from->getId()]['is_shown'])) {
                $existing_froms[$from->getId()] = $from;
            } else {
                unset($product_froms[$from->getId()]);
            }
        }

        unset($_existing_froms);

        $froms_struct = array();

        foreach ($product_froms as $from_id => $from) {
            $froms_struct[$from_id] = array(
                'id'    => $from_id,
                'title' => $from['title_from'],
                'date'  => null,
            );

            if (!empty($existing_froms[$from_id])) {
                if ($existing_froms[$from_id]->getDate() !== null) {
                    $froms_struct[$from_id]['date'] = $existing_froms[$from_id]->getDate();
                }
            }
        }

        unset($existing_froms, $product_froms);

        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'froms' => $froms_struct,
            )
        );

        return $smarty->fetch('backend/product_from_list.tpl.php');
    }

    /**
     * @return string
     */
    protected function opDiscounts()
    {
        return $this->baseDiscounts('DISCOUNT');
    }

    /**
     * @return string
     */
    protected function opPromoprice()
    {
        return $this->baseDiscounts('PROMO');
    }

    protected function baseDiscounts($type)
    {
        if (!$this->getContext()->getCurrentUser()->canEdit($type == 'DISCOUNT' ? '.adm.product.discounts' : '.adm.product.promoprice')) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $this->TMPL_GROUP = 'discount';

        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        /** @var DomainObjectQuery_Discount $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_Discount');

        $default_discount = $q->findDefault($type);

        $existing_discounts = $q->findByType($type);
        $existing_discounts_ids = array();

        foreach ($existing_discounts as $k => $d) {
            if ($default_discount) {
                if ($d->getId() == $default_discount->getId()) {
                    unset($existing_discounts[$k]);

                    continue;
                }
            }

            $existing_discounts_ids[] = $d->getId();
        }

        if (array_key_exists('__change', $_POST)) {
            if (!$default_discount) {
                $default_discount = new DomainObjectModel_Discount;

                $default_discount->setQnt(99999);
                $default_discount->setType($type);
            }

            $default_discount_percent = 0;

            if (!empty($_POST['default_discount'])) {
                $default_discount_percent = (int)$_POST['default_discount'];

                if ($default_discount_percent <= 0 || $default_discount_percent > 100) {
                    $default_discount_percent = 0;
                }
            }

            $default_discount->setPercent($default_discount_percent);

            $k = 0;

            $alreadyExists = array();

            foreach ($_POST['discounts'] as $id => $val) {
                if (empty($val['discount_percent']) || $val['discount_percent'] <= 0 || $val['discount_percent'] > 100) {
                    continue;
                }

                if (empty($val['country_id']) && empty($val['touroperator_id']) && empty($val['departure_city_id']) && empty($val['price_min']) && empty($val['price_max'])) {
                    continue;
                }

                $alreadyExistsKey =
                    (empty($val['country_id']) ? '' : (int)$val['country_id'])
                        .'|'.
                    (empty($val['touroperator_id']) ? '' : (int)$val['touroperator_id'])
                        .'|'.
                    (empty($val['departure_city_id']) ? '' : (int)$val['departure_city_id'])
                        .'|'.
                    (empty($val['price_min']) ? '' : (int)$val['price_min'])
                        .'|'.
                    (empty($val['price_max']) ? '' : (int)$val['price_max']);

                if (!empty($alreadyExists[$alreadyExistsKey])) {
                    continue;
                }

                $alreadyExists[$alreadyExistsKey] = true;

                $k++;

                if ($id[0] == '_') {
                    $o = DxFactory::getInstance('DomainObjectModel_Discount');
                } else {
                    $o = $q->findById($id);

                    if (in_array($id, $existing_discounts_ids)) {
                        unset($existing_discounts_ids[array_search($id, $existing_discounts_ids)]);
                    }
                }

                if ($o === null) {
                    $o = new DomainObjectModel_Discount;
                }

                $o->setQnt($k);

                $o->setType($type);
                $o->setCountryId(empty($val['country_id'])              ? null : $val['country_id']);
                $o->setTouroperatorId(empty($val['touroperator_id'])    ? null : $val['touroperator_id']);
                $o->setDepartureCityId(empty($val['departure_city_id']) ? null : $val['departure_city_id']);
                $o->setPriceMin(empty($val['price_min'])                ? null : $val['price_min']);
                $o->setPriceMax(empty($val['price_max'])                ? null : $val['price_max']);

                $o->setPercent(empty($val['discount_percent']) ? null : $val['discount_percent']);
            }

            if (!empty($existing_discounts_ids)) {
                $toRemove = $q->findByIds($existing_discounts_ids);

                foreach ($toRemove as $r) {
                    $r->remove();
                }
            }

            // Remove all of the caches, so new data can be propogated into them
            $smarty->clearAllCache();

            $this->getDomainObjectManager()->flush();
            $url = empty($_SERVER['HTTP_REFERER']) ? $this->getUrlList() : $_SERVER['HTTP_REFERER'];
            $this->getURL()->redirect($url);
        }

        /** @var DomainObjectModel_Product $product */
        $product = DxFactory::getSingleton('DomainObjectModel_Product');

        $all_froms = $product->getFromAll();

        foreach ($existing_discounts as $k => $discount) {
            if ($discount->getDepartureCityId() === null && $discount->getCountryId() === null && $discount->getTouroperatorId() === null && $discount->getPriceMin() === null && $discount->getPriceMax() === null) {
                unset($existing_discounts[$k]);

                break;
            }
        }

        if ($default_discount === null) {
            $default_discount = new DomainObjectModel_Discount;

            $default_discount->setPercent(5);
            $default_discount->setType($type);
        }

        if ($this->getContext()->getCurrentUser()->getRole() == 'OPERATOR') {
            $_users_froms = $this->getContext()->getCurrentUser()->getUser()->getFroms();

            $all_froms = array();

            foreach ($_users_froms as $from) {
                $all_froms[$from['departure_id']] = $from;
            }
        }

        /** @var DomainObjectQuery_Touroperator $q_to */
        $q_to = DxFactory::getSingleton('DomainObjectQuery_Touroperator');

        /** @var DomainObjectQuery_Country $q_co */
        $q_co = DxFactory::getSingleton('DomainObjectQuery_Country');

        $smarty->assign(
            array(
                'default_discount'   => $default_discount,
                'existing_discounts' => $existing_discounts,
                'froms_list'         => $all_froms,
                'touroperator_list'  => $q_to->findAll(true),
                'country_list'       => $q_co->findAll(true),
            )
        );

        return $smarty->fetch('backend/discount_list.tpl.php');
    }

    /**
     * @param string $mode
     * @param DomainObjectModel $m
     * @return string
     */
    protected function manage($mode, DomainObjectModel $m)
    {
        /**
         * @var Form_Backend_Product $form
         */
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

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

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
                default:
                    break;
            }

            $this->getUrl()->redirect($url);
        }

        $smarty->assign(
            array(
                'form_html' => $form->draw(),
            )
        );

        return $smarty->fetch($this->TMPL_MANAGE);
    }

    /**
     * @return string
     */
    protected function opAds()
    {
        if (!$this->getContext()->getCurrentUser()->canEdit('.adm.product.ads')) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        $this->TMPL_GROUP = 'product_ads';

        /** @var DomainObjectQuery_Product $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_Product');

        if (!empty($_REQUEST['type']) && !empty($_REQUEST['product_id'])) {
            $o = $q->findById($_REQUEST['product_id']);

            if ($o !== null && $o->getIsHighlight()) {
                try {
                    $o->setIsHighlight(0);
                } catch (Exception $e) {
                }
            }

            $this->getDomainObjectManager()->flush();
            $this->getURL()->redirect('/adm/product/ads');
        }

        $ads = $q->findForAdsList();

        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'ads' => $ads,
            )
        );

        return $smarty->fetch('backend/product_ads_list.tpl.php');
    }
}