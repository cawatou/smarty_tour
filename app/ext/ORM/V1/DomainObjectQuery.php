<?php

abstract class DomainObjectQuery
{
    /** @var DomainObjectQueryBuilder */
    protected $cached_qb = null;

    /**
     * @param mixed $id
     * @return DomainObjectModel|null
     */
    abstract public function findById($id);

    /**
     * @return DomainObjectQueryBuilder
     */
    public function getQueryBuilder()
    {
        return new DomainObjectQueryBuilder($this->getDomainObjectManager()->getConnection());
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return Doctrine::getTable(str_replace('Query', 'Model', get_class($this)))->getTableName();
    }

    /**
     * @return void
     */
    public function truncateTable()
    {
        /** @var $dbo DxDBO_PDO */
        $dbo = DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_DBO);
        $dbo->query("TRUNCATE TABLE {$this->getTableName()}");
    }

    /**
     * @param DomainObjectQueryBuilder $qb
     * @param array                    $params
     * @return DomainObjectModel|null
     */
    protected function getSingleFound(DomainObjectQueryBuilder $qb, $params = array())
    {
        if (is_object($o = $qb->fetchOne($params)) && $o instanceof DomainObjectModel) {
            /** @var $o DomainObjectModel */
            return $o;
        }

        return null;
    }

    /**
     * @param DomainObjectQueryBuilder $qb
     * @param array                    $params
     * @return DomainObjectModel[]|array
     */
    protected function &getMultiFound(DomainObjectQueryBuilder $qb, $params = array())
    {
        if (count($result = $qb->execute($params)) && $result instanceof DomainObjectCollection) {
            /** @var $result DomainObjectCollection */
            return $result->getModels();
        } else {
            $result = array();
        }

        return $result;
    }

    /**
     * @param DomainObjectQueryBuilder $qb
     * @param array                    $params
     * @return array
     */
    protected function &getArrayResult(DomainObjectQueryBuilder $qb, $params = array())
    {
        $result = $qb->execute($params, Doctrine_Core::HYDRATE_ARRAY);
        return $result;
    }

    /**
     * @param DomainObjectQueryBuilder $qb
     * @param array                    $params
     * @return array
     */
    protected function &getScalarResult(DomainObjectQueryBuilder $qb, $params = array())
    {
        $result = $qb->execute($params, Doctrine_Core::HYDRATE_SCALAR);
        return $result;
    }

    /**
     * @param DomainObjectQueryBuilder $qb
     * @return int
     */
    protected function getCount(DomainObjectQueryBuilder $qb)
    {
        return $qb->count($qb->getParameters());
    }

    /**
     * @param DomainObjectQueryBuilder $qb
     */
    protected function setCachedQueryBuilder(DomainObjectQueryBuilder $qb)
    {
        $this->cached_qb = $qb;
    }

    /**
     * @param bool $clone
     * @return DomainObjectQueryBuilder|null
     */
    protected function getCachedQueryBuilder($clone = false)
    {
        if (is_null($this->cached_qb)) {
            return null;
        }

        return $clone ? clone $this->cached_qb : $this->cached_qb;
    }

    /**
     * @return DomainObjectManager
     */
    protected function getDomainObjectManager()
    {
        return DxComponent_DomainObjectManager::get();
    }
}