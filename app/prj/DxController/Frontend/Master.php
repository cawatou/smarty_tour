<?php

DxFactory::import('DxController_Frontend');

class DxController_Frontend_Master extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        DxCommand::CMD_DEFAULT => 'index',
        '.tours'               => 'index',
        '.tours.details'       => 'details',
        '.ads'                 => 'adsDetails',

        '.imagi' => 'generateImage',

        '.city' => 'setActiveCity',
    );

    /**
     * @return string
     */
    protected function index()
    {
        $active_city = $this->getContext()->getCity();

        if ($this->getContext()->getCurrentCommand()->getArguments('city') && $active_city->getAlias() != $this->getContext()->getCurrentCommand()->getArguments('city')) {
            /** @var DomainObjectQuery_City $q_c */
            $q_c = DxFactory::getInstance('DomainObjectQuery_City');

            /** @var DomainObjectModel_City $selected_city */
            $selected_city = $q_c->findByAlias($this->getContext()->getCurrentCommand()->getArguments('city'));

            if ($selected_city) {
                $active_city = $selected_city;
            }
        }

        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $smarty->setCacheLifetime(300);

        $display_style = empty($_REQUEST['display_style']) ? 'THUMB' : $_REQUEST['display_style'];

        if (!$smarty->isCached('frontend/master_main.tpl.php', 'MAIN_PAGE_'. $display_style .'_'. $active_city->getId())) {
            /** @var DomainObjectQuery_Product $q */
            $q = DxFactory::getSingleton('DomainObjectQuery_Product');

            /** @var DataList_Paginator $dl */
            $dl = DxFactory::getInstance('DataList_Paginator', array($q));
            $dl->setItemsPerPage(1000);

            $arr = array('s' => array());
            $arr['s']['product_status'] = 'ENABLED';
            $arr['s']['tour_type']      = 'WORLD';

            $_arr = $arr;
            $_arr['s']['tour_type'] = 'RUSSIA';

            $dep_list = $active_city->getDepartureList();

            $min_prices = array();

            foreach ($dep_list as $v) {
                if (empty($v['is_hidden']) && $v['departure_id'] != 20) {
                    $dep_ids[$v['departure_id']] = DomainObjectModel_Product::getFromItem($v['departure_id'], 'title_from');

                    $_arr['s']['product_from_id'] = $v['departure_id'];

                    $min_prices[$v['departure_id']] = $q->findMinPrice($_arr['s']);
                }
            }

            if (!empty($dep_ids)) {
                $arr['s']['product_from_ids'] = array_keys($dep_ids);
            }

            $arr['s']['product_only_parent'] = true;

            $dl->setParameters($arr);
            $dl->setCurrentPageNumber(1);

            /** @var DomainObjectModel_Product[] $_list */
            $_list =& $dl->getRequestedPage();

            $list = array();

            foreach ($dep_ids as $dep_id => $dep_title) {
                $list[$dep_id] = array(
                    'departure' => $dep_title,
                    'tours'     => array(),
                    'updated'   => null,
                );
            }

            foreach ($_list as $m) {
                $list[$m->getFromId()]['tours'][$m->getId()] = $m;
                $list[$m->getFromId()]['updated']            = $m->getFromUpdate();
            }

            $smarty->assign(
                array(
                    'list'       => $list,
                    'min_prices' => $min_prices,
                )
            );
        }

        $smarty->assign(
            array(
                'display_style' => $display_style,
            )
        );

        $html = $smarty->fetch('frontend/master_main.tpl.php', 'MAIN_PAGE_'. $display_style .'_'. $active_city->getId());

        $blocks = array(
            'left' => array(
                'SIGNUP',
                'OFFICES',
                'QUALITY',
                'NEWS',
            ),
            'center' => array(
                'BANNER_EXTRALUXE',
            ),
            'right' => array(
                'BANNER',
                'HELP',
                'STAFF',
                'FEEDBACK',
                'VK',
            ),
        );

        return $this->wrap($html, $blocks);
    }

    /**
     * @return string
     */
    protected function details()
    {
        $product_id = $this->getContext()->getCurrentCommand()->getArguments('id');

        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $cache_key = 'TOUR_'. $product_id;

        if (!empty($_SESSION['access_code_available'])) {
            $cache_key .= '_SEC';
        }

        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $smarty->setCacheLifetime(1200);

        /** @var DomainObjectQuery_Product $q_p */
        $q_p = DxFactory::getInstance('DomainObjectQuery_Product');

        $product = $q_p->findById($product_id);

        if (empty($product) || $product->getStatus() != 'ENABLED' || $product->getCountryId() == DomainObjectQuery_Product::COUNTRY_ID_RUSSIA) {
            return $this->notFound();
        }

        $dep_list = $this->getContext()->getCity()->getSimilarProductCities();
        $dep_ids  = array();

        if (!empty($dep_list)) {
            foreach ($dep_list as $dep_id => $v) {
                if ($dep_id != $product->getFromId()) {
                    $dep_ids[] = $dep_id;
                }
            }
        }

        if (!$smarty->isCached('frontend/master_tour.tpl.php', $cache_key)) {
            /** @var DomainObjectQuery_Discount $q_d */
            $q_d = DxFactory::getInstance('DomainObjectQuery_Discount');

            /** @var DomainObjectQuery_Country $q_c */
            $q_c = DxFactory::getInstance('DomainObjectQuery_Country');

            $defaultDiscount = $q_d->findDefault('DISCOUNT');
            $defaultPromo    = $q_d->findDefault('PROMO');
            $discounts       = $product->getFittingDiscounts();

            $smarty->assign(
                array(
                    'tour' => $product,

                    'default_discount' => $defaultDiscount,
                    'default_promo'    => $defaultPromo,
                    'discounts'        => $discounts,

                    'country_visa_list' => $q_c->getByVisaDays(),
                )
            );
        }

        $similar_products = array();

        if (!empty($dep_ids)) {
            $arr = array(
                's' => array(
                    'product_status'   => 'ENABLED',
                    'product_from_ids' => $dep_ids,
                    'country_id'       => $product->getCountryId(),
                    'tour_type'        => 'WORLD',
                ),
            );

            if ($product->getResortId()) {
                $arr['s']['resort_id'] = $product->getResortId();
            }

            $similar_products = $q_p->findByParams($arr, 10);
        }

        $smarty->assign(
            array(
                'similar_products' => $similar_products,
            )
        );

        $html = $smarty->fetch('frontend/master_tour.tpl.php', $cache_key);

        $blocks = array(
            'left' => array(
                'SIGNUP',
                'OFFICES',
                'QUALITY',
                'NEWS',
            ),
            'center' => array(
                'BANNER_EXTRALUXE',
            ),
            'right' => array(
                'BANNER',
                'HELP',
                'STAFF',
                'FEEDBACK',
                'VK',
            ),
        );

        return $this->wrap($html, $blocks);
    }

    /**
     * @return string
     */
    protected function adsDetails()
    {
        $from_alias    = $this->getContext()->getCurrentCommand()->getArguments('from_alias');
        $country_alias = $this->getContext()->getCurrentCommand()->getArguments('country_alias');

        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $smarty->setCacheLifetime(1200);

        if (!$smarty->isCached('frontend/master_tour.tpl.php', 'TOUR_ADS_'. $from_alias .'_'. $country_alias) || !empty($_SESSION['access_code_available'])) {
            /** @var DomainObjectQuery_Product $q_p */
            $q_p = DxFactory::getInstance('DomainObjectQuery_Product');

            /** @var DomainObjectQuery_Discount $q_d */
            $q_d = DxFactory::getInstance('DomainObjectQuery_Discount');

            $product = $q_p->findForAds($from_alias, $country_alias);

            if (empty($product) || $product->getStatus() != 'ENABLED' || $product->getCountryId() == DomainObjectQuery_Product::COUNTRY_ID_RUSSIA) {
                return $this->notFound();
            }

            /** @var DomainObjectQuery_Country $q_c */
            $q_c = DxFactory::getInstance('DomainObjectQuery_Country');

            $defaultDiscount = $q_d->findDefault();
            $discounts = $product->getFittingDiscounts();

            // should be "300"
            $smarty->setCacheLifetime(300);

            $smarty->assign(
                array(
                    'tour' => $product,

                    'default_discount' => $defaultDiscount,
                    'discounts'        => $discounts,

                    'country_visa_list' => $q_c->getByVisaDays(),
                )
            );
        }

        $html = $smarty->fetch('frontend/master_tour.tpl.php', 'TOUR_ADS_'. $from_alias .'_'. $country_alias);

        $blocks = array(
            'left' => array(
                'SIGNUP',
                'OFFICES',
                'QUALITY',
                'NEWS',
            ),
            'center' => array(
                'BANNER_EXTRALUXE',
            ),
            'right' => array(
                'BANNER',
                'HELP',
                'STAFF',
                'FEEDBACK',
                'VK',
            ),
        );

        return $this->wrap($html, $blocks);
    }

    /**
     * @return string
     */
    protected function setActiveCity()
    {
        $city_id = $this->getContext()->getCurrentCommand()->getArguments('id');
        if (empty($city_id)) {
            return $this->notFound();
        }

        /** @var DomainObjectQuery_City $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_City');

        /** @var DomainObjectModel_City $city */
        $city = $q->findById($city_id);

        if (!$city || $city->getStatus() != 'ENABLED') {
            $city = $q->findDefault();
        }

        if (isset($_COOKIE['user_are_we_correct'])) {
            unset($_COOKIE['user_are_we_correct']);
        }

        $this->getContext()->setCity($city, true);

        if (!empty($_REQUEST['to'])) {
            $redirect_to = $this->getUrl()->url($_REQUEST['to'], true);
        } else {
            $redirect_to = empty($_SERVER['HTTP_REFERER']) ? $this->getUrl()->main() : $_SERVER['HTTP_REFERER'];
        }


        $this->getUrl()->redirect($redirect_to);
    }

    protected function generateImage()
    {
        if (empty($_REQUEST['text'])) {
            return $this->notFound();
        }

        $text = $_REQUEST['text'];
        $text = iconv('utf-8', 'windows-1251', $text);

	$max_width_str = (int)(empty($_REQUEST['width']) ? 0 : $_REQUEST['width']);

	$char_width  = 8;
	$char_height = 16;

	if (strlen($text) <= 0 || strlen($text) > 100) {
            $text = ' ';
	}

	if ($max_width_str < 10) {
            $max_width_str = 10;
        }

	$str_height = $char_height;
	$str_width  = strlen($text);

	if (strlen($text) * $char_width > $max_width_str) {
            $str_width  = $max_width_str;
            $str_height = ceil(strlen($text) / ($max_width_str / $char_width)) * $char_height;
	} else {
            $str_width = strlen($text) * $char_width;
        }

        header('Content-type: image/png');

        $mf = imageloadfont(ROOT .'/static/fonts/Courier.phpfont');
        $im = imagecreate($str_width, $str_height);

        $background_color = imagecolorallocate($im, 255, 255, 255);
        imagecolortransparent($im, $background_color);
        $text_color       = imagecolorallocate($im, 80,  80,  80);

        if (strlen($text) * $char_width > $max_width_str) {
            for ($q = 0; $q < ($str_height / $char_height); $q++) {
                imagestring($im, $mf, 0, $q * $char_height, substr($text, $max_width_str / $char_width * $q, $max_width_str / $char_width), $text_color);
            }
        } else {
            imagestring($im, $mf, 0, 0, $text, $text_color);
        }

	imagepng($im);
	imagedestroy($im);
    }
}
