<?php

DxFactory::import('Form_Filter_Backend');

class Form_Filter_Backend_GalleryImage extends Form_Filter_Backend
{
    public function draw()
    {
        $params = $this->getParameters();
        $this->setFormData(array_merge($params[self::FILTER_SEARCH_PARAMS], $params[self::FILTER_ORDER_PARAMS]));

        /** @var $q DomainObjectQuery_Gallery */
        $q = DxFactory::getSingleton('DomainObjectQuery_Gallery');

        $this->smarty->assign(array(
            'gallery_list' => $q->findAll(),
        ));

        return $this->smarty->fetch('backend/filter/gallery_image.tpl.php');
    }
}