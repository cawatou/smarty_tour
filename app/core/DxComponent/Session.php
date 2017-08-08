<?php

DxFactory::import('DxComponent');

class DxComponent_Session extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return null|DxSession_Default
     */
    public static function getComponent(array $params = array())
    {
        if (!DxApp::isCli()) {
            try {
                return DxFactory::getSingleton('DxSession_Default', array(DxApp::config('session')));
            } catch (Exception $e) {
                throw new DxException('Error occurred while init \'Session\' component', self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
            }
        }

        return null;
    }
}