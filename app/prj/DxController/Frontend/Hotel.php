<?php

DxFactory::import('DxController_Frontend');

class DxController_Frontend_Hotel extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.hotel.details' => 'details',
    );


    protected function details()
    {
        $hotel_id = (int)$this->getContext()->getCurrentCommand()->getArguments('id');

        if ($hotel_id <= 0) {
            return $this->notFound();
        }

        /** @var $q DomainObjectQuery_Hotel */
        $q = DxFactory::getSingleton('DomainObjectQuery_Hotel');

        $hotel = $q->findById($hotel_id);

        if (empty($hotel) || $hotel->getStatus() != 'ENABLED') {
            return $this->notFound();
        }

        /** @var $form Form_Frontend_Feedback_Hotel */
        $form = DxFactory::getInstance('Form_Frontend_Feedback_Hotel', array('feedback_add'));
        $form->setUrl($hotel->getUrl() . '#form');
        $form->getModel()->setHotelId($hotel_id);

        if ($form->isProcessed()) {
            $q_settings = DxFactory::getSingleton('DomainObjectQuery_Settings');
            $config     = $q_settings->getByGroup('COMMON');

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
                $data['for'] = 'FEEDBACK_HOTEL';

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

        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'hotel'           => $hotel,
                'total_feedbacks' => count($hotel->getFeedbacks()),
                'total_images'    => count($hotel->getImages()),

                '__f'            => $form,
                'form_html'      => $form->draw(),
                'form_submitted' => $form->isSubmitted() || $form->isProcessed(),
            )
        );

        $html = $smarty->fetch('frontend/hotel_details.tpl.php');

        return $this->wrap($html);
    }
}