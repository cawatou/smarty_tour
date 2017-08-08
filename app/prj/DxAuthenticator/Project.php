<?php

DxFactory::import('DxAuthenticator');

class DxAuthenticator_Project extends DxAuthenticator
{
    const AUTH_SESSION_BACKEND_ID  = 'DX_USER_BACKEND_ID';

    /** @var null|DxUser_Project */
    private $user = null;

    /**
     * @param DxCommand $command
     * @return DxUser_Project
     */
    public function getCurrentUser(DxCommand $command)
    {
        if (is_null($this->user)) {
            $auth_key = self::AUTH_SESSION_BACKEND_ID;
            $sess_id = $this->getSessionId($auth_key);
            $this->user = DxFactory::getSingleton('DxUser_Project', array($sess_id));
        }
        return $this->user;
    }

    /**
     * @param int $sess_id
     * @param $auth_key
     * @return void
     */
    public function setSessId($sess_id, $auth_key)
    {
        $_SESSION[$auth_key] = $sess_id;
    }

    /**
     * @param $auth_key
     * @return mixed
     */
    public function getSessionId($auth_key)
    {
        return empty($_SESSION[$auth_key]) ? null : $_SESSION[$auth_key];
    }
}