<?php

DxFactory::import('DxComponent');

class DxComponent_AppContext extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return DxAppContext
     */
    public static function getComponent(array $params = array())
    {
        try {
            return DxFactory::getInstance('DxAppContext', array(DxApp::config(DxApp::CFG_APP, DxApp::SECTION_CONTEXT)));
        } catch (Exception $e) {
            throw new DxException('Error occurred while init \'AppContext\' component', self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }
}