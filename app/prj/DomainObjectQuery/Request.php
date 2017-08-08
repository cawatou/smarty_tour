<?php

DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Request extends DomainObjectQuery implements DataListQuery
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
            ->select('r')
            ->from('DomainObjectModel_Request', 'r');

        if (!empty($search_params['request_type'])) {
            if (is_array($search_params['request_type'])) {
                $qb->andWhereIn('r.request_type', $search_params['request_type']);
                $placeholders = array_merge($placeholders, $search_params['request_type']);
            } else {
                $qb->andWhere('r.request_type = ?');
                $placeholders[] = $search_params['request_type'];
            }
        }

        if (!empty($search_params['office_id'])) {
            $qb->andWhere('r.office_id = ?');
            $placeholders[] = $search_params['office_id'];
        }

        if (!empty($search_params['office_ids'])) {
            $qb->andWhereIn('r.office_id', $search_params['office_ids']);
            $placeholders = array_merge($placeholders, $search_params['office_ids']);
        }

        if (!empty($search_params['request_status'])) {
            $qb->andWhere('r.request_status = ?');
            $placeholders[] = $search_params['request_status'];
        }

        if (empty($order_params)) {
            $qb->orderBy('r.created', 'DESC');
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
     * @return DomainObjectModel_Request|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('r')
            ->from('DomainObjectModel_Request', 'r')
            ->where('r.request_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $qb = $this->getQueryBuilder()
            ->select('r')
            ->from('DomainObjectModel_Request', 'r')
            ->orderBy('r.created', 'ASC');

        return $this->getMultiFound($qb, array());
    }
}