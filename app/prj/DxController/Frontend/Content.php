<?php

DxFactory::import('DxController_Frontend');

class DxController_Frontend_Content extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.content'         => 'expanded',
        '.content.gallery' => 'expanded',
        '.howtobuy'        => 'howtobuy',

        '.turistam' => 'tourists',
        '.turistam.oformlenie_zagranpasporta' => 'expandedCitySelector',

        '.content.special.weather' => 'expandedWeather',
    );

    /** @var null DomainObjectModel_Page */
    protected $page = null;

    /**
     * @param DomainObjectModel_Page $page
     * @return DxController_Frontend_Content
     */
    public function setPage(DomainObjectModel_Page $page)
    {
        $this->page = $page;
        return $this;
    }

    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return string
     */
    protected function usual()
    {
        $cmd = $this->getContext()->getCurrentCommand()->getArguments('cmd');

        if (empty($cmd)) {
            return $this->notFound();
        }

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $html = $smarty->fetch('frontend/content' . str_replace('.', '_', $cmd) . '.tpl.php');

        return $this->wrap($html);
    }

    /**
     * @return string
     */
    protected function expanded()
    {
        if (null === $this->getPage()) {
            DxApp::terminate();
        }

        /** @var $ctx DxAppContext_Project */
        $ctx = $this->getContext();

        $ctx->setPageTitle($this->getPage()->getTitle());
        $ctx->setPageKeywords($this->getPage()->getKeywords());
        $ctx->setPageDescription($this->getPage()->getDescription());
        $ctx->setPageId($this->getPage()->getId());

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $assign = array(
            'page' => $this->getPage(),
         );

        $page_html = $this->getPage()->getContent();

        if ($ctx->getCurrentCommand()->getCmd() === '.content.gallery') {
            preg_match_all("~\[gallery\](.*)\[/gallery\]~U", $page_html, $matches, PREG_SET_ORDER);

            foreach ($matches as $i) {
                $page_html = str_replace($i[0], $this->getGalleryHtml($i[1]), $page_html);
            }
        }

        $assign['page_html'] = $page_html;

        $smarty->assign($assign);

        $html = $smarty->fetch('frontend/content.tpl.php');

        return $this->wrap($html);
    }

    protected function tourists()
    {
        /** @var $q DomainObjectQuery_Page */
        $q = DxFactory::getSingleton('DomainObjectQuery_Page');

        $page = $q->findByPath(str_replace('.', '/', $this->getContext()->getCurrentCommand()->getCmd()));

        if (!$page || $page->getStatus() != 'ENABLED') {
            return $this->forceNotFound();
        }

        $this->setPage($page);

        $page = $this->getPage();

        if ($page === null) {
            DxApp::terminate();
        }

        /** @var $ctx DxAppContext_Project */
        $ctx = $this->getContext();

        $ctx->setPageTitle($page->getTitle());
        $ctx->setPageKeywords($page->getKeywords());
        $ctx->setPageDescription($page->getDescription());
        $ctx->setPageId($page->getId());

        $tree = $q->getChildrens($page, 2);

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'pages' => $tree,
            )
        );

        $html = $smarty->fetch('frontend/content_special_tourists.tpl.php');

        return $this->wrap($html);
    }

    /**
     * @return string
     */
    protected function expandedWeather()
    {
        if (null === $this->getPage()) {
            DxApp::terminate();
        }

        /** @var $ctx DxAppContext_Project */
        $ctx = $this->getContext();

        $ctx->setPageTitle($this->getPage()->getTitle());
        $ctx->setPageKeywords($this->getPage()->getKeywords());
        $ctx->setPageDescription($this->getPage()->getDescription());
        $ctx->setPageId($this->getPage()->getId());

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'page' => $this->getPage(),
            )
        );

        $html = $smarty->fetch('frontend/content_special_weather.tpl.php');

        return $this->wrap($html);
    }

    public function getGalleryHtml($gallery_alias)
    {
        /** @var $q DomainObjectQuery_Gallery */
        $q = DxFactory::getInstance('DomainObjectQuery_Gallery');

        $gallery = $q->findByAlias($gallery_alias);

        if (empty($gallery)) {
            return '';
        }

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'gallery' => $gallery,
            )
        );

        return $smarty->fetch('frontend/content_gallery.tpl.php');
    }

    protected function expandedCitySelector()
    {
        /** @var $q DomainObjectQuery_Page */
        $q = DxFactory::getSingleton('DomainObjectQuery_Page');

        $page = $q->findByPath(str_replace('.', '/', $this->getContext()->getCurrentCommand()->getCmd()));

        if (!$page || $page->getStatus() != 'ENABLED') {
            return $this->forceNotFound();
        }

        $this->setPage($page);

        $this->setPage($q->findByPath(str_replace('.', '/', $this->getContext()->getCurrentCommand()->getCmd())));

        $page = $this->getPage();

        if ($page === null) {
            DxApp::terminate();
        }

        /** @var $ctx DxAppContext_Project */
        $ctx = $this->getContext();

        $ctx->setPageTitle($page->getTitle());
        $ctx->setPageKeywords($page->getKeywords());
        $ctx->setPageDescription($page->getDescription());
        $ctx->setPageId($page->getId());

        $tree = $q->getChildrens($page);

        $selected_city = $this->getContext()->getCurrentCommand()->getArguments('city', null);

        if ($selected_city === null) {
            $active_city = $this->getContext()->getCity();

            if (empty($active_city)) {
                return $this->forceNotFound();
            }

            $selected_city = $active_city->getAlias();
        }

        $is_found = false;

        foreach ($tree as $_page) {
            if ($_page->getAlias() != $selected_city) {
                continue;
            }

            $active_page = $_page;

            $is_found = true;

            break;
        }

        if (!$is_found || empty($active_page) || $active_page->getStatus() == 'DISABLED') {
            return $this->forceNotFound();
        }

        $i = $k = 0;

        $page_list = array();

        $delimiter = ceil(count($tree) / 3);

        foreach ($tree as $page) {
            $i++;

            $page_list[$k][] = $page;

            if ($i == $delimiter) {
                $k++;

                $i = 0;

                continue;
            }
        }

        $active_page = $q->findById($active_page->getId());

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'page_list'   => $page_list,
                'parent_page' => $this->getPage(),
                'active_page' => $active_page,
            )
        );

        return $this->wrap($smarty->fetch('frontend/content_city.tpl.php'));
    }

    protected function howtobuy()
    {
        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
            )
        );

        $html = $smarty->fetch('frontend/content_howtobuy.tpl.php');

        return $this->wrap($html);
    }
}