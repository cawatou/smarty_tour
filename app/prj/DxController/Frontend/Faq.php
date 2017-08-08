<?php

DxFactory::import('DxController_Frontend');

class DxController_Frontend_Faq extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.faq' => 'index',
    );

    /**
     * @return string
     */
    protected function index()
    {
        /** @var $form Form_Frontend_Faq */
        $form = DxFactory::getInstance('Form_Frontend_Faq', array('faq_add'));
        $form->setUrl($this->getUrl()->url('/faq') .'#form');
        $form->setContext($this->getContext());

        if ($form->isProcessed()) {
            $q_settings = DxFactory::getSingleton('DomainObjectQuery_Settings');
            $config     = $q_settings->getByGroup('COMMON');

            $notice_email = array();

            if (!empty($config['NOTICE_EMAIL'])) {
                $notice_email = preg_split('~\s*,\s*~', $config['NOTICE_EMAIL']);
            }

            if ($form->getModel()->getCity() !== null && $form->getModel()->getCity()->getEmail() !== null) {
                $notice_email[] = $form->getModel()->getCity()->getEmail();
            } else {
                if ($this->getContext()->getCity()->getEmail() !== null) {
                    $notice_email[] = $this->getContext()->getCity()->getEmail();
                }
            }

            if (!empty($notice_email)) {
                $mail = DxFactory::getInstance('Utils_Mail');
                $data = $form->getEnvData('_POST');
                $data['for'] = 'FAQ';

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

            $form->setSuccessful();
            $this->getUrl()->redirect($form->getUrl());
        }

        /** @var $q  DomainObjectQuery_Faq */
        $q = DxFactory::getSingleton('DomainObjectQuery_Faq');

        $page_number = $this->getContext()->getCurrentCommand()->getArguments('page');

        $arr = array(
            's' => array(
                'faq_status' => 'ENABLED',
            )
        );

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageUrl($this->getUrl()->url('/faq,%s'));
        $dl->setItemsPerPage(10);
        $dl->setParameters($arr);
        $dl->setCurrentPageNumber((int)$page_number < 1 ? 1 : (int)$page_number);

        $list  =& $dl->getRequestedPage();
        $state =  $dl->getState();

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'list'  => $list,
                'state' => $state,

                '__f' => $form,
                'form_html'      => $form->draw(),
                'form_submitted' => $form->isSubmitted() || $form->isProcessed(),
            )
        );

        $html = $smarty->fetch('frontend/faq.tpl.php');

        return $this->wrap($html);
    }
}