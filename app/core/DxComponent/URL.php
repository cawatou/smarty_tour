<?php

DxFactory::import('DxComponent');

class DxComponent_URL extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return DxURL_Default
     */
    public static function getComponent(array $params = array())
    {
        try {
            return DxFactory::getInstance('DxURL_Default', array(DxApp::config('url')));
        } catch (Exception $e) {
            throw new DxException('Error occurred while init \'URL\' component', self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }
}