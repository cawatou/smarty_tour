<?php

DxFactory::import('Utils');

/**
 * Based on RiSearch PHP
 * Homepage: http://risearch.org/
 */
class Utils_Spider
{
    const ERROR_BASE      = 2000;
    const ERROR_MAIL_SEND = 2001;

    protected $to_visit = array();
    protected $visited  = array();
    protected $index_count = 0;
    protected $index_size  = 0;
    /**
     * @var array
     * Starting URL (used by spider)
     */
    protected $start_url = array();

    /**
     * @var array
     * Spider will index only files from these servers
     */
    protected $allow_url = array();

    /**
     * @var string
     * List of directories, which should not be indexed
     */
    protected $no_index_dir = 'static';

    /**
     * @var string
     * List of files, which should not be indexed
     */
    protected $no_index_files = 'robots.txt';

    /**
     * @var array
     * Parts of documents, which should not be indexed
     */
    protected $no_index_tags = array();

    /**
     * @var bool
     * Index or not numbers
     */
    protected $no_digits = true;

    /**
     * @var bool
     * Translate escape chars (like &Egrave; or &#255;) ("YES" or "NO")
     */
    protected $use_esc = true;


    /**
     * @return Utils_Spider;
     */
    public function __construct()
    {
        $config = DxApp::config('search');
        $fields = array('start_url', 'allow_url', 'allow_url', 'no_index_dir', 'no_index_tags', 'use_esc');
        foreach ($fields as $field) {
            if (isset($config['spider'][$field])) {
                $this->$field = $config['spider'][$field];
            }
        }
    }

    /**
     * @return void;
     */
    public function spidering()
    {
        $time1 = $this->getmicrotime();
        print "START<br />\n";

        /** @var $index DomainObjectQuery_Index */
        $q = DxFactory::getInstance('DomainObjectQuery_Index');
        $q->truncateTable();
        print "Clear index<br />\n";

        foreach ($this->start_url as $v) {
            $this->to_visit[$v] = 1;
        }

        do {
            if (count($this->to_visit) == 0) {
                break;
            } else {
                list ($url,) = each($this->to_visit);
            }

            $fp = fopen($url, 'r');
            $this->visited[$url] = 1;
            if ($fp === false) {
                print "Error in opening file: $url<br />\n";
            } else {
                $text = '';
                while (!feof($fp)) {
                    $text .= fgets($fp, 4096);
                }

                print "URL: $url - " . strlen($text) . " bytes <br />\n";

                $base = $url;
                if (preg_match_all("/<base\\s+href=([\"']?)([^\\s\"'>]+)\\1/is", $text, $matches, PREG_SET_ORDER)) {
                    $base = $matches[0][2];
                }

                $links = $this->getLinks($text);
                foreach ($links as $k => $v) {
                    $new_link = $this->getAbsoluteUrl($base, $k);
                    $new_link = preg_replace("/#.*/", "", $new_link);
                    $new_link = rtrim($new_link, '/');
                    if ($this->checkUrl($new_link)) {
                        if (!array_key_exists($new_link, $this->visited)) {
                            $this->to_visit[$new_link] = 1;
                        }
                    }
                }

                $this->indexing($text, $url);
            }
            unset($this->to_visit[$url]);
        } while (1);

        $time2 = $this->getmicrotime();
        $time = $time2 - $time1;
        print "FINISH - Scan took {$time} sec<br />\n";
        print "{$this->index_count} files are indexed\n";
        DxApp::terminate();
    }

