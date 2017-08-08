<?php

DxFactory::import('DxCommandHook');

class DxCommandHook_Default implements DxCommandHook
{
    /**
     * @param DxCommand $command
     * @param DxUser    $user
     * @param           $hook_event_type
     * @return mixed
     */
    public function execute(DxCommand $command, DxUser $user, $hook_event_type)
    {
        return;
    }
}