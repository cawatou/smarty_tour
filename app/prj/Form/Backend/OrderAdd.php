<?php
dxFactory::import('Form');

class Form_Backend_OrderAdd extends Form_Backend
{
    /** @var DomainObjectModel_Order */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Order|null $form_model
     */
    public function setModel(DomainObjectModel_Order $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Order|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Order|null
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
        $data   = $this->getEnvData('_POST');
        $errors = array();

        $m = $this->getModel();

        if ($m === null) {
            return false;
        }

        $map = array(
            'order_customer_name' => array(
                'method' => 'setCustomerName',
                'value'  => empty($data['order_customer_name']) ? null : $data['order_customer_name'],
            ),
            'order_customer_phone' => array(
                'method' => 'setCustomerPhone',
                'value'  => empty($data['order_customer_phone']) ? null : $data['order_customer_phone'],
            ),
            'order_customer_email' => array(
                'method' => 'setCustomerEmail',
                'value'  => empty($data['order_customer_email']) ? null : $data['order_customer_email'],
            ),
            'order_price' => array(
                'method' => 'setPrice',
                'value'  => empty($data['order_price']) || $data['order_price'] <= 0 ? null : (float)$data['order_price'],
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

        foreach ($map as $key => $val) {
            try {
                call_user_func(array($m, $val['method']), $val['value']);
            } catch (DxException $e) {
                if ($e->getCode() == DomainObjectModel::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT) {
                    $errors[$key] = 'INVALID_FORMAT';
                } else {
                    $errors[$key] = 'NOT_VALID';
                }
            }
        }

        $m->setPriceOpening($m->getPrice());

        if ($m->getPrice() === null) {
            $errors['order_price'] = 'NOT_VALID';
        }

        $product_data = (array)$m->getProductData();

        if (empty($data['country_id'])) {
            $errors['country_id'] = 'NOT_VALID';
        } else {
            $product_data['country_id'] = $data['country_id'];

            $country = $m->getCountry();

            if ($country !== null) {
                $product_data['country_name'] = $country->getTitle();
            }
        }

        if (!empty($data['resort_id'])) {
            $product_data['resort_id'] = $data['resort_id'];

            $m->setProductData('resort_id', $product_data['resort_id']);

            $resort = $m->getResort();

            if ($resort !== null) {
                $product_data['resort_name'] = $resort->getTitle();
            }
        } else {
            if (!empty($data['resort_name'])) {
                $product_data['resort_name'] = $data['resort_name'];
            }
        }

        if (empty($product_data['resort_id']) && empty($product_data['resort_name'])) {
            $errors['resort_id'] = 'NOT_VALID';
        }

        if (empty($data['order_product_from_id'])) {
            $errors['order_product_from_id'] = 'NOT_VALID';
        } else {
            $product_data['product_from_id'] = $data['order_product_from_id'];
        }

        $m->setProductData($product_data);

        $m->setCustomerIp(empty($_SERVER['REMOTE_ADDR']) ? '0.0.0.0' : $_SERVER['REMOTE_ADDR']);

        $hotel_data = (array)$m->getHotelData();

        if (empty($data['order_hotel_name'])) {
            $errors['order_hotel_name'] = 'NOT_VALID';
        } else {
            $hotel_data['name'] = $data['order_hotel_name'];

            if (!empty($data['order_hotel_id'])) {
                $hotel_data['id'] = $data['order_hotel_id'];
            }
        }

        if (!empty($data['order_hotel_stars'])) {
            $hotel_data['stars'] = $data['order_hotel_stars'];
        } else {
            $hotel_data['stars'] = null;
        }

        if (!empty($data['order_hotel_nutrition'])) {
            $hotel_data['nutrition_type'] = $data['order_hotel_nutrition'];
        } else {
            $hotel_data['nutrition_type'] = null;
        }

        if (empty($hotel_data['id'])) {
            if (empty($data['order_hotel_url'])) {
                $errors['order_hotel_url'] = 'NOT_VALID';
            } else {
                $hotel_data['url'] = $data['order_hotel_url'];
            }
        } else {
            $hotel_data['url'] = null;
        }

        $hotel_data['departure_date'] = null;

        if (!empty($data['order_hotel_departure_date'])) {
            try {
                $hotel_data['departure_date'] = $data['order_hotel_departure_date'];
            } catch (Exception $e) {
                $hotel_data['departure_date'] = null;
            }
        }

        if (empty($hotel_data['departure_date'])) {
            $errors['order_hotel_departure_date'] = 'NOT_VALID';
        }

        if (!empty($data['order_hotel_departure_daynum']) && $data['order_hotel_departure_daynum'] > 0) {
            $hotel_data['departure_daynum'] = $data['order_hotel_departure_daynum'];
        } else {
            $errors['order_hotel_departure_daynum'] = 'NOT_VALID';
        }

        $m->setHotelData($hotel_data);

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        $this->getDomainObjectManager()->flush();

        return true;
    }

    /**
     * @return string
     */
    public function draw()
    {
        /** @var $q_cry DomainObjectQuery_Country */
        $q_cry = DxFactory::getSingleton('DomainObjectQuery_Country');

        $resorts = array();

        $from_all_list = DxFactory::invoke('DomainObjectModel_Product', 'getFromAll');

        $model = $this->getModel();

        if ($model && $model->getProductData('country_id') > 0) {
            /** @var $q_res DomainObjectQuery_Resort */
            $q_res = DxFactory::getSingleton('DomainObjectQuery_Resort');

            $resorts = $q_res->getByCountryId($model->getProductData('country_id'), true);
        }

        $this->smarty->assign(
            array(
                'adults_vals'  => range(1, 10),
                'childs_vals'  => range(0, 10),
                'country_list' => $q_cry->getAll(true),
                'resort_list'  => $resorts,
                'froms_list'   => $from_all_list,
            )
        );

        return $this->smarty->fetch('backend/form/order_add.tpl.php');
    }
}