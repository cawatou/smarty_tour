<?php
DxFactory::import('DxController_Frontend');

class DxController_Frontend_Request extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.request' => 'request',
        '.order'   => 'request',
    );

    protected function request()
    {
        /** @var $form Form_Frontend_Request */
        $form = DxFactory::getInstance('Form_Frontend_Request', array('request_add'));

        $form->setContext($this->getContext());

        $form->setUrl($this->getUrl()->url('/request') .'#form');
        $form->getModel()->setType('REQUEST');

        if ($this->getContext()->getCurrentCommand()->getCmd() == '.order') {
            $form->setUrl($this->getUrl()->url('/order') .'#form');
            $form->getModel()->setType('ORDER');
        }

        if ($form->isProcessed()) {
            /** @var $qsettings DomainObjectQuery_Settings */
            $qsettings = DxFactory::getSingleton('DomainObjectQuery_Settings');

            if ($form->getModel()->getType() == 'REQUEST') {
                $config = $qsettings->getByGroup('COMMON');

                $notice_email = array();

                if (!empty($config['NOTICE_EMAIL'])) {
                    $notice_email = preg_split('~\s*,\s*~', $config['NOTICE_EMAIL']);
                }

                if ($form->getModel()->getOffice() !== null && $form->getModel()->getOffice()->getEmail() !== null) {
                    $notice_email[] = $form->getModel()->getOffice()->getEmail();
                } else {
                    if ($form->getModel()->getOffice() === null && $form->getModel()->getExtendedData('office_other') !== null) {
                        $notice_email = preg_split('~\s*,\s*~', $config['REQUEST_OTHER_NOTICE_EMAIL']);
                    } else {
                        if ($this->getContext()->getCity()->getEmail() !== null) {
                            $notice_email[] = $this->getContext()->getCity()->getEmail();
                        }
                    }
                }

                if (!empty($notice_email)) {
                    /** @var $mail Utils_Mail */
                    $mail = DxFactory::getInstance('Utils_Mail');

                    $data = $form->getEnvData('_POST');

                    $data['for']     = 'REQUEST';
                    $data['request'] = $form->getModel();

                    $subject = Utils_Mail::textOfTemplate('frontend/mail/subject.tpl.php', $data);
                    $body    = Utils_Mail::textOfTemplate('frontend/mail/body.tpl.php',    $data);

                    if (!empty($notice_email)) {
                        $notice_email = array_unique($notice_email);

                        try {
                            $mail->send($notice_email, '', $subject, $body);
                        } catch (DxException $e) {
                        }
                    }
                }
            } elseif ($form->getModel()->getType() == 'ORDER') {
                $settings = $qsettings->getByGroup('BUY_TOUR');

                $notice_email = array();

                if (!empty($settings['MANAGER_EMAIL'])) {
                    $notice_email[] = $settings['MANAGER_EMAIL'];
                }

                if (!empty($notice_email)) {
                    $mail = DxFactory::getInstance('Utils_Mail');
                    $data = $form->getEnvData('_POST');

                    $data['request'] = $form->getModel();
                    $data['for']     = 'ORDER';

                    $subject = Utils_Mail::textOfTemplate('frontend/mail/subject.tpl.php', $data);
                    $body    = Utils_Mail::textOfTemplate('frontend/mail/body.tpl.php',    $data);

                    if (!empty($notice_email) && !empty($_REQUEST['request_custom_staff'])) {
                        $notice_email = array_unique($notice_email);

                        try {
                            $mail->send($notice_email, '', $subject, $body);
                        } catch (DxException $e) {
                        }
                    }
                }
            }

            $form->setSuccessful();
            $this->getUrl()->redirect($form->getUrl());
        }

        /** @var $q DomainObjectQuery_Office */
        $q = DxFactory::getSingleton('DomainObjectQuery_Office');

        DxFactory::import('DomainObjectModel_Hotel');

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'office_list' => $q->findAll(true),

                '__f' => $form,
                'form_html' => $form->draw(),
            )
        );

        $html = $smarty->fetch('frontend/master_request.tpl.php');

        return $this->wrap($html);
    }
}