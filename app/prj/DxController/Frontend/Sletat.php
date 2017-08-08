<?php
DxFactory::import('DxController_Frontend');

class DxController_Frontend_Sletat extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.import.sletat.orders' => 'importSletat',
    );

    /**
     * @return string
     */
    protected function importSletat()
    {
        DxFactory::import('Utils_MailReader');

        $utils = new Utils_MailReader;

        $utils->grab();

        DxApp::terminate();
    }
}