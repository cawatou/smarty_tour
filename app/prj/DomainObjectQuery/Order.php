<?php

DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Order extends DomainObjectQuery implements DataListQuery
{
    /**
     * @param array $params
     */
    public function initByListParams(array &$params = array())
    {
        $search_params = empty($params[Form_Filter::FILTER_SEARCH_PARAMS]) ? array() : $params[Form_Filter::FILTER_SEARCH_PARAMS];
        //$order_params  = empty($params[Form_Filter::FILTER_ORDER_PARAMS]) ? array() : $params[Form_Filter::FILTER_ORDER_PARAMS];
        $placeholders  = array();

        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Order', 'o')
            ->orderBy('o.order_id', 'DESC');

        if (!empty($search_params['order_status'])) {
            $qb->andWhere('o.order_status = ?');
            $placeholders[] = $search_params['order_status'];
        }

        $this->setCachedQueryBuilder($qb->setParameters($placeholders));
    }

    /**
     * @param int    $offset
     * @param int    $length
     * @return array
     */
    public function &findForList($offset, $length)
    {
        $qb = $this->getCachedQueryBuilder(true)
            ->offset($offset)
            ->limit($length);

        return $this->getMultiFound($qb);
    }

    /**
     * @return int
     */
    public function findCountForList()
    {
        return $this->getCount($this->getCachedQueryBuilder(true));
    }

    /**
     * @return string|int
     */
    public function getChecksumForList()
    {
        $qb = $this->getCachedQueryBuilder();

        if ($qb === null) {
            return 0;
        }

        return md5($qb->getSQL() . serialize($qb->getParameters()));
    }

    /**
     * @param int $id
     * @return DomainObjectModel_Order|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Order', 'o')
            ->where('o.order_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param $signature
     * @return DomainObjectModel|null
     */
    public function findBySignature($signature)
    {
        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Order', 'o')
            ->where('o.order_signature = ?');

        return $this->getSingleFound($qb, array($signature));
    }
}
