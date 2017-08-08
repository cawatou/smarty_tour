<?php
abstract class DxUser
{
    /** @var null */
    protected $roles = null;

    /**
     * @abstract
     * @param array $roles
     * @return bool
     */
    public abstract function isUserInRoles($roles = array());

    /**
     * @param $role
     * @return void
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return array
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param $command
     * @return bool
     */
    public function canNot($command)
    {
        return DxACL::canNot($command, $this);
    }

    /**
     * @param $command
     * @return bool
     */
    public function canCreate($command)
    {
        return DxACL::canCreate($command, $this);
    }

    /**
     * @param $command
     * @return bool
     */
    public function canView($command)
    {
        return DxACL::canView($command, $this);
    }

    /**
     * @param $command
     * @return bool
     */
    public function canEdit($command)
    {
        return DxACL::canEdit($command, $this);
    }

    /**
     * @param $command
     * @return bool
     */
    public function canDelete($command)
    {
        return DxACL::canDelete($command, $this);
    }
}