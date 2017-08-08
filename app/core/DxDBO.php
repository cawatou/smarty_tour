<?php

abstract class DxDBO
{
    const DX_DBO_ERROR_BASE      = 1000;
    const DX_DBO_ERROR_CONNECT   = 1001;
    const DX_DBO_ERROR_STATEMENT = 1002;
    const DX_DBO_ERROR_INTERNAL  = 1003;
    const DX_DBO_ERROR_RESULT    = 1004;

    const AUTOQUERY_INSERT  = 1;
    const AUTOQUERY_UPDATE  = 2;
    const AUTOQUERY_REPLACE = 3;

    /** @var null|string */
    protected $prefix;

    /** @var PDO */
    protected $pdo;

    /**
     * @abstract
     * @param array $params
     * @return DxDBO
     */
    abstract public function connect(array $params);

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->connect($params);
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->pdo = null;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    public function prepare($sql)
    {
        $sql = str_replace('?_', $this->prefix, $sql);
        return new DxDBOStatement($sql, $this->pdo);
    }

    /**
     * @param string $sql
     * @param array  $params
     * @return DxDBOStatement
     */
    public function query($sql, array $params = array())
    {
        $st = $this->prepare($sql);
        $st->execute($params);
        return $st;
    }

    /**
     * @param string $sql
     * @param array  $params
     * @param int    $fetchmode
     * @return array
     */
    public function &getAll($sql, array $params = array(), $fetchmode = PDO::FETCH_ASSOC)
    {
        $st = $this->prepare($sql);
        $st->execute($params);

        $result = array();
        while (!$st->eof()) {
            $result[] = $st->fetchRow($fetchmode);
        }

        return $result;
    }

    /**
     * @param string $sql
     * @param array  $params
     * @param int    $fetchmode
     * @return array
     */
    public function &getRow($sql, array $params = array(), $fetchmode = PDO::FETCH_ASSOC)
    {
        if (!strpos(strtolower($sql), 'limit')) {
            $sql .= ' LIMIT 1';
        }

        $st = $this->prepare($sql);
        $st->execute($params);

        $result = $st->containsData() ? $st->fetchRow($fetchmode) : array();

        return $result;
    }

    /**
     * @param string $sql
     * @param string $col
     * @param array  $params
     * @return array
     */
    public function &getCol($sql, $col, array $params = array())
    {
        $st = $this->prepare($sql);
        $st->execute($params);

        $result    = array();
        $fetchmode = is_numeric($col) ? PDO::FETCH_NUM : PDO::FETCH_ASSOC;
        while (!$st->eof()) {
            $row = $st->fetchRow($fetchmode);
            if (array_key_exists($col, $row)) {
                $result[] = $row[$col];
            }
        }

        return $result;
    }

    /**
     * @param string $sql
     * @param array  $params
     * @return null|string
     */
    public function getOne($sql, array $params = array())
    {
        $row = $this->getRow($sql, $params, PDO::FETCH_NUM);
        return empty($row) ? null : $row[0];
    }

    /**
     * @param string $sql
     * @param array  $params
     * @param null   $key
     * @param bool   $group
     * @param int    $fetchmode
     * @return array
     */
    public function &getAssoc($sql, array $params = array(), $key = null, $group = false, $fetchmode = PDO::FETCH_ASSOC)
    {
        $st = $this->prepare($sql);
        $st->execute($params);

        $result = array();

        if ($st->containsData() && $st->numFields() >= 2) {
            while (!$st->eof()) {
                $row = $st->fetchRow(PDO::FETCH_ASSOC);
                if (is_null($key)) {
                    $t    = array_keys($row);
                    $_key = $row[$t[0]];
                } else {
                    $_key = $row[$key];
                }

                if ($fetchmode == PDO::FETCH_NUM) {
                    $row = array_values($row);
                } elseif ($fetchmode == PDO::FETCH_OBJ) {
                    $row = (object)$row;
                }

                if (!$group) {
                    $result[$_key] = $row;
                } else {
                    if (!array_key_exists($_key, $result)) {
                        $result[$_key] = array();
                    }
                    $result[$_key][] = $row;
                }
            }
        }

        return $result;
    }

