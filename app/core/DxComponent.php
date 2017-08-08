<?php

abstract class DxComponent
{
    const DX_COMPONENT_ERROR_BASE           = 300;
    const DX_COMPONENT_ERROR_INIT_COMPONENT = 301;

    /**
     * @static
     * @abstract
     * @param array $params
     * @return object
     */
    public static function getComponent(array $params = array()) {
        throw DxException('Method "getComponent" should be defined', DX_COMPONENT_ERROR_BASE);
    }
}