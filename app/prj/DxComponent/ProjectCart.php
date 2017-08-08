<?php

DxFactory::import('DxComponent');

class DxComponent_ProjectCart extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return DxCart_Project
     */
    public static function getComponent(array $params = array())
    {
        try {
            return DxFactory::getInstance('DxCart_Project');
        } catch (Exception $e) {
            throw new DxException("Error occured while init 'DxCart_Project' component", self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }
}