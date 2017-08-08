<?php

class DomainObjectCollection extends Doctrine_Collection
{
    /**
     * @param string $model
     */
    public function __construct($model)
    {
        parent::__construct($model);
    }

    /**
     * @return array|DomainObjectController[]

    public function &getControllers()
    {
        $controllers = array();

        if ($this->count()) {
            ** @var $record DomainObjectModel *
            foreach ($this->data as &$record) {
                $controllers[] = $record->getController();
            }
        }

        return $controllers;
    }
*/
    /**
     * @return array|DomainObjectModel[]
     */
    public function &getModels()
    {
        $models = array();

        if ($this->count()) {
            /** @var $record DomainObjectModel */
            foreach ($this->data as &$record) {
                $models[] = $record;
            }
        }

        return $models;
    }

    public function __destruct()
    {
        $this->clear();
    }
}