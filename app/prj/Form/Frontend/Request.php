<?php
dxFactory::import('Form_Frontend');

class Form_Frontend_Request extends Form_Frontend
{
    /** @var DomainObjectModel_Request */
    protected $form_model = null;

    protected $request_hotel_types = array(
        'самый недорогой горящий отель' => 'самый недорогой "горящий" отель',
        'отели 3-4*'                    => 'горящие" отели 3-4',
        '4*-5*'                         => 'горящие" отели 4-5',
        'только отели 5*'               => 'только отели 5*',
        'только VIP отели'              => 'только VIP отели',
    );

    /**
     * @param DomainObjectModel_Request|null $form_model
     */
    public function setModel(DomainObjectModel_Request $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Request|null
     */
    public function getModel()
    {
        if ($this->form_model === null) {
            $this->form_model = DxFactory::getInstance('DomainObjectModel_Request');
        }

        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Request|null
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

        $map = array(
            'request_user_name' => array(
                'method' => 'setUserName',
                'value'  => empty($data['request_user_name']) ? null : mb_substr($data['request_user_name'], 0, 255),
            ),
            'request_user_email' => array(
                'method' => 'setUserEmail',
                'value'  => empty($data['request_user_email']) ? null : mb_strtolower(mb_substr(trim($data['request_user_email']), 0, 255)),
            ),
            'request_user_phone' => array(
                'method' => 'setUserPhone',
                'value'  => empty($data['request_user_phone']) ? null : mb_substr($data['request_user_phone'], 0, 255),
            ),
            'request_message' => array(
                'method' => 'setMessage',
                'value'  => empty($data['request_message']) ? null : $data['request_message'],
            ),
            'office_id' => array(
                'method' => 'setOfficeId',
                'value'  => empty($data['office_id']) ? null : $data['office_id'],
            ),
        );

        if (!empty($data['office_id']) && $data['office_id'] == 'other') {
            unset($map['office_id']);
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

        if (empty($data['request_user_phone'])) {
            $errors['request_user_phone'] = 'NOT_VALID';
        }

        if (empty($data['request_user_email'])) {
            $errors['request_user_email'] = 'NOT_VALID';
        }

        $extended_required = array(
            'country'    => true,
            'date_begin' => true,
            'date_end'   => true,
            'adults'     => true,
            'price'      => true,
        );

        if ($m->getType() == 'ORDER') {
            $extended_required = array(
                'country'    => true,
                'date_begin' => true,
                'date_end'   => true,
                'adults'     => true,
                'price'      => true,
            );
        }

        $extended = array(
            'office' => $data['office_id'] == 'other' ? 'other' : null,
        );

        $_keys_ext = array(
            'hotel_stars',
            'country',
            'price',
            'flyaway',
            'adults',
            'children',
            'children_age_from',
            'children_age_to',
            'date_begin',
            'date_end',
            'office_other',
            'daynum',
        );

        if ($m->getType() === 'REQUEST') {
            $_keys_ext[] = 'spam_email';
            $_keys_ext[] = 'spam_sms';
        } elseif ($m->getType() === 'ORDER') {
            $_keys_ext = array(
                'hotel_stars',
                'country',
                'price',
                'flyaway',
                'adults',
                'children',
                'children_age_from',
                'children_age_to',
                'date_begin',
                'date_end',
                'daynum',
                'office',
                'office_other',
            );
        }

        foreach ($_keys_ext as $k) {
            if (!empty($data['request_extended_'. $k])) {
                $extended[$k] = $data['request_extended_'. $k];
            }

            if (isset($extended_required[$k])) {
                if (empty($extended[$k])) {
                    $errors['request_extended_'. $k] = 'NOT_VALID';
                }
            }
        }

        if ($extended['office'] == 'other') {
            $extended['office'] = null;

            if (empty($extended['office_other'])) {
                $errors['request_extended_office_other'] = 'NOT_VALID';
            }
        } else {
            $extended['office_other'] = null;
        }

        $m->setExtendedData(empty($extended) ? null : $extended);
        $m->setStatus('ENABLED');
        $m->setUserIp(empty($_SERVER['REMOTE_ADDR']) ? '0.0.0.0' : $_SERVER['REMOTE_ADDR']);

        if (!empty($errors)) {
            $this->errors = $errors;

            $this->getDomainObjectManager()->rollback();

            return false;
        }

        if (!empty($_REQUEST['request_custom_staff'])) {
            $this->getDomainObjectManager()->flush();
        }

        return true;
    }

    /**
     * @return string
     */
    public function draw()
    {
        $this->setFormData($this->getEnvData());

        $model = $this->getModel();

        /** @var DomainObjectQuery_Office $q */
        $q = DxFactory::getInstance('DomainObjectQuery_Office');

        $_offices = $q->getAll(true);
        $offices  = array();

        foreach ($_offices as $_office) {
            if ($model->getOfficeId() === null && $model->getExtendedData('office_other') === null) {
                if ($this->getContext()->getCity()->getId() == $_office['city_id']) {
                    $model->setOfficeId($_office['office_id']);
                }
            }

            $offices[$_office['city_name']][$_office['office_id']] = $_office;
        }

        ksort($offices);

        $this->smarty->assign(
            array(
                'office_list' => $offices,
                'hotel_stars' => $this->request_hotel_types,
            )
        );

        return $this->smarty->fetch('frontend/form/request.tpl.php');
    }
}