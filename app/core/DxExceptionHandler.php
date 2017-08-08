<?php

DxFactory::import('DxCommand');

abstract class DxExceptionHandler
{
    /**
     * @abstract
     * @param Exception $e
     * @param DxCommand|null $command
     * @return void
     */
    public abstract function handle(Exception $e, DxCommand $command = null);
}