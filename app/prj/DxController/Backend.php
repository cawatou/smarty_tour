<?php

DxFactory::import('DxController');


class DxController_Backend extends DxController

{
    /** @var array */
    protected $cmd_method = array();

    protected function getCommandMethod(DxCommand $command)
    {
        return array_key_exists($command->getCmd(), $this->cmd_method) ? $this->cmd_method[$command->getCmd()] : null;
    }

    /**
     * @return DxURL_Default_Project
     */
    protected function getUrl()
    {
        return DxApp::getComponent(DxApp::ALIAS_URL);
    }

    /**
     * @return Smarty
     */
    protected function getSmarty()
    {
        return DxApp::getComponent(DxConstant_Project::ALIAS_SMARTY);
    }

    /**
     * @return DomainObjectManager
     */
    protected function getDomainObjectManager()
    {
        return DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER);
    }

    /**
     * @param $html
     * @param array $data
     * @param string $type
     * @return string
     */
    protected function wrap($html, $data = array(), $type = 'COMMON')
    {
        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $assign = array(
            'data' => $data,
            'html' => $html,
            'type' => $type,
        );

        if ($type == 'COMMON') {
            $assign['config'] = DxApp::config('cms');
        }

        $smarty->assign($assign);
        return $smarty->fetch('backend/wrapper.tpl.php');
    }
}