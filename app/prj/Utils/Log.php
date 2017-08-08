<?php

class Utils_Log
{
    /**
     * @static
     * @param $message
     * @param null $file
     * @param bool $timestamp
     * @return bool
     */
    public static function put($message, $file = null, $timestamp = true)
    {
        $file = is_null($file) ? date('Ymd') . '.log' : $file;
        $dir = DX_VAR_DIR . DS . 'log';
        DxFactory::import('DxFile');
        DxFile::createDir($dir);

        $message .= PHP_EOL;
        if ($timestamp) {
            $message = date('Y-m-d H:i:s -> ') . $message;
        }
        return error_log($message, 3, $dir . DS . $file);
    }
}