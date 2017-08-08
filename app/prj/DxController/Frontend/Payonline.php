<?php
DxFactory::import('DxController_Frontend');

class DxController_Frontend_Payonline extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.payonline.success' => 'success',
        '.payonline.fail'    => 'fail',
    );

    /**
     * @return string
     */
    protected function success()
    {
        $payment_id = (empty($_REQUEST['OrderId']) ? '-1' : $_REQUEST['OrderId']);

        $check_sign = Utils_Payonline::getCrcSuccess($_REQUEST['DateTime'], $_REQUEST['TransactionID'], $_REQUEST['OrderId'], $_REQUEST['Amount']);

        if (empty($_REQUEST['SecurityKey']) || $_REQUEST['SecurityKey'] != $check_sign) {
            throw new DxException('Incorrect security key');
        }

        /** @var $q DomainObjectQuery_OrderPayment */
        $q = DxFactory::getSingleton('DomainObjectQuery_OrderPayment');

        $payment = $q->findById($payment_id);

        if ($payment === null || $payment->getStatus() != 'NEW') {
            throw new DxException('Unknown invoice');
        }

        if ($_REQUEST['Amount'] != $payment->getAmount()) {
            throw new DxException('Invalid amount');
        }

        $payment->setStatus('PREAUTH');
        $payment->setTransactionId($_REQUEST['TransactionID']);

        $payment->setResponse(
            array(
                'PREAUTH' => array(
                    'data' => $_REQUEST,
                    'date' => date('Y-m-d H:i:s'),
                ),
            )
        );

        $payment->save();

        exit(1);
    }

    /**
     * @return string
     */
    protected function fail()
    {
        $payment_id = (empty($_REQUEST['OrderId']) ? '-1' : $_REQUEST['OrderId']);

        /** @var $q DomainObjectQuery_OrderPayment */
        $q = DxFactory::getSingleton('DomainObjectQuery_OrderPayment');

        $payment = $q->findById($payment_id);

        if ($payment === null || $payment->getStatus() != 'NEW') {
            throw new DxException('Unknown invoice');
        }

        $payment->setResponse(
            array(
                'FAILED' => array(
                    'data' => $_REQUEST,
                    'date' => date('Y-m-d H:i:s'),
                ),
            )
        );

        exit(1);
    }
}