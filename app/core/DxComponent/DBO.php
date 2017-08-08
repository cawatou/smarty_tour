<?php

DxFactory::import('DxComponent');

class DxComponent_DBO extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return DxDBO_MySQL
     * @throws DxException
     */
    public static function getComponent(array $params = array())
    {
        try {
            return DxFactory::getInstance('DxDBO_MySQL', array(DxApp::config('dbo.mysql')));
        } catch (Exception $e) {
            throw new DxException('Error occured while init \'DBO\' component', self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }
}
?>