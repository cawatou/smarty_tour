<?php

DxFactory::import('Form_Filter_Backend');

class Form_Filter_Backend_Publication extends Form_Filter_Backend
{
    public function draw()
    {
        $params = $this->getParameters();
        $this->setFormData(array_merge($params[self::FILTER_SEARCH_PARAMS], $params[self::FILTER_ORDER_PARAMS]));

        $this->smarty->assign(array(
            'publication_categories' => DxFactory::invoke('DomainObjectModel_Publication', 'getCategories'),
        ));

        return $this->smarty->fetch('backend/filter/publication.tpl.php');
    }
}