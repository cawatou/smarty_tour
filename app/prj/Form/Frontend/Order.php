<?php
dxFactory::import('Form_Frontend');

class Form_Frontend_Order extends Form_Frontend
{
    /** @var DomainObjectModel_Order */
    protected $form_model = null;

    /** @var array */
    protected $data_product = null;

    /** @var array */
    protected $data_hotel = null;

    /**
     * @param DomainObjectModel|null $form_model
     */
    public function setModel(DomainObjectModel $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Order|null
     */
    public function getModel()
    {
        if ($this->form_model === null) {
            $this->form_model = DxFactory::getInstance('DomainObjectModel_Order');
        }

        return $this->form_model;
    }

    /**
     * @return DomainObjectModel|null
     */
    public function m()
    {
        return $this->getModel();
    }

    /**
     * @return bool
     */
    protected function process()
    {
        $data = $this->getEnvData('_POST');
        $errors = array();

        if (empty($data['order_type'])) {
            $data['order_type'] = 'ONLINE';
        }

        $m = $this->getModel();

        if ($m === null) {
            return false;
        }

        $map = array(
            'order_customer_name' => array(
                'method' => 'setCustomerName',
                'value'  => empty($data['order_customer_name']) ? null : mb_substr($data['order_customer_name'], 0, 255),
            ),
            'order_customer_email' => array(
                'method' => 'setCustomerEmail',
                'value'  => empty($data['order_customer_email']) ? null : mb_substr($data['order_customer_email'], 0, 255),
            ),
            'order_customer_phone' => array(
                'method' => 'setCustomerPhone',
                'value'  => empty($data['order_customer_phone']) ? null : mb_substr($data['order_customer_phone'], 0, 255),
            ),
            'order_customer_total_adults' => array(
                'method' => 'setCustomerTotalAdults',
                'value'  => empty($data['order_customer_total_adults']) ? 0 : (int)$data['order_customer_total_adults'],
            ),
            'order_customer_total_children' => array(
                'method' => 'setCustomerTotalChildren',
                'value'  => empty($data['order_customer_total_children']) ? 0 : (int)$data['order_customer_total_children'],
            ),
        );

        $m->setOfficeId(null);

        if ($data['order_type'] == 'OFFICE') {
            if (empty($data['office_id'])) {
                $errors['office_id'] = 'NOT_VALID';
            } else {
                $m->setCustomerData($data['office_id'], 'office');
                $m->setOfficeId($data['office_id']);
            }
        }

        foreach ($map as $key => $val) {
            try {
                DxFactory::invoke($m, $val['method'], array($val['value']));
            } catch (DxException $e) {
                if ($e->getCode() == DomainObjectModel::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT) {
                    $errors[$key] = 'INVALID_FORMAT';
                } else {
                    $errors[$key] = 'NOT_VALID';
                }
            }
        }

        $m->setStatus('NEW');
        $m->setCustomerIp(empty($_SERVER['REMOTE_ADDR']) ? '0.0.0.0' : $_SERVER['REMOTE_ADDR']);

        $m->setProductId($this->data_product['product_id']);

        $m->setProductData($this->data_product);
        $m->setHotelData($this->data_hotel);

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        if ($data['order_type'] == 'OFFICE') {
            $model = DxFactory::getInstance('DomainObjectModel_Request');

            $model->setType('ORDER_SHORT');
            $model->setUserName($m->getCustomerName());
            $model->setUserPhone($m->getCustomerPhone());
            $model->setUserEmail($m->getCustomerEmail());
            $model->setUserIp($m->getCustomerIp());
            $model->setOfficeId($data['office_id']);

            $ext_data = array(
                'product'     => $m->getProductData('product_id'),
                'office'      => $data['office_id'],
                'country'     => $m->getProductData('country_name'),
                'resort'      => $m->getProductData('resort_name'),
                'adults'      => $m->getCustomerTotalAdults(),
                'children'    => $m->getCustomerTotalChildren(),
                'price'       => (($m->getPriceOpening() * $m->getCustomerTotalChildren()) + ($m->getPriceOpening() * $m->getCustomerTotalAdults())),
                'daynum'      => $m->getHotelData('departure_daynum'),
                'flyaway'     => $m->getProductFrom('title_from'),
                'hotel_name'  => $m->getHotelData('name'),
                'hotel_stars' => $m->getHotelData('stars'),
            );

            if ($m->getProductData('country_id') == DomainObjectQuery_Product::COUNTRY_ID_RUSSIA && !empty($data['order_extended_get_via_value'])) {
                $ext_data['get_via_price'] = $data['order_extended_get_via_value'];
                $ext_data['get_via_title'] = $data['order_extended_get_via_title'];
            }

            $model->setExtendedData($ext_data);

            $this->getDomainObjectManager()->detach($m);

            $this->setModel($model);
        } else {
            if ($m->getProductData('country_id') == DomainObjectQuery_Product::COUNTRY_ID_RUSSIA && !empty($data['order_extended_get_via_value'])) {
                $m->setCustomerData($data['order_extended_get_via_value'], 'get_via_price');
                $m->setCustomerData($data['order_extended_get_via_title'], 'get_via_title');
            }
        }

        $this->getDomainObjectManager()->flush();

        return true;
    }

    /**
     * Accessor for protected 'isSubmited' method
     *
     * @return boolean
     */
    public function isSubmitted()
    {
        return $this->isSubmited();
    }

    /**
     * @return string
     */
    public function draw()
    {
        $this->setFormData($this->getEnvData('_POST'));

        /** @var $q DomainObjectQuery_Office */
        $q = DxFactory::getSingleton('DomainObjectQuery_Office');

        $offices = array();
        foreach ($q->getAll(true) as $_office) {
            $offices[$_office['city_name']][$_office['office_id']] = $_office;
        }
        ksort($offices);

        $this->smarty->assign(
            array(
                'adults_vals' => range(1, 10),
                'childs_vals' => range(0, 3),
                'office_list' => $offices,
            )
        );

        return $this->smarty->fetch('frontend/form/order.tpl.php');
    }

    public function setProductData(array $data)
    {
        $this->data_product = $data;
    }

    public function setHotelData(array $data)
    {
        $this->data_hotel = $data;
    }
}