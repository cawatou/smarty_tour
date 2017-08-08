<?php

dxFactory::import('Form_Frontend');

class Form_Frontend_Feedback_Hotel extends Form_Frontend
{
    const LIMIT_FILES = 10;

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

        $m->setType('HOTEL');

        $map = array(
            'feedback_user_name' => array(
                'method' => 'setUserName',
                'value'  => empty($data['feedback_user_name']) ? null : mb_substr($data['feedback_user_name'], 0, 255),
            ),
            'feedback_user_email' => array(
                'method' => 'setUserEmail',
                'value'  => empty($data['feedback_user_email']) ? null : mb_strtolower(mb_substr(trim($data['feedback_user_email']), 0, 255)),
            ),
            'feedback_message' => array(
                'method' => 'setMessage',
                'value'  => empty($data['feedback_message']) ? null : $data['feedback_message'],
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

        $extended_required = array(
            'rating_room'      => true,
            'rating_beach'     => true,
            'rating_territory' => true,
            'rating_service'   => true,
            'rating_food'      => true,
            'rating_anim'      => true,

            'date_staying' => true,

            'agreed_rules'     => true,
            'agreed_pdp'       => true,
        );

        $extended = array();

        $_keys_ext = array(
            'rating_room',
            'rating_beach',
            'rating_territory',
            'rating_service',
            'rating_food',
            'rating_anim',

            'date_staying',

            'recommend_family',
            'recommend_young',
            'recommend_family_children',
            'recommend_old',
            'recommend_dont_ask',
            'recommend_no_opinion',

            'agreed_rules',
            'agreed_pdp',
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

        if (!empty($extended['date_staying'])) {
            if (!strtotime($extended['date_staying'])) {
                $errors['feedback_extended_date_staying'] = 'INVALID_FORMAT';
            } else {
                $extended['date_staying'] = date('d.m.Y', strtotime($extended['date_staying']));
            }
        }

        $m->setExtendedData(empty($extended) ? null : $extended);

        if (!empty($data['feedback_extended_photos']) && is_array($data['feedback_extended_photos'])) {
            $i = 0;

            foreach ($data['feedback_extended_photos'] as $k => $photo) {
                $i++;

                if ($i > self::LIMIT_FILES) {
                    continue;
                }

                $photo = str_replace(DS .'static'. DS .'files'. DS .'upload'. DS . date('Y') . DS . date('m'), '', $photo);
                $photo = trim($photo, DS);
                $photo = '/static/files/upload/'. date('Y') . DS . date('m') . DS . $photo;
                $photo = str_replace('\\', '/', $photo);

                $data['feedback_extended_photos'][$k] = $photo;
            }

            $m->setExtendedData($data['feedback_extended_photos'], 'photos');
        }

        $m->setStatus('DISABLED');
        $m->setUserIp(empty($_SERVER['REMOTE_ADDR']) ? '0.0.0.0' : $_SERVER['REMOTE_ADDR']);

        if ($m->getUserName() === null) {
            $errors['feedback_user_name'] = 'NOT_VALID';
        }

        if ($m->getUserEmail() === null) {
            $errors['feedback_user_email'] = 'NOT_VALID';
        }

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        $m->setHotelTitle($m->getHotel()->getTitle());

        $this->getDomainObjectManager()->flush();

        return true;
    }

    /**
     * @return string
     */
    public function draw()
    {
        $this->setFormData($this->getEnvData());

        return $this->smarty->fetch('frontend/form/feedback-hotel.tpl.php');
    }

    public function isSubmitted()
    {
        return $this->isSubmited();
    }
}