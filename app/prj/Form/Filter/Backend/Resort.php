<?php
DxFactory::import('Form_Filter_Backend');

class Form_Filter_Backend_Resort extends Form_Filter_Backend
{
    public function draw()
    {
        $params = $this->getParameters();
        $this->setFormData(array_merge($this->getFormData(), $params[self::FILTER_SEARCH_PARAMS], $params[self::FILTER_ORDER_PARAMS]));

        /** @var $q DomainObjectQuery_Country */
        $q = DxFactory::getSingleton('DomainObjectQuery_Country');

        $this->smarty->assign(
            array(
                'country_list' => $q->findAll(true),
            )
        );

        unset($q);

        return $this->smarty->fetch('backend/filter/resort.tpl.php');
    }
}