<?php

DxFactory::import('DxUser');

class DxUser_Default extends DxUser
{
    /**
     * @param array $roles
     * @return bool
     */
    public function isUserInRoles($roles = array())
    {
        if (empty($roles)) {
            return true;
        }

        return count(array_intersect($roles, $this->getRoles())) ? true : false;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
}