<?php

interface DxCommandHook
{
    const DX_COMMANDHOOK_EVENT_BEFORE = 0;
    const DX_COMMANDHOOK_EVENT_AFTER  = 1;

    /**
     * @abstract
     * @param DxCommand $command
     * @param DxUser    $user
     * @param int       $hook_event_type
     * @return void
     */
    public function execute(DxCommand $command, DxUser $user, $hook_event_type);
}

?>