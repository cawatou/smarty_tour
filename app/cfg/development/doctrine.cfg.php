<?php defined('DX_CFG_DIR') or die('No direct access allowed');

return array(
    'connection' => array(
        'protocol' => 'mysql',
        'hostname' => 'localhost',
        'port'     => 3306,
        'database' => 'kaa_prj_moihottur_new',
        'username' => 'db_user',
        'password' => '123123',
        'prefix'   => 'moihottur__',
        'charset'  => 'utf8'
    ),

    'entity'     => array(
        'dir'       => DX_PRJ_DIR . DS . 'DomainObjectModel',
        'namespace' => ''
    ),

    'cache'      => array(
        'implementation' => '',
        'query_cache'    => 0,
        'result_cache'   => 0
    ),

    'generated'  => array(
        'generated_path'   => DX_VAR_DIR . DS . 'generated',
        'output_path'      => DX_VAR_DIR . DS . 'generated' . DS . 'output',
        'models_path'      => DX_VAR_DIR . DS . 'generated' . DS . 'DomainObjectModel',
        'queries_path'     => DX_VAR_DIR . DS . 'generated' . DS . 'DomainObjectQuery'
    ),
);