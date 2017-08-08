<?php

class DxCommand
{
    const DX_COMMAND_ERROR_BASE       = 500;
    const DX_COMMAND_ERROR_DEFINITION = 501;

    const CMD_DEFAULT    = '.default';
    const CMD_NOT_FOUND  = '.not_found';
    const CMD_AUTH_ERROR = '.auth_error';

    const CFG_COMMANDS = 'commands';

    /** @var null|string */
    protected $cmd;

    /** @var array */
    protected $args = array();

    /** @var array */
    protected $data = array();

    /**
     * @param null|string $cmd
     * @param null|array  $args
     */
    public function __construct($cmd = null, $args = null)
    {
        $args = !empty($args) ? $args : array();
        $data = array();

        if (empty($cmd)) {
            $cmd = empty($args['request']) ? self::CMD_DEFAULT : null;
        }

        if (!empty($cmd)) {
            $data = DxApp::config(self::CFG_COMMANDS, $cmd);
        }

        if (!empty($data)) {
            $this->setCmd($cmd);
        } else {
            if (!is_null($data = DxApp::config(self::CFG_COMMANDS, self::CMD_NOT_FOUND))) {
                $this->setCmd(self::CMD_NOT_FOUND);
            } else {
                throw new DxException("Unknown command definition for cmd '{$cmd}'", self::DX_COMMAND_ERROR_DEFINITION);
            }
        }

        $this->setArguments($args);
        $this->setData($data);
    }

    /**
     * @param DxUser|null $user
     * @return bool
     */
    public function isExecutableByUser(DxUser $user = null)
    {
        if (!count($this->getRoles())) {
            return true;
        }

        if (is_null($user) || !$user->isUserInRoles($this->getRoles())) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     * @throws DxException
     */
    public function getControllerClass()
    {
        if (!isset($this->data['controller'])) {
            throw new DxException("Unknown controller class in command definition for cmd '{$this->cmd}'", self::DX_COMMAND_ERROR_DEFINITION);
        }

        return $this->data['controller'];
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return isset($this->data['roles']) && is_array($this->data['roles']) ? $this->data['roles'] : array();
    }

    /**
     * @return array
     */
    public function getDisabledComponents()
    {
        return isset($this->data['disabled_components']) ? $this->data['disabled_components'] : array();
    }

    /**
     * @return null|string
     */
    public function getCmd()
    {
        return $this->cmd;
    }

    /**
     * @param null $key
     * @param null $default
     * @return array
     */
    public function getArguments($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->args;
        }
        return empty($this->args[$key]) ? $default : $this->args[$key];
    }

    /**
     * @param null $key
     * @param null $default
     * @return array
     */
    public function getData($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->data;
        }
        return empty($this->data[$key]) ? $default : $this->data[$key];
    }

    /**
     * @param array $data
     * @return void
     */
    protected function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $cmd
     */
    protected function setCmd($cmd)
    {
        $this->cmd = $cmd;
    }

    /**
     * @param array $args
     */
    protected function setArguments(array $args)
    {
        $this->args = $args;
    }
}