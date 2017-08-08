<?php

DxFactory::import('DxDBO');

class DxDBO_MySQL extends DxDBO
{
    /**
     * @param array $params
     * @return DxDBO_MySQL
     * @throws DxException_DBO
     */
    public function connect(array $params)
    {
        $default = array(
            'database' => 'data_name',
            'prefix'   => null,
            'username' => 'root',
            'password' => '',
            'hostname' => 'localhost',
            'port'     => 3306,
            'timeout'  => 5,
            'charset'  => 'utf8',
        );
        
        foreach ($default as $k => $v) {
            if (!isset($params[$k])) $params[$k] = $v;
        }
        
        $this->prefix = $params['prefix'];
        $dsn = "mysql: host={$params['hostname']}; dbname={$params['database']}; port={$params['port']}";
        
        try {
            $this->pdo = new PDO($dsn, $params['username'], $params['password'],
                array(
                    1002              => "SET NAMES {$params['charset']}",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
        } catch (PDOException $e) {
            throw new DxException_DBO("Can not connect to MySQL", DxDBO::DX_DBO_ERROR_CONNECT, $e);
        }
 
        return $this;
    }
}