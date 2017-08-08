<?php
DxFactory::import('DxController');
DxFactory::import('DxConstant_Project');

class DxController_Frontend extends DxController
{
    /** @var array */
    protected $cmd_method = array(
        DxCommand::CMD_NOT_FOUND => 'notFound',
    );

    protected $list_offices = array();
    protected $list_blocks  = array();

    /**
     * @param DxCommand $command
     * @return array
     */
    protected function getCommandMethod(DxCommand $command)
    {
        return array_key_exists($command->getCmd(), $this->cmd_method) ? $this->cmd_method[$command->getCmd()] : null;
    }

    /**
     * @return DxURL_Default_Project
     */
    protected function getUrl()
    {
        return DxApp::getComponent(DxApp::ALIAS_URL);
    }

    /**
     * @return Smarty
     */
    protected function getSmarty()
    {
        return DxApp::getComponent(DxConstant_Project::ALIAS_SMARTY, true);
    }

    /**
     * @return DxAppContext_Project
     */
    public function getContext()
    {
        return $this->ctx;
    }

    /**
     * @return DomainObjectManager
     */
    protected function getDomainObjectManager()
    {
        return DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER);
    }

    /**
     * @return string
     */
    protected function notFound()
    {
        $ctx  = $this->getContext();
        $args = $ctx->getCurrentCommand()->getArguments();

        if (empty($args['request'])) {
            return $this->forceNotFound();
        }

        $map_redirect = array(
            '/gorjashie_tury_iz_tomska'           => '/tours/tomsk/',
            '/gorjashie_tury_iz_abakana'          => '/tours/abakan/',
            '/gorjashie_tury_iz_berdska'          => '/tours/berdsk/',
            '/gorjashie_tury_iz_ekaterinburga'    => '/tours/ekaterinburg/',
            '/gorjashie_tury_iz_kemerovo'         => '/tours/kemerovo/',
            '/gorjashie_tury_iz_krasnojarska'     => '/tours/krasnojarsk/',
            '/gorjashie_tury_iz_leninsk-kuznecka' => '/tours/leninsk-kuzneckii/',
            '/gorjashie_tury_iz_moskvy'           => '/tours/moskva/',
            '/gorjashie_tury_iz_nizhnevartovska'  => '/tours/nizhnevartovsk/',
            '/gorjashie_tury_iz_novokuznecka'     => '/tours/novokuzneck/',
            '/gorjashie_tury_iz_novosibirska'     => '/tours/novosibirsk/',
            '/gorjashie_tury_iz_omska'            => '/tours/omsk/',
            '/gorjashie_tury_iz_prokopevska'      => '/tours/prokopevsk/',
            '/gorjashie_tury_iz_severska'         => '/tours/seversk/',
            '/gorjashie_tury_iz_strezhevogo'      => '/tours/strezhevoj/',
            '/gorjashie_tury_iz_surguta'          => '/tours/surgut/',

            '/online_pokupka'             => '/order/',
            '/poisk_pary'                 => '/companion/',
            '/pokupka_zhd_biletov_online' => '/search/train/',
            '/vopros-otvet'               => '/faq/',
            '/poisk_tura_online'          => '/search/',
            '/zakaz_tura_online'          => '/request/',
            '/hotels'                     => '/search/hotel/',
        );

        $request_uri = rtrim($args['request'], '/');

        if (array_key_exists($request_uri, $map_redirect)) {
            $this->getUrl()->redirect($map_redirect[$request_uri]);
        }

        /** @var DomainObjectQuery_Page $query */
        $query = DxFactory::getInstance('DomainObjectQuery_Page');
        $page  = $query->findByPath($args['request']);

        if ($page !== null && $page->getStatus() == 'ENABLED') {
            $content_cmd = $page->getCmd();

            if (($data = DxApp::config(DxCommand::CFG_COMMANDS, $content_cmd)) !== null) {
                /** @var $command DxCommand */
                $command = new DxCommand($content_cmd, $args);

                /** @var $hook DxCommandHook|null */
                $hook = DxApp::existComponent(DxApp::ALIAS_COMMAND_HOOK) ? DxApp::getComponent(DxApp::ALIAS_COMMAND_HOOK) : null;

                $ctx->setCurrentCommand($command);
                $c = DxFactory::getSingleton($command->getControllerClass(), array($ctx, $hook));

                return $c->setPage($page)->execute();
            }
        }

        return $this->forceNotFound();
    }

    /**
     * @return string
     */
    protected function forceNotFound()
    {
        $ctx  = $this->getContext();

        $ctx->addHeader('Status: 404 Not Found');
        $html = $this->getSmarty()->fetch('frontend/master_notfound.tpl.php');

        return $this->wrap($html);
    }

    /**
     * @param string $html
     * @param array  $blocks
     * @param array  $data
     * @return string
     */
    protected function wrap($html, $blocks = array(), $data = array())
    {
        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        if (!empty($_REQUEST['__submit_seccode'])) {
            if (!empty($_REQUEST['secure_code']) && empty($_SESSION['access_code_available'])) {
                /** @var DomainObjectQuery_Settings $q_settings */
                $q_settings = DxFactory::getSingleton('DomainObjectQuery_Settings');
                $access_code = $q_settings->getByKey('ACCESS_CODE');

                if ($access_code == $_REQUEST['secure_code']) {
                    $_SESSION['access_code_available'] = true;
                }
            }

            $smarty->clearCache('frontend/master_tour.tpl.php');

            $this->getUrl()->redirect('http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }

        if (!empty($_REQUEST['__submit_seccode_exit'])) {
            if (!empty($_SESSION['access_code_available'])) {
                unset($_SESSION['access_code_available']);
            }

            $smarty->clearCache('frontend/master_tour.tpl.php');

            $this->getUrl()->redirect('http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }

        $side_left_html   = '';
        $side_center_html = '';
        $side_right_html  = '';

        if (empty($blocks)) {
            $blocks = array(
                'left' => array(
                    'SIGNUP',
                    'OFFICES',
                    'QUALITY',
                    'NEWS',
                ),
                'center' => array(
                    'HOTELSEARCH',
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
        }

        $this->seoJunk();

        foreach ($blocks['left'] as $block) {
            switch (strtoupper($block)) {
                case 'SIGNUP':
                        $side_left_html .= $this->getSideSignupHtml();
                    break;
                case 'OFFICES':
                        $side_left_html .= $this->getSideOfficeHtml();
                    break;
                case 'QUALITY':
                        $side_left_html .= $this->getSideQualityHtml();
                    break;
                case 'NEWS':
                        $side_left_html .= $this->getSideNewsHtml();
                    break;
                default:
                    break;
            }
        }

        foreach ($blocks['center'] as $block) {
            switch (strtoupper($block)) {
                case 'HOTELSEARCH':
                        $side_center_html .= $this->getSideHotelSearchHtml();
                    break;
                case 'BANNER_EXTRALUXE':
                        $side_center_html .= $this->getSideBannerExtraluxeHtml();
                    break;
                default:
                    break;
            }
        }

        foreach ($blocks['right'] as $block) {
            switch (strtoupper($block)) {
                case 'BANNER':
                        $side_right_html .= $this->getSideBannerHtml();
                    break;
                case 'STAFF':
                        $side_right_html .= $this->getSideStaffHtml();
                    break;
                case 'FEEDBACK':
                        $side_right_html .= $this->getSideFeedbackHtml();
                    break;
                case 'HELP':
                        $side_right_html .= $this->getSideHelpHtml();
                    break;
                case 'VK':
                        $side_right_html .= $this->getSideVkHtml();
                    break;
                default:
                    break;
            }
        }

        /** @var DomainObjectQuery_City $q_c */
        $q_c = DxFactory::getSingleton('DomainObjectQuery_City');

        $current_city    = $this->getContext()->getCity();
        $offices         = $this->getCurrentCityOffices();
        $offices_current = empty($offices[$current_city->getId()]) ? array() : $offices[$current_city->getId()];

        /** @var DomainObjectQuery_Office $q_o */
        $q_o = DxFactory::getSingleton('DomainObjectQuery_Office');

        $offices_staffs = array();

        foreach ($q_o->findAll(true) as $office) {
            $_staffs = $office->getStaffs();

            foreach ($_staffs as $_staff) {
                $offices_staffs[$office->getCity()->getId()][$office->getTitle()][$_staff->getId()] = $_staff->getName();
            }
        }

        $cities = $q_c->findAll(true);

        $cities_alpha = array();

        foreach ($cities as $city) {
            $city_title = $city->getTitle();

            $city_title_letter = mb_strtoupper(mb_substr($city_title, 0, 1));

            if (empty($cities_alpha[$city_title_letter])) {
                $cities_alpha[$city_title_letter] = array();
            }

            $cities_alpha[$city_title_letter][] = $city;
        }

        $cities_alpha = array_chunk($cities_alpha, floor(count($cities_alpha) / 3), true);

        $smarty->assign(
            array(
                'body_html'        => $html,
                'side_left_html'   => $side_left_html,
                'side_center_html' => $side_center_html,
                'side_right_html'  => $side_right_html,

                'data' => $data,

                'offices'         => $offices,
                'offices_current' => $offices_current,
                'offices_staffs'  => json_encode($offices_staffs),

                'cities'      => $cities,
                'city_alphas' => $cities_alpha,
                'user_city'   => $current_city,

                'blocks' => $this->getBlocks(),

                'is_logged' => !empty($_SESSION['access_code_available']),
            )
        );

        // Configuration data for signup
        if (!empty($blocks['left']['SIGNUP'])) {
            $smarty->assign('signup_cfg', DxApp::config('signup'));
        }

        $tpl = 'wrapper';

        if (
            $this->getContext()->getCurrentCommand()->getCmd() == '.russia'
                ||
            $this->getContext()->getCurrentCommand()->getCmd() == '.russia.details'
                ||
            $this->getContext()->getCurrentCommand()->getCmd() == '.russia.ads'
        ) {
            $tpl = 'wrapper_yellow';
        }

        return $smarty->fetch('frontend/'. $tpl .'.tpl.php');
    }

    protected function seoJunk()
    {
        $ctx  = $this->getContext();

        /** @var DomainObjectQuery_Settings $q_settings */
        $q_settings = DxFactory::getSingleton('DomainObjectQuery_Settings');
        $config = $q_settings->getByGroup('SEO');

        if (null !== $ctx->getPageTitle()) {
            $ctx->setPageTitle($ctx->getPageTitle() . $config['SEO_POSTFIX']);
        }

        if (null === $ctx->getPageKeywords() && !empty($config['SEO_KEYWORDS'])) {
            $ctx->setPageKeywords($config['SEO_KEYWORDS']);
        }

        if (null === $ctx->getPageDescription() && !empty($config['SEO_DESCRIPTION'])) {
            $ctx->setPageDescription($config['SEO_DESCRIPTION']);
        }

        $request = $ctx->getCurrentCommand()->getArguments('request', '/');

        /** @var DomainObjectQuery_Seo $q_seo */
        $q_seo = DxFactory::getSingleton('DomainObjectQuery_Seo');

        /** @var DomainObjectModel_Seo $seo */
        $seo = $q_seo->findByRequest($request);

        if (null !== $seo && $seo->getStatus() == 'ENABLED') {
            if (null !== $seo->getTitle()) {
                $ctx->setPageTitle($seo->getTitle());
            }

            if (null !== $seo->getKeywords()) {
                $ctx->setPageKeywords($seo->getKeywords());
            }

            if (null !== $seo->getDescription()) {
                $ctx->setPageDescription($seo->getDescription());
            }
        }

        if (null !== $ctx->getPageLastModified() && is_a($ctx->getPageLastModified(), 'DxDateTime')) {
            $ctx->addHeader('Last-Modified: ' . $ctx->getPageLastModified()->format("D, d M Y") . ' 12:00:01 GMT');
        } else {
            $lm = DxFactory::getInstance('DxDateTime');

            if ($lm->format('G') < 12) {
                $lm->modify('-1 day');
            }

            $ctx->addHeader('Last-Modified: ' . $lm->format("D, d M Y") . ' 12:00:01 GMT');
        }

        if (null !== $ctx->getPageExpires() && is_a($ctx->getPageExpires(), 'DxDateTime')) {
            $ctx->addHeader('Expires: ' . $ctx->getPageExpires()->format("D, d M Y") . ' 12:00:01 GMT');
        } else {
            $exp = DxFactory::getInstance('DxDateTime');

            if ($exp->format('G') > 12) {
                $exp->modify('+1 day');
            }

            $ctx->addHeader('Expires: ' . $exp->format("D, d M Y") . ' 12:00:01 GMT');
        }
    }

    /**
     * @return string
     */
    protected function getSideSignupHtml()
    {
        return $this->getSmarty()->fetch('frontend/include/sidebars/side_left_signup.tpl.php');
    }

    /**
     * @return string
     */
    protected function getSideOfficeHtml()
    {
        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $smarty->setCacheLifetime(3600);

        if (!$smarty->isCached('frontend/include/sidebars/side_left_office.tpl.php', 'SIDEBAR_OFFICE_'. $this->getContext()->getCity()->getId())) {
            $smarty->assign(
                array(
                    'offices_list' => $this->getCurrentCityOffices(),
                )
            );
        }

        return $smarty->fetch('frontend/include/sidebars/side_left_office.tpl.php', 'SIDEBAR_OFFICE_'. $this->getContext()->getCity()->getId());
    }

    /**
     * @return string
     */
    protected function getSideQualityHtml()
    {
        return $this->getSmarty()->fetch('frontend/include/sidebars/side_left_quality.tpl.php');
    }

    protected function getSideNewsHtml()
    {
        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $smarty->setCacheLifetime(3600);

        if (!$smarty->isCached('frontend/include/sidebars/side_left_news.tpl.php', 'SIDEBAR_NEWS_'. $this->getContext()->getCity()->getId())) {
            /** @var $q  DomainObjectQuery_Publication */
            $q = DxFactory::getSingleton('DomainObjectQuery_Publication');

            /** @var $dl DataList_Paginator */
            $dl = DxFactory::getInstance('DataList_Paginator', array($q));
            $dl->setItemsPerPage(5);

            $arr = array(
                's' => array(
                    'is_active_date'       => true,
                    'publication_status'   => 'ENABLED',
                    'publication_category' => 'NEWS',
                ),
            );

            $dl->setParameters($arr);
            $dl->setCurrentPageNumber(1);

            $publications =& $dl->getRequestedPage();

            $smarty->assign(
                array(
                    'news' => $publications,
                )
            );
        }

        return $smarty->fetch('frontend/include/sidebars/side_left_news.tpl.php', 'SIDEBAR_NEWS_'. $this->getContext()->getCity()->getId());
    }

    /**
     * @return string
     */
    protected function getSideBannerHtml()
    {
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'blocks' => $this->getBlocks(),
            )
        );

        return $smarty->fetch('frontend/include/sidebars/side_right_banner.tpl.php');
    }

    /**
     * @return string
     */
    protected function getSideHelpHtml()
    {
        return $this->getSmarty()->fetch('frontend/include/sidebars/side_right_help.tpl.php');
    }

    /**
     * @return string
     */
    protected function getSideStaffHtml()
    {
        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $smarty->setCacheLifetime(3600);

        if (!$smarty->isCached('frontend/include/sidebars/side_right_staff.tpl.php', 'SIDEBAR_STAFF_'. $this->getContext()->getCity()->getId())) {
            $is_staff_exists = false;

            foreach ($this->getCurrentCityOffices() as $offices) {
                foreach ($offices as $office) {
                    if (count($office->getStaffs()) > 0) {
                        $is_staff_exists = true;

                        break;
                    }
                }
            }

            $smarty->assign(
                array(
                    'offices'      => $this->getCurrentCityOffices(),
                    'is_not_empty' => $is_staff_exists,
                )
            );
        }

        return $smarty->fetch('frontend/include/sidebars/side_right_staff.tpl.php', 'SIDEBAR_STAFF_'. $this->getContext()->getCity()->getId());
    }

    /**
     * @return string
     */
    protected function getSideFeedbackHtml()
    {
        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $smarty->setCacheLifetime(3600);

        if (!$smarty->isCached('frontend/include/sidebars/side_right_feedback.tpl.php', 'SIDEBAR_FEEDBACK_'. $this->getContext()->getCity()->getId())) {
            /** @var $q DomainObjectQuery_Feedback */
            $q = DxFactory::getSingleton('DomainObjectQuery_Feedback');

            $smarty->assign(
                array(
                    'feedbacks' => $q->findLatest(5, 'PROPOSE'),
                )
            );
        }

        return $smarty->fetch('frontend/include/sidebars/side_right_feedback.tpl.php', 'SIDEBAR_FEEDBACK_'. $this->getContext()->getCity()->getId());
    }

    /**
     * @return string
     */
    protected function getSideVkHtml()
    {
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'blocks' => $this->getBlocks(),
            )
        );

        return $smarty->fetch('frontend/include/sidebars/side_right_vk.tpl.php');
    }

    public function getSideHotelSearchHtml()
    {
        return $this->getSmarty()->fetch('frontend/include/sidebars/side_center_hotelsearch.tpl.php');
    }

    public function getSideBannerExtraluxeHtml()
    {
        return $this->getSmarty()->fetch('frontend/include/sidebars/side_center_banner_extraluxe.tpl.php');
    }

    /**
     * @return array
     *
     * @protected
     */
    protected function getCurrentCityOffices()
    {
        if (!empty($this->list_offices)) {
            return $this->list_offices;
        }

        $this->list_offices = $this->getCityOffices($this->getContext()->getCity()->getId());

        return $this->list_offices;
    }

    /**
     * @param $city_id
     * @return array
     */
    public function getCityOffices($city_id)
    {
        /** @var $q DomainObjectQuery_Office */
        $q = DxFactory::getSingleton('DomainObjectQuery_Office');

        /** @var $q_city DomainObjectQuery_City */
        $q_city = DxFactory::getSingleton('DomainObjectQuery_City');

        /** @var $city DomainObjectModel_City */
        $city = $q_city->findById($city_id);

        if (empty($city) || $city->getStatus() != 'ENABLED') {
            return array();
        }

        $related_offices = array();

        $_offices_local = $q->findByCityId($city_id);

        foreach ($_offices_local as $office) {
            $related_offices[$office->getCityId()][] = $office;
        }

        $related_city_ids = $city->getCityIds();
        if (!empty($related_city_ids)) {
            $_related_offices = $q->findByCityIds(array_keys($related_city_ids));

            foreach ($related_city_ids as $_city_id => $qnt) {
                $related_offices[$_city_id] = array();
            }

            foreach ($_related_offices as $office) {
                if ($office->getStatus() != 'ENABLED') {
                    continue;
                }

                $related_offices[$office->getCityId()][] = $office;
            }
        }

        foreach ($related_offices as $city_id => $offices) {
            if (empty($offices)) {
                unset($related_offices[$city_id]);
            }
        }

        return $related_offices;
    }

    protected function getBlocks()
    {
        if (!empty($this->list_blocks)) {
            return $this->list_blocks;
        }

        /** @var $q_b DomainObjectQuery_Block */
        $q_b = DxFactory::getSingleton('DomainObjectQuery_Block');

        $this->list_blocks = $q_b->getAllGrouped(true);

        return $this->list_blocks;
    }
}