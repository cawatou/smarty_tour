<?php

DxFactory::import('DxConstant');
DxFactory::import('DxDateTime');

class DxApp
{
    /**
     * The constants that describe the possible errors
     */
    const DX_APP_ERROR_BASE            = 200;
    const DX_APP_ERROR_CONFIG          = 201;
    const DX_APP_ERROR_CORE_COMPONENT  = 202;
    const DX_APP_ERROR_GET_COMPONENT   = 203;
    const DX_APP_ERROR_AUTHORIZATION   = 204;
    const DX_APP_ERROR_EXECUTE_COMMAND = 205;

    /**
     * The constants that describe the names of required configuration files
     */
    const CFG_ENV = 'env';
    const CFG_PHP = 'php';
    const CFG_APP = 'app';

    /**
     * The constants that describe the names of base sections in required configuration files
     */
    const SECTION_CONTEXT            = 'SECTION_CONTEXT';
    const SECTION_COMPONENTS         = 'SECTION_COMPONENTS';
    const SECTION_COMPONENTS_CORE    = 'SECTION_COMPONENTS_CORE';
    const SECTION_COMPONENTS_PROJECT = 'SECTION_COMPONENTS_PROJECT';
    const SECTION_CFG_DIR            = 'SECTION_CFG_DIR';
    const SECTION_APP_ENV            = 'SECTION_APP_ENV';

    /**
     * The constants that describe "development, staging, production" model in app environment
     */
    const ENV_DEVELOPMENT = 1;
    const ENV_STAGING     = 2;
    const ENV_PRODUCTION  = 3;

    /**
     * The constants that describe the aliases of core components
     */
    const ALIAS_EXCEPTION_HANDLER = 'DX_EXCEPTION_HANDLER';
    const ALIAS_URL               = 'DX_URL';
    const ALIAS_AUTHENTICATOR     = 'DX_AUTHENTICATOR';
    const ALIAS_APP_CONTEXT       = 'DX_APP_CONTEXT';
    const ALIAS_COMMAND_HOOK      = 'DX_COMMAND_HOOK';

    /** @var array */
    private static $configs;

    /** @var object[] */
    private static $components = array();

    /**
     * @static
     * @throws DxException
     */
    public static function bootstrap($cmd = null, $args = array())
    {
        try {
            foreach (array('DS', 'ROOT', 'DX_CORE_DIR', 'DX_PRJ_DIR', 'DX_EXT_DIR', 'DX_CFG_DIR', 'DX_VAR_DIR') as $system_var) {
                if (!defined($system_var)) {
                    throw new DxException("System variable '{$system_var}' is not declared", self::DX_APP_ERROR_BASE);
                }
            }

            foreach ((array)self::config(self::CFG_PHP) as $key => $val) {
                ini_set($key, $val);
            }

            self::initCoreComponents();

            /** @var $eh DxExceptionHandler */
            $eh = self::getComponent(self::ALIAS_EXCEPTION_HANDLER);

            /** @var $url DxURL */
            $url = self::getComponent(self::ALIAS_URL);

            /** @var $auth DxAuthenticator */
            $auth = self::getComponent(self::ALIAS_AUTHENTICATOR);

            /** @var $ctx DxAppContext */
            $ctx = self::getComponent(self::ALIAS_APP_CONTEXT);

            /** @var $command DxCommand */
            if (null === $cmd) {
                $command = $url->getRequestedCommand();
            } else {
                $command = DxFactory::getSingleton('DxCommand', array($cmd, $args));
            }

            self::initProjectComponents($command);

            /** @var $user DxUser */
            $user = $auth->getCurrentUser($command);

            /** @var $hook DxCommandHook|null */
            $hook = self::existComponent(self::ALIAS_COMMAND_HOOK) ? self::getComponent(self::ALIAS_COMMAND_HOOK) : null;

            $ctx->setCurrentCommand($command);
            $ctx->setCurrentUser($user);

            if (!$command->isExecutableByUser($user)) {
                throw new DxException('Access denied', self::DX_APP_ERROR_AUTHORIZATION);
            }

            $response = DxFactory::getInstance($command->getControllerClass(), array($ctx, $hook))->execute();

            self::response($response, $ctx);
        } catch (Exception $e) {
            if (isset($eh) && $eh instanceof DxExceptionHandler) {
                $eh->handle($e, isset($command) ? $command : null);
            } else {
                print $e;
            }
        }
    }

