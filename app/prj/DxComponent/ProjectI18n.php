<?php

DxFactory::import('DxComponent');

class DxComponent_ProjectI18n extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return I18n
     */
    public static function getComponent(array $params = array())
    {
        try {
            return DxFactory::getInstance('I18n_Project');
        } catch (Exception $e) {
            throw new DxException('Error occured while init ProjectI18n component', self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }
}