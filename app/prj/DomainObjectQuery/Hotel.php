<?php
class DomainObjectQuery_Hotel extends DomainObjectQuery implements DataListQuery
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
            ->select('h')
            ->from('DomainObjectModel_Hotel', 'h')
            ->innerJoin('h.Country', 'c')
            ->innerJoin('h.Resort', 'r')
            ->orderBy('h.title', 'DESC');

        if (!empty($search_params['country_id'])) {
            $qb->andWhere('h.country_id = ?');
            $placeholders[] = $search_params['country_id'];
        }

        if (!empty($search_params['country_ids'])) {
            $qb->andWhereIn('с.country_id', $search_params['country_ids']);
            $placeholders = array_merge($placeholders, $search_params['country_ids']);
        }

        if (!empty($search_params['country_status'])) {
            $qb->andWhere('c.country_status = ?');
            $placeholders[] = $search_params['country_status'];
        }

        if (!empty($search_params['resort_id'])) {
            $qb->andWhere('h.resort_id = ?');
            $placeholders[] = $search_params['resort_id'];
        }

        if (!empty($search_params['resort_ids'])) {
            $qb->andWhereIn('r.resort_id', $search_params['resort_ids']);
            $placeholders = array_merge($placeholders, $search_params['resort_ids']);
        }

        if (!empty($search_params['resort_status'])) {
            $qb->andWhere('r.resort_status = ?');
            $placeholders[] = $search_params['resort_status'];
        }

        if (!empty($search_params['hotel_title'])) {
            $qb->andWhere('h.hotel_title LIKE ?');
            $placeholders[] = '%'. $search_params['hotel_title'] .'%';
        }

        if (!empty($search_params['hotel_stars'])) {
            $qb->andWhere('h.hotel_stars = ?');
            $placeholders[] = $search_params['hotel_stars'];
        }

        if (!empty($search_params['hotel_stars_in'])) {
            $qb->andWhereIn('h.hotel_stars', $search_params['hotel_stars_in']);
            $placeholders = array_merge($placeholders, $search_params['hotel_stars_in']);
        }

        if (!empty($search_params['hotel_status'])) {
            $qb->andWhere('h.hotel_status = ?');
            $placeholders[] = $search_params['hotel_status'];
        }

        if (empty($order_params)) {
            $qb->orderBy('h.hotel_title', 'ASC');
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
     * @param bool $enabled
     * @return DomainObjectModel_Hotel[]
     */
    public function findAll($enabled = false)
    {
        $qb = $this->getQueryBuilder()
            ->select('h')
            ->from('DomainObjectModel_Hotel', 'h')
            ->orderBy('h.created', 'ASC')
            ->offset(0)
            ->limit(10000);

        $placeholders = array();

        if ($enabled) {
            $qb->andWhere('h.hotel_status = ?');
            $placeholders = array('ENABLED');
        }

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param int $id
     * @return DomainObjectModel_Hotel|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('h')
            ->from('DomainObjectModel_Hotel', 'h')
            ->where('h.hotel_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param string $title
     * @return DomainObjectModel_Hotel|null
     */
    public function findByTitle($title)
    {
        $qb = $this->getQueryBuilder()
            ->select('h')
            ->from('DomainObjectModel_Hotel', 'h')
            ->where('h.hotel_title = ?');

        return $this->getSingleFound($qb, array($title));
    }

    /**
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('h')
            ->from('DomainObjectModel_Hotel', 'h')
            ->where('h.hotel_id = ?');

        return $this->getArrayResult($qb, array($id));
    }

    /**
     * @param int $ids
     * @param null $status
     * @return DomainObjectModel_Hotel[]|array
     */
    public function findByIds($ids, $status = null)
    {
        if (empty($ids)) {
            return array();
        }

        $qb = $this->getQueryBuilder()
            ->select('h')
            ->from('DomainObjectModel_Hotel', 'h')
            ->whereIn('h.hotel_id', $ids);

        if ($status !== null) {
            $qb->andWhere('h.hotel_status = ?');
            $ids = array_merge($ids, (array)$status);
        }

        return $this->getMultiFound($qb, $ids);
    }

    /**
     * @param int $external_id
     * @return DomainObjectModel_Hotel|null
     */
    public function findByExternalId($external_id)
    {
        $qb = $this->getQueryBuilder()
            ->select('h')
            ->from('DomainObjectModel_Hotel', 'h')
            ->where('h.hotel_external_id = ?');

        return $this->getSingleFound($qb, array($external_id));
    }

    /**
     * @param int $resort_id
     * @return array
     */
    public function getByResortId($resort_id)
    {
        $qb = $this->getQueryBuilder()
            ->select('h')
            ->from('DomainObjectModel_Hotel', 'h')
            ->where('h.resort_id = ?');

        return $this->getArrayResult($qb, array($resort_id));
    }

    public function findByTitleCountryAndResort($title, $country_id = null, $resort_id = null)
    {
        $qb = $this->getQueryBuilder()
            ->select('h')
            ->from('DomainObjectModel_Hotel', 'h')
            ->where('h.hotel_title LIKE ?');

        $placeholders = array('%'. $title .'%');

        if ($country_id > 0) {
            $qb->andWhere('h.country_id = ?');
            $placeholders[] = $country_id;
        }

        if ($resort_id > 0) {
            $qb->andWhere('h.resort_id = ?');
            $placeholders[] = $resort_id;
        }

        return $this->getSingleFound($qb, $placeholders);
    }

    public function getByTitleCountryAndResort($title, $country_id = null, $resort_id = null)
    {
        $qb = $this->getQueryBuilder()
            ->select('h')
            ->from('DomainObjectModel_Hotel', 'h')
            ->where('h.hotel_title LIKE ?');

        $placeholders = array('%'. $title .'%');

        if ($country_id > 0) {
            $qb->andWhere('h.country_id = ?');
            $placeholders[] = $country_id;
        }

        if ($resort_id > 0) {
            $qb->andWhere('h.resort_id = ?');
            $placeholders[] = $resort_id;
        }

        return $this->getArrayResult($qb, $placeholders);
    }

    public function suggest($query, array $filter = array(), $limit = 1000, $is_ignore_query = true)
    {
        $qb = $this->getQueryBuilder()
                   ->select('h')
                   ->from('DomainObjectModel_Hotel', 'h')
                   ->where('h.hotel_status = ?')
                   ->offset(0);

        if (!empty($limit)) {
            $qb->limit($limit);
        }

        $placeholders = array(
            'ENABLED',
        );

        if (!$is_ignore_query && !empty($query)) {
            $qb->andWhere('h.hotel_title LIKE ?');

            $placeholders[] = '%'. $query .'%';
        }

        if (!empty($filter['country_id'])) {
            $qb->andWhere('h.country_id = ?');

            $placeholders[] = $filter['country_id'];
        }

        if (!empty($filter['resort_id'])) {
            $qb->andWhere('h.resort_id = ?');

            $placeholders[] = $filter['resort_id'];
        }

        return $this->getArrayResult($qb, $placeholders);
    }

    /**
     * @param string $signature
     * @param integer|null $country_id
     * @param integer|null $resort_id
     * @param boolean $is_unique_only
     * @return DomainObjectModel_Hotel|null
     */
    public function findBySignature($signature, $country_id = null, $resort_id = null, $is_unique_only = false)
    {
        $qb = $this->getQueryBuilder()
                   ->select('h')
                   ->from('DomainObjectModel_Hotel', 'h')
                   ->where('h.hotel_signature = ?');

        $placeholders = array($signature);

        if ($country_id !==  null) {
            $qb->andWhere('h.country_id = ?');
            $placeholders[] = $country_id;
        }

        if ($resort_id !== null) {
            $qb->andWhere('h.resort_id = ?');
            $placeholders[] = $resort_id;
        }

        $result = $this->getMultiFound($qb, $placeholders);

        if ($is_unique_only && count($result) > 1) {
            return array();
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getExternalIdKeys()
    {
        $qb = $this->getQueryBuilder()
            ->select('h.hotel_external_id')
            ->from('DomainObjectModel_Hotel', 'h');

        $external_ids = $this->getArrayResult($qb);

        $reform = array();

        foreach ($external_ids as $external_id) {
            $reform[$external_id['hotel_external_id']] = $external_id['hotel_external_id'];
        }

        return $reform;
    }
}