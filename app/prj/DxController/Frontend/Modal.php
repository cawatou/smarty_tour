<?php
DxFactory::import('DxController_Frontend');

class DxController_Frontend_Modal extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.modal.complain' => 'modalComplain',
        '.modal.callback' => 'modalCallback',
    );

    /**
     * @return string
     */
    protected function modalComplain()
    {
        if (!isset($_REQUEST['ajax'])) {
            return null;
        }

        /** @var Form_Frontend_Feedback_Quality $form */
        $form = DxFactory::getInstance('Form_Frontend_Feedback_Quality', array('feedback_add'));
        $form->setUrl($this->getUrl()->url('/modal/complain'));
        $form->setContext($this->getContext());
        $form->setSuccessful(false);

        if ($form->isProcessed()) {
            /** @var DomainObjectQuery_Settings $qsettings */
            $qsettings = DxFactory::getSingleton('DomainObjectQuery_Settings');
            $config = $qsettings->getByGroup('COMMON');

            $notice_email = array();

            if (!empty($config['NOTICE_EMAIL'])) {
                $notice_email = preg_split('~\s*,\s*~', $config['NOTICE_EMAIL']);
            }

            if ($this->getContext()->getCity()->getEmail() !== null) {
                $notice_email[] = $this->getContext()->getCity()->getEmail();
            }

            if (!empty($notice_email)) {
                /** @var Utils_Mail $mail */
                $mail = DxFactory::getInstance('Utils_Mail');
                $data = $form->getEnvData('_POST');

                $data['for']      = 'FEEDBACK';
                $data['feedback'] = $form->getModel();

                $subject = Utils_Mail::textOfTemplate('frontend/mail/subject.tpl.php', $data);

                $body = Utils_Mail::textOfTemplate('frontend/mail/body.tpl.php', $data);

                if (!empty($notice_email)) {
                    $notice_email = array_unique($notice_email);

                    try {
                        $mail->send($notice_email, '', $subject, $body);
                    } catch (DxException $e) {
                    }
                }
            }

            $form->setSuccessful();
        }

        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                '__f'       => $form,
                'form_html' => $form->draw(),
            )
        );

        $res = array(
            'data' => $form->getModel()->toArray(),
            'html' => $smarty->fetch('frontend/modal_complain.tpl.php'),
        );

        return json_encode($res);
    }

    /**
     * @return string
     */
    protected function modalCallback()
    {
        if (!isset($_REQUEST['ajax'])) {
            return null;
        }

        /** @var Form_Frontend_Request_Callback $form */
        $form = DxFactory::getInstance('Form_Frontend_Request_Callback', array('request_callback_add'));
        $form->setUrl($this->getUrl()->url('/modal/callback'));
        $form->setContext($this->getContext());
        $form->setSuccessful(false);

        if ($form->isProcessed()) {
            /** @var DomainObjectQuery_Settings $qsettings */
            $qsettings = DxFactory::getSingleton('DomainObjectQuery_Settings');
            $config = $qsettings->getByGroup('COMMON');

            $notice_email = array();

            if (!empty($config['NOTICE_EMAIL'])) {
                $notice_email = preg_split('~\s*,\s*~', $config['NOTICE_EMAIL']);
            }

            if ($this->getContext()->getCity()->getEmail() !== null) {
                $notice_email[] = $this->getContext()->getCity()->getEmail();
            }

            if (!empty($notice_email)) {
                /** @var Utils_Mail $mail */
                $mail = DxFactory::getInstance('Utils_Mail');
                $data = $form->getEnvData('_POST');

                $data['for']     = 'CALLBACK';
                $data['request'] = $form->getModel();

                $subject = Utils_Mail::textOfTemplate('frontend/mail/subject.tpl.php', $data);

                $body = Utils_Mail::textOfTemplate('frontend/mail/body.tpl.php', $data);

                if (!empty($notice_email)) {
                    $notice_email = array_unique($notice_email);

                    try {
                        $mail->send($notice_email, '', $subject, $body);
                    } catch (DxException $e) {
                    }
                }
            }

            $form->setSuccessful();
        }

        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                '__f'       => $form,
                'form_html' => $form->draw(),
            )
        );

        $res = array(
            'data' => $form->getModel()->toArray(),
            'html' => $smarty->fetch('frontend/modal_callback.tpl.php'),
        );

        return json_encode($res);
    }
}