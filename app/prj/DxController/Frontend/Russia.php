<?php
DxFactory::import('DxController_Frontend');

class DxController_Frontend_Russia extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.russia'         => 'index',
        '.russia.details' => 'details',

        '.russia.ads' => 'adsDetails',
    );

    /**
     * @return string
     */
    protected function index()
    {
        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $smarty->setCacheLifetime(300);

        $display_style = empty($_REQUEST['display_style']) ? 'THUMB' : $_REQUEST['display_style'];

        if (!$smarty->isCached('frontend/master_russia.tpl.php', 'RUSSIA_PAGE_'. $display_style .'_'. $this->getContext()->getCity()->getId())) {
            /** @var DomainObjectQuery_Product $q */
            $q = DxFactory::getSingleton('DomainObjectQuery_Product');

            /** @var DataList_Paginator $dl */
            $dl = DxFactory::getInstance('DataList_Paginator', array($q));
            $dl->setItemsPerPage(1000);

            $arr = array('s' => array());
            $arr['s']['product_status'] = 'ENABLED';
            $arr['s']['tour_type']      = 'RUSSIA';

            $dep_list   = $this->getContext()->getCity()->getDepartureList();
            $min_prices = array();

            $_arr = $arr;

            $_arr['s']['tour_type'] = 'WORLD';

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

            /*foreach ($list as $dep_id => $tours) {
                if (empty($tours['tours'])) {
                    unset($list[$dep_id]);
                }
            }*/

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

        $html = $smarty->fetch('frontend/master_russia.tpl.php', 'RUSSIA_PAGE_'. $display_style .'_'. $this->getContext()->getCity()->getId());

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

        $cache_key = 'RUSTOUR_'. $product_id;

        if (!empty($_SESSION['access_code_available'])) {
            $cache_key .= '_SEC';
        }

        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $smarty->setCacheLifetime(1200);

        /** @var DomainObjectQuery_Product $q_p */
        $q_p = DxFactory::getInstance('DomainObjectQuery_Product');

        $product = $q_p->findById($product_id);

        if (empty($product) || $product->getStatus() != 'ENABLED' || $product->getCountryId() != DomainObjectQuery_Product::COUNTRY_ID_RUSSIA) {
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

        if (!$smarty->isCached('frontend/master_russia_tour.tpl.php', $cache_key)) {
            /** @var DomainObjectQuery_Discount $q_d */
            $q_d = DxFactory::getInstance('DomainObjectQuery_Discount');

            /** @var DomainObjectQuery_Country $q_c */
            $q_c = DxFactory::getInstance('DomainObjectQuery_Country');

            $defaultDiscount = $q_d->findDefault('DISCOUNT');
            $defaultPromo    = $q_d->findDefault('PROMO');
            $discounts = $product->getFittingDiscounts();

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
                    'tour_type'        => 'RUSSIA',
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

        $html = $smarty->fetch('frontend/master_russia_tour.tpl.php', $cache_key);

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
        $from_alias = $this->getContext()->getCurrentCommand()->getArguments('from_alias');

        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $smarty->setCacheLifetime(1200);

        if (!$smarty->isCached('frontend/master_russia_tour.tpl.php', 'RUSTOUR_ADS_'. $from_alias) || !empty($_SESSION['access_code_available'])) {
            /** @var DomainObjectQuery_Product $q_p */
            $q_p = DxFactory::getInstance('DomainObjectQuery_Product');

            /** @var DomainObjectQuery_Discount $q_d */
            $q_d = DxFactory::getInstance('DomainObjectQuery_Discount');

            $product = $q_p->findForRussiaAds($from_alias);

            if (empty($product) || $product->getStatus() != 'ENABLED' || $product->getCountryId() != DomainObjectQuery_Product::COUNTRY_ID_RUSSIA) {
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

        $html = $smarty->fetch('frontend/master_russia_tour.tpl.php', 'RUSTOUR_ADS_'. $from_alias);

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
}