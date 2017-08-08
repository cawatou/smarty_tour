<?php
DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Companion extends DomainObjectQuery implements DataListQuery
{
    /**
     * @param array $params
     */
    public function initByListParams(array &$params = array())
    {
        $search_params = empty($params[Form_Filter::FILTER_SEARCH_PARAMS]) ? array() : $params[Form_Filter::FILTER_SEARCH_PARAMS];
        $order_params  = empty($params[Form_Filter::FILTER_ORDER_PARAMS])  ? array() : $params[Form_Filter::FILTER_ORDER_PARAMS];
        $placeholders  = array();

        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Companion', 'c');

        if (!empty($search_params['companion_status'])) {
            $qb->andWhere('c.companion_status = ?');
            $placeholders[] = $search_params['companion_status'];
        }

        if (!empty($search_params['active_only'])) {
            $qb->andWhere('c.companion_date_to >= ?');
            $placeholders[] = date('Y-m-d');
        }

        if (empty($order_params)) {
            $qb->orderBy('c.created', 'DESC');
        } else {
            foreach ($order_params as $field => $condition) {
                $qb->addOrderBy($field, $condition);
            }
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
     * @return DomainObjectModel_Companion|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Companion', 'c')
            ->where('c.companion_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findByIds(array $ids)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Companion', 'c')
            ->where('c.companion_id IN (?)');

        $placeholders = array(implode(', ', $ids));

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param bool $enabled
     * @return DomainObjectModel_Companion[]
     */
    public function findAll($enabled = false)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Companion', 'c')
            ->orderBy('c.created', 'ASC');

        $placeholders = array();

        if ($enabled) {
            $qb->andWhere('c.companion_status = ?');
            $placeholders = array('ENABLED');
        }

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param bool $enabled
     * @return array
     */
    public function getAll($enabled = false)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Companion', 'c')
            ->orderBy('c.created', 'ASC');

        $placeholders = array();

        if ($enabled) {
            $qb->andWhere('c.companion_status = ?');
            $placeholders = array('ENABLED');
        }

        return $this->getArrayResult($qb, $placeholders);
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Companion', 'c')
            ->where('c.companion_id = ?');

        $res = $this->getArrayResult($qb, array($id));

        return empty($res[0]) ? array() : $res[0];
    }
}