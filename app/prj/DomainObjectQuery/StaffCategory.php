<?php

DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_StaffCategory extends DomainObjectQuery implements DataListQuery
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
            ->select('sc')
            ->from('DomainObjectModel_StaffCategory', 'sc');

        if (!empty($search_params['staff_category_status'])) {
            $qb->andWhere('sc.staff_category_status = ?');
            $placeholders[] = $search_params['staff_category_status'];
        }

        if (empty($order_params)) {
            $qb->orderBy('sc.staff_category_qnt', 'ASC');
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
     * @return DomainObjectModel_StaffCategory|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('sc')
            ->from('DomainObjectModel_StaffCategory', 'sc')
            ->where('sc.staff_category_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @return int
     */
    public function getMaxQnt()
    {
        $qb = $this->getQueryBuilder()
            ->select('MAX(sc.staff_category_qnt) as m')
            ->from('DomainObjectModel_StaffCategory', 'sc');

        $res = $this->getArrayResult($qb, array());
        return empty($res[0]['m']) ? 0 : $res[0]['m'];
    }

    /**
     * @param null $status
     * @return array
     */
    public function findAll($status = null)
    {
        $qb = $this->getQueryBuilder()
            ->select('sc')
            ->from('DomainObjectModel_StaffCategory', 'sc')
            ->orderBy('sc.staff_category_qnt', 'ASC');

        $placeholder = array();
        if (null !== $status) {
            $qb->where('sc.staff_category_status = ?');
            $placeholder = array($status);
        }

        return $this->getMultiFound($qb, $placeholder);
    }
}
