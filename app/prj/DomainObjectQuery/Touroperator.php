<?php
DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Touroperator extends DomainObjectQuery implements DataListQuery
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
            ->select('t')
            ->from('DomainObjectModel_Touroperator', 't');

        if (!empty($search_params['touroperator_status'])) {
            $qb->andWhere('t.touroperator_status = ?');
            $placeholders[] = $search_params['touroperator_status'];
        }

        if (!empty($search_params['touroperator_title'])) {
            $qb->andWhere('t.touroperator_title LIKE ?');
            $placeholders[] = '%'. $search_params['touroperator_title'] .'%';
        }

        if (empty($order_params)) {
            $qb->orderBy('t.touroperator_title', 'ASC');
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
     * @return DomainObjectModel_Touroperator|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('t')
            ->from('DomainObjectModel_Touroperator', 't')
            ->where('t.touroperator_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findByIds(array $ids)
    {
        $qb = $this->getQueryBuilder()
            ->select('t')
            ->from('DomainObjectModel_Touroperator', 't')
            ->whereIn('t.touroperator_id', $ids);

        $placeholders = $ids;

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param string $str
     * @return DomainObjectModel_Touroperator|null
     */
    public function findByTitle($str)
    {
        $qb = $this->getQueryBuilder()
            ->select('t')
            ->from('DomainObjectModel_Touroperator', 't')
            ->where('t.touroperator_title LIKE ?');

        return $this->getSingleFound($qb, array('%'. $str .'%'));
    }

    /**
     * @param bool $enabled
     * @return DomainObjectModel_Touroperator[]
     */
    public function findAll($enabled = false)
    {
        $qb = $this->getQueryBuilder()
            ->select('t')
            ->from('DomainObjectModel_Touroperator', 't')
            ->orderBy('t.touroperator_title', 'ASC');

        $placeholders = array();

        if ($enabled) {
            $qb->andWhere('t.touroperator_status = ?');
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
            ->select('t')
            ->from('DomainObjectModel_Touroperator', 't')
            ->orderBy('t.touroperator_title', 'ASC');

        $placeholders = array();

        if ($enabled) {
            $qb->andWhere('t.touroperator_status = ?');
            $placeholders = array('ENABLED');
        }

        return $this->getArrayResult($qb, $placeholders);
    }
}