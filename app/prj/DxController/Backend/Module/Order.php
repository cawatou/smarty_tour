<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Order extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.order' => 'index',
    );

    /**
     * @return null
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'order_id';

        $this->CMD      = '.adm.order';
        $this->CMD_LIST = '.order.list';
        $this->CMD_ADD  = '.order.add';
        $this->CMD_EDIT = '.order.edit';

        $this->FORM_ADD        = 'order_add';
        $this->FORM_EDIT       = 'order_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Order';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Order';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Order';

        $this->TMPL_GROUP   = null;
        $this->TMPL_LIST    = 'backend/order_list.tpl.php';
        $this->TMPL_MANAGE  = 'backend/order_manage.tpl.php';
        $this->TMPL_SUBMENU = 'backend/submenu/order.tpl.php';

        $this->ITEMS_PER_PAGE = 30;
    }

    /**
     * @throws DxException
     * @return string
     */
    protected function opList()
    {
        if (!$this->canView()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var DomainObjectQuery_Order $q */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var Form_Filter_Backend_Order $filter */
        $filter = DxFactory::getInstance('Form_Filter_Backend_Order', array('fo'));
        $filter->setUrl($this->getUrlList());

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

        $dl->setParameters($parameters);

        $list =& $dl->getRequestedPage();
        $state = $dl->getState();

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
                        $url = $this->getUrlEdit($m->getId());
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

        $assign = array(
            'form_html' => $form->draw(),
        );

        if (!empty($_REQUEST['message'])) {
            $assign['message'] = $_REQUEST['message'];
        }

        $smarty->assign($assign);

        return $smarty->fetch($this->TMPL_MANAGE);
    }

    /**
     * @param string $mode
     * @return Form_Backend_Order|Form_Backend_OrderAdd
     */
    public function getForm($mode)
    {
        if ($mode == $this->FORM_EDIT) {
            $class = $this->FORM_CONTROLLER;
        } else {
            $class = 'Form_Backend_OrderAdd';
        }

        /** @var Form_Backend_Order|Form_Backend_OrderAdd $form */
        return DxFactory::getInstance($class, array($mode));
    }

    /**
     * @throws DxException
     * @return null
     */
    protected function opStatus()
    {
        throw new DxException("Unknown operation 'status'");
    }

    /**
     * @return null
     */
    protected function opDelete()
    {
        $model = $this->obtainRequestedModel();

        if (!$model || $model->getStatus() != 'NEW') {
            throw new DxException('Incorrect model');
        }

        parent::opDelete();
    }

    protected function opSend()
    {
        $m = $this->obtainRequestedModel();

        /** @var $mail Utils_Mail */
        $mail = DxFactory::getInstance('Utils_Mail');

        /** @var $qsettings DomainObjectQuery_Settings */
        $qsettings = DxFactory::getSingleton('DomainObjectQuery_Settings');
        $settings  = $qsettings->getByGroup('BUY_TOUR');

        $data = array(
            'model'    => $m,
            'settings' => $settings,
        );

        $subject = Utils_Mail::textOfTemplate('backend/mail/subject_order.tpl.php', $data);
        $body    = Utils_Mail::textOfTemplate('backend/mail/body_order.tpl.php',    $data);

        try {
            $mail->send($m->getCustomerEmail(), '', $subject, $body);
        } catch (DxException $e) {
        }

        if (empty($_REQUEST[$this->REQUEST_ID])) {
            $this->getUrl()->redirect($this->getUrl()->adm('.order'));
        }

        $this->getUrl()->redirect("{$this->getUrl()->adm('.order.edit')}?{$this->REQUEST_ID}={$_REQUEST[$this->REQUEST_ID]}&message=MAIL_SENT");
    }

    protected function opCancelPayment()
    {
        /** @var DomainObjectQuery_OrderPayment $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_OrderPayment');

        $order_payment_id = -1;

        if (!empty($_REQUEST['order_payment_id'])) {
            $order_payment_id = $_REQUEST['order_payment_id'];
        }

        /** @var DomainObjectModel_OrderPayment $payment */
        $payment = $q->findById($order_payment_id);

        if ($payment === null || $payment->getStatus() != 'PREAUTH') {
            $this->getUrl()->redirect("{$this->getUrl()->adm('.order.edit')}?{$this->REQUEST_ID}={$_REQUEST[$this->REQUEST_ID]}&message=PAYMENT_UNCANCELLABLE");
        }

        $cfg = DxApp::config('payonline');

        $data = array(
            'MerchantId'    => $cfg['merchant_id'],
            'TransactionId' => $payment->getTransactionId(),
            'SecurityKey'   => Utils_Payonline::getCrcCancelTransaction($payment->getTransactionId()),
        );

        $response = Utils_Payonline::request($cfg['url_cancel'], $data);
        $response = Utils_Payonline::parseRequestText($response);

        if ($response['Result'] == 'Ok') {
            $payment->setStatus('CANCELLED');
            $payment->save();
        }

        $this->getUrl()->redirect("{$this->getUrl()->adm('.order.edit')}?{$this->REQUEST_ID}={$_REQUEST[$this->REQUEST_ID]}&message=PAYMENT_CANCELLED");
    }

    protected function opCompletePayment()
    {
        /** @var DomainObjectQuery_OrderPayment $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_OrderPayment');

        $order_payment_id = -1;

        if (!empty($_REQUEST['order_payment_id'])) {
            $order_payment_id = $_REQUEST['order_payment_id'];
        }

        /** @var DomainObjectModel_OrderPayment $payment */
        $payment = $q->findById($order_payment_id);

        if ($payment === null) {
            $this->getUrl()->redirect("{$this->getUrl()->adm('.order.edit')}?{$this->REQUEST_ID}={$_REQUEST[$this->REQUEST_ID]}&message=PAYMENT_UNCOMPLETEABLE");
        }

        if ($payment->getStatus() == 'COMPLETED' || $payment->getStatus() == 'RESERVED') {
            $this->getUrl()->redirect("{$this->getUrl()->adm('.order.edit')}?{$this->REQUEST_ID}={$_REQUEST[$this->REQUEST_ID]}&message=PAYMENT_ALREADY_COMPLETED");
        }

        if ($payment->getStatus() != 'PREAUTH') {
            $this->getUrl()->redirect("{$this->getUrl()->adm('.order.edit')}?{$this->REQUEST_ID}={$_REQUEST[$this->REQUEST_ID]}&message=PAYMENT_UNCOMPLETEABLE");
        }

        $cfg = DxApp::config('payonline');

        $data = array(
            'MerchantId'    => $cfg['merchant_id'],
            'TransactionId' => $payment->getTransactionId(),
            'SecurityKey'   => Utils_Payonline::getCrcCompleteTransaction($payment->getTransactionId()),
        );

        $response = Utils_Payonline::request($cfg['url_complete'], $data);
        $response = Utils_Payonline::parseRequestText($response);

        if ($response['Result'] == 'Ok') {
            $payment->setStatus('RESERVED');
            $payment->setCompleted(new DxDateTime('now'));
            $payment->save();
        }

        $this->getUrl()->redirect("{$this->getUrl()->adm('.order.edit')}?{$this->REQUEST_ID}={$_REQUEST[$this->REQUEST_ID]}&message=PAYMENT_COMPLETED");
    }
}