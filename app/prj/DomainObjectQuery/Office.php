<?php
DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Office extends DomainObjectQuery implements DataListQuery
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
            ->select('o')
            ->from('DomainObjectModel_Office', 'o');

        if (!empty($search_params['office_ids'])) {
            $qb->andWhereIn('o.office_id', $search_params['office_ids']);
            $placeholders = array_merge($placeholders, $search_params['office_ids']);
        }

        if (!empty($search_params['city_id'])) {
            $qb->andWhere('o.city_id = ?');
            $placeholders[] = $search_params['city_id'];
        }

        if (!empty($search_params['city_ids'])) {
            $qb->andWhereIn('o.city_id', $search_params['city_ids']);
            $placeholders = array_merge($placeholders, $search_params['city_ids']);
        }

        if (!empty($search_params['office_status'])) {
            $qb->andWhere('o.office_status = ?');
            $placeholders[] = $search_params['office_status'];
        }

        if (empty($order_params)) {
            $qb->orderBy('o.office_qnt', 'ASC');
        } else {
            foreach ($order_params as $field => $condition) {
                $qb->addOrderBy($field, $condition);
            }
        }

        $this->setCachedQueryBuilder($qb->setParameters($placeholders));
    }

    /**
     * @param int $offset
     * @param int $length
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
     * @return DomainObjectModel_Office|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Office', 'o')
            ->where('o.office_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param string $alias
     * @return DomainObjectModel_Office|null
     */
    public function findByAlias($alias)
    {
        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Office', 'o')
            ->where('o.office_alias= ?');

        return $this->getSingleFound($qb, array($alias));
    }

    /**
     * @return int
     */
    public function getMaxQnt()
    {
        $qb = $this->getQueryBuilder()
            ->select('MAX(o.office_qnt) as m')
            ->from('DomainObjectModel_Office', 'o');

        $res = $this->getArrayResult($qb, array());
        return empty($res[0]['m']) ? 0 : $res[0]['m'];
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Office', 'o')
            ->where('o.office_status = ?')
            ->orderBy('o.office_qnt', 'ASC');

        return $this->getMultiFound($qb, array('ENABLED'));
    }
    /**
     * @return array
     */
    public function getAll()
    {
        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Office', 'o')
            ->where('o.office_status = ?')
            ->orderBy('o.office_qnt', 'ASC');

        return $this->getArrayResult($qb, array('ENABLED'));
    }

    /**
     * @param $city_id
     * @return array|DomainObjectModel[]
     */
    public function findByCityId($city_id)
    {
        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Office', 'o')
            ->where('o.office_status = ?')
            ->orderBy('o.office_qnt', 'ASC');

        $qb->andWhere('o.city_id = ?');

        $placeholders = array('ENABLED', $city_id);

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param array $related_city_ids
     * @return array
     */
    public function findRelatedOffices(array $related_city_ids)
    {
        if (empty($related_city_ids)) {
            return array();
        }

        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Office', 'o')
            ->andWhere('o.office_status = ?')
            ->orderBy('o.office_qnt', 'ASC');

        $placeholders = array('ENABLED');

        $where_str = array();

        foreach ($related_city_ids as $related_city) {
            $where_str[] = 'o.city_id = ?';

            $placeholders[] = $related_city;
        }

        if (!empty($where_str)) {
            $qb->andWhere(implode(' OR ', $where_str));
        }

        return $this->getMultiFound($qb, $placeholders);
    }

    public function getBySubdivisionId($subdivision_id)
    {
        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Office', 'o')
            ->where('o.subdivision_id = ?')
            ->andWhere('o.office_status = ?')
            ->orderBy('o.office_qnt', 'ASC');

        return $this->getArrayResult($qb, array($subdivision_id, 'ENABLED'));
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findByCityIds(array $ids)
    {
        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Office', 'o')
            ->whereIn('o.city_id', $ids)
            ->orderBy('o.office_qnt', 'ASC');

        $placeholders = $ids;

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param int $id
     * @return DomainObjectModel_Office|null
     */
    public function findBySubdivisionId($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Office', 'o')
            ->where('o.subdivision_id = ?')
            ->andWhere('o.office_status = ?');

        return $this->getMultiFound($qb, array($id, 'ENABLED'));
    }

    public function findForSletatMail()
    {
        $qb = $this->getQueryBuilder()
            ->select('o')
            ->from('DomainObjectModel_Office', 'o')
            ->where('o.office_sletat_data IS NOT NULL');

        return $this->getMultiFound($qb, array());
    }
}