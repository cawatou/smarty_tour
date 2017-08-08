<?php
class Utils_Payonline
{
    /**
     * @param integer     $order_id    Order ID
     * @param float       $amount      Order amount
     * @return string
     *
     * @static
     */
    static public function getCrcSend($order_id, $amount)
    {
        $conf = DxApp::config('payonline');

        $parts = array(
            'MerchantId=' . $conf['merchant_id'],
            'OrderId=' . $order_id,
            'Amount='.     $amount,
            'Currency='.   (empty($conf['currency']) ? 'RUB' : $conf['currency']),
        );

        /* @todo
        if ($description !== null) {
            $parts[] = 'OrderDescription='. $description;
        }
         */

        $parts[] = 'PrivateSecurityKey='. $conf['private_key'];

        return md5(implode('&', $parts));
    }

    /**
     * @param string  $datetime
     * @param string  $transaction_id
     * @param integer $order_id
     * @param float   $amount
     * @return string
     *
     * @static
     */
    static public function getCrcSuccess($datetime, $transaction_id, $order_id, $amount)
    {
        $conf = DxApp::config('payonline');

        $parts = array(
            'DateTime='.           $datetime,
            'TransactionID='.      $transaction_id,
            'OrderId='.            $order_id,
            'Amount='.             $amount,
            'Currency='.           (empty($conf['currency']) ? 'RUB' : $conf['currency']),
            'PrivateSecurityKey='. $conf['private_key'],
        );

        return md5(implode('&', $parts));
    }

    /**
     * @param string  $transaction_id
     * @return string
     *
     * @static
     */
    static public function getCrcCancelTransaction($transaction_id)
    {
        $conf = DxApp::config('payonline');

        $parts = array(
            'MerchantId='.         $conf['merchant_id'],
            'TransactionId='.      $transaction_id,
            'PrivateSecurityKey='. $conf['private_key'],
        );

        return md5(implode('&', $parts));
    }

    /**
     * @param integer|null $order_id
     * @param string|null  $transaction_id
     * @return string
     *
     * @static
     */
    static public function getCrcSearch($order_id = null, $transaction_id = null)
    {
        $conf = DxApp::config('payonline');

        if ($order_id === null) {
            // Check using TRANSACTION_ID
            $parts = array(
                'MerchantId='.         $conf['merchant_id'],
                'TransactionId='.      $transaction_id,
                'PrivateSecurityKey='. $conf['private_key'],
            );
        } else {
            // Check using ORDER_ID
            $parts = array(
                'MerchantId='.         $conf['merchant_id'],
                'OrderId='.            $order_id,
                'PrivateSecurityKey='. $conf['private_key'],
            );
        }

        return md5(implode('&', $parts));
    }

    /**
     * @param string     $transaction_id
     * @param float|null $amount
     * @return string
     *
     * @static
     */
    static public function getCrcCompleteTransaction($transaction_id, $amount = null)
    {
        $conf = DxApp::config('payonline');

        if ($amount === null) {
            $parts = array(
                'MerchantId='.         $conf['merchant_id'],
                'TransactionId='.      $transaction_id,
                'PrivateSecurityKey='. $conf['private_key'],
            );
        } else {
            $parts = array(
                'MerchantId='.         $conf['merchant_id'],
                'TransactionId='.      $transaction_id,
                'Amount='.             $amount,
                'PrivateSecurityKey='. $conf['private_key'],
            );
        }

        return md5(implode('&', $parts));
    }

    /**
     * @param string $transaction_id
     * @param float  $amount
     * @return string
     *
     * @static
     */
    static public function getCrcRefundTransaction($transaction_id, $amount)
    {
        $conf = DxApp::config('payonline');

        $parts = array(
            'MerchantId='.         $conf['merchant_id'],
            'TransactionId='.      $transaction_id,
            'Amount='.             $amount,
            'PrivateSecurityKey='. $conf['private_key'],
        );

        return md5(implode('&', $parts));
    }

    /**
     * @static
     * @param DomainObjectModel_Order $order
     * @return string
     */
    public static function getFormHtml(DomainObjectModel_Order $order)
    {
        $conf = DxApp::config('payonline');

        /** @var $smarty Smarty */
        $smarty = DxApp::getComponent(DxConstant_Project::ALIAS_SMARTY, true);

        $smarty->assign(
            array(
                'order' => $order,
                'conf'  => $conf,
                'crc'   => self::getCrcSend($order->getId(), $order->getPrice()),
            )
        );

        return $smarty->fetch('frontend/form/payment_payonline.tpl.php');
    }

    static public function prepareDescription($description)
    {
        if (!$description) {
            return null;
        }

        $description = mb_substr($description, 0, 100);

        $description = preg_replace('/[^a-zA-Zа-яА-Я0-9 \,\.\!\?\;\:\%\*\(\)-]+/u', '?', $description);

        return $description;
    }

    static public function prepareAmount($amount)
    {
        if (empty($amount)) {
            return null;
        }

        return sprintf('%0.2f', $amount);
    }

    static public function parseRequestText($request)
    {
        $parsed = array();

        parse_str($request, $parsed);

        return $parsed;
    }

    static public function parseRequestXml($request)
    {
        $request = json_decode(json_encode((array)@simplexml_load_string($request)), true);

        foreach ($request as $k => $r) {
            // Fix for empty array() instead of NULL/''/etc.
            if (empty($r)) {
                $r = null;

                $request[$k] = $r;
            }
        }

        return $request;
    }

    static public function request($url, array $data, $timeout = null)
    {
        $data = http_build_query($data);

        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $data,
            ),
        );

        if ($timeout !== null) {
            $options['http']['timeout'] = $timeout;
        }

        $context = stream_context_create($options);

        $res = @file_get_contents($url, false, $context);

        return empty($res) ? null : $res;
    }
}