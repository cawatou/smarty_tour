<?php

DxFactory::import('DxDBO');

class DxDBO_PDO extends DxDBO
{
    /**
     * @param array $params
     * @return DxDBO_PDO
     * @throws DxException_DBO
     */
    public function connect(array $params)
    {
        try {
            if (!isset($params['pdo']) || !($params['pdo'] instanceof PDO)) {
                throw new PDOException("Invalid PDO connection");
            }

            $this->pdo    = $params['pdo'];
            $this->prefix = isset($params['connection']['prefix']) ? $params['connection']['prefix'] : null;

        } catch (PDOException $e) {
            throw new DxException_DBO("Can not connect to database", DxDBO::DX_DBO_ERROR_CONNECT, $e);
        }

        return $this;
    }
}