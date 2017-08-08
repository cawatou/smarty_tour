<?php

dxFactory::import('Form');

class Form_Backend_Staff extends Form_Backend
{
    /** @var DomainObjectModel_Staff */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Staff|null $form_model
     */
    public function setModel(DomainObjectModel_Staff $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Staff|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Staff|null
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
            'office_id'   => array(
                'method' => 'setOfficeId',
                'value'  => empty($data['office_id']) ? null : $data['office_id'],
            ),
            'staff_name'  => array(
                'method' => 'setName',
                'value'  => mb_substr(trim($data['staff_name']), 0, 255),
            ),
            'staff_description'  => array(
                'method' => 'setDescription',
                'value'  => empty($data['staff_description']) ? null : trim($data['staff_description']),
            ),
            'staff_email'   => array(
                'method' => 'setEmail',
                'value'  => empty($data['staff_email']) ? null : mb_strtolower(trim($data['staff_email'])),
            ),
            'staff_status' => array(
                'method' => 'setStatus',
                'value'  => $data['staff_status'] == 'ENABLED' ? 'ENABLED' : 'DISABLED',
            ),
            'staff_photo'  => array(
                'method' => 'setPhoto',
                'value'  => empty($data['staff_photo']) ? null : $data['staff_photo'],
            ),
            'staff_phone'  => array(
                'method' => 'setPhone',
                'value'  => empty($data['staff_phone']) ? null : mb_substr(trim($data['staff_phone']), 0, 255),
            ),
            'staff_skype'  => array(
                'method' => 'setSkype',
                'value'  => empty($data['staff_skype']) ? null : mb_substr(trim($data['staff_skype']), 0, 255),
            ),
            'staff_icq'  => array(
                'method' => 'setIcq',
                'value'  => empty($data['staff_icq']) ? null : mb_substr(trim($data['staff_icq']), 0, 12),
            ),
            'staff_position'  => array(
                'method' => 'setPosition',
                'value'  => empty($data['staff_position']) ? null : mb_substr($data['staff_position'], 0, 255),
            ),
            'staff_is_highlight' => array(
                'method' => 'setIsHighlight',
                'value'  => empty($data['staff_is_highlight']) ? 0 : 1,
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

        if (empty($errors)) {
            $m->setSignature();

            /** @var $q DomainObjectQuery_Staff */
            $q = DxFactory::getInstance('DomainObjectQuery_Staff');
            $_m = $q->findBySignature($m->getSignature());

            if ($_m !== null && $m->getId() != $_m->getId()) {
                $errors['staff_name'] = 'ALREADY_EXISTS';
            }
        }

        if (!empty($data['staff_photo'])) {
            try {
                DxFactory::invoke('DxFile_Image', 'createByPath', array(ROOT . $data['staff_photo']));
            } catch (DxException $e) {
                $img_errors = array(
                    DxFile_Image::ERROR_IMAGE_NOT_FOUND   => 'IMAGE_NOT_FOUND',
                    DxFile_Image::ERROR_IMAGE_LOAD        => 'IMAGE_NOT_LOAD',
                    DxFile_Image::ERROR_IMAGE_UNSUPPORTED => 'IMAGE_UNSUPPORTED',
                );

                $errors['staff_photo'] = array_key_exists($e->getCode(), $img_errors) ? $img_errors[$e->getCode()] : 'NOT_VALID';
            }
        }

        if ($this->getId() === 'staff_add') {
            $qnt = DxFactory::getSingleton('DomainObjectQuery_Staff')->getMaxQnt();
            $m->setQnt($qnt + 3);
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
        /** @var DomainObjectQuery_Office $q */
        $q = DxFactory::getInstance('DomainObjectQuery_Office');

        if ($this->getContext()->getCurrentUser()->getRole() == 'DIRECTOR') {
            $offices = (array)$this->getContext()->getCurrentUser()->getSubdivisionOffices();
        } else {
            $offices = $q->findAll(true);
        }

        $offices_array = array();

        foreach ($offices as $office) {
            $offices_array[$office->getCity()->getTitle()][$office->getId()] = $office;
        }

        $this->smarty->assign(
            array(
                'offices_array' => $offices_array,
            )
        );

        return $this->smarty->fetch('backend/form/staff.tpl.php');
    }
}