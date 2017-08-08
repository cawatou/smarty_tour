<?php
dxFactory::import('Form_Frontend');

class Form_Frontend_Order_CustomerData extends Form_Frontend
{
    /** @var DomainObjectModel_Order */
    protected $form_model = null;

    protected $grouped_errors = array();

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
        if ($this->form_model === null) {
            $this->form_model = DxFactory::getInstance('DomainObjectModel_Order');
        }

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
        $data = $this->getEnvData('_POST');
        $errors = array();

        $m = $this->getModel();

        if ($m === null) {
            return false;
        }

        $required = array(
            'name_latin',
            'surname_latin',
            'birthday_day',
            'birthday_month',
            'birthday_year',
            'citizenship',
            'gender',
            'passport_series',
            'passport_number',
            'passport_issue_date_day',
            'passport_issue_date_month',
            'passport_issue_date_year',
            'passport_expiration_date_day',
            'passport_expiration_date_month',
            'passport_expiration_date_year',
            'passport_issuer',
        );

        $formatted = array();

        foreach ($data['customer_data'] as $type => $customers) {
            if (empty($formatted[$type])) {
                $formatted[$type] = array();
            }

            foreach ($customers as $k => $customer) {
                foreach ($required as $key) {
                    if (empty($customer[$key])) {
                        $errors[$type][$k][$key] = 'EMPTY';
                    }
                }

                if (!empty($customer['gender']) && !in_array($customer['gender'], array('MALE', 'FEMALE'))) {
                    $errors[$type][$k]['gender'] = 'INCORRECT';
                }

                if (!empty($customer['passport_issue_date_year']) && $customer['passport_issue_date_year'] < 100) {
                    $customer['passport_issue_date_year'] = $this->prefilterYear($customer['passport_issue_date_year']);
                }

                $date_issue = $customer['passport_issue_date_year'] .'-'. $customer['passport_issue_date_month'] .'-'. $customer['passport_issue_date_day'];

                try {
                    $date_issue = new DxDateTime($date_issue);

                    $customer['passport_issue_date'] = $date_issue;
                } catch (Exception $e) {
                    if (empty($errors[$type][$k]['passport_issue_date_day'])) {
                        $errors[$type][$k]['passport_issue_date_day'] = 'INVALID_DATE';
                    }

                    $customer['passport_issue_date'] = null;
                }

                if (!empty($customer['birthday_year']) && $customer['birthday_year'] < 100) {
                    $customer['birthday_year'] = $this->prefilterYear($customer['birthday_year']);
                }

                $date_birthday = $customer['birthday_year'] .'-'. $customer['birthday_month'] .'-'. $customer['birthday_day'];

                try {
                    $date_birthday = new DxDateTime($date_birthday);

                    $customer['birthday'] = $date_birthday;
                } catch (Exception $e) {
                    if (empty($errors[$type][$k]['birthday_day'])) {
                        $errors[$type][$k]['birthday_day'] = 'INVALID_DATE';
                    }

                    $customer['birthday'] = null;
                }

                if (!empty($customer['passport_expiration_date_year']) && $customer['passport_expiration_date_year'] < 100) {
                    $customer['passport_expiration_date_year'] = $this->prefilterYear($customer['passport_expiration_date_year']);
                }

                $date_expiration = $customer['passport_expiration_date_year'] .'-'. $customer['passport_expiration_date_month'] .'-'. $customer['passport_expiration_date_day'];

                try {
                    $date_expiration = new DxDateTime($date_expiration);

                    $customer['passport_expiration_date'] = $date_expiration;
                } catch (Exception $e) {
                    if (empty($errors[$type][$k]['passport_expiration_date_day'])) {
                        $errors[$type][$k]['passport_expiration_date_day'] = 'INVALID_DATE';
                    }

                    $customer['passport_expiration_date'] = null;
                }

                $formatted[$type][$k] = $customer;
            }
        }

        $m->setCustomerData($formatted);

        if ($m->getContract()) {
            if (empty($data['order_is_contract_agree'])) {
                $errors['order_is_contract_agree'] = 'NOT_AGREED';
            } else {
                $m->setIsContractAgree(1);
            }
        } else {
            $m->setIsContractAgree(0);
        }

        if (!empty($errors)) {
            $this->grouped_errors = $errors;
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
        $this->setFormData($this->getEnvData());

        $this->smarty->assign(
            array(
                'errors' => $this->grouped_errors,
            )
        );

        return $this->smarty->fetch('frontend/form/order/customer_data.tpl.php');
    }

    /**
     * @param int $year
     * @return null|int
     *
     * @static
     */
    static public function prefilterYear($year)
    {
        if (empty($year)) {
            return null;
        }

        // Already prefiltered
        if (strlen($year) == 4) {
            return $year;
        }

        if ($year <= date('y')) {
            return '20'. $year;
        }

        return '19'. $year;
    }

    public function getErrors()
    {
        return $this->grouped_errors;
    }

    public function hasErrors()
    {
        return count($this->getErrors()) > 0;
    }
}