<?php
DxFactory::import('DxController_Frontend');

class DxController_Frontend_AjaxHelper extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.ajax.countries' => 'getCountries',
        '.ajax.resorts'   => 'getResorts',
        '.ajax.hotels'    => 'getHotels',

        '.ajax.suggest.countries' => 'suggestCountries',
        '.ajax.suggest.resorts'   => 'suggestResorts',
        '.ajax.suggest.hotels'    => 'suggestHotels',
    );

    /**
     * @return string
     */
    protected function getCountries()
    {
        /** @var $q DomainObjectQuery_Country */
        $q = DxFactory::getSingleton('DomainObjectQuery_Country');

        $countries = array();

        foreach ($q->getAll(true) as $country) {
            $countries[$country['country_id']] = array(
                'id'    => $country['country_id'],
                'title' => $country['country_title'],
                'alias' => $country['country_alias'],
            );
        }

        return $this->response($countries);
    }

    /**
     * @return string
     */
    protected function getResorts()
    {
        if (empty($_REQUEST['country_id']) || $_REQUEST['country_id'] <= 0) {
            return $this->response();
        }

        /** @var $q DomainObjectQuery_Resort */
        $q = DxFactory::getSingleton('DomainObjectQuery_Resort');

        $resorts = array();

        foreach ($q->getByCountryId($_REQUEST['country_id'], true) as $resort) {
            $resorts[$resort['resort_id']] = array(
                'id'    => $resort['resort_id'],
                'title' => $resort['resort_title'],
                'alias' => $resort['resort_alias'],
            );
        }

        return $this->response($resorts);
    }

    /**
     * @return string
     */
    protected function getHotels()
    {
        if (empty($_REQUEST['resort_id']) || $_REQUEST['resort_id'] <= 0) {
            return $this->response();
        }

        /** @var $q DomainObjectQuery_Hotel */
        $q = DxFactory::getSingleton('DomainObjectQuery_Hotel');

        $hotels = array();

        foreach ($q->getByResortId($_REQUEST['resort_id']) as $hotel) {
            $hotels[$hotel['hotel_id']] = array(
                'id'    => $hotel['hotel_id'],
                'title' => $hotel['hotel_title'],
                'stars' => $hotel['hotel_stars'],
            );
        }

        return $this->response($hotels);
    }

    /**
     * @return string
     */
    protected function suggestCountries()
    {
        if (empty($_REQUEST['query'])) {
            return $this->response();
        }

        $query = $_REQUEST['query'];

        /** @var $q DomainObjectQuery_Country */
        $q = DxFactory::getSingleton('DomainObjectQuery_Country');

        $countries = $q->suggest($query);

        if (empty($countries)) {
            return $this->response();
        }

        $response = array();

        foreach ($countries as $country) {
            $response[] = array(
                'id'    => $country['country_id'],
                'title' => $country['country_title'],
            );
        }

        return $this->response($response);
    }

    /**
     * @return string
     */
    protected function suggestResorts()
    {
        if (empty($_REQUEST['query']) || mb_strlen($_REQUEST['query']) <= 2) {
            return $this->response();
        }

        $query = $_REQUEST['query'];

        $filter = array();

        if (!empty($_REQUEST['country_id'])) {
            $filter['country_id'] = $_REQUEST['country_id'];
        }

        /** @var $q DomainObjectQuery_Resort */
        $q = DxFactory::getSingleton('DomainObjectQuery_Resort');

        $resorts = $q->suggest($query, $filter);

        if (empty($resorts)) {
            return $this->response();
        }

        $response = array();

        foreach ($resorts as $resort) {
            $response[] = array(
                'id'         => $resort['resort_id'],
                'title'      => $resort['resort_title'],
                'country_id' => $resort['country_id'],
            );
        }

        return $this->response($response);
    }

    /**
     * @return string
     */
    protected function suggestHotels()
    {
        if (empty($_REQUEST['query']) || mb_strlen($_REQUEST['query']) < 3) {
            return $this->response();
        }

        $query = $_REQUEST['query'];

        $filter = array();

        if (!empty($_REQUEST['country_id'])) {
            $filter['country_id'] = $_REQUEST['country_id'];
        }

        if (!empty($_REQUEST['resort_id'])) {
            $filter['resort_id'] = $_REQUEST['resort_id'];
        }

        $is_ignore_query = true;

        if (isset($_REQUEST['query_based'])) {
            $is_ignore_query = false;
        }

        /** @var $q DomainObjectQuery_Hotel */
        $q = DxFactory::getSingleton('DomainObjectQuery_Hotel');

        $hotels = $q->suggest($query, $filter, ($is_ignore_query ? 5000 : 10), $is_ignore_query);

        if (empty($hotels)) {
            return $this->response();
        }

        $response = array();

        // @todo! Add countries and resorts
        foreach ($hotels as $hotel) {
            $response[] = array(
                'id'         => $hotel['hotel_id'],
                'title'      => $hotel['hotel_title'],
                'stars'      => $hotel['hotel_stars'],
                'country_id' => $hotel['country_id'],
                'resort_id'  => $hotel['resort_id'],
            );
        }

        return $this->response($response);
    }

    public function response(array $array = array())
    {
        $json = json_encode($array);

        $this->getContext()->addHeader('Content-Type: application/json');

        echo $json;

        DxApp::terminate();
    }
}