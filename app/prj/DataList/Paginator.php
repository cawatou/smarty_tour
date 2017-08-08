<?php

DxFactory::import('DataList');

class DataList_Paginator extends DataList
{
    /** @var null|string */
    protected $paginator_page_url = null;

    /** @var null|string */
    protected $paginator_page_name = null;

    /** @var int */
    protected $paginator_page_in_margin = 3;

    /** @var int */
    protected $paginator_page_in_block = 5;

    /**
     * @param string $page_name
     */
    public function setPaginatorPageName($page_name)
    {
        $this->paginator_page_name = $page_name;
    }

    /**
     * @return string
     */
    public function getPaginatorPageName()
    {
        return !is_null($this->paginator_page_name) ? $this->paginator_page_name : self::DL_PAGE;
    }

    /**
     * @param string $page_url
     */
    public function setPaginatorPageUrl($page_url)
    {
        $this->paginator_page_url = $page_url;
    }

    /**
     * @param null|int $page_number
     * @return mixed
     */
    public function getPaginatorPageUrl($page_number = null)
    {
        if (is_null($this->paginator_page_url)) {
            return null;
        }
        
        return is_null($page_number) ? $this->paginator_page_url : sprintf($this->paginator_page_url, $page_number);
    }

    /**
     * @param int $page_in_margin
     */
    public function setPaginatorPageInMargin($page_in_margin)
    {
        $this->paginator_page_in_margin = $page_in_margin;
    }

    /**
     * @param int $page_in_block
     */
    public function setPaginatorPageInBlock($page_in_block)
    {
        $this->paginator_page_in_margin = $page_in_block;
    }

    /**
     * @return int|null
     */
    protected function getRequestedPageNumber()
    {
        $page_name = $this->getPaginatorPageName();

        if (isset($_REQUEST[$page_name]) && intval($_REQUEST[$page_name])) {
            return intval($_REQUEST[$page_name]);
        }

        return null;
    }

    /**
     * @return void
     */
    protected function calcState()
    {
        parent::calcState();
        $this->state['next_page_url'] = is_null($this->state['next_page']) ? null : $this->getPaginatorPageUrl($this->state['next_page']);
        $this->state['prev_page_url'] = is_null($this->state['prev_page']) ? null : $this->getPaginatorPageUrl($this->state['prev_page']);
    }

    /**
     * @return array
     */
    protected function generatePagesIndex()
    {
        $state = $this->getState();

        $current = $state['current_page'];
        $found   = $state['found_pages'];
        $block   = $this->paginator_page_in_block;
        $margin  = $this->paginator_page_in_margin;

        $index = array();
        if ($found <= 2 * $margin + $block) {
            $i = 0;
            while ($i < $found) {
                $i++;
                $index[] = $i;
            }
        } else {
            $half_block = floor($block / 2);
            $check_point = $half_block + $margin + 1;
            if ($current <= $check_point) {
                $i = 0;
                while ($i < max($current + $half_block, $margin)) {
                    $i++;
                    $index[] = $i;
                }
                $index[] = null;
                $i = $found - $margin;
                while ($i < $found) {
                    $i++;
                    $index[] = $i;
                }
            } elseif($current > $found - $check_point) {
                $i = 0;
                while ($i < $margin) {
                    $i++;
                    $index[] = $i;
                }
                $index[] = null;
                $i = min($current - $half_block -1, $found - $margin);
                while ($i < $found) {
                    $i++;
                    $index[] = $i;

                }
            } else {
                $i = 0;
                while ($i < $margin) {
                    $i++;
                    $index[] = $i;
                }
                $index[] = null;

                $i = $current - $half_block;
                while ($i <= $current + $half_block) {

                    $index[] = $i;
                    $i++;
                }
                $index[] = null;
                $i = $found - $margin;
                while ($i < $found) {
                    $i++;
                    $index[] = $i;
                }
            }
        }

        $pages = array();
        foreach ($index as $number) {
            if (is_null($number)) {
                $pages[] = array('separator' => true);
            } else {
                $pages[] = array(
                    'number'    => $number,
                    'url'       => $this->getPaginatorPageUrl($number),
                );
            }
        }

        return $pages;
    }
}