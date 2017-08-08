<?php

DxFactory::import('DxExceptionHandler');

class DxExceptionHandler_Default extends DxExceptionHandler
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

        $cause = $e instanceof DxException ? $e->getOriginalCause() : $e;

        if ($cause->getCode() == DxConstant::DX_APP_ERROR_AUTHORIZATION) {
            DxURL::redirect($url->url(DxCommand::CMD_AUTH_ERROR));
        } elseif ($cause->getCode() == DxConstant::DX_COMMAND_ERROR_DEFINITION) {
            DxURL::redirect($url->url(DxCommand::CMD_NOT_FOUND));
        }

        print $e;
    }
}