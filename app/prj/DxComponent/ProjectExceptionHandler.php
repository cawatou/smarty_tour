<?php

DxFactory::import('DxComponent');

class DxComponent_ProjectExceptionHandler extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return DxExceptionHandler_Default
     */
    public static function getComponent(array $params = array())
    {
        try {
            return DxFactory::getInstance('DxExceptionHandler_Project');
        } catch (Exception $e) {
            throw new DxException("Error occurred while init 'ExceptionHandler_Project' component", self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }
}