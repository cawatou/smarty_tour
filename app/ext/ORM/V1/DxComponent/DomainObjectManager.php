<?php

DxFactory::import('DxComponent');

class DxComponent_DomainObjectManager extends DxComponent
{
    /** @var DomainObjectManager[] */
    protected static $managers = array();

    /**
     * @static
     * @param array $params
     * @return Doctrine_Manager
     * @throws DxException
     */
    public static function getComponent(array $params = array())
    {
        try {
            $default = array(
                'connection' => array(
                    'protocol' => 'mysql',
                    'hostname' => 'localhost',
                    'port'     => 3306,
                    'database' => null,
                    'username' => 'root',
                    'password' => null,
                    'prefix'   => null,
                    'charset'  => 'utf8'
                ),

                'entity'     => array(
                    'dir'       => null,
                    'namespace' => null
                ),

                'cache'      => array(
                    'implrmentation' => null,
                    'query_cache'    => 0,
                    'result_cache'   => 0
                ),

                'generated'  => array(
                    'output_path'      => null,
                    'models_path'      => null,
                    'controllers_path' => null,
                    'queries_path'     => null
                ),
            );

            $params = (array)DxApp::config('doctrine');

            foreach ($default as $k1 => $v1) {
                if (!isset($params[$k1])) {
                    $params[$k1] = array();
                }

                foreach ($v1 as $k2 => $v2) {
                    if (!isset($params[$k1][$k2])) {
                        $params[$k1][$k2] = $v2;
                    }
                }
            }

            DxFactory::import('Doctrine', DX_EXT_DIR . DS . 'Doctrine');
            DxFactory::import('DomainObjectManager', str_replace(DS . 'DxComponent', '', dirname(__FILE__)));

            spl_autoload_register(array('Doctrine', 'autoload'));
            spl_autoload_register(array('Doctrine', 'modelsAutoload'));
            spl_autoload_register(array('DomainObjectManager', 'autoload'));

            $manager = Doctrine_Manager::getInstance();

            $dsn  = "{$params['connection']['protocol']}:host={$params['connection']['hostname']}; dbname={$params['connection']['database']}; port={$params['connection']['port']}";
            $conn = $manager->openConnection(array($dsn, $params['connection']['username'], $params['connection']['password']), 'main');
            $conn->setOption('username', $params['connection']['username']);
            $conn->setOption('password', $params['connection']['password']);
            $conn->setCharset($params['connection']['charset']);

            $manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
            $manager->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
            $manager->setAttribute(Doctrine_Core::ATTR_HYDRATE_OVERWRITE, false);
            $manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);
            $manager->setAttribute(Doctrine_Core::ATTR_AUTO_FREE_QUERY_OBJECTS, true);
            $manager->setAttribute(Doctrine_Core::ATTR_COLLECTION_CLASS, 'DomainObjectCollection');

            if (!empty($params['cache']['implrmentation'])) {
                $cache = new $params['cache']['implrmentation'];

                if (!empty($params['cache']['query_cache'])) {
                    $manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cache);
                    $manager->setAttribute(Doctrine::ATTR_QUERY_CACHE_LIFESPAN, 3600);
                }

                if (!empty($params['cache']['result_cache'])) {
                    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cache);
                    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE_LIFESPAN, 3600);
                }
            }

            Doctrine::loadModels($params['entity']['dir'], null, 'DomainObjectModel_');

            return self::$managers['main'] = new DomainObjectManager($manager, $conn, $params);
        } catch (Exception $e) {
            throw new DxException('Error occured while init DomainObjectManager component', self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }

    /**
     * @static
     * @param null|string $alias
     * @return DomainObjectManager
     */
    public static function get($alias = null)
    {
        return (!is_null($alias) && array_key_exists($alias, self::$managers)) ? self::$managers[$alias] : reset(self::$managers);
    }

    /**
     * @static
     * @param string              $alias
     * @param DomainObjectManager $o
     */
    public static function set($alias, DomainObjectManager $o)
    {
        self::$managers[$alias] = $o;
    }
}