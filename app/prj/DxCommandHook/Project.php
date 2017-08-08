<?php

DxFactory::import('DxCommandHook');

class DxCommandHook_Project implements DxCommandHook
{
    /**
     * @param DxCommand $command
     * @param DxUser    $user
     * @param           $hook_event_type
     * @return mixed
     */
    public function execute(DxCommand $command, DxUser $user, $hook_event_type)
    {
        if ($hook_event_type == DxCommandHook::DX_COMMANDHOOK_EVENT_BEFORE) {
            if (DxApp::existComponent(DxConstant_Project::ALIAS_I18N)) {
                /** @var $i18n I18n_Project */
                $i18n = DxApp::getComponent(DxConstant_Project::ALIAS_I18N);
                $i18n->setSupported(array('ru-RU', 'en-US'));
                if (strpos($command->getControllerClass(), 'DxController_Backend') !== false) {
                    // backend i18n settings
                    $i18n->setSource('ru-RU');
                    $i18n->setTarget('ru-RU');
                    if (!is_null($i18n->getBackendLocale())) {
                        $i18n->setTarget($i18n->getBackendLocale());
                    }
                } else {
                    // frontend i18n settings
                    /** @var DxAppContext_Project $ctx */
                    $ctx = DxApp::getComponent(DxApp::ALIAS_APP_CONTEXT);
                    $ctx->defineCity();

                    $i18n->setSource('ru-RU');
                    $i18n->setTarget('ru-RU');
                }
            }
        }
    }
}