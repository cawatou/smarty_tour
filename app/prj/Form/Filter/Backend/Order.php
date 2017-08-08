<?php

DxFactory::import('Form_Filter_Backend');

class Form_Filter_Backend_Order extends Form_Filter_Backend
{
    public function draw()
    {
        $params = $this->getParameters();
        $this->setFormData(array_merge($params[self::FILTER_SEARCH_PARAMS], $params[self::FILTER_ORDER_PARAMS]));

        $this->smarty->assign(array(
            'order_statuses' => DxFactory::invoke('DomainObjectModel_Order', 'getOrderStatuses'),
        ));

        return $this->smarty->fetch('backend/filter/order.tpl.php');
    }
}