    /**
     * @param null|string $sequence_name
     * @return int
     */
    public function lastAutoincrementId($sequence_name = null)
    {
        return $this->pdo->lastInsertId($sequence_name);
    }

    /**
     * @return void
     */
    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    /**
     * @return void
     */
    public function rollBackTransaction()
    {
        $this->pdo->rollBack();
    }

    /**
     * @return void
     */
    public function commitTransaction()
    {
        $this->pdo->commit();
    }

    /**
     * @param string        $table
     * @param array         $fields_data
     * @param array         $as_is
     * @param int           $mode
     * @param null|string   $where
     * @param array         $where_data
     * @return int
     */
    public function autoExecute($table, array &$fields_data, array $as_is = array(), $mode = DxDBO::AUTOQUERY_INSERT, $where = null, array $where_data = array())
    {
        $st = $this->autoPrepare($table, $fields_data, $as_is, $mode, $where);

        foreach ($fields_data as $f => $v) {
            if (in_array($f, $as_is)) {
                unset($fields_data[$f]);
            }
        }
        $params = array_merge(array_values($fields_data), $where_data);
        $st->execute($params);

        return $st;
    }

    /**
     * @param string        $table
     * @param array         $fields_data
     * @param array         $as_is
     * @param int           $mode
     * @param null|string   $where
     * @internal param null $sequence_name
     * @return int
     */
    private function autoPrepare($table, array &$fields_data, array $as_is = array(), $mode = DxDBO::AUTOQUERY_INSERT, $where = null)
    {
        $fields = array();

        foreach ($fields_data as $f => $v) {
            $fields[] = "`{$f}` = " . (in_array($f, $as_is) ? $v : '?');
        }

        if ($mode == DxDBO::AUTOQUERY_INSERT) {
            $sql = 'INSERT';
        } else {
            $sql = $mode == DxDBO::AUTOQUERY_UPDATE ? 'UPDATE' : 'REPLACE';
        }

        if (!is_null($this->prefix) && !strpos($table, $this->prefix)) {
            $table = $this->prefix . $table;
        }

        $sql .= " `$table` SET " . implode(', ', $fields);
        if ($where) {
            $sql .= " {$where}";
        }

        return $this->prepare($sql);
    }
}

class DxDBOStatement
{
    /** @var PDO */
    private $pdo;

    /** @var PDOStatement */
    private $statement;

    /** @var bool */
    private $executed = false;

    /** @var int */
    private $iterator = 0;

    /**
     * @param string $sql
     * @param PDO    $pdo
     */
    public function __construct($sql, PDO $pdo)
    {
        $statement = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        if (!is_object($statement) || !($statement instanceof PDOStatement)) {
            throw new DxException_DBO("Error preparing '{$sql}'", DxDBO::DX_DBO_ERROR_STATEMENT, $statement);
        }

        $this->pdo       = $pdo;
        $this->statement = $statement;
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        if ($this->executed) {
            $this->statement->closeCursor();
        }

        $this->pdo       = null;
        $this->statement = null;
    }

    /**
     * @param array $params
     * @return bool
     * @throws DxException_DBO
     */
    public function execute(array $params = array())
    {
        if ($this->executed) {
            $this->statement->closeCursor();
        }

        try {
            $types = empty($params) ? array() : $this->getParamsTypes($params);
            for ($i = 0; $i < count($params); $i++) {
                if (get_magic_quotes_gpc()) {
                    $params[$i] = stripslashes($params[$i]);
                }

                $this->statement->bindParam($i + 1, $params[$i], $types[$i]);
            }
        } catch (PDOException $e) {
            throw new DxException_DBO('Error on binding params', DxDBO::DX_DBO_ERROR_STATEMENT, $this->statement);
        }

        try {
            if ($this->statement->execute() === false) {
                throw new DxException_DBO('Error on execute', DxDBO::DX_DBO_ERROR_STATEMENT, $this->statement);
            }
        } catch (PDOException $e) {
            throw new DxException_DBO('Error on execute', DxDBO::DX_DBO_ERROR_STATEMENT, $e);
        }

        $this->executed = true;
        $this->iterator = 0;

        return true;
    }

