<?php
DxFactory::import('Form_Filter_Backend');

class Form_Filter_Backend_Office extends Form_Filter_Backend
{
    public function draw()
    {
        $params = $this->getParameters();
        $this->setFormData(array_merge($params[self::FILTER_SEARCH_PARAMS], $params[self::FILTER_ORDER_PARAMS]));

        /** @var DomainObjectQuery_City $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_City');

        $cities = $q->getAll(true);

        $this->smarty->assign(
            array(
                'city_list' => $cities,
            )
        );

        return $this->smarty->fetch('backend/filter/office.tpl.php');
    }
}