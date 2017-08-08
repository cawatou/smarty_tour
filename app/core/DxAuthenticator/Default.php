<?php

DxFactory::import('DxAuthenticator');

class DxAuthenticator_Default extends DxAuthenticator
{
    /**
     * @param DxCommand $command
     * @return DxUser_Default
     */
    public function getCurrentUser(DxCommand $command)
    {
        return DxFactory::getSingleton('DxUser_Default');
    }
}