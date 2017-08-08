<?php

DxFactory::import('DxUser');

abstract class DxAuthenticator
{
    /**
     * @abstract
     * @param \DxCommand $command
     * @return DxUser
     */
    public abstract function getCurrentUser(DxCommand $command);
}