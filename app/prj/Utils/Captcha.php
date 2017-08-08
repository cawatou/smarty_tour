<?php

DxFactory::import('Utils');

class Utils_Captcha extends Utils
{    
    private $key_string = null;
    
    public function __construct($custom = array())
    {
        include_once('kcaptcha/kcaptcha.php');
        $captcha = new KCAPTCHA($custom);
        $this->key_string = $captcha->getKeyString();

    }
    
    public function getKeyString()
    {
        return $this->key_string;
    }
}