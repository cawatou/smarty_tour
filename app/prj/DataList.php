<?php

DxFactory::import('DataListQuery');

class DataList
{
    const DL_ERROR_BASE         = 1100;
    const DL_ERROR_WRONG_PAGE   = 1101;

    const DL_DEFAULT_ITEMS_PER_PAGE = 5;

    const DL_ID_OF_PERSISTENT_STORE = '__LISTS__';

    const DL_PAGE = 'dl_page';

    /** @var int */
    protected $items_per_page = self::DL_DEFAULT_ITEMS_PER_PAGE;

    /** @var int */
    protected $current_page_number = 1;

    /** @var DataListQuery */
    protected $query;

    /** @var string|int */
    protected $list_id;

    /** @var array */
    protected $params = array();

    /** @var array */
    protected $state = array();

    /** @var int */
    protected $found_count = 0;

    /** @var int|string */
    protected $checksum = 0;

    public function __construct(DataListQuery $query, $list_id = null)
    {
        $this->setQuery($query);
        $this->setListId($list_id);
        $this->load();
    }

    /**
     * @return array
     */
    public function &getRequestedPage()
    {
        if (!is_null($n = $this->getRequestedPageNumber())) {
            return $this->getPage($n);
        }
		
		

        return $this->getCurrentPage();
    }

    /**
     * @return array
     */
    public function &getNextPage()
    {
        return $this->getPageByNumber($this->getCurrentPageNumber() + 1);
    }

    /**
     * @return array
     */
    public function &getPreviousPage()
    {
        return $this->getPageByNumber($this->getCurrentPageNumber() - 1);
    }

    /**
     * @param $n
     * @return array
     */
    public function &getPage($n)
    {
        return $this->getPageByNumber($n);
    }

    /**
     * @return array
     */
    public function &getCurrentPage()
    {
        return $this->getPageByNumber($this->getCurrentPageNumber());
    }

    /**
     * @param string|$list_id
     */
    protected function setListId($list_id = null)
    {
        $this->list_id = $list_id;
    }

    /**
     * @param array $params
     */
    public function setParameters(array &$params = array())
    {
        $this->getQuery()->initByListParams($params);

        if ($this->getChecksum() != $this->getQuery()->getChecksumForList()) {
            $this->reset();
        }

        $this->params = $params;
    }

    /**
     * @param $n
     */
    public function setItemsPerPage($n)
    {
        $this->items_per_page = $n;
    }

    /**
     * @param $n
     */
    public function setCurrentPageNumber($n)
    {
        if (!is_integer($n) || $n < 1) {
            throw new DxException('Wrong page number', self::DL_ERROR_WRONG_PAGE);
        }

        $this->current_page_number = $n;
    }

    /**
     * @param $n
     */
    public function setFoundCount($n)
    {
        $this->found_count = $n;
    }

    /**
     * @param int|string $cs
     */
    public function setChecksum($cs)
    {
        $this->checksum = $cs;
    }

    /**
     * @param DataListQuery $q
     */
    public function setQuery(DataListQuery $q)
    {
        $this->query = $q;
    }

    /**
     * @return array
     */
    public function &getparameters()
    {
        return $this->params;
    }

    /**
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->items_per_page;
    }

    /**
     * @return int
     */
    public function getCurrentPageNumber()
    {
        return $this->current_page_number;
    }

    /**
     * @return int
     */
    public function getFoundCount()
    {
        return $this->found_count;
    }

    /**
     * @return int|string
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * @return int|null|string
     */
    public function getListId()
    {
        return $this->list_id;
    }

    /**
     * @return array
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return DataListQuery
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return int
     */
    protected function getRequestedPageNumber()
    {
        if (isset($_REQUEST[self::DL_PAGE]) && intval($_REQUEST[self::DL_PAGE])) {
            return intval($_REQUEST[self::DL_PAGE]);
        }

        return null;
    }

