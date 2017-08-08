<?php defined('DX_CFG_DIR') or die('No direct access allowed');

return array(
    'connection' => array(
        'protocol' => 'mysql',
        'hostname' => 'localhost',
        'port'     => 3306,
        'database' => 'oldtour',
        'username' => 'oldtour',
        'password' => '4L8j4Q0h',
        'prefix'   => 'moihottur__',
        'charset'  => 'utf8'
    ),

    'entity'     => array(
        'dir'       => DX_PRJ_DIR . DS . 'DomainObjectModel',
        'namespace' => ''
    ),

    'cache'      => array(
        'implementation' => 'Doctrine_Cache_Apc',
        'query_cache'    => true,
        'result_cache'   => true
    ),

    'generated'  => array(
        'generated_path'   => DX_VAR_DIR . DS . 'generated',
        'output_path'      => DX_VAR_DIR . DS . 'generated' . DS . 'output',
        'models_path'      => DX_VAR_DIR . DS . 'generated' . DS . 'DomainObjectModel',
        'queries_path'     => DX_VAR_DIR . DS . 'generated' . DS . 'DomainObjectQuery'
    ),
);