    /**
     * @return bool
     * @throws DxException_DBO
     */
    public function containsData()
    {
        if (!$this->executed) {
            throw new DxException_DBO('Statement is not executed yet', DxDBO::DX_DBO_ERROR_STATEMENT, $this->statement);
        }

        return $this->statement->rowCount() ? true : false;
    }

    /**
     * @param null $sequence_name
     * @return string
     * @throws DxException_DBO
     */
    public function lastAutoincrementId($sequence_name = null)
    {
        if (!$this->executed) {
            throw new DxException_DBO('Statement is not executed yet', DxDBO::DX_DBO_ERROR_STATEMENT, $this->statement);
        }

        return $this->pdo->lastInsertId($sequence_name);
    }

    /**
     * @return int
     * @throws DxException_DBO
     */
    function numFields()
    {
        if (!$this->executed) {
            throw new DxException_DBO('Statement is not executed yet', DxDBO::DX_DBO_ERROR_STATEMENT, $this->statement);
        }

        return $this->statement->columnCount();
    }

    /**
     * @return int
     * @throws DxException_DBO
     */
    function numRows()
    {
        if (!$this->executed) {
            throw new DxException_DBO('Statement is not executed yet', DxDBO::DX_DBO_ERROR_STATEMENT, $this->statement);
        }

        return $this->statement->rowCount();
    }

    /**
     * @return bool
     * @throws DxException_DBO
     */
    function eof()
    {
        if (!$this->executed) {
            throw new DxException_DBO('Statement is not executed yet', DxDBO::DX_DBO_ERROR_STATEMENT, $this->statement);
        }

        return $this->iterator < $this->numRows() ? false : true;
    }

    /**
     * @param int  $fetchmode
     * @param null $rownum
     * @return mixed
     * @throws DxException_DBO
     */
    public function fetchRow($fetchmode = PDO::FETCH_ASSOC, $rownum = null)
    {
        if (!$this->executed) {
            throw new DxException_DBO('Statement is not executed yet', DxDBO::DX_DBO_ERROR_STATEMENT, $this->statement);
        }

        if (is_numeric($rownum) && $rownum > -1 && $rownum < $this->numRows()) {
            $this->iterator = $rownum;

            $result = $this->statement->fetch($fetchmode, PDO::FETCH_ORI_ABS, $rownum);
        } elseif ($this->eof()) {
            throw new DxException_DBO('EOF reached', DxDBO::DX_DBO_ERROR_RESULT, $this->statement);
        } else {
            $result = $this->statement->fetch($fetchmode);
        }

        if (is_null($result)) {
            throw new DxException_DBO('Invalid row', DxDBO::DX_DBO_ERROR_RESULT, $this->statement);
        } elseif ($result === false) {
            throw new DxException_DBO('Error fetching result data', DxDBO::DX_DBO_ERROR_RESULT, $this->statement);
        }

        $this->iterator++;
        return $result;
    }

    /**
     * @param array $params
     * @return array
     * @throws DxException_DBO
     */
    private function getParamsTypes(array &$params)
    {
        if (!count($params)) {
            throw new DxException_DBO('No params passed', DxDBO::DX_DBO_ERROR_INTERNAL, $this->statement);
        }

        $types = array();
        for ($i = 0; $i < count($params); $i++) {
            if (is_bool($params[$i])) {
                $types[$i] = PDO::PARAM_BOOL;
            } elseif (is_int($params[$i]) || is_double($params[$i])) {
                $types[$i] = PDO::PARAM_INT;
            } elseif (is_null($params[$i])) {
                $types[$i] = PDO::PARAM_NULL;
            } else {
                $types[$i] = PDO::PARAM_STR;
            }
        }

        return $types;
    }
}

if (!function_exists('get_magic_quotes_gpc')) {
    function get_magic_quotes_gpc()
    {
        return false;
    }
}