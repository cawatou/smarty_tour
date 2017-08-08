<?php

DxFactory::import('DxController_Frontend');

class DxController_Frontend_Order extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.order.create' => 'create',
        '.order.view'   => 'view',
    );

    /**
     * @return string
     */
    protected function create()
    {
        if (!isset($_REQUEST['ajax'])) {
            return null;
        }

        $product_id = $this->getContext()->getCurrentCommand()->getArguments('product_id');
        $date       = $this->getContext()->getCurrentCommand()->getArguments('departure_date');
        $signature  = $this->getContext()->getCurrentCommand()->getArguments('hotel_signature');
        $price      = $this->getContext()->getCurrentCommand()->getArguments('hotel_price');

        if (!$product_id || !$date || !$signature || !$price) {
            return $this->forceNotFound();
        }

        /** @var $q DomainObjectQuery_Product */
        $q = DxFactory::getSingleton('DomainObjectQuery_Product');

        $product = $q->findById($product_id);

        if (!$product || $product->getStatus() != 'ENABLED') {
            return $this->forceNotFound();
        }

        $departures = $product->getDepartures();

        /** @var $q DomainObjectModel_ProductDeparture */
        $found_departure = null;

        foreach ($departures as $departure) {
            if ($departure->getDate()->format('d-m-Y') == $date) {
                $found_departure = $departure;

                break;
            }
        }

        if (!$found_departure) {
            return $this->forceNotFound();
        }

        $found_hotel = null;

        foreach ($found_departure->getHotels() as $hotel) {
            if ($hotel['signature'] == $signature) {
                $found_hotel = $hotel;

                break;
            }
        }

        if (empty($found_hotel)) {
            return $this->forceNotFound();
        }

        $hotel_price = $found_hotel['sale_price'];

        $is_price_changed = false;

        if ($product->getIsDiscountApplied()) {
            /** @var DomainObjectQuery_Discount $q_d */
            $q_d = DxFactory::getInstance('DomainObjectQuery_Discount');

            $default_discount = $q_d->findDefault('DISCOUNT');
            $default_promo    = $q_d->findDefault('PROMO');
            $discounts = $product->getFittingDiscounts();

            if (!empty($found_hotel['is_discountable'])) {
                $isAnyFitting = false;
                $type = 'DISCOUNT';

                if ($product->getIsHighlight()) {
                    $type = 'PROMO';
                } elseif (!empty($found_hotel['is_promoprice'])) {
                    $type = 'PROMO';
                }

                $usableDiscount = null;

                if (!empty($discounts[$type])) {
                    foreach ($discounts[$type] as $discount) {
                        $isFitting = $product->isDiscountFitting($discount, $hotel_price);

                        if (!$isFitting) {
                            continue;
                        }

                        $isAnyFitting = true;

                        $usableDiscount = $discount;

                        break;
                    }
                }

                if (!$isAnyFitting) {
                    if ($type == 'DISCOUNT') {
                        if ($default_discount !== null && $default_discount->getPercent() > 0) {
                            $usableDiscount = $default_discount;
                        }
                    } else {
                        if ($default_promo !== null && $default_promo->getPercent() > 0) {
                            $usableDiscount = $default_promo;
                        }
                    }
                }

                $hotel_price = $product->calculatePriceWithDiscount($hotel_price, $usableDiscount);
            }
        }

        if ($hotel_price != $price) {
            $is_price_changed = true;
        }

        /** @var Form_Frontend_Order $form */
        $form = DxFactory::getInstance('Form_Frontend_Order', array('order_add'));
        $form->setUrl($this->getUrl()->url('/order/create/'. $product_id .'/'. $date .'/'. $signature .'/'. $price .'/'));
        $form->setContext($this->getContext());
        $form->setSuccessful(false);

        $product_data = $product->toArray();

        // Remove related product departure
        if (isset($product_data['ProductDeparture'])) {
            unset($product_data['ProductDeparture']);
        }

        $form->getModel()->setPriceOpening($price);
        $form->setProductData($product_data);

        $form->setHotelData(
            array_merge(
                $found_hotel,
                array(
                    'departure_date'     => new DxDateTime($date),
                    'departure_daynum'   => $found_departure->getDaynum(),
                    'departure_nightnum' => $found_departure->getNightnum(),
                    'departure_seats'    => $found_departure->getSeats(),
                )
            )
        );

        if ($form->isProcessed()) {
            $data = $form->getEnvData('_POST');

            /** @var DomainObjectQuery_Settings $qsettings */
            $qsettings = DxFactory::getSingleton('DomainObjectQuery_Settings');

            if ($data['order_type'] == 'ONLINE') {
                $settings = $qsettings->getByGroup('BUY_TOUR');

                $notice_email = array();

                if ($form->getModel()->getCustomerEmail()) {
                    $notice_email[] = $form->getModel()->getCustomerEmail();
                }

                if (!empty($notice_email)) {
                    $mail = DxFactory::getInstance('Utils_Mail');
                    $data = $form->getEnvData('_POST');

                    $data['settings'] = $settings;
                    $data['model']    = $form->getModel();
                    $data['for']      = 'ORDER_PAYABLE';

                    $subject = Utils_Mail::textOfTemplate('frontend/mail/subject_order.tpl.php', $data);
                    $body    = Utils_Mail::textOfTemplate('frontend/mail/body_order.tpl.php',    $data);

                    $notice_email = array_unique($notice_email);

                    try {
                        $mail->send($notice_email, '', $subject, $body);
                    } catch (DxException $e) {
                    }
                }
            } else {
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
                'form'             => $form,
                'tour'             => $product,
                'found_departure'  => $found_departure,
                'found_hotel'      => $found_hotel,
                'is_price_changed' => $is_price_changed,
                'original_price'   => $price,
                'changed_price'    => $found_hotel['price'],
                'hotel_price'      => $hotel_price,
                'order_url'        => method_exists($form->getModel(), 'getSignature') && $form->getModel()->getSignature() ? $form->getModel()->getUrl() : null,
            )
        );

        $res = array(
            'data' => $form->getModel()->toArray(),
            'html' => $smarty->fetch('frontend/order.tpl.php'),
        );

        return json_encode($res);
    }

    /**
     * @return string
     */
    protected function view()
    {
        $signature = $this->getContext()->getCurrentCommand()->getArguments('signature');
        $callback  = $this->getContext()->getCurrentCommand()->getArguments('callback');

        /** @var $q DomainObjectQuery_Order */
        $q = DxFactory::getSingleton('DomainObjectQuery_Order');

        /** @var $order DomainObjectModel_Order */
        $order = $q->findBySignature($signature);

        if (empty($order)) {
            return $this->forceNotFound();
        }

        if (!$order->isAvailable()) {
            return $this->forceNotFound();
        }

        $callback_data = $this->executeCallback($callback, $order);

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $tour = null;

        if ($order->getProduct() !== null) {
            $tour = $order->getProduct();
        }

        /** @var $form Form_Frontend_Order_CustomerData */
        $form = DxFactory::getInstance('Form_Frontend_Order_CustomerData', array('order_add'));
        $form->setUrl($this->getUrl()->url('/order/'. $order->getSignature() .'/') .'#form');
        $form->setModel($order);

        /** @var DomainObjectQuery_Settings $qsettings */
        $qsettings = DxFactory::getSingleton('DomainObjectQuery_Settings');
        $settings  = $qsettings->getByGroup('BUY_TOUR');

        if ($form->isProcessed()) {
            if (!$order->getIsContractAgree()) {
                $notice_email = array();

                if (!empty($settings['MANAGER_EMAIL'])) {
                    $notice_email[] = $settings['MANAGER_EMAIL'];
                }

                if (!empty($notice_email)) {
                    $mail = DxFactory::getInstance('Utils_Mail');
                    $data = $form->getEnvData('_POST');

                    $data['for'] = 'ORDER_PAYABLE';

                    $subject = Utils_Mail::textOfTemplate('frontend/mail/subject.tpl.php', $data);
                    $body    = Utils_Mail::textOfTemplate('frontend/mail/body.tpl.php', $data);

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

        $smarty->assign(
            array(
                'order'         => $order,
                'tour'          => $tour,
                'form'          => $form,
                'errors'        => $form->getErrors(),
                'settings'      => $settings,
                'payonline_cfg' => DxApp::config('payonline'),
                'callback'      => $callback_data,
                'urls'          => array(
                    'success' => rtrim($order->getUrl(), '/') .'/success/',
                    'fail'    => rtrim($order->getUrl(), '/') .'/fail/',
                ),
            )
        );

        $html = $smarty->fetch('frontend/order_view.tpl.php');

        return $this->wrap($html);
    }

    protected function executeCallback($callback, DomainObjectModel_Order $order)
    {
        $data = array();

        switch ($callback) {
            case 'success':
                    $data['message'] = 'SUCCESS';
                break;
            case 'fail':
                    $data['message'] = 'FAIL';
                break;
            default:
                break;
        }

        return $data;
    }

    /**
     * @return string
     */
    protected function modalComplain()
    {
        if (!isset($_REQUEST['ajax'])) {
            return null;
        }

        /** @var $form Form_Frontend_Feedback_Quality */
        $form = DxFactory::getInstance('Form_Frontend_Feedback_Quality', array('feedback_add'));
        $form->setUrl($this->getUrl()->url('/modal/complain'));
        $form->setContext($this->getContext());
        $form->setSuccessful(false);

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

                $data['for']      = 'FEEDBACK';
                $data['feedback'] = $form->getModel();

                $subject = Utils_Mail::textOfTemplate('frontend/mail/subject.tpl.php', $data);

                $body = Utils_Mail::textOfTemplate('frontend/mail/body.tpl.php', $data);

                $notice_email = array_unique($notice_email);

                try {
                    $mail->send($notice_email, '', $subject, $body);
                } catch (DxException $e) {
                }
            }

            $form->setSuccessful();
        }

        /** @var $smarty Smarty */
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
}