    /**
     * @param $n
     * @return array
     * @throws DxException
     */
    protected function &getPageByNumber($n)
    {
        if (!is_integer($n) || $n < 1) {
            $n = 1;
        }

        $offset = ($n - 1) * $this->getItemsPerPage();
        $length = $this->getItemsPerPage();

        $result =& $this->getQuery()->findForList($offset, $length);
        $count  = $this->getQuery()->findCountForList();

        $this->setFoundCount($count);

        $this->setCurrentPageNumber($n);
        $this->calcState();
        $this->save();

        $state = $this->getState();

        if (empty($result) && $count > 0) {
            $result =& $this->getPageByNumber($state['found_pages']);
        }

        return $result;
    }

    /**
     * @return void
     */
    protected function calcState()
    {
        $items_per_page = $this->getItemsPerPage() ? $this->getItemsPerPage() : self::DL_DEFAULT_ITEMS_PER_PAGE;

        $this->resetState();
        $this->state['items_per_page'] = $items_per_page;
        $this->state['found_count']    = $this->getFoundCount();
        $this->state['found_pages']    = ($this->state['found_count'] % $items_per_page) ? intval($this->state['found_count'] / $items_per_page) + 1 : $this->state['found_count'] / $items_per_page;
        $this->state['next_page']      = ($this->state['found_pages'] - $this->getCurrentPageNumber() >= 1) ? $this->getCurrentPageNumber() + 1 : null;
        $this->state['prev_page']      = ($this->getCurrentPageNumber() > 1) ? $this->getCurrentPageNumber() - 1 : null;
        $this->state['current_page']   = $this->getCurrentPageNumber();
        $this->state['pages_index']    = $this->generatePagesIndex();
    }

    /**
     * @return array
     */
    protected function generatePagesIndex()
    {
        $state = $this->getState();
        $pages = array();

        for ($i = 1; $i <= $state['found_pages']; $i++) {
            $pages[$i] = $i;
        }

        return $pages;
    }

    /**
     * @return void
     */
    protected function save()
    {
        if ($this->getListId()) {
            $ps =& $this->getPersistentStore();

            $ps = array(
                'current_page_number' => $this->getCurrentPageNumber(),
                'items_per_page'      => $this->getItemsPerPage(),
                'parameters'           => $this->getparameters(),
                'checksum'            => $this->getQuery()->getChecksumForList()
            );
        }
    }

    /**
     * @return void
     */
    protected function load()
    {
        if ($this->getListId()) {
            $ps =& $this->getPersistentStore();
        } else {
            $ps = array();
        }

        $this->setCurrentPageNumber(isset($ps['current_page_number']) ? $ps['current_page_number'] : 1);
        $this->setItemsPerPage(isset($ps['items_per_page']) ? $ps['items_per_page'] : self::DL_DEFAULT_ITEMS_PER_PAGE);
        $this->setChecksum(isset($ps['checksum']) ? $ps['checksum'] : null);
        $p = isset($ps['parameters']) ? $ps['parameters'] : array();
        $this->setParameters($p);
    }

    /**
     * @return void
     */
    protected function reset()
    {
        $this->resetState();
        $this->setCurrentPageNumber(1);
    }

    /**
     * @return void
     */
    protected function resetState()
    {
        $this->state = array();
    }

    /**
     * @return array
     */
    protected function &getPersistentStore()
    {
        if (!array_key_exists(self::DL_ID_OF_PERSISTENT_STORE, $_SESSION)) {
            $_SESSION[self::DL_ID_OF_PERSISTENT_STORE] = array();
        }

        if (!array_key_exists($this->getListId(), $_SESSION[self::DL_ID_OF_PERSISTENT_STORE])) {
            $_SESSION[self::DL_ID_OF_PERSISTENT_STORE][$this->getListId()] = array();
        }

        return $_SESSION[self::DL_ID_OF_PERSISTENT_STORE][$this->getListId()];
    }
}