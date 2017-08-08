<?php

DxFactory::import('DxSession');

class DxSession_Default extends DxSession
{
    /**
     * @return void
     */
    protected final function start()
    {
        session_start();
        register_shutdown_function('session_write_close');
    }
}