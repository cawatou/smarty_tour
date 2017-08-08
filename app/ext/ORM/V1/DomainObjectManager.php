<?php

class DomainObjectManager
{
    /** @var Doctrine_Manager */
    private $dm;

    /** @var Doctrine_Connection */
    private $conn;

    /** @var array */
    private $cfg;

    public function __construct(Doctrine_Manager $dm, Doctrine_Connection $conn, array $cfg)
    {
        $this->dm   = $dm;
        $this->conn = $conn;
        $this->cfg = $cfg;
    }

    /**
     * Get the current connection instance
     *
     * @throws Doctrine_Connection_Exception       if there are no open connections
     * @return Doctrine_Connection
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * beginTransaction
     * Start a transaction or set a savepoint.
     *
     * if trying to set a savepoint and there is no active transaction
     * a new transaction is being started
     *
     * Listeners: onPreTransactionBegin, onTransactionBegin
     *
     * @throws Doctrine_Transaction_Exception   if the transaction fails at database level
     * @return integer                          current transaction nesting level
     */
    public function beginTransaction()
    {
        return $this->getConnection()->beginTransaction();
    }

    /**
     * commit
     * Commit the database changes done during a transaction that is in
     * progress or release a savepoint. This function may only be called when
     * auto-committing is disabled, otherwise it will fail.
     *
     * Listeners: onPreTransactionCommit, onTransactionCommit
     *
     * @throws Doctrine_Transaction_Exception   if the transaction fails at PDO level
     * @throws Doctrine_Validator_Exception     if the transaction fails due to record validations
     * @return boolean                          false if commit couldn't be performed, true otherwise
     */
    public function commit()
    {
        if (!$this->getConnection()->getTransactionLevel()) {
            return false;
        }

        return $this->getConnection()->commit();
    }

    /**
     * rollback
     * Cancel any database changes done during a transaction or since a specific
     * savepoint that is in progress. This function may only be called when
     * auto-committing is disabled, otherwise it will fail. Therefore, a new
     * transaction is implicitly started after canceling the pending changes.
     *
     * this method can be listened with onPreTransactionRollback and onTransactionRollback
     * eventlistener methods
     *
     * @throws Doctrine_Transaction_Exception   if the rollback operation fails at database level
     * @return boolean                          false if rollback couldn't be performed, true otherwise
     */
    public function rollback()
    {
        if (!$this->getConnection()->getTransactionLevel()) {
            return false;
        }

        return $this->getConnection()->rollback();
    }

    /**
     * flush
     * saves all the records from all tables
     * this operation is isolated using a transaction
     *
     * @throws PDOException         if something went wrong at database level
     * @return void
     */
    public function flush()
    {
        $this->getConnection()->flush();
    }

    /**
     * clear
     * clears all repositories
     *
     * @param null $entityName
     * @return void
     */
    public function clear($entityName = null)
    {
        $this->getConnection()->clear();
    }

    /**
     * close
     * closes the connection
     *
     * @return void
     */
    public function close()
    {
        $this->getConnection()->close();
    }

    /**
     * @param DomainObjectModel $entity
     * @return void
     */
    public function persist(DomainObjectModel $entity)
    {
        return;
    }

    /**
     * @param DomainObjectModel $entity
     * @return void
     */
    public function remove(DomainObjectModel $entity)
    {
        $entity->remove();
    }

    /**
     * refresh
     * refresh internal data from the database
     *
     * @param DomainObjectModel $entity
     *
     * @throws Doctrine_Record_Exception        When the refresh operation fails (when the database row
     *                                          this record represents does not exist anymore)
     * @return boolean
     */
    public function refresh(DomainObjectModel $entity)
    {
        $entity->refresh(true);
    }

    /**
     * @param DomainObjectModel $entity
     * @return mixed
     */
    public function detach(DomainObjectModel $entity)
    {
        $entity->getTable()->getRepository()->evictAll();
        $entity->getTable()->clear();
    }

    /**
     * @param DomainObjectModel $entity
     * @return bool
     */
    public function contains(DomainObjectModel $entity)
    {
        return $entity->getTable()->getRepository()->contains($entity->getOid());
    }

    /**
     * Check wherther the connection to the database has been made yet
     *
     * @return boolean
     */
    public function isOpen()
    {
        return $this->conn->isConnected() ? true : false;
    }

    /**
     * @return Doctrine_Manager
     */
    public function getOriginalManager()
    {
        return $this->dm;
    }

    /**
     * @return PDO
     */
    public function getWrappedConnection()
    {
        return $this->getConnection()->getDbh();
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->cfg;
    }

    /**
     * @static
     * @param string $class_name
     */
    public static function autoload($class_name)
    {
        try {
            DxFactory::import($class_name, dirname(__FILE__));
        } catch (DxException $e) {}
    }
}
