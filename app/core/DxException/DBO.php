<?php

DxFactory::import('DxException');

class DxException_DBO extends DxException
{
    /**
     * @param string $message
     * @param int $code
     * @param PDOStatement|PDOException $e
     */
    public function __construct($message, $code, $e)
    {
        if ($e instanceof PDOStatement) {
            $error = $e->errorInfo();
            $e = new PDOException($error[2], $error[1]);
        }

        parent::__construct($message, $code, $e);
    }
}