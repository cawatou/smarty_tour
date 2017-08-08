<?php
DxFactory::import('Form_Filter_Backend');

class Form_Filter_Backend_Hotel extends Form_Filter_Backend
{
    public function draw()
    {
        $params = $this->getParameters();
        $this->setFormData(array_merge($this->getFormData(), $params[self::FILTER_SEARCH_PARAMS], $params[self::FILTER_ORDER_PARAMS]));

        $data = $this->getFormData();

        $resorts = array();

        if (!empty($data['country_id']) && $data['country_id'] > 0) {
            /** @var $q_res DomainObjectQuery_Resort */
            $q_res = DxFactory::getSingleton('DomainObjectQuery_Resort');

            $resorts = $q_res->getByCountryId($data['country_id']);
        }

        /** @var $q_cry DomainObjectQuery_Country */
        $q_cry = DxFactory::getSingleton('DomainObjectQuery_Country');

        $this->smarty->assign(
            array(
                'country_list' => $q_cry->getAll(true),
                'resort_list'  => $resorts,
            )
        );

        return $this->smarty->fetch('backend/filter/hotel.tpl.php');
    }

    /**
     * @return array
     */
    protected function getDefaultSearchParams()
    {
        return array(
            'hotel_status' => 'ENABLED',
        );
    }
}