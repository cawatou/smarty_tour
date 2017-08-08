<?php

class DxConstant
{
    /**
     * The constants that describe the names of required configuration files
     */
    const CFG_ENV = DxApp::CFG_ENV;
    const CFG_PHP = DxApp::CFG_PHP;
    const CFG_APP = DxApp::CFG_APP;

    /**
     * The constants that describe the names of base sections in required configuration files
     */
    const SECTION_CONTEXT            = DxApp::SECTION_CONTEXT;
    const SECTION_COMPONENTS         = DxApp::SECTION_COMPONENTS;
    const SECTION_COMPONENTS_CORE    = DxApp::SECTION_COMPONENTS_CORE;
    const SECTION_COMPONENTS_PROJECT = DxApp::SECTION_COMPONENTS_PROJECT;
    const SECTION_CFG_DIR            = DxApp::SECTION_CFG_DIR;
    const SECTION_APP_ENV            = DxApp::SECTION_APP_ENV;

    /**
     * The constants that describe "development, staging, production" model in app environment
     */
    const ENV_DEVELOPMENT = DxApp::ENV_DEVELOPMENT;
    const ENV_STAGING     = DxApp::ENV_STAGING;
    const ENV_PRODUCTION  = DxApp::ENV_PRODUCTION;

    /**
     * The constants that describe the aliases of core components
     */
    const ALIAS_EXCEPTION_HANDLER = DxApp::ALIAS_EXCEPTION_HANDLER;
    const ALIAS_URL               = DxApp::ALIAS_URL;
    const ALIAS_AUTHENTICATOR     = DxApp::ALIAS_AUTHENTICATOR;
    const ALIAS_APP_CONTEXT       = DxApp::ALIAS_APP_CONTEXT;
    const ALIAS_COMMAND_HOOK      = DxApp::ALIAS_COMMAND_HOOK;

    /**
     * The constants that describe the possible errors
     */
    const DX_FACTORY_ERROR_BASE                     = DxFactory::DX_FACTORY_ERROR_BASE;
    const DX_FACTORY_ERROR_IMPORT_CLASS             = DxFactory::DX_FACTORY_ERROR_IMPORT_CLASS;
    const DX_APP_ERROR_BASE                         = DxApp::DX_APP_ERROR_BASE;
    const DX_APP_ERROR_CONFIG                       = DxApp::DX_APP_ERROR_CONFIG;
    const DX_APP_ERROR_GET_COMPONENT                = DxApp::DX_APP_ERROR_GET_COMPONENT;
    const DX_APP_ERROR_AUTHORIZATION                = DxApp::DX_APP_ERROR_AUTHORIZATION;
    const DX_APP_ERROR_EXECUTE_COMMAND              = DxApp::DX_APP_ERROR_EXECUTE_COMMAND;
    const DX_COMPONENT_ERROR_BASE                   = DxComponent::DX_COMPONENT_ERROR_BASE;
    const DX_COMPONENT_ERROR_INIT_COMPONENT         = DxComponent::DX_COMPONENT_ERROR_INIT_COMPONENT;
    const DX_APPCONTEXT_ERROR_BASE                  = DxAppContext::DX_APPCONTEXT_ERROR_BASE;
    const DX_COMMAND_ERROR_BASE                     = DxCommand::DX_COMMAND_ERROR_BASE;
    const DX_COMMAND_ERROR_DEFINITION               = DxCommand::DX_COMMAND_ERROR_DEFINITION;
    const DX_CONTROLLER_ERROR_BASE                  = DxController::DX_CONTROLLER_ERROR_BASE;
    const DX_CONTROLLER_ERROR_NO_SUCH_METHOD        = DxController::DX_CONTROLLER_ERROR_NO_SUCH_METHOD;
    const DX_CONTROLLER_INVOCATION_TARGET_EXCEPTION = DxController::DX_CONTROLLER_INVOCATION_TARGET_EXCEPTION;
    const DX_COMMANDHOOK_EVENT_BEFORE               = DxCommandHook::DX_COMMANDHOOK_EVENT_BEFORE;
    const DX_COMMANDHOOK_EVENT_AFTER                = DxCommandHook::DX_COMMANDHOOK_EVENT_AFTER;
    const DX_URL_ERROR_BASE                         = DxURL::DX_URL_ERROR_BASE;
    const DX_URL_ERROR_PROCESS                      = DxURL::DX_URL_ERROR_PROCESS;
    const DX_SESSION_ERROR_BASE                     = DxSession::DX_SESSION_ERROR_BASE;
    const DX_SESSION_ERROR_CONFIG                   = DxSession::DX_SESSION_ERROR_CONFIG;
    const DX_SESSION_ERROR_START                    = DxSession::DX_SESSION_ERROR_START;
    const DX_SESSION_ERROR_PROCESS                  = DxSession::DX_SESSION_ERROR_PROCESS;
}