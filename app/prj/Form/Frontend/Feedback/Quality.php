<?php

dxFactory::import('Form_Frontend');

class Form_Frontend_Feedback_Quality extends Form_Frontend
{
    /** @var DomainObjectModel_Feedback */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Feedback|null $form_model
     */
    public function setModel(DomainObjectModel_Feedback $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Feedback|null
     */
    public function getModel()
    {
        if ($this->form_model === null) {
            $this->form_model = DxFactory::getInstance('DomainObjectModel_Feedback');
        }

        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Feedback|null
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

        if ($m->getExtendedData('city_id') === null) {
            $m->setExtendedData($this->getContext()->getCity()->getId(), 'city_id');
        }

        $m->setType('QUALITY');

        $map = array(
            'feedback_user_name' => array(
                'method' => 'setUserName',
                'value'  => empty($data['feedback_user_name']) ? null : mb_substr($data['feedback_user_name'], 0, 255),
            ),
            'feedback_user_phone' => array(
                'method' => 'setUserPhone',
                'value'  => empty($data['feedback_user_phone']) ? null : mb_substr($data['feedback_user_phone'], 0, 255),
            ),
            'feedback_message' => array(
                'method' => 'setMessage',
                'value'  => empty($data['feedback_message']) ? null : $data['feedback_message'],
            ),
            'office_id' => array(
                'method' => 'setOfficeId',
                'value'  => empty($data['office_id']) ? null : $data['office_id'],
            ),
        );

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

        $extended = array();

        if ($m->getOffice()) {
            $data['feedback_extended_city_id'] = $m->getOffice()->getCityId();
        }

        $extended_required = array(
            'city_id'       => true,
            'complain_type' => true,
        );

        $_keys_ext = array(
            'city_id',
            'staff_id',
            'complain_type',
            'rating_service',
        );

        foreach ($_keys_ext as $k) {
            if (!empty($data['feedback_extended_'. $k])) {
                $extended[$k] = $data['feedback_extended_'. $k];
            } else {
                if (isset($extended_required[$k])) {
                    $errors['feedback_extended_'. $k] = 'NOT_VALID';
                }
            }
        }

        $m->setExtendedData(empty($extended) ? null : $extended);

        $m->setStatus('DISABLED');
        $m->setUserIp(empty($_SERVER['REMOTE_ADDR']) ? '0.0.0.0' : $_SERVER['REMOTE_ADDR']);

        if ($m->getUserName() === null) {
            $errors['feedback_user_name'] = 'NOT_VALID';
        }

        if ($m->getUserPhone() === null) {
            $errors['feedback_user_phone'] = 'NOT_VALID';
        }

        if (!array_key_exists('__FEEDBACK_CAPTCHA__', $_SESSION) || $_SESSION['__FEEDBACK_CAPTCHA__'] != $data['captcha']) {
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

        $model = $this->getModel();

        if ($model !== null) {
            if ($model->getExtendedData('city_id') === null) {
                $model->setExtendedData($this->getContext()->getCity()->getId(), 'city_id');
            }
        }

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

        $offices_staffs = array();

        foreach ($q->findAll(true) as $office) {
            $_staffs = $office->getStaffs();

            foreach ($_staffs as $_staff) {
                $offices_staffs[$office->getCity()->getId()][$office->getTitle()][$_staff->getId()] = $_staff->getName();
            }
        }

        DxFactory::import('DomainObjectModel_Feedback');

        $this->smarty->assign(
            array(
                'office_list'    => $offices,
                'office_staffs'  => $offices_staffs,
                'complain_types' => DomainObjectModel_Feedback::getComplainTypes(),
            )
        );

        return $this->smarty->fetch('frontend/form/feedback-quality.tpl.php');
    }
}