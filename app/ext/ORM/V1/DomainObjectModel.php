<?php

class DomainObjectModel extends Doctrine_Record
{
    const DOMAIN_OBJECT_MODEL_ERROR_BASE         = 1000;
    const DOMAIN_OBJECT_MODEL_ERROR_CALLBACK     = 1001;
    const DOMAIN_OBJECT_MODEL_ERROR_VALIDATE     = 1002;
    const DOMAIN_OBJECT_MODEL_ERROR_FIELD        = 1003;
    const DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT = 1004;

    /** @var string */
    protected $field_prefix = '';

    /** @var bool */
    protected $is_removed = false;

    /**
     * @var array|null
     */
    protected $annotated_methods = null;

    /**
     * @param string $name
     * @param array  $arguments
     * @return mixed|void
     */
    public function __call($name, $arguments = array())
    {
        if (preg_match('~set[A-Z]\w+~', $name)) {
            $field = preg_replace('~([A-Z])~', '_\1', substr($name, 3));
            $field = substr(strtolower($field), 1);

            if (!array_key_exists($field, $this->getAllFieldsValues())) {
                $field = $this->getFieldPrefix() ? $this->getFieldPrefix() . '_' . $field : $field;
            }

            return $this->setFieldValue($field, !empty($arguments) ? $arguments[0] : null);
        } elseif (preg_match('~get[A-Z]\w+~', $name)) {
            $field = preg_replace('~([A-Z])~', '_\1', substr($name, 3));
            $field = substr(strtolower($field), 1);

            if (!array_key_exists($field, $this->getAllFieldsValues())) {
                $field = $this->getFieldPrefix() ? $this->getFieldPrefix() . '_' . $field : $field;
            }

            return $this->getFieldValue($field);
        } else {
            return parent::__call($name, $arguments);
        }
    }

    /**
     * @return void
     */
    public function construct()
    {
        if ($this->getId()) {
            $this->validateField();
        }
    }

    /**
     * @return void
     */
    public function validateFields()
    {
        $this->validateField();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        if (($id = current($this->identifier())) === false) {
            return null;
        } else {
            return $id;
        }
    }

    /**
     * @return bool
     */
    public function isRemoved()
    {
        return $this->is_removed;
    }

    /**
     * @param $offset
     * @return bool
     */
    public function remove($offset = null)
    {
        if ($this->isRemoved()) {
            return false;
        }

        $this->_pendingDeletes[] = $this;
        $this->markRemoved();

        return true;
    }

    /**
     * @param array $skip_fields
     * @return array
     */
    public function getAllFieldsValues(array $skip_fields = array())
    {
        $fields_values = array();

        foreach ($this->getData() as $field => $value) {
            if (in_array($field, $skip_fields)) {
                continue;
            }

            $fields_values[$field] = $this->getFieldValue($field);
        }

        return $fields_values;
    }

    /**
     * @return DomainObjectQuery
     */
    public function getQuery()
    {
        return DxFactory::getInstance(str_replace('DomainObjectModel', 'DomainObjectQuery', get_class($this)));
    }

    /**
     * @param string $field
     * @param mixed  $value
     */
    public function setFieldValue($field, $value)
    {
        $this->$field = $value;
        $this->validateField($field);
    }

    /**
     * @param string $field
     * @param bool   $without_accessor
     * @return mixed
     */
    public function getFieldValue($field, $without_accessor = false)
    {
        if ($without_accessor) {
            return $this->_get($field);
        }

        return $this->$field;
    }

    /**
     * @return string
     */
    public function getFieldPrefix()
    {
        return $this->field_prefix;
    }

    /**
     * @param Doctrine_Event $event
     */
    public function preSave($event)
    {
        if ($this->isRemoved()) {
            $this->state(self::STATE_CLEAN);
        }
    }

    /**
     * @param Doctrine_Event $event
     */
    public function preInsert($event)
    {
        if ($this->isRemoved()) {
            throw new DxException("Can't insert removed model", self::DOMAIN_OBJECT_MODEL_ERROR_CALLBACK);
        }

        $this->setCreated();
        $this->setUpdated();
    }

    /**
     * @param Doctrine_Event $event
     */
    public function preUpdate($event)
    {
        if ($this->isRemoved()) {
            throw new DxException("Can't update removed model", self::DOMAIN_OBJECT_MODEL_ERROR_CALLBACK);
        }

        $this->setUpdated();
    }

    /**
     * @param Doctrine_Event $event
     */
    public function preDelete($event)
    {
        $this->markRemoved();
    }

    /**
     * @param Doctrine_Event $event
     */
    public function postInsert($event)
    {
        if ($this->isRemoved()) {
            throw new DxException("Can't insert removed model", self::DOMAIN_OBJECT_MODEL_ERROR_CALLBACK);
        }
    }

    /**
     * @param Doctrine_Event $event
     */
    public function postUpdate($event)
    {
        if ($this->isRemoved()) {
            throw new DxException("Can't update removed model", self::DOMAIN_OBJECT_MODEL_ERROR_CALLBACK);
        }
    }

    /**
     * @return void
     */
    public function setUp()
    {
        $this->hasAccessor('created', 'getCreated');
        $this->hasAccessor('updated', 'getUpdated');
    }

    /**
     * @return DxDateTime
     */
    public function getCreated()
    {
        return new DxDateTime($this->getFieldValue('created', true));
    }

    /**
     * @return DxDateTime
     */
    public function getUpdated()
    {
        return new DxDateTime($this->getFieldValue('updated', true));
    }

    /**
     * @return void
     */
    protected function setCreated()
    {
        $dt = new DxDateTime();
        $this->setFieldValue('created', $dt->toUTC()->getMySQLDateTime());
    }

    /**
     * @return void
     */
    protected function setUpdated()
    {
        $dt = new DxDateTime();
        $this->setFieldValue('updated', $dt->toUTC()->getMySQLDateTime());
    }

    /**
     * @return void
     */
    protected function markRemoved()
    {
        $this->is_removed = true;
    }

    /**
     * @param mixed $field
     */
    protected function validateField($field = null)
    {
    }

    /**
     * @param string $method
     * @return bool
     */
    public function isMethodExists($method)
    {
        if (method_exists($this, $method) || in_array($method, $this->getAnnotatedMethods(get_class($this)))) {
            return true;
        }

        return false;
    }

    /**
     * @param string $class
     * @return array
     */
    public function getAnnotatedMethods($class)
    {
        if (is_null($this->annotated_methods)) {
            $this->annotated_methods = array();

            $r = new ReflectionClass($class);

            do {
                $m = preg_replace('~^.*?(@method)~', '$1', str_replace("\n", '', $r->getDocComment()));
                $m = preg_replace('~(@method.*\)).*$~', '$1', $m);
                $m = preg_replace('~\([^\(\)]+\)~', '()', $m);
                $m = preg_replace('~\*|@method\s+.*?\s*([a-zA-Z]+\(\))~', '$1', $m);
                $m = preg_split('~\(\)\s*~', $m);

                $this->annotated_methods = array_unique(array_merge($this->annotated_methods, (array)$m));
            } while (($r = $r->getParentClass()) !== false);

            $this->annotated_methods = array_reverse($this->annotated_methods);
        }

        return $this->annotated_methods;
    }


    /**
     * @return void


    public function destruct()
    {
        if (!is_null($this->controller)) {
            $c = $this->controller;
            $this->controller = null;
            $c->destruct();
            $this->free(true);
        }
    }
     *  */
}