    /**
     * @param $html
     * @param $url
     * @return bool
     */
    protected function indexing($html, $url)
    {
        $this->index_count++;

        $size = strlen($html);
        $this->index_size += intval($size/1024);
        print "{$this->index_count} -> $url; totalsize -> {$this->index_size} Kb<br />\n";

        // Delete parts of document, which should not be indexed
        if (!empty($this->no_index_tags)) {
            $html = preg_replace("/{$this->no_index_tags['BEGIN']}.*?{$this->no_index_tags['END']}/s", " ", $html);
        }

        $title = '';
        if (preg_match("/<title>\s*(.*?)\s*<\/title>/is", $html, $matches)) {
            $title = $matches[1];
        }
        $title = preg_replace("/\s+/", " ", $title);
        $meta = $this->getMetaTags($html);
        $keywords    = $meta['keywords'];
        $description = $meta['description'];

        $html = preg_replace("/<title>\s*(.*?)\s*<\/title>/is"," ",$html);
        $html = preg_replace("/<!--.*?-->/s"," ",$html);
        $html = preg_replace("/<[Ss][Cc][Rr][Ii][Pp][Tt].*?<\/[Ss][Cc][Rr][Ii][Pp][Tt]>/s"," ",$html);
        $html = preg_replace("/<[Ss][Tt][Yy][Ll][Ee].*?<\/[Ss][Tt][Yy][Ll][Ee]>/s"," ",$html);
        $html = preg_replace("/<[^>]*>/s"," ",$html);
        if ($this->use_esc) {
            $html = preg_replace_callback("/&[a-zA-Z0-9#]*?;/", array(&$this, 'esc2char'), $html);
        }

        $html = preg_replace("/[^a-zA-Zа-яА-Я0-9 -]/usi", ' ', $html);
        //$html = preg_replace('/[^\w\d]/usi', ' ', $html);
        if ($this->no_digits) {
            $html = preg_replace('/[\d]/usi', ' ', $html);
        }
        $html = preg_replace("/\s+/s", ' ',$html);
        $html = trim(mb_strtolower($html));

        /** @var $index DomainObjectController_Index */
        $index = DxFactory::getInstance('DomainObjectController_Index');
        $index->setUrl($url);
        $index->setTitle($title);
        $index->setKeywords($keywords);
        $index->setDescription($description);
        $index->setHtml($html);
        DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER)->flush();

