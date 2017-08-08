<?php

DxFactory::import('DxComponent');

class DxComponent_CommandHook extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return DxCommandHook_Default
     */
    public static function getComponent(array $params = array())
    {
        try {
            return DxFactory::getInstance('DxCommandHook_Default');
        } catch (Exception $e) {
            throw new DxException('Error occurred while init \'CommandHook\' component', self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }
}