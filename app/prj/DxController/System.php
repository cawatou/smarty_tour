<?php

DxFactory::import('DxController');

class DxController_System extends DxController
{
    /** @var array */
    protected $cmd_method = array(
        '.sys.models' => 'generateModels',
        '.sys.spider' => 'runSpider',
    );

    /**
     * @param DxCommand $command
     * @return string
     */
    protected function getCommandMethod(DxCommand $command)
    {
        return array_key_exists($command->getCmd(), $this->cmd_method) ? $this->cmd_method[$command->getCmd()] : null;
    }

    /**
     * @return void
     */
    protected function generateModels()
    {
        if (DxApp::getEnv() == DxApp::ENV_PRODUCTION) {
			throw new DxException("Is used only for development. Change the environment variable");
        }
        $dog = new DomainObjectGenerator($this->getDomainObjectManager());
        $dog->generateDomainObjects();
    }

    /**
     * @return void
     */
    protected function runSpider()
    {
        /** @var $spider Utils_Spider */
        $spider = DxFactory::getInstance('Utils_Spider');
        $spider->spidering();
    }

    /**
     * @return DomainObjectManager
     */
    protected function getDomainObjectManager()
    {
        return DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER);
    }
}