<?php

interface DataListQuery
{
    /**
     * @abstract
     * @param array $params
     */
    public function initByListParams(array &$params = array());

    /**
     * @abstract
     * @param int    $offset
     * @param int    $length
     * @return array
     */
    public function &findForList($offset, $length);

    /**
     * @abstract
     * @return int
     */
    public function findCountForList();

    /**
     * @abstract
     * @return string|int
     */
    public function getChecksumForList();
}