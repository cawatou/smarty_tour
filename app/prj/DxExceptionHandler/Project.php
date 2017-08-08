<?php

DxFactory::import('DxExceptionHandler');
DxFactory::import('DxConstant_Project');

class DxExceptionHandler_Project extends DxExceptionHandler
{
    /**
     * @param Exception $e
     * @param DxCommand|null $command
     * @return void
     */
    public function handle(Exception $e, DxCommand $command = null)
    {
        /** @var $url DxURL */
        $url = DxApp::getComponent(DxApp::ALIAS_URL);

        /** @var $ctx DxAppContext */
        $ctx = DxApp::getComponent(DxApp::ALIAS_APP_CONTEXT);

        if (DxApp::existComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER)) {
            /** @var $dom DomainObjectManager */
            $dom = DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER);
            $dom->rollback();
        }

        $cause = $e instanceof DxException ? $e->getOriginalCause() : $e;

        if ($cause->getCode() == DxConstant::DX_APP_ERROR_AUTHORIZATION) {
            $command_roles = $ctx->getCurrentCommand()->getRoles();
            if (!in_array(DxConstant_Project::ROLE_ADMIN, $command_roles)) {
                DxURL::redirect($url->url(DxCommand::CMD_AUTH_ERROR, true));
            } else {
                DxURL::redirect($url->adm('', '?access_denied'));
            }
        } elseif ($cause->getCode() == DxConstant::DX_COMMAND_ERROR_DEFINITION) {
            DxURL::redirect($url->url(DxCommand::CMD_NOT_FOUND));
        }

        print $e;
    }
}