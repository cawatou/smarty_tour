<?php
DxFactory::import('DxController_Frontend');
DxFactory::import('Form_Filter');

class DxController_Frontend_Franchise extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.franchise' => 'index',
    );

    /**
     * @return string
     */
    protected function index()
    {
        
        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $html = $smarty->fetch('frontend/franchise.tpl.php');

        return $this->wrap($html);
    }
}