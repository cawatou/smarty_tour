<?php defined('DX_CFG_DIR') or die('No direct access allowed');

return array(
    'connection' => array(
        'protocol' => 'mysql',
        'hostname' => 'localhost',
        'port'     => 3306,
        'database' => null,
        'username' => 'root',
        'password' => null,
        'prefix'   => null,
        'charset'  => 'utf8'
    ),

    'entity'     => array(
        'dir'       => DX_PRJ_DIR . DS . 'DomainObjectModel',
        'namespace' => ''
    ),

    'cache'      => array(
        'implrmentation' => '',
        'query_cache'    => 0,
        'result_cache'   => 0
    ),

    'generated'  => array(
        'output_path'      => DX_VAR_DIR . DS . 'generated' . DS . 'output',
        'models_path'      => DX_VAR_DIR . DS . 'generated' . DS . 'DomainObjectModel',
        'controllers_path' => DX_VAR_DIR . DS . 'generated' . DS . 'DomainObjectController',
        'queries_path'     => DX_VAR_DIR . DS . 'generated' . DS . 'DomainObjectQuery'
    ),
);