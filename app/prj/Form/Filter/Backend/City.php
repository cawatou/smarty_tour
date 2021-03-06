<?php
DxFactory::import('Form_Filter_Backend');

class Form_Filter_Backend_City extends Form_Filter_Backend
{
    public function draw()
    {
        $params = $this->getParameters();
        $this->setFormData(array_merge($this->getFormData(), $params[self::FILTER_SEARCH_PARAMS], $params[self::FILTER_ORDER_PARAMS]));

        return $this->smarty->fetch('backend/filter/city.tpl.php');
    }

    /**
     * @return array
     */
    protected function getDefaultSearchParams()
    {
        return array(
            'city_status' => 'ENABLED',
        );
    }
}