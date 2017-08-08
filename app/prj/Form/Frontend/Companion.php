<?php
dxFactory::import('Form_Frontend');

class Form_Frontend_Companion extends Form_Frontend
{
    /** @var DomainObjectModel_Companion */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Companion|null $form_model
     */
    public function setModel(DomainObjectModel_Companion $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Companion|null
     */
    public function getModel()
    {
        if ($this->form_model === null) {
            $this->form_model = DxFactory::getInstance('DomainObjectModel_Companion');
        }

        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Companion|null
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
            'companion_location' => array(
                'method' => 'setLocation',
                'value'  => empty($data['companion_location']) ? null : mb_substr(trim($data['companion_location']), 0, 255),
            ),
            'companion_price' => array(
                'method' => 'setPrice',
                'value'  => empty($data['companion_price']) ? null : $data['companion_price'],
            ),
            'companion_notes' => array(
                'method' => 'setNotes',
                'value'  => empty($data['companion_notes']) ? null : trim($data['companion_notes']),
            ),

            'companion_user_name' => array(
                'method' => 'setUserName',
                'value'  => mb_substr(trim($data['companion_user_name']), 0, 255),
            ),
            'companion_user_phone' => array(
                'method' => 'setUserPhone',
                'value'  => mb_substr(trim($data['companion_user_phone']), 0, 255),
            ),
            'companion_user_email' => array(
                'method' => 'setUserEmail',
                'value'  => mb_strtolower(mb_substr(trim($data['companion_user_email']), 0, 255)),
            ),
            'companion_user_age' => array(
                'method' => 'setUserAge',
                'value'  => empty($data['companion_user_age']) || $data['companion_user_age'] <= 0 ? null : $data['companion_user_age'],
            ),
            'companion_user_city' => array(
                'method' => 'setUserCity',
                'value'  => mb_substr(trim($data['companion_user_city']), 0, 255),
            ),
            'companion_user_photo_uploaded' => array(
                'method' => 'setUserPhoto',
                'value'  => empty($data['companion_user_photo_uploaded']) ? null : $data['companion_user_photo_uploaded'],
            ),

            'companion_user_gender' => array(
                'method' => 'setUserGender',
                'value'  => empty($data['companion_user_gender']) || !in_array($data['companion_user_gender'], array('MALE', 'FEMALE')) ? 'MALE' : $data['companion_user_gender'],
            ),
            'companion_target_gender' => array(
                'method' => 'setTargetGender',
                'value'  => empty($data['companion_target_gender']) || !in_array($data['companion_target_gender'], array('MALE', 'FEMALE', 'UNKNOWN')) ? 'UNKNOWN' : $data['companion_target_gender'],
            ),

            'companion_date_from' => array(
                'method' => 'setDateFrom',
                'value'  => empty($data['companion_date_from']) ? null : new DxDateTime($data['companion_date_from']),
            ),
            'companion_date_to' => array(
                'method' => 'setDateTo',
                'value'  => empty($data['companion_date_to']) ? null : new DxDateTime($data['companion_date_to']),
            ),

            'companion_daynum_from' => array(
                'method' => 'setDaynumFrom',
                'value'  => empty($data['companion_daynum_from']) || $data['companion_daynum_from'] <= 0 ? null : (int)$data['companion_daynum_from'],
            ),
            'companion_daynum_to' => array(
                'method' => 'setDaynumTo',
                'value'  => empty($data['companion_daynum_to']) || $data['companion_daynum_to'] <= 0 ? null : (int)$data['companion_daynum_to'],
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
        );

        $extended = array();

        $_keys_ext = array(
            'is_signup_email',
            'is_signup_sms',
        );

        foreach ($_keys_ext as $k) {
            if (!empty($data['companion_extended_'. $k])) {
                $extended[$k] = $data['companion_extended_'. $k];
            } else {
                if (isset($extended_required[$k])) {
                    $errors['companion_extended_'. $k] = 'NOT_VALID';
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

        if (empty($errors['companion_date_from']) && empty($errors['companion_date_to'])) {
            if ($m->getDateFrom() !== null && $m->getDateTo() !== null) {
                $date_from = $m->getDateFrom()->format('Ymd');
                $date_to   = $m->getDateTo()->format('Ymd');

                if ($date_from > $date_to) {
                    $errors['companion_date_from'] = 'MORE_THAN_DATE_TO';
                }
            }
        }

        $m->setStatus('DISABLED');
        $m->setUserIp(empty($_SERVER['REMOTE_ADDR']) ? '0.0.0.0' : $_SERVER['REMOTE_ADDR']);

        if (!empty($_FILES['companion_user_photo']) && is_readable($_FILES['companion_user_photo']['tmp_name'])) {
            $res = $this->uploadUserPhoto($_FILES['companion_user_photo']);

            if ($res['code'] == 'OK') {
                $m->setUserPhoto($res['full_path']);
            } else {
                $errors['companion_user_photo'] = 'INVALID_FORMAT';
            }
        }

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        if (!empty($data['is_signup_email'])) {
            $this->signupEmail();
        }

        if (!empty($data['is_signup_phone'])) {
            $this->signupPhone();
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

        /** @var $q DomainObjectQuery_Companion */
        $model = $this->getModel();

        $this->smarty->assign(
            array(
                'user_genders'   => $model->getUserGenders(),
                'target_genders' => $model->getTargetGenders(),
            )
        );

    	return $this->smarty->fetch('frontend/form/companion.tpl.php');
    }

    public function signupEmail()
    {
        // In case, in the future, this feature will be requested
    }

    public function signupPhone()
    {
        // In case, in the future, this feature will be requested
    }

    public function uploadUserPhoto($files)
    {
        $res = array(
            'code' => 'ERROR',
        );

        DxFactory::import('DxFile');
        DxFactory::import('DxFile_Image');
        DxFactory::import('Utils_NameMaker');

        if (empty($files)) {
            return $res;
        }

        $res['code'] = 'OK';

        $_files = array();

        $dst_name = time() . '_' . $files['name'];

        $_files[] = array(
            'src_path' => $files['tmp_name'],
            'src_name' => $files['name'],
            'dst_name' => Utils_NameMaker::modifyFileName($dst_name),
        );

        $config = DxApp::config('url', 'static');
        $full_files_path = DxFile::makeFullPath($config['files']);
        $full_files_path = DxFile::cleanPath($full_files_path . DS .'upload'. DS . 'companion', DS);
        DxFile::createDir($full_files_path);
        $new_files = DxFile_Upload::createByRequest($_files, $full_files_path);

        foreach ($new_files as $file) {
            $relative = $file->makeRelativePath($file->getFullPath());

            $relative = explode('/', $relative);
            $filename = end($relative);

            try {
                $image = DxFactory::invoke('DxFile_Image', 'createByPath', array($file->getFullPath()));

                $image->resize(500, null, DxFile_Image::RESIZE_WIDTH);

                $image->commit($file->getFullPath(), 75);
            } catch (DxException $e) {
                $res['code'] = 'ERROR';

                $file->removeFile($file->getFullPath());

                continue;
            }

            $res = array(
                'code'      => 'OK',
                'name'      => $filename,
                'full_path' => $file->getRelativePath($file->getFullPath()),
            );

            break;
        }

        return $res;
    }

    public function isSubmitted()
    {
        return $this->isSubmited();
    }
}