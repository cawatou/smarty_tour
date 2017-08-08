<?php

DxFactory::import('Form_Filter_Backend');

class Form_Filter_Backend_I18n extends Form_Filter_Backend
{
    /**
     * @return string
     */
    public function draw()
    {
        $params = $this->getParameters();
        $this->setFormData(array_merge($params[self::FILTER_SEARCH_PARAMS], $params[self::FILTER_ORDER_PARAMS]));

        /** @var $q_i18n DomainObjectQuery_I18n */
        $q_i18n = DxFactory::getInstance('DomainObjectQuery_I18n');

        $this->smarty->assign(
            array(
                'q_i18n' => $q_i18n
            )
        );

        return $this->smarty->fetch('backend/filter/i18n.tpl.php');
    }

    /**
     * @return array
     */
    protected function getDefaultSearchParams()
    {
        return array('only_not_translated' => 1);
    }
}