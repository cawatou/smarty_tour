<?php

DxFactory::import('DxFile');

class DomainObjectGenerator
{
    /** @var DomainObjectManager */
    protected $dom;

    /** @var array */
    protected $cfg = array();

    /** @var array */
    protected $cache = array();

    /** @var string */
    protected $model_tpl =
        '<?php

/**
<doctype>
 */
class DomainObjectModel_<class> extends DomainObjectModel_Base<class>
{
    /** @var string */
    protected $field_prefix = \'<field_prefix>\';
}
';

    /** @var string */
    protected $query_tpl =
        '<?php

class DomainObjectQuery_<class> extends DomainObjectQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_<class>|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select(\'<alias>\')
            ->from(\'DomainObjectModel_<class>\', \'<alias>\')
            ->where(\'<alias>.<primary_key> = ?\');

        return $this->getSingleFound($qb, array($id));
    }
}
';

    /**
     * @param DomainObjectManager $dom
     */
    public function __construct(DomainObjectManager $dom)
    {
        $cfg = $dom->getConfiguration();
        $cfg['generated']['table_prefix'] = $cfg['connection']['prefix'];


        $this->dom = $dom;
        $this->cfg = $cfg['generated'];
    }

    /**
     * @return void
     */
    public function generateDomainObjects()
    {
        if (!is_writable($this->cfg['generated_path'])) {
            throw new DxException("No write permission '{$this->cfg['generated_path']}'");
        }

        $output_path      = $this->cfg['output_path'];
        $models_path      = $this->cfg['models_path'];
        $queries_path     = $this->cfg['queries_path'];
        $table_prefix     = $this->cfg['table_prefix'];
        $generated_path   = $models_path . DS . 'generated' . DS;
        $partial_path     = $models_path . DS . 'partial' . DS;

        $options = array(
            'generateTableClasses' => false,
            'generateBaseClasses'  => true,
            'baseClassName'        => 'DomainObjectModel',
            'classPrefix'          => 'DomainObjectModel_',
            'classPrefixFiles'     => false
        );

        DxFile::removeDir($partial_path);

        DxFile::createDir($output_path, 0777);
        DxFile::createDir($models_path, 0777);
        DxFile::createDir($queries_path, 0777);

        $this->generateModels($output_path, $models_path, $table_prefix, $options);

        foreach (DxFile::readDir($generated_path, true) as $item) {
            $class = preg_replace('~Base|\.php~', '', str_replace($generated_path, '', $item));

            $m_file = $models_path . DS . $class . '.php';
            $q_file = $queries_path . DS . $class . '.php';

            if (!is_file($m_file) || strpos(file_get_contents($m_file), 'method') === false) {
                file_put_contents($m_file, $this->generateModelBody($class, $item));
            }

            if (!is_file($q_file)) {
                file_put_contents($q_file, $this->generateQueryBody($class, $item));
            }
        }

        DxFile::renameDirOrFile($generated_path, $partial_path);

        foreach (array(dirname($models_path), dirname($queries_path)) as $dirname) {
            DxFile::changeMode($dirname, 0777, true);
        }

        DxFile::removeDir($output_path);
        print 'COMPLETED';
    }

    /**
     * @param string $field
     * @return string
     */
    protected function getFieldName($field)
    {
        return substr(strtolower(preg_replace('~([A-Z])~', '_\1', ucfirst($field))), 1);
    }

    /**
     * @param string $name
     * @param bool   $without_prefix
     * @return string
     */
    protected function getClassName($name, $without_prefix = false)
    {
        return str_replace(ucfirst(str_replace('_', '', $this->cfg['table_prefix'])), $without_prefix ? '' : 'Base', $name);
    }

    /**
     * @param string $class
     * @param string $model
     * @return string
     */
    protected function generateModelBody($class, $model)
    {
        $out = str_replace('<class>', $class, $this->model_tpl);
        $out = str_replace('<field_prefix>', $this->getFieldName($class), $out);
        $out = str_replace('<doctype>', $this->getModelDoctype($class, $model), $out);

        return $out;
    }

    /**
     * @param string $class
     * @param string $model
     * @return string
     */
    protected function generateQueryBody($class, $model)
    {
        $alias = strtolower(preg_replace('~[a-z]+~', '', $class));

        $out = str_replace('<class>', $class, $this->query_tpl);
        $out = str_replace('<alias>', $alias, $out);

        $model_body = file_get_contents($model);

        $res = preg_split("~'primary'\s*=>\s*true~", $model_body);
        $m   = array();
        if (count($res) && preg_match("~.*hasColumn\('([^',]+).*~", $res[0], $m)) {
            $out = str_replace('<primary_key>', $m[1], $out);
        }

        return $out;
    }

    protected function getDoctypeMethods($class, $model)
    {
        if (!empty($this->cache[$class]) && !empty($this->cache[$class]['doctype_methods'])) {
            return $this->cache[$class]['doctype_methods'];
        } else {
            $this->cache[$class] = array('doctype_methods' => null);
        }

        $prefix    = $class;
        $prefix{0} = strtolower($prefix{0});
        $prefix    = strtolower(preg_replace('~([A-Z])+~', '_$1', $prefix));

        $m = preg_replace('~^.*?(@property)~', '$1', str_replace("\n", '', file_get_contents($model)));
        $m = preg_replace('~@package.*~', '', $m);

        $getters = array();
        $setters = array();

        $res = preg_split('~\s*\*\s*~', $m);
        foreach ($res as $v) {
            $v = preg_replace('~@property\s*~', '', $v);
            $v = explode(' ', $v);

            if (count($v) == 1) {
                continue;
            }

            $name = str_replace("{$prefix}_", '', str_replace('$', '', $v[1]));
            $name = explode('_', $name);

            foreach ($name as $k => $c) {
                $c{0}     = strtoupper($c{0});
                $name[$k] = $c;
            }

            $name = implode('', $name);

            $set = " set{$name}(%s \$arg)";
            $get = " %s get{$name}";

            $type = null;
            if (strpos($v[0], 'DomainObjectModel') !== false) {
                $type = "DomainObjectModel_{$name}";
            } else {
                switch ($v[0]) {
                    case 'integer' :
                        $type = 'int';
                        break;
                    case 'decimal' :
                        $type = 'float';
                        break;
                    case 'float' :
                        $type = 'float';
                        break;
                    case 'bool' :
                        $type = 'bool';
                        break;
                    case 'string' :
                        $type = 'string';
                        break;
                    case 'enum' :
                        $type = 'string';
                        break;
                    case 'timestamp' :
                        $type = 'DxDateTime';
                        break;
                    case 'date' :
                        $type = 'DxDateTime';
                        break;
                    case 'Doctrine_Collection' :
                        $type = "DomainObjectModel_{$name}[]";
                        $get .= 's';
                        $set = null;
                        break;
                }
            }

            $getters[] = ' * @method ' . sprintf("{$get}()", $type);
            $set ? $setters[] = ' * @method ' . sprintf($set, $type) : false;
        }

        $getters[] = " * \n";

        return $this->cache[$class]['doctype_methods'] = implode("\n", $getters) . implode("\n", $setters);
    }

    /**
     * @param string $class
     * @param string $model
     * @return string
     */
    protected function getModelDoctype($class, $model)
    {
        return $this->getDoctypeMethods($class, $model);
    }

    /**
     * @param string $output_path
     * @param null   $table_prefix
     */
    protected function addClassNames($output_path, $table_prefix = null)
    {
        $temp_file_path = $output_path . '.old';

        rename($output_path, $temp_file_path);

        $temp_file = fopen($temp_file_path, 'r');
        $yaml_file = fopen($output_path, 'w');

        $table_prefix = ucfirst(preg_replace('~(_)+~', '_', $table_prefix));

        while (!feof($temp_file)) {
            $line = fgets($temp_file);

            if (strpos($line, $table_prefix) !== false) {
                $new_line = str_replace($table_prefix, '', $line);
                fwrite($yaml_file, $new_line);
            } else {
                fwrite($yaml_file, $line);
            }
        }

        fclose($temp_file);
        fclose($yaml_file);
        unlink($temp_file_path);
    }

    /**
     * @param string $output_path
     * @param string $models_path
     * @param null   $table_prefix
     * @param array  $options
     */
    protected function generateModels($output_path, $models_path, $table_prefix = null, $options = array())
    {
        $output_path .= DS . 'database.yml';

        Doctrine_Core::generateYamlFromDb($output_path);

        $this->addClassNames($output_path, $table_prefix);

        Doctrine_Core::generateModelsFromYaml($output_path, $models_path, $options);

        unlink($output_path);
    }
}