<?php
dxFactory::import('Form');

class Form_Backend_Order extends Form_Backend
{
    /** @var DomainObjectModel_Order */
    protected $form_model = null;

    protected $errors_customer_data = array();

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

        $customer_data = array();

        $req_cust_data = array(
            'name_latin',
            'surname_latin',
            'birthday',
            'citizenship',
            'gender',
            'passport_series',
            'passport_number',
            'passport_issue_date',
            'passport_expiration_date',
            'passport_issuer',
        );

        $errors_customer_data = array();

        if (!empty($data['order_customer_data']) && $m->getPrice() !== null && $m->getContract() !== null && count($m->getPayments()) > 0) {
            foreach ($data['order_customer_data'] as $type => $customers) {
                $errors_customer_data[$type] = array();

                foreach ($customers as $k => $customer) {
                    $errors_customer_data[$type][$k] = array();

                    foreach ($req_cust_data as $name) {
                        if (empty($customer[$name])) {
                            $errors_customer_data[$type][$k][$name] = 'NOT_VALID';
                        }
                    }

                    $date_birthday = $customer['birthday'];

                    try {
                        $customer['birthday'] = new DxDateTime($date_birthday);
                    } catch (Exception $e) {
                        $errors_customer_data[$type][$k]['birthday'] = 'NOT_VALID';
                        $customer['birthday'] = null;
                    }

                    $date_issue = $customer['passport_issue_date'];

                    try {
                        $customer['passport_issue_date'] = new DxDateTime($date_issue);
                    } catch (Exception $e) {
                        $errors_customer_data[$type][$k]['passport_issue_date'] = 'NOT_VALID';
                        $customer['passport_issue_date'] = null;
                    }

                    $date_expiration = $customer['passport_expiration_date'];

                    try {
                        $customer['passport_expiration_date'] = new DxDateTime($date_expiration);
                    } catch (Exception $e) {
                        $errors_customer_data[$type][$k]['passport_expiration_date'] = 'NOT_VALID';
                        $customer['passport_expiration_date'] = null;
                    }

                    if (!in_array($customer['gender'], array('MALE', 'FEMALE'))) {
                        $errors_customer_data[$type][$k]['gender'] = 'NOT_VALID';
                    }

                    $customer_data[$type][$k] = $customer;
                }
            }

            $m->setCustomerData($customer_data);
        }

        $map = array(
            'order_price' => array(
                'method' => 'setPrice',
                'value'  => empty($data['order_price']) || $data['order_price'] <= 0 ? null : (float)$data['order_price'],
            ),
            'order_comment' => array(
                'method' => 'setComment',
                'value'  => empty($data['order_comment']) ? null : trim($data['order_comment']),
            ),
            'order_status' => array(
                'method' => 'setStatus',
                'value'  => empty($data['order_status']) ? null : trim($data['order_status']),
            ),
        );

        if (!$m->isCustomerDataFilled()) {
            $map['order_customer_total_adults'] = array(
                'method' => 'setCustomerTotalAdults',
                'value'  => empty($data['order_customer_total_adults']) ? 0 : (int)$data['order_customer_total_adults'],
            );

            $map['order_customer_total_children'] = array(
                'method' => 'setCustomerTotalChildren',
                'value'  => empty($data['order_customer_total_children']) ? 0 : (int)$data['order_customer_total_children'],
            );
        }

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

        $uploaded_contract = null;

        if (!empty($_FILES['order_contract'])) {
            $uploaded_contract = $this->handleUpload();
        }

        if (!empty($uploaded_contract)) {
            $m->setContract($uploaded_contract);
        }

        /** @var $query_payment DomainObjectQuery_OrderPayment */
        $query_payment = DxFactory::getInstance('DomainObjectQuery_OrderPayment');

