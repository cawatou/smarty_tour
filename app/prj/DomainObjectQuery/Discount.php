<?php
DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Discount extends DomainObjectQuery implements DataListQuery
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
            ->select('d')
            ->from('DomainObjectModel_Discount', 'd');

        if (!empty($search_params['touroperator_id'])) {
            $qb->andWhere('d.touroperator_id = ?');
            $placeholders[] = $search_params['touroperator_id'];
        }

        if (!empty($search_params['country_id'])) {
            $qb->andWhere('d.country_id = ?');
            $placeholders[] = $search_params['country_id'];
        }

        if (!empty($search_params['discount_price_min'])) {
            $qb->andWhere('d.discount_price_min = ?');
            $placeholders[] = $search_params['discount_price_min'];
        }

        if (!empty($search_params['discount_price_max'])) {
            $qb->andWhere('d.discount_price_max = ?');
            $placeholders[] = $search_params['discount_price_max'];
        }

        if (!empty($search_params['departure_city_id'])) {
            $qb->andWhere('d.departure_city_id = ?');
            $placeholders[] = $search_params['departure_city_id'];
        }

        if (!empty($search_params['discount_status'])) {
            $qb->andWhere('d.discount_status = ?');
            $placeholders[] = $search_params['discount_status'];
        }

        if (empty($order_params)) {
            $qb->orderBy('d.discount_qnt', 'ASC');
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
     * @return DomainObjectModel_Discount|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('d')
            ->from('DomainObjectModel_Discount', 'd')
            ->where('d.discount_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findByIds(array $ids)
    {
        $qb = $this->getQueryBuilder()
            ->select('d')
            ->from('DomainObjectModel_Discount', 'd')
            ->whereIn('d.discount_id', $ids);

        $placeholders = $ids;

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @return DomainObjectModel_Discount[]
     */
    public function findAll()
    {
        $qb = $this->getQueryBuilder()
            ->select('d')
            ->from('DomainObjectModel_Discount', 'd')
            ->orderBy('d.discount_qnt', 'ASC');

        $placeholders = array();

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param string $type Type for filtering
     * @return DomainObjectModel_Discount[]
     */
    public function findByType($type)
    {
        $qb = $this->getQueryBuilder()
            ->select('d')
            ->from('DomainObjectModel_Discount', 'd')
            ->andWhere('d.discount_type = ?')
            ->orderBy('d.discount_qnt', 'ASC');

        $placeholders = array(
            $type,
        );

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $qb = $this->getQueryBuilder()
            ->select('d')
            ->from('DomainObjectModel_Discount', 'd')
            ->orderBy('d.discount_qnt', 'ASC');

        $placeholders = array();

        return $this->getArrayResult($qb, $placeholders);
    }

    public function findDefault($type = 'DISCOUNT')
    {
        $qb = $this->getQueryBuilder()
            ->select('d')
            ->from('DomainObjectModel_Discount', 'd')
            ->andWhere('d.discount_type = ?');

        $placeholders = array($type);

        $qb->andWhere('d.country_id IS NULL');
        $qb->andWhere('d.touroperator_id IS NULL');
        $qb->andWhere('d.departure_city_id IS NULL');
        $qb->andWhere('d.discount_price_min IS NULL');
        $qb->andWhere('d.discount_price_max IS NULL');

        return $this->getSingleFound($qb, $placeholders);
    }

    public function findByProduct(DomainObjectModel_Product $product)
    {
        $qb = $this->getQueryBuilder()
            ->select('d')
            ->from('DomainObjectModel_Discount', 'd');

        $placeholders = array();

        $qb->andWhere('d.country_id IS NULL OR d.country_id = ?');
        $placeholders[] = $product->getCountryId();

        $touroperators = array();

        if ($product->getTouroperatorId()) {
            $touroperators[$product->getTouroperatorId()] = $product->getTouroperatorId();
        }

        if (count($product->getLinkedProducts())) {
            foreach ($product->getLinkedProducts() as $linked) {
                if ($linked->getTouroperatorId()) {
                    $touroperators[$linked->getTouroperatorId()] = $linked->getTouroperatorId();
                }
            }
        }

        if (count($touroperators)) {
            $qb->andWhere('d.touroperator_id IS NULL OR d.touroperator_id IN ('. implode(', ', array_fill(0, count($touroperators), '?')) .')');
            $placeholders = array_merge($placeholders, $touroperators);
        }

        $qb->andWhere('d.departure_city_id IS NULL OR d.departure_city_id = ?');
        $placeholders[] = $product->getFromId();

        $qb->orderBy('d.discount_qnt', 'ASC');

        $result = $this->getMultiFound($qb, $placeholders);

        $formatted = array();

        foreach ($result as $res) {
            if (empty($formatted[$res->getType()])) {
                $formatted[$res->getType()] = array();
            }

            $formatted[$res->getType()][] = $res;
        }

        return $formatted;
    }
}