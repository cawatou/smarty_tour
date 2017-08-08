<?php

DxFactory::import('DxComponent');

class DxComponent_ProjectAppContext extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return DxAppContext_Project
     */
    public static function getComponent(array $params = array())
    {
        try {
            return DxFactory::getInstance('DxAppContext_Project', array(DxApp::config(DxApp::CFG_APP, DxApp::SECTION_CONTEXT)));
        } catch (Exception $e) {
            throw new DxException('Error occurred while init \'Project\' component', self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }
}