        if (!empty($data['order_payment'])) {
            foreach ($data['order_payment'] as $k => $payment) {
                $payment['order_payment_amount'] = str_replace(',', '.', $payment['order_payment_amount']);

                if (empty($payment['order_payment_amount'])) {
                    continue;
                }

                if ($k == 0) {
                    /** @var $model_payment DomainObjectModel_OrderPayment */
                    $model_payment = DxFactory::getInstance('DomainObjectModel_OrderPayment');

                    try {
                        $model_payment->setAmount($payment['order_payment_amount']);
                    } catch (DxException $e) {
                        $errors['order_payment_0_amount'] = 'NOT_VALID';
                    }

                    $m->setPayment($model_payment);
                } else {
                    /** @var $model_payment null|DomainObjectModel_OrderPayment */
                    $model_payment = $query_payment->findById($k);

                    if ($model_payment === null) {
                        continue;
                    }

                    if ($model_payment->getStatus() != 'NEW') {
                        continue;
                    }

                    try {
                        $model_payment->setAmount($payment['order_payment_amount']);
                    } catch (DxException $e) {
                        $errors['order_payment_'. $k .'_amount'] = 'NOT_VALID';
                    }
                }
            }
        }

        $total_payments = 0;

        foreach ($m->getPayments() as $pay) {
            if ($pay->getStatus() == 'CANCELLED') {
                continue;
            }

            $total_payments += $pay->getAmount();
        }

        if ($m->getPrice() < $total_payments) {
            $errors['order_price'] = 'LESS_THAN_PAYMENTS';
        }

        if (empty($errors_customer_data['ADULTS'])) {
            $errors_customer_data['ADULTS'] = array();
        }

        if (empty($errors_customer_data['CHILDREN'])) {
            $errors_customer_data['CHILDREN'] = array();
        }

        $error_adults   = array_filter($errors_customer_data['ADULTS']);
        $error_children = array_filter($errors_customer_data['CHILDREN']);

        if (!empty($errors) || !empty($error_adults) || !empty($error_children)) {
            $this->errors = $errors;
            $this->errors_customer_data = $errors_customer_data;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        $m->save();

        $this->getDomainObjectManager()->flush();

        return true;
    }

    /**
     * @return string
     */
    public function draw()
    {
        $payments = $this->getModel()->getPayments();

        foreach ($payments as $payment) {
            if (in_array($payment->getStatus(), array('PREAUTH', 'RESERVED'))) {
                $payment->checkStatus();
            }
        }

        $this->smarty->assign(
            array(
                'adults_vals'    => range(1, 10),
                'childs_vals'    => range(0, 10),
                'errors_cust'    => $this->errors_customer_data,
                'order_statuses' => $this->getModel()->getOrderStatuses(),
            )
        );

        return $this->smarty->fetch('backend/form/order.tpl.php');
    }

    public function handleUpload()
    {
        DxFactory::import('Utils_NameMaker');

        if (empty($_FILES['order_contract'])) {
            return null;
        }

        if (is_array($_FILES['order_contract']['tmp_name'])) {
            return null;
        }

        $_files = array();

        $path_info = pathinfo($_FILES['order_contract']['name']);
        $ext = mb_strtolower(empty($path_info['extension']) ? '' : '.'. $path_info['extension']);
        $dst_name = $this->getModel()->getSignature() . $ext;

        $_files[] = array(
            'src_path' => $_FILES['order_contract']['tmp_name'],
            'src_name' => $_FILES['order_contract']['name'],
            'dst_name' => Utils_NameMaker::modifyFileName($dst_name),
        );

        $config = DxApp::config('url', 'static');

        $full_files_path = DxFile::makeFullPath($config['files']);
        $full_files_path = DxFile::cleanPath($full_files_path . DS .'contracts'. DS . date('Y') . DS . date('m'), DS);

        DxFile::createDir($full_files_path);

        $new_file = DxFile_Upload::createByRequest($_files, $full_files_path);
        $new_file = current($new_file);

        if (empty($new_file)) {
            return null;
        }

        return str_replace('\\', '/', $new_file->getRelativePath($new_file->getFullPath()));
    }

    /**
     * @return bool
     */
    protected function isNotify()
    {
        return isset($this->env_data['_POST']['__send_mail']);
    }
}