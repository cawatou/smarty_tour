<?php

DxFactory::import('DxSession');

class DxSession_DB extends DxSession
{
    /** @var DxDBO */
    private $db = null;

    /**
     * @param array $params
     * @return DxSession_DB
     */
    public function __construct(array $params)
    {
        $this->db = $params['db'];
        parent::__construct($params);
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        unset($this->db);
    }

    /**
     * @return void
     * @throws DxException
     */
    protected final function start()
    {
        $res = session_set_save_handler(
            array($this, 'save_handler_open'),
            array($this, 'save_handler_close'),
            array($this, 'save_handler_read'),
            array($this, 'save_handler_write'),
            array($this, 'save_handler_destroy'),
            array($this, 'save_handler_gc')
        );

        if ($res === false) {
            throw new DxException('\'session_set_save_handler\' failed', self::DX_SESSION_ERROR_START);
        }

        session_start();
        register_shutdown_function('session_write_close');
    }

    /**
     * @param string $save_path
     * @param string $session_name
     * @return bool
     * @throws DxException
     */
    public function save_handler_open($save_path, $session_name)
    {
        try {
            $tables = $this->db->getCol('SHOW TABLES', 0);
            if (!in_array("{$this->db->getPrefix()}_SESSION", $tables)) {
                $sql = "CREATE TABLE `?_SESSION` (`id` CHAR(32) NOT NULL, updated DATETIME NOT NULL, `data` MEDIUMTEXT NOT NULL, PRIMARY KEY (`id`)) DEFAULT CHARSET = utf8";
                $this->db->query($sql);
            }
        } catch (Exception $e) {
            throw new DxException("Error in 'open' session handler", $e, self::DX_SESSION_ERROR_PROCESS);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function save_handler_close()
    {
        return true;
    }

    /**
     * @param string $id
     * @return null|string
     */
    public function save_handler_read($id)
    {
        $sql = "SELECT `data` FROM `?_SESSION` WHERE `id` = ? AND UNIX_TIMESTAMP(UTC_TIMESTAMP()) - UNIX_TIMESTAMP(`updated`) <= ?";
        return $this->db->getOne($sql, array($id, $this->timeout));
    }

    /**
     * @param string $id
     * @param string $session_data
     * @return bool
     */
    public function save_handler_write($id, $session_data)
    {
        $sql = "REPLACE `?_SESSION` SET `id` = ?, `data` = ?, `updated` = UTC_TIMESTAMP()";
        $this->db->query($sql, array($id, $session_data));
        return true;
    }

    /**
     * @param string $id
     */
    public function save_handler_destroy($id)
    {
        $sql = "DELETE FROM `?_SESSION` WHERE `id` = ?";
        $this->db->query($sql, array($id));
    }

    /**
     * @param int $max_lifetime
     */
    public function save_handler_gc($max_lifetime)
    {
        $sql = "DELETE FROM `?_SESSION` WHERE UNIX_TIMESTAMP(UTC_TIMESTAMP()) - UNIX_TIMESTAMP(`updated`) > ?";
        $this->db->query($sql, array($max_lifetime));
    }
}

?>