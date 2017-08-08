<?php
DxFactory::import('Form_Filter_Frontend');

class Form_Filter_Frontend_Hotel extends Form_Filter_Frontend
{
    public function draw()
    {
        $params = $this->getParameters();

        $this->setFormData(array_merge($params[self::FILTER_SEARCH_PARAMS], $params[self::FILTER_ORDER_PARAMS]));

        $data = $this->getFormData();

        $resorts = array();

        if (!empty($data['country_id']) && $data['country_id'] > 0) {
            /** @var $q_res DomainObjectQuery_Resort */
            $q_res = DxFactory::getSingleton('DomainObjectQuery_Resort');

            $resorts = $q_res->getByCountryId($data['country_id']);
        }

        /** @var $q_cry DomainObjectQuery_Country */
        $q_cry = DxFactory::getSingleton('DomainObjectQuery_Country');

        /** @var $model DomainObjectModel_Hotel */
        $model = DxFactory::getSingleton('DomainObjectModel_Hotel');

        $stars = $model->getHotelStars();

        $this->smarty->assign(
            array(
                'country_list' => $q_cry->getAll(true),
                'resort_list'  => $resorts,
                'stars'        => $stars,
            )
        );

        return $this->smarty->fetch('frontend/filter/hotel.tpl.php');
    }
}