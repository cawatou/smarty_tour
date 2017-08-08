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
require_once(DX_CORE_DIR . DS . 'DxApp.php');

if (!DxApp::isCli()) {
    exit();
}

ini_set('memory_limit', '256M');
set_time_limit(500);

$commands = array(
    array(
        'cmd'    => '.import.sletat.orders',
        'args'   => array(),
        'launch' => array(
            'day'    => '*', // * - every day, 1 - monday, 7 - sunday
            'hour'   => '*', // * - every hour
            'minute' => '*',
        ),
    ),
);

$now = DxFactory::getInstance('DxDateTime');
$now->setDefaultTimeZone();

foreach ($commands as $data) {
    $day = preg_split('/\s*,\s*/', $data['launch']['day']);

    if (!in_array('*', $day) && !in_array($now->format('N'), $day)) {
        continue;
    }

    $hour = preg_split('/\s*,\s*/', $data['launch']['hour']);

    if (!in_array('*', $hour) && !in_array($now->format('G'), $hour)) {
        continue;
    }

    $minute = preg_split('/\s*,\s*/', $data['launch']['minute']);

    if (!in_array('*', $minute) && !in_array((int)$now->format('i'), $minute)) {
        continue;
    }

    try {
        DxFactory::invoke('DxApp', 'bootstrap', array($data['cmd'], $data['args']));
    } catch (Exception $e) { // catch unhandled exception
        print $e;
    }
}
