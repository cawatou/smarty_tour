<?php defined('DX_CFG_DIR') or die('No direct access allowed');

error_reporting(E_ALL | E_STRICT);
setlocale(LC_ALL, 'ru_RU.UTF-8');
setlocale(LC_NUMERIC, "C");

if (function_exists('mb_internal_encoding')) {
    // Set the MB extension encoding to the same character set
    mb_internal_encoding('utf-8');
}

return array(
    'date.timezone'              => 'Asia/Novosibirsk',
    'intl.default_locale'        => 'ru-RU',
    'mbstring.internal_encoding' => 'utf-8',
    'zlib.output_compression'    => 0,    
    'display_startup_errors'     => 1,
    'display_errors'             => 1,
    'include_path'               => ini_get('include_path') . (getenv('COMSPEC') ? ';' : ':') . DX_EXT_DIR
);