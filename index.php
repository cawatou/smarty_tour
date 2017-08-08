<?php

define('DS',   DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

define('DX_CORE_DIR', realpath(ROOT . DS . 'app' . DS . 'core'));
define('DX_PRJ_DIR',  realpath(ROOT . DS . 'app' . DS . 'prj'));
define('DX_EXT_DIR',  realpath(ROOT . DS . 'app' . DS . 'ext'));
define('DX_CFG_DIR',  realpath(ROOT . DS . 'app' . DS . 'cfg'));
define('DX_VAR_DIR',  realpath(ROOT . DS . 'app' . DS . 'var'));

require_once(DX_CORE_DIR . DS . 'DxException.php');
require_once(DX_CORE_DIR . DS . 'DxFactory.php');

try {
    DxFactory::invoke('DxApp', 'bootstrap');
} catch (Exception $e) { // catch unhandled exception
    print $e;
}