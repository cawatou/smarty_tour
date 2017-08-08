<?php

DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Faq extends DomainObjectQuery implements DataListQuery
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
            ->from('DomainObjectModel_Faq', 'f');

        if (!empty($search_params['faq_status'])) {
            $qb->andWhere('f.faq_status = ?');
            $placeholders[] = $search_params['faq_status'];
        }

        if (!empty($search_params['city_id'])) {
            $qb->andWhere('f.city_id = ?');
            $placeholders[] = $search_params['city_id'];
        }

        if (!empty($search_params['city_ids'])) {
            $qb->andWhereIn('f.city_id', $search_params['city_ids']);
            $placeholders = array_merge($placeholders, $search_params['city_ids']);
        }

        if (!empty($search_params['office_id'])) {
            $qb->andWhere('f.office_id = ?');
            $placeholders[] = $search_params['office_id'];
        }

        if (!empty($search_params['office_ids'])) {
            $qb->andWhereIn('f.office_id', $search_params['office_ids']);
            $placeholders = array_merge($placeholders, $search_params['office_ids']);
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
     * @return DomainObjectModel_Faq|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('f')
            ->from('DomainObjectModel_Faq', 'f')
            ->where('f.faq_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param array $params
     * @return DomainObjectModel_Faq[]|null
     */
    public function findByParams(array $params = array())
    {
        $search_params = empty($params[Form_Filter::FILTER_SEARCH_PARAMS]) ? array() : $params[Form_Filter::FILTER_SEARCH_PARAMS];
        $order_params  = empty($params[Form_Filter::FILTER_ORDER_PARAMS]) ? array() : $params[Form_Filter::FILTER_ORDER_PARAMS];
        $placeholders  = array();

        $qb = $this->getQueryBuilder()
            ->select('f')
            ->from('DomainObjectModel_Faq', 'f');

        if (!empty($search_params['faq_status'])) {
            $qb->andWhere('f.faq_status = ?');
            $placeholders[] = $search_params['faq_status'];
        }

        if (!empty($search_params['faq_answer'])) {
            $qb->andWhere("f.faq_answer {$search_params['faq_answer']}");
        }


        if (!empty($search_params['limit'])) {
            $qb->limit($search_params['limit']);
        }

        foreach ($order_params as $field => $direction) {
            $qb->orderBy('f.' . $field, $direction);
        }

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param int $limit
     * @return DomainObjectModel_Faq[]|array
     */
    public function findLatest($limit = 3)
    {
        $qb = $this->getQueryBuilder()
            ->select('f')
            ->from('DomainObjectModel_Faq', 'f')
            ->where('f.faq_status = ?')
            ->orderBy('f.created', 'DESC')
            ->offset(0)
            ->limit($limit);

        return $this->getMultiFound($qb, array('ENABLED'));
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $qb = $this->getQueryBuilder()
            ->select('f')
            ->from('DomainObjectModel_Faq', 'f')
            ->orderBy('f.created', 'ASC');

        return $this->getMultiFound($qb, array());
    }
}