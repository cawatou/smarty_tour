<?php

class DxACL
{
    const CAN_CREATE = 1;  // 0001
    const CAN_VIEW   = 2;  // 0010
    const CAN_EDIT   = 4;  // 0100
    const CAN_DELETE = 8;  // 1000
    const CAN_NOT    = 0;  // 0000
    const CAN_ALL    = 15; // 0001 | 0010 | 0100 | 1000

    /**
     * @static
     * @param $command
     * @param $user
     * @return bool
     */
    public static function canNot($command, $user)
    {
        $user_perm = self::getPermission($command, $user);
        return self::can($user_perm, (self::CAN_NOT));
    }

    /**
     * @static
     * @param $command
     * @param $user
     * @return bool
     */
    public static function canAnything($command, $user)
    {
        $user_perm = self::getPermission($command, $user);
        return self::can($user_perm, (self::CAN_CREATE | self::CAN_VIEW | self::CAN_EDIT | self::CAN_DELETE));
    }

    /**
     * @static
     * @param $command
     * @param $user
     * @return bool
     */
    public static function canCreate($command, $user)
    {
        $user_perm = self::getPermission($command, $user);
        return self::can($user_perm, self::CAN_CREATE);
    }

    /**
     * @static
     * @param $command
     * @param $user
     * @return bool
     */
    public static function canView($command, $user)
    {
        $user_perm = self::getPermission($command, $user);
        return self::can($user_perm, self::CAN_VIEW);
    }

    /**
     * @static
     * @param $command
     * @param $user
     * @return bool
     */
    public static function canEdit($command, $user) 
    {
        $user_perm = self::getPermission($command, $user);
        return self::can($user_perm, self::CAN_EDIT);
    }

    /**
     * @static
     * @param $command
     * @param $user
     * @return bool
     */
    public static function canDelete($command, $user)
    {
        $user_perm = self::getPermission($command, $user);
        return self::can($user_perm, self::CAN_DELETE);
    }

    /**
     * @static
     * @param $user_perm
     * @param $mask
     * @return bool
     */
    public static function can($user_perm, $mask)
    {
        if ($user_perm & $mask) {
            return true;
        }
        return false;
    }

    /**
     * @static
     * @param $command
     * @param $user
     * @return int
     * @throws DxException
     */
    protected static function getPermission($command, $user) 
    {    
        $cmd = $command;
        if (is_object($command)) {
            if ($command instanceof DxCommand) {
                $cmd = $command->getCmd();
            } else {
                throw new DxException('Wrong type of object'); 
            }
        }

        $acl = DxApp::config('acl', $cmd);
        
        if (empty($acl)) {
            return self::CAN_ALL;
        }

        $role = $user;
        if (is_object($user)) {
            if ($user instanceof DxUser) {
                $role = $user->getRole();
            } else {
                throw new DxException('Wrong type of object');
            }
        }

        return isset($acl[$role]) ? $acl[$role] : self::CAN_NOT;
    }
}