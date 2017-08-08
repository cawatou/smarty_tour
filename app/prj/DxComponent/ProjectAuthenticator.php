<?php

DxFactory::import('DxComponent');

abstract class DxComponent_ProjectAuthenticator extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return DxAuthenticator_Default
     */
    public static function getComponent(array $params = array())
    {
        try {
            return DxFactory::getInstance('DxAuthenticator_Project');
        } catch (Exception $e) {
            throw new DxException("Error occured while init 'Authenticator' component", self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }
}