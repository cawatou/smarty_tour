<?php
DxFactory::import('DxController_Frontend');

class DxController_Frontend_DemoPayonline extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.payonline'            => 'index',
        '.payonline.success'    => 'success',
        '.payonline.success.cb' => 'successCallback',
        '.payonline.fail'       => 'fail',
        '.payonline.fail.xb'    => 'failCallback',
        '.payonline.search'     => 'search',
        '.payonline.confirm'    => 'confirm',
        '.payonline.cancel'     => 'cancel',
        '.payonline.refund'     => 'refund',
    );

    /**
     * @return string
     */
    protected function index()
    {
        $cfg = DxApp::config('payonline');

        $data = array(
            'order_id'    => 51,
            'amount'      => 1,
            'description' => 'Из Новосибирска на Ибицу _@#$%^&*('
        );

        $data['amount'] = Utils_Payonline::prepareAmount($data['amount']);

        $data['description'] = Utils_Payonline::prepareDescription($data['description']);
        $data['description'] = null;
        $data['signature']   = Utils_Payonline::getCrcSend($data['order_id'], $data['amount'], $data['description']);

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'data' => $data,
                'crc'  => $data['signature'],
                'cfg'  => $cfg,
            )
        );

        $html = $smarty->fetch('frontend/form/payment_payonline_demo.tpl.php');

        return $this->wrap($html);
    }

    /**
     * @return string
     */
    protected function success()
    {
        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
            )
        );

        $html = $smarty->fetch('frontend/payonline_success.tpl.php');

        return $this->wrap($html);
    }

    /**
     * @return string
     */
    protected function successCallback()
    {
        $order_id = (empty($_REQUEST['OrderId']) ? '-1' : $_REQUEST['OrderId']);

        $check_sign = Utils_Payonline::getCrcSuccess($_REQUEST['DateTime'], $_REQUEST['TransactionID'], $_REQUEST['OrderId'], $_REQUEST['Amount']);

        $to_save = print_r($_REQUEST, true);

        if (empty($_REQUEST['SecurityKey']) || $_REQUEST['SecurityKey'] != $check_sign) {
            $to_save .= PHP_EOL .'INCORRECT SIGNATURE RECEIVED'. PHP_EOL .'Received: '. $_REQUEST['SecurityKey'] . PHP_EOL .'Expected: '. $check_sign;
        }

        file_put_contents(DX_VAR_DIR .'/log/payonline/success/'. $order_id .'_'. date('Y-m-d H i s.txt'), $to_save);
        exit(1);
    }

    /**
     * @return string
     */
    protected function fail()
    {
        $error_msg = 'Сбой в работе сервиса "Payonline"';

        if (!empty($_REQUEST['ErrorCode'])) {
            switch ($_REQUEST['ErrorCode']) {
                case 1:
                        $error_msg = 'Возникла техническая ошибка в работе сервиса "Payonline", плательщику стоит повторить попытку оплаты спустя некоторое время';
                    break;
                case 2:
                        $error_msg = 'Провести платеж по указанной банковской карте невозможно. Вам стоит воспользоваться другим способом оплаты, либо использовать другую карту.';
                    break;
                case 3:
                        $error_msg = 'Платеж отклоняется банком-эмитентом карты. Вам стоит связаться с банком, выяснить причину отказа и повторить попытку оплаты';
                    break;
                default:
                    break;
            }
        }

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'error_msg' => $error_msg,
            )
        );

        $html = $smarty->fetch('frontend/payonline_fail.tpl.php');

        return $this->wrap($html);
    }

    /**
     * @return string
     */
    protected function failCallback()
    {
        $order_id = $this->getContext()->getCurrentCommand()->getArguments('order_id');

        if (empty($order_id)) {
            $order_id = '-1';
        }

        file_put_contents(DX_VAR_DIR .'/log/payonline/fail/'. $order_id .'_'. date('Y-m-d H i s.txt'), print_r($_REQUEST, true));

        exit(1);
    }

    protected function search()
    {
        $cfg = DxApp::config('payonline');

        $data = array(
            'merchant_id'  => $cfg['merchant_id'],
            'order_id'     => 13,
            'content_type' => 'text',
        );

        $data['signature'] = Utils_Payonline::getCrcSearch($data['order_id']);

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'data' => $data,
                'crc'  => $data['signature'],
                'cfg'  => $cfg,
            )
        );

        return $smarty->fetch('frontend/form/payment_payonline_demo_search.tpl.php');
    }

    protected function confirm()
    {
        $cfg = DxApp::config('payonline');

        $data = array(
            'merchant_id'    => $cfg['merchant_id'],
            'transaction_id' => 26185987,
            'content_type'   => 'text',
        );

        $data['amount'] = null;

        $data['signature'] = Utils_Payonline::getCrcCompleteTransaction($data['transaction_id']);

        if (!empty($data['amount'])) {
            $data['signature'] = Utils_Payonline::getCrcCompleteTransaction($data['transaction_id'], $data['amount']);
        }

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'data' => $data,
                'crc'  => $data['signature'],
                'cfg'  => $cfg,
            )
        );

        return $smarty->fetch('frontend/form/payment_payonline_demo_confirm.tpl.php');
    }

    protected function cancel()
    {
        $cfg = DxApp::config('payonline');

        $data = array(
            'merchant_id'    => $cfg['merchant_id'],
            'transaction_id' => 26186437,
            'content_type'   => 'text',
        );

        $data['signature'] = Utils_Payonline::getCrcCancelTransaction($data['transaction_id']);

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'data' => $data,
                'crc'  => $data['signature'],
                'cfg'  => $cfg,
            )
        );

        return $smarty->fetch('frontend/form/payment_payonline_demo_cancel.tpl.php');
    }

    protected function refund()
    {
        $cfg = DxApp::config('payonline');

        $data = array(
            'merchant_id'    => $cfg['merchant_id'],
            'transaction_id' => 26186620,
            'amount'         => 3,
            'content_type'   => 'text',
        );

        $data['amount'] = Utils_Payonline::prepareAmount($data['amount']);

        $data['signature'] = Utils_Payonline::getCrcRefundTransaction($data['transaction_id'], $data['amount']);

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'data' => $data,
                'crc'  => $data['signature'],
                'cfg'  => $cfg,
            )
        );

        return $smarty->fetch('frontend/form/payment_payonline_demo_refund.tpl.php');
    }
}