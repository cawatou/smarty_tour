<?php

DxFactory::import('DxComponent');

class DxComponent_ProjectURL extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return DxURL_Default_Project
     */
    public static function getComponent(array $params = array())
    {
        try {
            return DxFactory::getInstance('DxURL_Default_Project', array(DxApp::config('url')));
        } catch (Exception $e) {
            throw new DxException('Error occurred while init \'ProjectURL\' component', self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }
}