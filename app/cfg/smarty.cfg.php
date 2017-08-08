<?php defined('DX_CFG_DIR') or die('No direct access allowed');

return array(
    'trim_white_spaces' => false,
    'use_sub_dirs'      => false,
    'compile_check'     => true,
    'template_dir'      => DX_VAR_DIR . DS . 'tpl',
    'compile_dir'       => DX_VAR_DIR . DS . 'tpl' . DS . '__tpl',
    'cache_dir'         => DX_VAR_DIR . DS . 'tpl' . DS . '__cache',
);