<?php

dxFactory::import('Form_Backend');

class Form_Backend_Faq extends Form_Backend
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
            'user_id' => array(
                'method' => 'setUserId',
                'value'  => empty($data['user_id']) ? null : $data['user_id'],
            ),
            'staff_answer_id' => array(
                'method' => 'setStaffAnswerId',
                'value'  => empty($data['staff_answer_id']) ? null : $data['staff_answer_id'],
            ),
            'faq_message' => array(
                'method' => 'setMessage',
                'value'  => $data['faq_message'],
            ),
            'faq_answer' => array(
                'method' => 'setAnswer',
                'value'  => empty($data['faq_answer']) ? null : $data['faq_answer'],
            ),
            'faq_status' => array(
                'method' => 'setStatus',
                'value'  => $data['faq_status'] == 'ENABLED' ? 'ENABLED' : 'DISABLED',
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

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        if (!empty($data['send_notification']) && $m->getUserEmail() !== null) {
            /** @var Utils_Mail $mail */
            $mail = DxFactory::getInstance('Utils_Mail');

            $mailData = array(
                'for'   => 'FAQ',
                'model' => $m,
                'src'   => $data,
            );

            $subject = Utils_Mail::textOfTemplate('backend/mail/subject_message.tpl.php', $mailData);
            $body    = Utils_Mail::textOfTemplate('backend/mail/body_message.tpl.php',    $mailData);

            try {
                $mail->send($m->getUserEmail(), '', $subject, $body);
            } catch (DxException $e) {
            }
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

        $offices   = $q->findAll(true);
        $staff_arr = array();

        foreach ($offices as $office) {
            $staff_arr[$office->getTitle()] = $office->getStaffs();
        }

        $this->smarty->assign(
            array(
                'staffs_array' => $staff_arr,
            )
        );

    	return $this->smarty->fetch('backend/form/faq.tpl.php');
    }
}