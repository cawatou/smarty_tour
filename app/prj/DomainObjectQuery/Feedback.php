<?php

DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Feedback extends DomainObjectQuery implements DataListQuery
{
    /**
     * @param array $params
     */
    public function initByListParams(array &$params = array())
    {
        $search_params = empty($params[Form_Filter::FILTER_SEARCH_PARAMS]) ? array() : $params[Form_Filter::FILTER_SEARCH_PARAMS];
        $order_params  = empty($params[Form_Filter::FILTER_ORDER_PARAMS]) ? array() : $params[Form_Filter::FILTER_ORDER_PARAMS];
        $placeholders  = array();

        $qb = $this->getQueryBuilder()
            ->select('f')
            ->from('DomainObjectModel_Feedback', 'f');

        if (!empty($search_params['feedback_type'])) {
            $qb->andWhere('f.feedback_type = ?');
            $placeholders[] = $search_params['feedback_type'];
        }

        if (!empty($search_params['office_id'])) {
            $qb->andWhere('f.office_id = ?');
            $placeholders[] = $search_params['office_id'];
        }

        if (!empty($search_params['office_ids']) && $search_params['feedback_type'] != 'HOTEL') {
            $qb->andWhereIn('f.office_id', $search_params['office_ids']);
            $placeholders = array_merge($placeholders, $search_params['office_ids']);
        }

        if (!empty($search_params['feedback_status'])) {
            $qb->andWhere('f.feedback_status = ?');
            $placeholders[] = $search_params['feedback_status'];
        }

        if (empty($order_params)) {
            $qb->orderBy('f.created', 'DESC');
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

        if (is_null($qb)) {
            return 0;
        }

        return md5($qb->getSQL() . serialize($qb->getParameters()));
    }

    /**
     * @param int $id
     * @return DomainObjectModel_Feedback|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('f')
            ->from('DomainObjectModel_Feedback', 'f')
            ->where('f.feedback_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param int $limit
     * @return DomainObjectModel_Feedback[]|array
     */
    public function findLatest($limit = 3, $type = null)
    {
        $qb = $this->getQueryBuilder()
            ->select('f')
            ->from('DomainObjectModel_Feedback', 'f')
            ->where('f.feedback_status = ?')
            ->orderBy('f.created', 'DESC')
            ->offset(0)
            ->limit($limit);

        $placeholder = array('ENABLED');
        if ($type !== null) {
            $qb->andWhere('f.feedback_type = ?');
            $placeholder[] = $type;
        }

        return $this->getMultiFound($qb, $placeholder);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $qb = $this->getQueryBuilder()
            ->select('f')
            ->from('DomainObjectModel_Feedback', 'f')
            ->orderBy('f.created', 'ASC');

        return $this->getMultiFound($qb, array());
    }
}