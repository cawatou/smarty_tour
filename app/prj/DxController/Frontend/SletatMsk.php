<?php
DxFactory::import('DxController_Frontend');

class DxController_Frontend_SletatMsk extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.import.sletat.msk' => 'importSletatMsk',
    );

    /**
     * @return string
     */
    protected function importSletatMsk()
    {
        DxFactory::import('Utils_MSKReader');

        $utils = new Utils_MSKReader;

        $utils->grab();

        DxApp::terminate();
    }
}