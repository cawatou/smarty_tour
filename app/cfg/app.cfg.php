<?php defined('DX_CFG_DIR') or die('No direct access allowed');

DxFactory::import('DxConstant_Project');

return array(
    DxApp::SECTION_COMPONENTS     => array(
        DxApp::SECTION_COMPONENTS_CORE    => array(
            DxApp::ALIAS_EXCEPTION_HANDLER => 'DxComponent_ProjectExceptionHandler',
            DxApp::ALIAS_URL               => 'DxComponent_ProjectURL',
            DxApp::ALIAS_AUTHENTICATOR     => 'DxComponent_ProjectAuthenticator',
            DxApp::ALIAS_APP_CONTEXT       => 'DxComponent_ProjectAppContext',
            DxApp::ALIAS_COMMAND_HOOK      => 'DxComponent_ProjectCommandHook',
        ),
        DxApp::SECTION_COMPONENTS_PROJECT => array(
            DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER => array(
                'class'    => 'DxComponent_DomainObjectManager',
                'ext_path' => DX_EXT_DIR . DS . 'ORM' . DS . 'V1'
            ),
            DxConstant_Project::ALIAS_DOMAIN_OBJECT_DBO     => array(
                'class'    => 'DxComponent_DomainObjectDBO',
                'ext_path' => DX_EXT_DIR . DS . 'ORM' . DS . 'V1'
            ),
            DxConstant_Project::ALIAS_SESSION               => 'DxComponent_Session',
            DxConstant_Project::ALIAS_I18N                  => 'DxComponent_ProjectI18n',
            DxConstant_Project::ALIAS_SMARTY                => 'DxComponent_Smarty',
        )
    ),
    DxApp::SECTION_CONTEXT        => array(
        'charset'      => 'utf-8',
        'content_type' => 'text/html',
        'compress'     => false,
    ),
);