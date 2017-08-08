<?php
DxFactory::import('DxController_Frontend');
DxFactory::import('Form_Filter');

class DxController_Frontend_Companion extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.companion' => 'index',
    );

    /**
     * @return string
     */
    protected function index()
    {
        /** @var $form Form_Frontend_Companion */
        $form = DxFactory::getInstance('Form_Frontend_Companion', array('companion_add'));
        $form->setUrl($this->getUrl()->url('/companion') . '#form');

        if ($this->getContext()->getCity() !== null) {
            $form->getModel()->setUserCity($this->getContext()->getCity()->getTitle());
        }

        $form->getModel()->setExtendedData(
            array(
                'is_signup_email' => 1,
                'is_signup_sms'   => 1,
            )
        );

        if ($form->isProcessed()) {
            /** @var $qsettings DomainObjectQuery_Settings */
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
                /** @var $mail Utils_Mail */
                $mail = DxFactory::getInstance('Utils_Mail');
                $data = $form->getEnvData('_POST');

                $data['for']       = 'COMPANION';
                $data['companion'] = $form->getModel();

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

        /** @var $q DomainObjectQuery_Companion */
        $q = DxFactory::getSingleton('DomainObjectQuery_Companion');

        $page_number = (int)$this->getContext()->getCurrentCommand()->getArguments('page');

        $arr = array(
            Form_Filter::FILTER_SEARCH_PARAMS => array(
                'companion_status' => 'ENABLED',
                'active_only'      => true,
            )
        );

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setPaginatorPageUrl($this->getUrl()->url('/companion,%s'));
        $dl->setItemsPerPage(10);
        $dl->setParameters($arr);
        $dl->setCurrentPageNumber($page_number < 1 ? 1 : $page_number);

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

        $html = $smarty->fetch('frontend/companion.tpl.php');

        return $this->wrap($html);
    }
}