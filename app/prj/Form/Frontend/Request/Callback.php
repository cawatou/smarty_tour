<?php
dxFactory::import('Form_Frontend');

class Form_Frontend_Request_Callback extends Form_Frontend
{
    /** @var DomainObjectModel_Request */
    protected $form_model = null;

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
            'request_user_phone' => array(
                'method' => 'setUserPhone',
                'value'  => empty($data['request_user_phone']) ? null : mb_substr($data['request_user_phone'], 0, 255),
            ),
            'office_id' => array(
                'method' => 'setOfficeId',
                'value'  => empty($data['office_id']) ? null : $data['office_id'],
            ),
        );

        if ($map['office_id']['value'] == 'other') {
            $map['office_id']['value'] = null;
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

        $extended = array(
            'office' => empty($data['office_id']) ? null : $data['office_id'],
        );

        $_keys_ext = array(
            'office',
            'office_other',
        );

        foreach ($_keys_ext as $k) {
            if (!empty($data['request_extended_'. $k])) {
                $extended[$k] = $data['request_extended_'. $k];
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
        $m->setType('CALLBACK');
        $m->setStatus('ENABLED');
        $m->setUserIp(empty($_SERVER['REMOTE_ADDR']) ? '0.0.0.0' : $_SERVER['REMOTE_ADDR']);

        if (!array_key_exists('__CALLBACK_CAPTCHA__', $_SESSION) || $_SESSION['__CALLBACK_CAPTCHA__'] != $data['captcha']) {
            $errors['captcha'] = 'NOT_VALID';
        }

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
        $this->setFormData($this->getEnvData());

        $offices = array();

        $model = $this->getModel();

        if ($model->getType() == 'REQUEST' || $model->getType() == 'CALLBACK') {
            /** @var $q DomainObjectQuery_Office */
            $q = DxFactory::getInstance('DomainObjectQuery_Office');

            $_offices = $q->getAll(true);

            foreach ($_offices as $_office) {
                if ($model->getExtendedData('office') === null && $model->getExtendedData('office_other') === null) {
                    if ($this->getContext()->getCity()->getId() == $_office['city_id']) {
                        $model->setExtendedData($_office['office_id'], 'office');
                    }
                }

                $offices[$_office['city_name']][$_office['office_id']] = $_office;
            }
        }

        ksort($offices);

        $this->smarty->assign(
            array(
                'office_list' => $offices,
            )
        );

        return $this->smarty->fetch('frontend/form/request/callback.tpl.php');
    }
}