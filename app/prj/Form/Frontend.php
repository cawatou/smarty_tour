<?php

dxFactory::import('Form');

abstract class Form_Frontend extends Form
{
    /**
     * @return DomainObjectManager
     */
    protected function getDomainObjectManager()
    {
        return DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER);
    }
}