<?php

DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Staff extends DomainObjectQuery implements DataListQuery
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
            ->select('s')
            ->from('DomainObjectModel_Staff', 's');

        if (!empty($search_params['office_id'])) {
            $qb->andWhere('s.office_id = ?');
            $placeholders[] = $search_params['office_id'];
        }

        if (!empty($search_params['office_ids'])) {
            $qb->andWhereIn('s.office_id', $search_params['office_ids']);
            $placeholders = array_merge($placeholders, $search_params['office_ids']);
        }

        if (!empty($search_params['staff_status'])) {
            $qb->andWhere('s.staff_status = ?');
            $placeholders[] = $search_params['staff_status'];
        }

        if (empty($order_params)) {
            $qb->orderBy('s.staff_qnt', 'ASC');
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
     * @return DomainObjectModel_Staff|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Staff', 's')
            ->where('s.staff_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param string $signature
     * @return DomainObjectModel_Staff|null
     */
    public function findBySignature($signature)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Staff', 's')
            ->where('s.staff_signature = ?');

        return $this->getSingleFound($qb, array($signature));
    }

    /**
     * @return int
     */
    public function getMaxQnt()
    {
        $qb = $this->getQueryBuilder()
            ->select('MAX(s.staff_qnt) as m')
            ->from('DomainObjectModel_Staff', 's');

        $res = $this->getArrayResult($qb, array());
        return empty($res[0]['m']) ? 0 : $res[0]['m'];
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Staff', 's')
            ->where('s.staff_status = ?')
            ->orderBy('s.staff_qnt', 'ASC');

        return $this->getMultiFound($qb, array('ENABLED'));
    }

    /**
     * @param string $name
     * @return array
     */
    public function getByName($name)
    {
        $name = trim($name);

        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Staff', 's')
            ->where('s.staff_name = ?');

        $staff = $this->getArrayResult($qb, array($name));

        if (empty($staff[0])) {
            return null;
        }

        return $staff[0];
    }
}