    /**
     * @static
     * @param mixed             $response
     * @param DxAppContext|null $ctx
     */
    public static function response(&$response, DxAppContext $ctx = null)
    {
        if (!is_null($ctx)) {
            $ctx->sendHeaders();
        }

        print $response;
        self::terminate();
    }

    /**
     * @static
     * @return bool
     */
    public static function isCli()
    {
        return PHP_SAPI === 'cli';
    }

    /**
     * @static
     * @return void
     */
    public static function terminate()
    {
        exit();
    }

    /**
     * @static
     * @param string      $name
     * @param null|string $section
     * @param bool        $check_section
     * @return mixed
     * @throws DxException
     */
    public static function config($name, $section = null, $check_section = false)
    {
        if (empty(self::$configs[$name])) {
            $cfg_dir = ($name != self::CFG_ENV) ? self::config(self::CFG_ENV, self::SECTION_CFG_DIR) : null;

            if (!empty($cfg_dir) && is_readable($config_path = DX_CFG_DIR . DS . $cfg_dir . DS . $name . '.cfg.php')) {
                self::$configs[$name] = include_once $config_path;
            } elseif (is_readable($config_path = DX_CFG_DIR . DS . $name . '.cfg.php')) {
                self::$configs[$name] = include_once $config_path;
            } else {
                throw new DxException("Configuration file '{$name}.cfg.php' can not to read", self::DX_APP_ERROR_CONFIG);
            }
        }

        if (is_null($section)) {
            return self::$configs[$name];
        } else {
            if (isset(self::$configs[$name][$section])) {
                return self::$configs[$name][$section];
            } elseif ($check_section) {
                throw new DxException("Unknown section '{$section}' in '{$name}.cfg.php'", self::DX_APP_ERROR_CONFIG);
            }
        }

        return null;
    }

    /**
     * @static
     * @param string $alias
     * @param bool   $clone
     * @return object
     * @throws DxException
     */
    public static function getComponent($alias, $clone = false)
    {
        if (!array_key_exists($alias, self::$components)) {
            throw new DxException("Component '{$alias}' doesn't exist in components array", self::DX_APP_ERROR_GET_COMPONENT);
        }

        if ($clone) {
            return clone(self::$components[$alias]);
        }

        return self::$components[$alias];
    }

    /**
     * @static
     * @return int
     * @throws DxException
     */
    public static function getEnv()
    {
        if (!in_array(self::config(self::CFG_ENV, self::SECTION_APP_ENV), array(self::ENV_DEVELOPMENT, self::ENV_STAGING, self::ENV_PRODUCTION))) {
            throw new DxException('Unknown app environment', self::DX_APP_ERROR_CONFIG);
        }

        return self::config(self::CFG_ENV, self::SECTION_APP_ENV);
    }

    /**
     * @static
     * @param string $alias
     * @return bool
     */
    public static function existComponent($alias)
    {
        return array_key_exists($alias, self::$components);
    }

    /**
     * @static
     * @return void
     */
    private static function initCoreComponents()
    {
        $cfg = self::config(self::CFG_APP, self::SECTION_COMPONENTS, true);
        foreach ((array)$cfg[self::SECTION_COMPONENTS_CORE] as $alias => $class) {
            $type  = str_replace(' ', '', ucwords(str_replace('_', ' ', $alias)));
            $const = str_replace('DX', 'ALIAS', $alias);

            if (!self::existComponent($alias)
                && !is_null(constant("DxApp::{$const}"))
                && is_object($o = DxFactory::invoke($class, 'getComponent'))
                && $o instanceof $type
            ) {
                self::$components[$alias] = $o;
            } else {
                throw new DxException("Invalid core components definition. Error in '{$alias}'", self::DX_APP_ERROR_CORE_COMPONENT);
            }
        }
    }

    /**
     * @static
     * @param DxCommand $command
     * @return void
     */
    private static function initProjectComponents(DxCommand $command)
    {
        $cfg = self::config(self::CFG_APP, self::SECTION_COMPONENTS, true);
        foreach ((array)$cfg[self::SECTION_COMPONENTS_PROJECT] as $alias => $v) {
            $class    = is_array($v) ? $v['class'] : $v;
            $ext_path = is_array($v) && !empty($v['ext_path']) ? $v['ext_path'] : null;

            if (!self::existComponent($alias) && !in_array($alias, $command->getDisabledComponents())) {
                if (is_object($o = DxFactory::invoke($class, 'getComponent', array(), $ext_path))) {
                    self::$components[$alias] = $o;
                }
            }
        }
    }
}