<?php

DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Seo extends DomainObjectQuery implements DataListQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_Seo|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Seo', 's')
            ->where('s.seo_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

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
            ->from('DomainObjectModel_Seo', 's');

        if (!empty($search_params['seo_status'])) {
            $qb->andWhere('s.seo_status = ?');
            $placeholders[] = $search_params['seo_status'];
        }

        if (empty($order_params)) {
            $qb->orderBy('s.seo_request', 'ASC');
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
     * @param $request
     * @return DomainObjectController|null
     */
    public function findByRequest($request)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Seo', 's')
            ->where('s.seo_request = ?');

        return $this->getSingleFound($qb, array($request));
    }

}
