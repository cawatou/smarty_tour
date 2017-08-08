<?php

dxFactory::import('Form');

abstract class Form_Backend extends Form
{
    /**
     * @return DomainObjectManager
     */
    protected function getDomainObjectManager()
    {
        return DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER);
    }

    public function setCmd($route = '', $suffix = null)
    {
        $route = str_replace('.adm', '', $route);
        $this->form_url = DxApp::getComponent(DxApp::ALIAS_URL)->adm($route, $suffix);
    }
}