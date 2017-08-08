<?php
/**
 * @method  int getId()
 * @method  string getTransactionId()
 * @method  float getAmount()
 * @method  string getStatus()
 * @method  string getResponse()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 *
 * @method  setId(int $arg)
 * @method  setOrderId(int $arg)
 * @method  setTransactionId(string $arg)
 * @method  setAmount(float $arg)
 * @method  setStatus(string $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 * @method  setOrder(DomainObjectModel_Order $arg)
 */
class DomainObjectModel_OrderPayment extends DomainObjectModel_BaseOrderPayment
{
    /** @var string */
    protected $field_prefix = 'order_payment';

    /** @var array */
    protected static $order_payment_statuses = array(
        'NEW' => array(
            'title' => 'Ожидаем оплату',
            'class' => 'default',
        ),
        'PREAUTH' => array(
            'title' => 'Денежные средства зарезервированы',
            'class' => 'warning',
        ),
        'RESERVED' => array(
            'title' => 'Денежные средства переводятся',
            'class' => 'warning',
        ),
        'CANCELLED' => array(
            'title' => 'Отменен',
            'class' => 'danger',
        ),
        'COMPLETED' => array(
            'title' => 'Выполнен',
            'class' => 'success',
        ),
    );

    public function setUp()
    {
        parent::setUp();

        $this->hasAccessor('order_payment_completed', 'getCompleted');
        $this->hasColumn('order_payment_response',    'array');
    }

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field == 'order_payment_status') {
            if (empty($this->order_payment_status) || !in_array($this->order_payment_status, array_keys(self::getPaymentStatuses()))) {
                throw new DxException("Invalid 'order_payment_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'order_payment_amount') {
            if (!is_numeric($this->order_payment_amount) || (int)$this->order_payment_amount < 0) {
                throw new DxException("Invalid 'order_payment_amount'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @static
     * @return array
     */
    public static function getPaymentStatuses()
    {
        return self::$order_payment_statuses;
    }

    /**
     * @return null|string
     */
    public function getStatusTitle()
    {
        $statuses = self::getPaymentStatuses();

        return !empty($statuses[$this->getStatus()]['title']) ? $statuses[$this->getStatus()]['title'] : null;
    }

    /**
     * @return null|string
     */
    public function getStatusClass()
    {
        $statuses = self::getPaymentStatuses();

        return !empty($statuses[$this->getStatus()]['class']) ? $statuses[$this->getStatus()]['class'] : 'default';
    }

    static public function getDefaultStatusTitle()
    {
        return self::$order_payment_statuses['NEW']['title'];
    }

    /**
     * @return null|DxDateTime
     */
    public function getCompleted()
    {
        if (!$this->getFieldValue('order_payment_completed', true)) {
            return null;
        }

        return new DxDateTime($this->getFieldValue('order_payment_completed', true));
    }

    /**
     * @param null|DxDateTime $order_payment_completed
     * @return null
     */
    public function setCompleted(DxDateTime $order_payment_completed = null)
    {
        if (empty($order_payment_completed)) {
            $order_payment_completed = null;
        }

        $this->setFieldValue('order_payment_completed', $order_payment_completed->toUTC()->getMySQLDateTime());
    }

    public function getPayonlineAmount()
    {
        return Utils_Payonline::prepareAmount($this->getAmount());
    }

    public function calculateCrcSend()
    {
        return Utils_Payonline::getCrcSend($this->getId(), $this->getPayonlineAmount());
    }

    /**
     * @return int|null
     */
    public function getOrderId()
    {
        return is_object($id = $this->getFieldValue('order_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @return DomainObjectModel_Order|null
     */
    public function getOrder()
    {
        return is_numeric($this->getOrderId()) ? $this->Order : null;
    }

    /**
     * @param DomainObjectModel_Order $order
     * @return DomainObjectModel_OrderPayment
     */
    public function setCountry(DomainObjectModel_Country $order)
    {
        $this->Order = $order;

        return $this;
    }

    /**
     * @param mixed       $data
     * @param string|null $key
     * @return mixed
     */
    public function setResponse($data, $key = null)
    {
        if (empty($data)) {
            return parent::setResponse(null);
        }

        if (null === $key) {
            $_data = $data;
        } else {
            $_data = $this->getResponse();
            $_data[$key] = $data;
        }

        return parent::setResponse($_data);
    }

    public function checkStatus()
    {
        $cfg = DxApp::config('payonline');

        $data = array(
            'MerchantId'  => $cfg['merchant_id'],
            'OrderId'     => $this->getId(),
            'SecurityKey' => Utils_Payonline::getCrcSearch($this->getId()),
        );

        $response = Utils_Payonline::request($cfg['url_search'], $data, 2);

        if ($response === null) {
            return null;
        }

        $response = Utils_Payonline::parseRequestText($response);

        $map = array(
            'Pending' => 'RESERVED',
            'Settled' => 'COMPLETED',
            'Voided'  => 'CANCELLED',
        );

        if (isset($map[$response['Status']]) && $this->getStatus() != $map[$response['Status']]) {
            $this->setStatus($map[$response['Status']]);
            $this->setCompleted(new DxDateTime());
            $this->save();
        }
    }
}