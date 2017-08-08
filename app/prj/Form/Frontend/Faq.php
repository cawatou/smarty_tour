<?php

dxFactory::import('Form_Frontend');

class Form_Frontend_Faq extends Form_Frontend
{
    /** @var DomainObjectModel_Faq */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Faq|null $form_model
     */
    public function setModel(DomainObjectModel_Faq $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Faq|null
     */
    public function getModel()
    {
        if ($this->form_model === null) {
            $this->form_model = DxFactory::getInstance('DomainObjectModel_Faq');
        }

        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Faq|null
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
            'faq_user_name' => array(
                'method' => 'setUserName',
                'value'  => empty($data['faq_user_name']) ? null : mb_substr($data['faq_user_name'], 0, 255),
            ),
            'faq_user_phone' => array(
                'method' => 'setUserPhone',
                'value'  => empty($data['faq_user_phone']) ? null : mb_substr($data['faq_user_phone'], 0, 255),
            ),
            'faq_user_email' => array(
                'method' => 'setUserEmail',
                'value'  => empty($data['faq_user_email']) ? null : mb_strtolower(mb_substr(trim($data['faq_user_email']), 0, 255)),
            ),
            'faq_message' => array(
                'method' => 'setMessage',
                'value'  => empty($data['faq_message']) ? null : $data['faq_message'],
            ),
            'city_id' => array(
                'method' => 'setCityId',
                'value'  => empty($data['city_id']) ? null : $data['city_id'],
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

        if ($m->getOffice() !== null && $m->getCityId() === null) {
            $m->setCityId($m->getOffice()->getCityId());
        }

        $m->setStatus('DISABLED');
        $m->setUserIp(empty($_SERVER['REMOTE_ADDR']) ? '0.0.0.0' : $_SERVER['REMOTE_ADDR']);

        if (!array_key_exists('__FAQ_CAPTCHA__', $_SESSION) || $_SESSION['__FAQ_CAPTCHA__'] != $data['captcha']) {
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

    public function isSubmitted()
    {
        return $this->isSubmited();
    }

    /**
     * @return string
     */
    public function draw()
    {
        $this->setFormData($this->getEnvData());

        /** @var DomainObjectModel_Faq $model */
        $model = $this->getModel();

        /** @var DomainObjectQuery_Office $q */
        $q = DxFactory::getInstance('DomainObjectQuery_Office');

        $_offices = $q->getAll(true);
        $offices  = array();

        foreach ($_offices as $_office) {
            if ($model->getOfficeId() === null) {
                if ($this->getContext()->getCity()->getId() == $_office['city_id']) {
                    $model->setCityId($_office['city_id']);
                    $model->setOfficeId($_office['office_id']);
                }
            }

            $offices[$_office['city_name']][$_office['office_id']] = $_office;
        }

        $this->smarty->assign(
            array(
                'office_list' => $offices,
            )
        );

    	return $this->smarty->fetch('frontend/form/faq.tpl.php');
    }
}