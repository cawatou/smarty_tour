<?php

class Utils_RSS {

    private $rss = null;

    private $generator = 'RSS 2.0 generation class';
    private $docs = 'http://blogs.law.harvard.edu/tech/rss';
    private $encoding = 'utf-8';

    private $title = null;
    private $link = null;
    private $description = null;

    private $language = 'ru-RU'; //en-US
    private $copyright = null;
    private $managingEditor = null;
    private $webMaster = null;
    private $ttl = null;
    private $pubDate = null;
    private $lastBuildDate = null;
    private $skipHours = null;
    private $skipDays = null;

    private $category = null;
    private $cloud = null;
    private $image = null;

    private $itemTitle = null;
    private $itemLink = null;
    private $itemDescription = null;
    private $itemAuthor = null;
    private $itemCategory = null;
    private $itemComments = null;
    private $itemEnclosure = null;
    private $itemGuid = null;
    private $itemPubDate = null;
    private $itemSource = null;

    public function channel($title, $link, $description, $additional = array())
    {
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;

        foreach (array('encoding', 'language', 'copyright', 'managingEditor', 'webMaster', 'ttl', 'pubDate', 'lastBuildDate', 'skipHours', 'skipDays') as $param) {
            if (array_key_exists($param, $additional)) {
                $this->$param = $additional[$param];
            }
        }
    }

    public function channelCategory($category, $domain = '')
    {
        $this->category .= $this->s(2) . '<category';
        if(!empty($domain)){ $this->category .= ' domain="' . $domain . '"'; }
        $this->category .= '>' . $category . '</category>' . "\n";
    }

    public function channelCloud($domain, $port, $path, $registerProcedure, $protocol)
    {
        $this->cloud .= $this->s(2) . '<cloud domain="' . $domain . '" port="' . $port . '" registerProcedure="' . $registerProcedure . '" protocol="' . $protocol . '" />';
    }

    public function channelImage($url, $title, $link, $width = '', $height = '', $description = '')
    {
        $this->image = $this->s(2) . '<image>' . "\n";
        $this->image .= $this->s(3) . '<url>' . $url . '</url>' . "\n";
        $this->image .= $this->s(3) . '<title>' . $title . '</title>' . "\n";
        $this->image .= $this->s(3) . '<link>' . $link . '</link>' . "\n";
        if ($width != '') { $this->s(3) . '<width>' . $width . '</width>' . "\n"; }
        if ($height != '') { $this->s(3) . '<height>' . $height . '</height>' . "\n"; }
        if ($description != '') { $this->s(3) . '<description>' . $description . '</description>' . "\n"; }
        $this->image .= $this->s(2) . '</image>' . "\n";
    }

    public function startRSS()
    {
        $this->rss = '<?xml version="1.0"';
        if (!empty($this->encoding)) {
            $this->rss .= ' encoding="' . $this->encoding . '"';
        }
        $this->rss .= '?>' . "\n";
        $this->rss .= '<rss version="2.0">' . "\n";
        $this->rss .= $this->s(1) . '<channel>' . "\n";
        $this->rss .= $this->s(2) . '<title>' . $this->title . '</title>' . "\n";
        $this->rss .= $this->s(2) . '<link>' . $this->link . '</link>' . "\n";
        $this->rss .= $this->s(2) . '<description>' . $this->description . '</description>' . "\n";
        if (!empty($this->language)) {
            $this->rss .= $this->s(2) . '<language>' . $this->language . '</language>' . "\n";
        }
        if (!empty($this->copyright)) {
            $this->rss .= $this->s(2) . '<copyright>' . $this->copyright . '</copyright>' . "\n";
        }
        if (!empty($this->managingEditor)) {
            $this->rss .= $this->s(2) . '<managingEditor>' . $this->managingEditor . '</managingEditor>' . "\n";
        }
        if (!empty($this->webMaster)) {
            $this->rss .= $this->s(2) . '<webMaster>' . $this->webMaster . '</webMaster>' . "\n";
        }
        if (!empty($this->pubDate)) {
            $this->rss .= $this->s(2) . '<pubDate>' . $this->pubDate . '</pubDate>' . "\n";
        }
        if (!empty($this->lastBuildDate)) {
            $this->rss .= $this->s(2) . '<lastBuildDate>' . $this->lastBuildDate . '</lastBuildDate>' . "\n";
        }
        if (!empty($this->category)) {
            $this->rss .= $this->category;
        }
        $this->rss .= $this->s(2) . '<generator>' . $this->generator . '</generator>' . "\n";
        $this->rss .= $this->s(2) . '<docs>' . $this->docs . '</docs>' . "\n";
        if (!empty($this->cloud)){
            $this->rss .= $this->cloud;
        }
        if (!empty($this->ttl)) {
            $this->rss .= $this->s(2) . '<ttl>' . $this->ttl . '</ttl>' . "\n";
        }
        if (!empty($this->image)) {
            $this->rss .= $this->image;
        }
        if (!is_null($this->skipHours)) {
            $this->rss .= $this->s(2) . '<skipHours>' . "\n";
            $tmp = split(',', str_replace(' ', '', $this->skipHours));
            for($i = 0; $i < count($tmp); $i++){
                $this->rss .= $this->s(3) . '<hour>' . $tmp[$i] . '</hour>' . "\n";
            }
            $this->rss .= $this->s(2) . '</skipHours>' . "\n";
        }
        if (!is_null($this->skipDays)) {
            $this->rss .= $this->s(2) . '<skipDays>' . "\n";
            $tmp = split(',', str_replace(' ', '', $this->skipDays));
            for($i = 0; $i < count($tmp); $i++) {
                $this->rss .= $this->s(3) . '<day>' . $tmp[$i] . '</day>' . "\n";
            }
            $this->rss .= $this->s(2) . '</skipDays>' . "\n";
        }
    }

