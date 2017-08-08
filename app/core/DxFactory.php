<?php

class DxFactory
{
    const DX_FACTORY_ERROR_BASE         = 100;
    const DX_FACTORY_ERROR_IMPORT_CLASS = 101;

    /** @var array */
    private static $instances = array();

    /**
     * Creates a new object instance
     *
     * This method creates a new object instance from from the passed $class_name
     * and $arguments. The second param $arguments is optional.
     *
     * @static
     * @param  string        $class_name simple class name or package name with class name separated by dot to instantiate
     * @param  array         $arguments  arguments required by $class_name's constructor
     * @param null|string    $ext_path
     * @return object instance of $class_name
     * @throws DxException
     */
    public static final function getInstance($class_name, array $arguments = array(), $ext_path = null)
    {
        self::import($class_name);

        $rc = new ReflectionClass($class_name);

        if (empty($arguments)) {
            return $rc->newInstance();
        } else {
            return $rc->newInstanceArgs($arguments);
        }
    }

    /**
     * @static
     * @param string|null $class_name
     * @param array       $arguments
     * @param null        $ext_path
     * @return array
     */
    public static final function getSingleton($class_name, array $arguments = array(), $ext_path = null)
    {
        if (!empty(self::$instances) && array_key_exists($class_name, self::$instances)) {
            return self::$instances[$class_name];
        }

        return self::$instances[$class_name] = self::getInstance($class_name, $arguments, $ext_path);
    }

    /**
     * @static
     * @param string|object $class
     * @param string        $method_name
     * @param array         $arguments
     * @param null|string   $ext_path
     * @return mixed
     */
    public static final function invoke($class, $method_name, array $arguments = array(), $ext_path = null)
    {
        $c = is_object($class) ? $class : self::import($class, $ext_path);

        if (is_object($c)) {
            switch (count($arguments)) {
                case 0:
                    return $c->$method_name();
                case 1:
                    return $c->$method_name($arguments[0]);
                case 2:
                    return $c->$method_name($arguments[0], $arguments[1]);
                case 3:
                    return $c->$method_name($arguments[0], $arguments[1], $arguments[2]);
                case 4:
                    return $c->$method_name($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
                case 5:
                    return $c->$method_name($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4]);
                default:
                    return call_user_func_array(array($c, $method_name), $arguments);
            }
        } else {
            if (strpos(phpversion(), '5.3') === false) {
                return call_user_func_array(array($c, $method_name), $arguments);
            } else {
                switch (count($arguments)) {
                    case 0:
                        return $c::$method_name();
                    case 1:
                        return $c::$method_name($arguments[0]);
                    case 2:
                        return $c::$method_name($arguments[0], $arguments[1]);
                    case 3:
                        return $c::$method_name($arguments[0], $arguments[1], $arguments[2]);
                    case 4:
                        return $c::$method_name($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
                    case 5:
                        return $c::$method_name($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4]);
                    default:
                        return call_user_func_array(array($c, $method_name), $arguments);
                }
            }
        }
    }

    /**
     * @static
     * @param string      $class
     * @param null|string $ext_path
     * @return mixed
     * @throws DxException
     */
    public static final function import($class, $ext_path = null)
    {
        if (class_exists($class) || interface_exists($class)) {
            return $class;
        }

        $file = str_replace('_', DS, $class) . '.php';

        if (!is_null($ext_path) &&
            is_readable($ext_path . DS . $file) && (include_once $ext_path . DS . $file) === false ||
            is_readable(DX_PRJ_DIR . DS . $file) && (include_once DX_PRJ_DIR . DS . $file) === false ||
            is_readable(DX_CORE_DIR . DS . $file) && (include_once DX_CORE_DIR . DS . $file) === false
        ) {
            throw new DxException("Can not include source file '{$file}'", self::DX_FACTORY_ERROR_IMPORT_CLASS);
        }

        if (!class_exists($class) && !interface_exists($class)) {
            throw new DxException("Class or interface '{$class}' does not exist", self::DX_FACTORY_ERROR_IMPORT_CLASS);
        }

        return $class;
    }
}