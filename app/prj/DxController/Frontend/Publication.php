<?php

DxFactory::import('DxController_Frontend');

class DxController_Frontend_Publication extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.news'         => 'newsList',
        '.news.details' => 'newsDetails',
    );

    /**
     * @return string
     */
    protected function newsList()
    {
        $html = $this->commonList('NEWS', 'frontend/publication_news_list.tpl.php', '/news,%s');

        return $this->wrap($html);
    }

    /**
     * @return string
     */
    protected function newsRss()
    {
        return $this->commonRss('NEWS');
    }

    /**
     * @return string
     */
    protected function newsDetails()
    {
        $html = $this->commonDetails('NEWS', 'frontend/publication_news_details.tpl.php');

        return $this->wrap($html);
    }

    /**
     * @param $category
     * @param $tpl
     * @param $url
     * @param int $per_page
     * @param array $arr
     * @return string
     */
    protected function commonList($category, $tpl, $url, $per_page = 10, $arr = array())
    {
        /** @var $q  DomainObjectQuery_Publication */
        $q = DxFactory::getSingleton('DomainObjectQuery_Publication');

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));
        $dl->setItemsPerPage($per_page);

        if (empty($arr)) {
            $arr['s'] = array(
                'is_active_date'       => true,
                'publication_status'   => 'ENABLED',
                'publication_category' => $category,
            );
        }

        $url = $this->getUrl()->url($url);

        $dl->setPaginatorPageUrl($url);
        $dl->setParameters($arr);

        $page_number = $this->getContext()->getCurrentCommand()->getArguments('page');
        $dl->setCurrentPageNumber((int)$page_number < 1 ? 1 : (int)$page_number);

        $publications =& $dl->getRequestedPage();
        $state        =  $dl->getState();

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'list'  => $publications,
            'state' => $state,
        ));

        return $smarty->fetch($tpl);
    }

    /**
     * @param $category
     * @param $tpl
     * @return string
     */
    protected function commonDetails($category, $tpl)
    {
        /** @var $q  DomainObjectQuery_Publication */
        $q = DxFactory::getSingleton('DomainObjectQuery_Publication');

        $pub_id = $this->getContext()->getCurrentCommand()->getArguments('id');
        $pub = $q->findById($pub_id);

        if ($pub === null || !in_array($pub->getCategory(), (array)$category)) {
            DxApp::response($this->notFound());
        }

        if ($pub->getStatus() != 'ENABLED' && (empty($_REQUEST['preview']) || $_REQUEST['preview'] != md5(date('Y.m.d')))) {
            DxApp::response($this->notFound());
        }

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'pub'     => $pub,
        ));

        return $smarty->fetch($tpl);
    }

    /**
     * @param $category
     * @return null|string
     */
    protected function commonRss($category)
    {
        /** var $q DomainObjectQuery_Publication */
        $q = DxFactory::getInstance('DomainObjectQuery_Publication');
        $list = $q->findLatest(30, $category);

        if (empty($list)) {
            return $this->notFound();
        }

        /** @var $rss Utils_RSS */
        $rss = DxFactory::getInstance('Utils_RSS');
        $rss->channel(
            'Мой горящий тур',
            $this->getUrl()->main(),
            $list[0]->getCategoryName()
        );

        $rss->startRSS();

        foreach ($list as $pub) {
            $rss->itemTitle(strip_tags($pub->getTitle()));
            $rss->itemLink($pub->getUrl());
            $rss->itemDescription("<![CDATA[{$pub->getBrief()}]]>");
            $rss->itemPubDate($pub->getDate()->setDefaultTimeZone()->format('D, d M Y H:i:s +0600'));
            $rss->addItem();
        }

        $this->getContext()->setContentType('text/xml');

        return $rss->RSSdone();
    }
}