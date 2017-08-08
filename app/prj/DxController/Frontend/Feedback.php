<?php

DxFactory::import('DxController_Frontend');

class DxController_Frontend_Feedback extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.feedback' => 'index',
    );

    /**
     * @return string
     */
    protected function index()
    {
        /** @var $form Form_Frontend_Feedback_Propose */
        $form = DxFactory::getInstance('Form_Frontend_Feedback_Propose', array('feedback_add'));
        $form->setUrl($this->getUrl()->url('/feedback') . '#form');
        $form->setContext($this->getContext());

        if ($form->isProcessed()) {
            /*
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
                $mail = DxFactory::getInstance('Utils_Mail');
                $data = $form->getEnvData('_POST');
                $data['for'] = 'FEEDBACK';

                $subject = Utils_Mail::textOfTemplate('frontend/mail/subject.tpl.php', $data);
                $body    = Utils_Mail::textOfTemplate('frontend/mail/body.tpl.php', $data);

                if (!empty($notice_email)) {
                    $notice_email = array_unique($notice_email);

                    try {
                        $mail->send($notice_email, '', $subject, $body);
                    } catch (DxException $e) {
                    }
                }
            }
            */

            $form->setSuccessful();
            $this->getUrl()->redirect($form->getUrl());
        }

        /** @var $q  DomainObjectQuery_Feedback */
        $q = DxFactory::getSingleton('DomainObjectQuery_Feedback');

        $page_number = $this->getContext()->getCurrentCommand()->getArguments('page');

        $arr = array(
            's' => array(
                'feedback_status' => 'ENABLED',
                'feedback_type'   => 'PROPOSE',
            )
        );

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageUrl($this->getUrl()->url('/feedback,%s'));
        $dl->setItemsPerPage(10);
        $dl->setParameters($arr);
        $dl->setCurrentPageNumber((int)$page_number < 1 ? 1 : (int)$page_number);

        $list  =& $dl->getRequestedPage();
        $state =  $dl->getState();

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'list'      => $list,
                'state'     => $state,
                '__f'       => $form,
                'form_html' => $form->draw(),
            )
        );

        $html = $smarty->fetch('frontend/feedback.tpl.php');

        return $this->wrap($html);
    }
}