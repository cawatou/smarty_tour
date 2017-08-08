<?php

DxFactory::import('Form_Filter_Backend');

class Form_Filter_Backend_Request extends Form_Filter_Backend
{
    public function draw()
    {
        $params = $this->getParameters();
        $this->setFormData(array_merge($params[self::FILTER_SEARCH_PARAMS], $params[self::FILTER_ORDER_PARAMS]));

        $this->smarty->assign(
            array(
                'request_types' => DomainObjectModel_Request::getTypesByRole($this->getContext()->getCurrentUser()->getRole()),
            )
        );

        return $this->smarty->fetch('backend/filter/request.tpl.php');
    }
}