    public function itemTitle($title)
    {
        $this->itemTitle = $title;
    }

    public function itemLink($link) {
        $this->itemLink = $link;
    }

    public function itemDescription($description) {
        $this->itemDescription = $description;
    }

    public function itemAuthor($author) {
        $this->itemAuthor = $author;
    }

    public function itemCategory($category, $domain = '')
    {
        $this->itemCategory .= $this->s(3) . '<category';
        if (!empty($domain)) {
            $this->itemCategory .= ' domain="' . $domain . '"';
        }
        $this->itemCategory .= '>' . $category . '</category>' . "\n";
    }

    public function itemComments($comments)
    {
        $this->itemComments = $comments;
    }

    public function itemEnclosure($enclosure)
    {
        $this->itemEnclosure = $enclosure;
    }

    public function itemGuid($guid, $isPermaLink = '')
    {
        $this->itemGuid = $this->s(3) . '<guid';
        if (!empty($isPermaLink)) {
            $this->itemGuid .= ' isPermaLink="' . $isPermaLink . '"';
        }
        $this->itemGuid .= '>' . $guid . '</guid>' . "\n";
    }

    public function itemPubDate($pubDate)
    {
        $this->itemPubDate = $pubDate;
    }

    public function itemSource($source, $url)
    {
        $this->itemSource = $this->s(3) . '<source url="' . $url . '">' . $source . '</source>' . "\n";
    }

    public function addItem()
    {
        $this->rss .= $this->s(2) . '<item>' . "\n";
        if (!empty($this->itemTitle))
        {
            $this->rss .= $this->s(3) . '<title>' . $this->itemTitle . '</title>' . "\n";
        }
        if (!empty($this->itemLink))
        {
            $this->rss .= $this->s(3) . '<link>' . $this->itemLink . '</link>' . "\n";
        }
        if (!empty($this->itemDescription))
        {
            $this->rss .= $this->s(3) . '<description>' . $this->itemDescription . '</description>' . "\n";
        }
        if (!empty($this->itemAuthor))
        {
            $this->rss .= $this->s(3) . '<author>' . $this->itemAuthor . '</author>' . "\n";
        }
        if (!empty($this->itemCategory))
        {
            $this->rss .= $this->itemCategory;
        }
        if (!empty($this->itemComments))
        {
            $this->rss .= $this->s(3) . '<comments>' . $this->itemComments . '</comments>' . "\n";
        }
        if (!empty($this->itemEnclosure))
        {
            $this->rss .= $this->s(3) . '<enclosure>' . $this->itemEnclosure . '</enclosure>' . "\n";
        }
        if (!empty($this->itemGuid))
        {
            $this->rss .= $this->itemGuid;
        }
        if (!empty($this->itemPubDate))
        {
            $this->rss .= $this->s(3) . '<pubDate>' . $this->itemPubDate . '</pubDate>' . "\n";
        }
        if (!empty($this->itemSource))
        {
            $this->rss .= $this->itemSource;
        }
        $this->rss .= $this->s(2) . '</item>' . "\n";

        $this->itemTitle = null;
        $this->itemLink = null;
        $this->itemDescription = null;
        $this->itemAuthor = null;
        $this->itemCategory = null;
        $this->itemComments = null;
        $this->itemEnclosure = null;
        $this->itemGuid = null;
        $this->itemPubDate = null;
        $this->itemSource = null;
    }

    public function RSSdone()
    {
        $this->rss .= $this->s(1) . '</channel>' . "\n";
        $this->rss .= '</rss>';

        return $this->rss;
    }

    function s($space)
    {
        $s = '';
        for ($i = 0; $i < $space; $i++) {
            $s .= '   ';
        }
        return $s;
    }
}