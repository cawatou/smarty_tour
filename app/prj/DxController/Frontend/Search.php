<?php
DxFactory::import('DxController_Frontend');

class DxController_Frontend_Search extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.search'       => 'index',
        '.search.train' => 'train',
        '.search.hotel' => 'hotel',
    );

    /**
     * @return string
     */
    protected function index()
    {
        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $html = $smarty->fetch('frontend/search.tpl.php');

        return $this->wrap($html);
    }

    /**
     * @return string
     */
    protected function train()
    {
        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $html = $smarty->fetch('frontend/search_train.tpl.php');

        return $this->wrap($html);
    }

    /**
     * @return string
     */
    protected function hotel()
    {
        /** @var $q DomainObjectQuery_Hotel */
        $q = DxFactory::getSingleton('DomainObjectQuery_Hotel');

        /** @var $filter Form_Filter_Frontend_Hotel */
        $filter = DxFactory::getInstance('Form_Filter_Frontend_Hotel', array('fh', true));
        $filter->setUrl('/search/hotel');

        /** @var $dl DataList_Paginator */
        $dl = DxFactory::getInstance('DataList_Paginator', array($q));

        $dl->setPaginatorPageName('page');
        $dl->setItemsPerPage(20);

        $parameters = array();

        $filter->setFormData(
            array(
                'hotel_status' => 'ENABLED',
            )
        );

        if ($filter->isProcessed() && $params_url = $filter->getParametersAsURL()) {
            $dl->setPaginatorPageUrl('/search/hotel,%s?'. $params_url .'');

            $parameters = $filter->getParameters();
        } else {
            $dl->setPaginatorPageUrl('/search/hotel,%s');

            $parameters = array(
                Form_Filter_Frontend_Hotel::FILTER_SEARCH_PARAMS => $filter->getFormData(),
            );
        }

        $dl->setParameters($parameters);

        $page_number = $this->getContext()->getCurrentCommand()->getArguments('page');
        $dl->setCurrentPageNumber((int)$page_number < 1 ? 1 : (int)$page_number);

        $list  =& $dl->getRequestedPage();
        $state =  $dl->getState();

        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'list'  => $list,
                'state' => $state,

                'filter' => $filter,
            )
        );

        $html = $smarty->fetch('frontend/search_hotel.tpl.php');

        return $this->wrap($html);
    }
}