        return true;
    }

    /**
     * @param $html
     * @return array
     */
    protected function getMetaTags($html)
    {
        preg_match("/<\s*[Mm][Ee][Tt][Aa]\s*[Nn][Aa][Mm][Ee]=\"?[Kk][Ee][Yy][Ww][Oo][Rr][Dd][Ss]\"?\s*[Cc][Oo][Nn][Tt][Ee][Nn][Tt]=\"?([^\"]*)\"?\s*\/?>/s", $html, $matches);
        $res['keywords'] = @$matches[1];
        preg_match("/<\s*[Mm][Ee][Tt][Aa]\s*[Nn][Aa][Mm][Ee]=\"?[Dd][Ee][Ss][Cc][Rr][Ii][Pp][Tt][Ii][Oo][Nn]\"?\s*[Cc][Oo][Nn][Tt][Ee][Nn][Tt]=\"?([^\"]*)\"?\s*\/?>/s", $html, $matches);
        $res['description'] = @$matches[1];
        return $res;
    }

    protected function esc2char($str)
    {
        $html_esc = array(
            "&Agrave;" => chr(192),
            "&Aacute;" => chr(193),
            "&Acirc;" => chr(194),
            "&Atilde;" => chr(195),
            "&Auml;" => chr(196),
            "&Aring;" => chr(197),
            "&AElig;" => chr(198),
            "&Ccedil;" => chr(199),
            "&Egrave;" => chr(200),
            "&Eacute;" => chr(201),
            "&Eirc;" => chr(202),
            "&Euml;" => chr(203),
            "&Igrave;" => chr(204),
            "&Iacute;" => chr(205),
            "&Icirc;" => chr(206),
            "&Iuml;" => chr(207),
            "&ETH;" => chr(208),
            "&Ntilde;" => chr(209),
            "&Ograve;" => chr(210),
            "&Oacute;" => chr(211),
            "&Ocirc;" => chr(212),
            "&Otilde;" => chr(213),
            "&Ouml;" => chr(214),
            "&times;" => chr(215),
            "&Oslash;" => chr(216),
            "&Ugrave;" => chr(217),
            "&Uacute;" => chr(218),
            "&Ucirc;" => chr(219),
            "&Uuml;" => chr(220),
            "&Yacute;" => chr(221),
            "&THORN;" => chr(222),
            "&szlig;" => chr(223),
            "&agrave;" => chr(224),
            "&aacute;" => chr(225),
            "&acirc;" => chr(226),
            "&atilde;" => chr(227),
            "&auml;" => chr(228),
            "&aring;" => chr(229),
            "&aelig;" => chr(230),
            "&ccedil;" => chr(231),
            "&egrave;" => chr(232),
            "&eacute;" => chr(233),
            "&ecirc;" => chr(234),
            "&euml;" => chr(235),
            "&igrave;" => chr(236),
            "&iacute;" => chr(237),
            "&icirc;" => chr(238),
            "&iuml;" => chr(239),
            "&eth;" => chr(240),
            "&ntilde;" => chr(241),
            "&ograve;" => chr(242),
            "&oacute;" => chr(243),
            "&ocirc;" => chr(244),
            "&otilde;" => chr(245),
            "&ouml;" => chr(246),
            "&divide;" => chr(247),
            "&oslash;" => chr(248),
            "&ugrave;" => chr(249),
            "&uacute;" => chr(250),
            "&ucirc;" => chr(251),
            "&uuml;" => chr(252),
            "&yacute;" => chr(253),
            "&thorn;" => chr(254),
            "&yuml;" => chr(255),
            "&nbsp;" => " ",
            "&amp;" => " ",
            "&quote;" => " ",
        );

        $esc = $str[0];
        $char = "";

        if (preg_match("/&[a-zA-Z]*;/", $esc)) {
            if (isset ($html_esc[$esc])) {
                $char = $html_esc[$esc];
            } else {
                $char = " ";
            }
        } elseif (preg_match ("/&#([0-9]*);/", $esc, $matches)) {
            $char = chr($matches[1]);
        } elseif (preg_match ("/&#x([0-9a-fA-F]*);/", $esc, $matches)) {
            $char = chr(hexdec($matches[1]));
        }
        return $char;
    }

    /**
     * @param $text
     * @return array
     */
    protected function getLinks($text)
    {
        $links = array();
        preg_match_all("/<a[^>]+href=([\"']?)([^\\s\"'>]+)\\1/is", $text, $matches, PREG_SET_ORDER);
        for($i = 0; $i < count($matches); $i++) {
            $links[$matches[$i][2]] = 1;
        }

        preg_match_all("/<frame[^>]+src=([\"']?)([^\\s\"'>]+)\\1/is", $text, $matches, PREG_SET_ORDER);
        for($i = 0; $i < count($matches); $i++) {
            $links[$matches[$i][2]] = 1;
        }

        preg_match_all("/<area[^>]+href=([\"']?)([^\\s\"'>]+)\\1/is", $text, $matches, PREG_SET_ORDER);
        for($i = 0; $i < count($matches); $i++) {
            $links[$matches[$i][2]] = 1;
        }

        return $links;
    }

    /**
     * @param $base
     * @param $url
     * @return mixed|string
     */
    protected function getAbsoluteUrl($base, $url) {

        $url_arr = parse_url($url);
        if (isset($url_arr["scheme"])) {
            return($url);
        }
        $base_arr = parse_url($base);
        $base_base = strtolower($base_arr["scheme"])."://";
        if (isset($base_arr["user"])) {
            $base_base .= $base_arr["user"].":".$base_arr["pass"]."@";
        }
        $base_base .= strtolower($base_arr["host"]);
        if (isset($base_arr["port"])) {
            $base_base .= ":".$base_arr["port"];
        }
        $base_path = @$base_arr["path"];
        if ($base_path == "") { $base_path = "/"; }
        $base_path = preg_replace("/(.*\/).*/","\\1",$base_path);

        if (@$url_arr["path"][0] == "/") {
            return $base_base.$url;
        }

        if (preg_match("'^\./'",$url)) {
            $url = preg_replace("'^\./'","",$url);
            return $base_base.$base_path.$url;
        }

        while (preg_match("'^\.\./'",$url)) {
            $url = preg_replace("'^\.\./'","",$url);
            $base_path = preg_replace("/(.*\/).*\//","\\1",$base_path);
        }
        return $base_base . $base_path . $url;
    }

    /**
     * @param $url
     * @return bool
     */
    protected function checkUrl($url)
    {
        if (!preg_match("'^http://'",$url)) {
            return false;
        }

        if ( preg_match ("'{$this->no_index_files}'i", $url)) {
            return false;
        }

        if ( preg_match ("'{$this->no_index_dir}'i", $url)) {
            return false;
        }

        $allow = false;
        foreach ($this->allow_url as $v) {
            if (preg_match("'{$v}'i", $url)) {
                $allow = true;
                break;
            }
        }

        return $allow;
    }

    /**
     * @return float
     */
    protected function getmicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}