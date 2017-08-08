<?php
/**
 * @method int getId()
 * @method string getSignature()
 * @method string getStatus()
 * @method float getPriceOpening()
 * @method float getPrice()
 * @method string getContract()
 * @method string getCustomerName()
 * @method string getCustomerPhone()
 * @method string getCustomerEmail()
 * @method int getCustomerTotalAdults()
 * @method int getCustomerTotalChildren()
 * @method string getCustomerIp()
 * @method string getComment()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 * @method DomainObjectModel_OrderPayment[] getOrderPayments()
 *
 * @method setId(int $arg)
 * @method setProductId(int $arg)
 * @method setSignature(string $arg)
 * @method setStatus(string $arg)
 * @method setPriceOpening(float $arg)
 * @method setPrice(float $arg)
 * @method setContract(string $arg)
 * @method setCustomerName(string $arg)
 * @method setCustomerPhone(string $arg)
 * @method setCustomerEmail(string $arg)
 * @method setCustomerTotalAdults(int $arg)
 * @method setCustomerTotalChildren(int $arg)
 * @method setCustomerIp(string $arg)
 * @method setComment(string $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Order extends DomainObjectModel_BaseOrder
{
    /** @var string */
    protected $field_prefix = 'order';

    /** @var array */
    protected static $order_statuses = array(
        'NEW'         => 'Новый',
        'IN_PROGRESS' => 'В работе',
        'CANCELLED'   => 'Отменен',
        'COMPLETED'   => 'Выполнен',
    );

    protected $order_payments = null;

    protected $ref_country = null;
    protected $ref_resort  = null;

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field == 'order_status') {
            if (empty($this->order_status) || !in_array($this->order_status, array_keys(self::getOrderStatuses()))) {
                throw new DxException("Invalid 'order_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'order_customer_name') {
            if (empty($this->order_customer_name) || !is_string($this->order_customer_name)) {
                throw new DxException("Invalid 'order_customer_name'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'order_customer_phone') {
            if (empty($this->order_customer_phone)) {
                throw new DxException("Invalid 'order_customer_phone'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            } elseif (!is_numeric($phone = preg_replace('~[^\d]+~', '', $this->order_customer_phone)) || empty($phone)) {
                throw new DxException("Invalid 'order_customer_phone'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field == 'order_customer_email') {
            if (empty($this->order_customer_email)) {
                throw new DxException("Invalid 'order_customer_email'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            } elseif (filter_var($this->order_customer_email, FILTER_VALIDATE_EMAIL) === false) {
                throw new DxException("Invalid 'order_customer_email'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field == 'product_id') {
            if ($this->product_id !== null && !is_numeric($this->product_id)) {
                throw new DxException("Invalid 'product_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'order_price') {
            if ($this->order_price !== null) {
                if (!is_numeric($this->order_price) || (int)$this->order_price < 0) {
                    throw new DxException("Invalid 'order_price'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
                }
            }
        }

        if ($field === null || $field == 'order_price_opening') {
            if (!is_numeric($this->order_price_opening) || (int)$this->order_price_opening < 0) {
                throw new DxException("Invalid 'order_price_opening'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'order_comment') {
            if ($this->order_comment !== null && (empty($this->order_comment) || !is_string($this->order_comment))) {
                throw new DxException("Invalid 'order_comment'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasColumn('order_customer_data', 'array');
        $this->hasColumn('order_product_data',  'array');
        $this->hasColumn('order_hotel_data',    'array');
    }

    /**
     * @static
     * @return array
     */
    public static function getOrderStatuses()
    {
        return self::$order_statuses;
    }

    /**
     * @return null|string
     */
    public function getStatusName()
    {
        $statuses = self::getOrderStatuses();
        return isset($statuses[$this->getStatus()]) ? $statuses[$this->getStatus()] : null;
    }

    /**
     * @return int|null
     */
    public function getProductId()
    {
        return is_object($id = $this->getFieldValue('product_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Product $p
     */
    public function setProduct(DomainObjectModel_Product $p)
    {
        $this->Product = $p;
    }

    /**
     * @return DomainObjectModel_Product|null
     */
    public function getProduct()
    {
        return is_numeric($this->getProductId()) ? $this->Product : null;
    }

    /**
     * @return array
     */
    public function getPayments()
    {
        if ($this->order_payments !== null) {
            return $this->order_payments;
        }

        $this->order_payments = array();

        /** @var $img DomainObjectModel_OrderPayment */
        foreach ($this->OrderPayment as $payment) {
            $this->order_payments[$payment->getId()] = $payment;
        }

        return $this->order_payments;
    }

    /**
     * @param mixed       $data
     * @param string|null $key
     * @return mixed
     */
    public function setCustomerData($data, $key = null)
    {
        if (empty($data)) {
            return parent::setCustomerData(null);
        }

        if (null === $key) {
            $_data = $data;
        } else {
            $_data = $this->getCustomerData();
            $_data[$key] = $data;
        }

        return parent::setCustomerData($_data);
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getProductData($key = null)
    {
        $data = parent::getProductData();

        if (null === $key) {
            return $data;
        }

        return isset($data[$key]) ? $data[$key] : null;
    }

    /**
     * @param mixed       $data
     * @param string|null $key
     * @return mixed
     */
    public function setProductData($data, $key = null)
    {
        if (empty($data)) {
            return parent::setProductData(null);
        }

        if (null === $key) {
            $_data = $data;
        } else {
            $_data = $this->getProductData();
            $_data[$key] = $data;
        }

        return parent::setProductData($_data);
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getHotelData($key = null)
    {
        $data = parent::getHotelData();

        if (null === $key) {
            return $data;
        }

        if ($key == 'departure_date') {
            return isset($data[$key]) ? (is_a($data[$key], 'DxDateTime') ? $data[$key] : new DxDateTime($data[$key])) : null;
        }

        return isset($data[$key]) ? $data[$key] : null;
    }

    /**
     * @param mixed       $data
     * @param string|null $key
     * @return mixed
     */
    public function setHotelData($data, $key = null)
    {
        if (empty($data)) {
            return parent::setHotelData(null);
        }

        if (null === $key) {
            $_data = $data;
        } else {
            $_data = $this->getHotelData();
            $_data[$key] = $data;
        }

        return parent::setHotelData($_data);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return DxApp::getComponent(DxApp::ALIAS_URL)->url('/order/' . $this->getSignature());
    }

    /**
     * Generates order's signature
     *
     * @return string SHA-1 hash
     */
    public function generateSignature()
    {
        return sha1(microtime(true) . rand());
    }

    public function preInsert($event)
    {
        parent::preInsert($event);

        $this->setSignature($this->generateSignature());
    }

    /**
     * Return either all "FROM" values or just one "FROM" value
     *
     * @param string|null $key
     * @return array|mixed|null
     */
    public function getProductFrom($key = null)
    {
        DxFactory::import('DomainObjectModel_Product');

        $froms   = DomainObjectModel_Product::getFromAll();
        $from_id = $this->getProductData('product_from_id');

        if (!array_key_exists($from_id, $froms)) {
            return null;
        }

        $active_from = $froms[$from_id];

        if ($key === null) {
            return $active_from;
        }

        return !array_key_exists($key, $active_from) ? null : $active_from[$key];
    }

    /**
     * @return boolean
     */
    public function isCustomerDataFilled()
    {
        $total = (int)$this->getCustomerTotalAdults() + (int)$this->getCustomerTotalChildren();

        $cdata = $this->getCustomerData();
        $existing = count(array_filter($cdata['ADULTS'])) + count(array_filter($cdata['CHILDREN']));

        return $existing == $total;
    }

    public function getCustomerData($type = null)
    {
        $data = parent::getCustomerData();

        if ($type !== null) {
            if (!empty($data[$type])) {
                return $data[$type];
            }

            return null;
        }

        $total_a = $this->getCustomerTotalAdults();
        $total_c = $this->getCustomerTotalChildren();

        if (empty($data['ADULTS'])) {
            $data['ADULTS'] = array();
        }

        $to_add = $total_a - count($data['ADULTS']);

        if ($to_add) {
            for ($i = 1; $i <= $to_add; $i++) {
                $data['ADULTS'][] = array();
            }
        }

        if (empty($data['CHILDREN'])) {
            $data['CHILDREN'] = array();
        }

        $to_add = $total_c - count($data['CHILDREN']);

        if ($to_add) {
            for ($i = 1; $i <= $to_add; $i++) {
                $data['CHILDREN'][] = array();
            }
        }

        $this->setCustomerData($data);

        return $data;
    }

    public function getContractUrl()
    {
        if ($this->getContract() === null) {
            return null;
        }

        $url = DxApp::getComponent(DxApp::ALIAS_URL)->url($this->getContract());

        return rtrim($url, '/ ');
    }

    /**
     * @param DomainObjectModel_OrderPayment $order
     */
    public function setPayment(DomainObjectModel_OrderPayment $order)
    {
        $this->OrderPayment[] = $order;
    }

    public function getCountry()
    {
        if (!$this->getProductData('country_id')) {
            return null;
        }

        if ($this->ref_country !== null) {
            return $this->ref_country;
        }

        /** @var DomainObjectQuery_Country $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_Country');

        $this->ref_country = $q->findById($this->getProductData('country_id'));

        return $this->ref_country;
    }

    public function getResort()
    {
        if (!$this->getProductData('resort_id')) {
            return null;
        }

        if ($this->ref_resort !== null) {
            return $this->ref_resort;
        }

        /** @var DomainObjectQuery_Resort $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_Resort');

        $this->ref_resort = $q->findById($this->getProductData('resort_id'));

        return $this->ref_resort;
    }

    /**
     * @return boolean
     */
    public function isAvailable()
    {
        return true;

        if ($this->getStatus() == 'NEW' || count($this->getPayments()) == 0 || $this->getContract() === null) {
            return false;
        }

        return true;
    }

    /**
     * @return int|null
     */
    public function getOfficeId()
    {
        return is_object($id = $this->getFieldValue('office_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @return DomainObjectModel_Office|null
     */
    public function getOffice()
    {
        return is_numeric($this->getOfficeId()) ? $this->Office : null;
    }

    /**
     * @param DomainObjectModel_Office $m
     * @return DomainObjectModel_Feedback
     */
    public function setOffice(DomainObjectModel_Office $m)
    {
        $this->Office = $m;

        return $this;
    }
}