<?php

DxFactory::import('Form_Filter_Backend');

class Form_Filter_Backend_GalleryCategory extends Form_Filter_Backend
{
    public function draw()
    {
        $params = $this->getParameters();
        $this->setFormData(array_merge($params[self::FILTER_SEARCH_PARAMS], $params[self::FILTER_ORDER_PARAMS]));

        $this->smarty->assign(array(
            'categories' => DxFactory::invoke('DomainObjectModel_Gallery', 'getCategories'),
        ));

        return $this->smarty->fetch('backend/filter/gallery_category.tpl.php');
    }
}