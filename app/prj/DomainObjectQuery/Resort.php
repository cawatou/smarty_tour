<?php
DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Resort extends DomainObjectQuery implements DataListQuery
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
            ->select('r', 'c')
            ->from('DomainObjectModel_Resort', 'r')
            ->innerJoin('r.Country', 'c');

        if (!empty($search_params['country_id'])) {
            $qb->andWhere('r.country_id = ?');
            $placeholders[] = $search_params['country_id'];
        }

        if (!empty($search_params['country_ids']) && is_array($search_params['country_ids'])) {
            $qb->andWhereIn('c.country_id', $search_params['country_ids']);
            $placeholders = array_merge($placeholders, $search_params['country_ids']);
        }

        if (!empty($search_params['country_status'])) {
            $qb->andWhere('c.country_status = ?');
            $placeholders[] = $search_params['country_status'];
        }

        if (!empty($search_params['resort_status'])) {
            $qb->andWhere('r.resort_status = ?');
            $placeholders[] = $search_params['resort_status'];
        }

        if (!empty($search_params['resort_weather_status'])) {
            $qb->andWhere('r.resort_weather_status = ?');
            $placeholders[] = $search_params['resort_weather_status'];
        }

        if (!empty($search_params['resort_title'])) {
            $qb->andWhere('r.resort_title LIKE ?');
            $placeholders[] = '%'. $search_params['resort_title'] .'%';
        }

        if (!empty($search_params['country_alias'])) {
            $qb->andWhere('c.country_alias = ?');
            $placeholders[] = $search_params['country_alias'];
        }

        if (!empty($search_params['country_id'])) {
            $qb->andWhere('c.country_id = ?');
            $placeholders[] = $search_params['country_id'];
        }

        if (!empty($search_params['country_status'])) {
            $qb->andWhere('c.country_status = ?');
            $placeholders[] = $search_params['country_status'];
        }

        if (empty($order_params)) {
            $qb->orderBy('r.resort_title', 'ASC');
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
     * @return DomainObjectModel_Resort|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('r')
            ->from('DomainObjectModel_Resort', 'r')
            ->where('r.resort_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findByIds(array $ids)
    {
        $qb = $this->getQueryBuilder()
            ->select('r')
            ->from('DomainObjectModel_Resort', 'r')
            ->where('r.resort_id IN (?)');

        $placeholders = array(implode(', ', $ids));

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param string $alias
     * @return DomainObjectModel_Resort|null
     */
    public function findByAlias($alias)
    {
        $qb = $this->getQueryBuilder()
            ->select('r')
            ->from('DomainObjectModel_Resort', 'r')
            ->where('r.resort_alias = ?');

        return $this->getSingleFound($qb, array($alias));
    }

    /**
     * @param string $external_id
     * @return DomainObjectModel_Resort|null
     */
    public function findByExternalId($external_id)
    {
        $qb = $this->getQueryBuilder()
            ->select('r')
            ->from('DomainObjectModel_Resort', 'r')
            ->where('r.resort_external_id = ?');

        return $this->getSingleFound($qb, array($external_id));
    }

    /**
     * @param array $external_ids
     * @return array
     */
    public function findByExternalResortIds(array $external_ids)
    {
        $qb = $this->getQueryBuilder()
            ->select('r')
            ->from('DomainObjectModel_Resort', 'r')
            ->where('r.resort_external_id IN (?)');

        return $this->getMultiFound($qb, array(implode(', ', $external_ids)));
    }

    /**
     * @param string $str
     * @return DomainObjectModel_Resort|null
     */
    public function findByTitleOrAlias($str)
    {
        $qb = $this->getQueryBuilder()
            ->select('r')
            ->from('DomainObjectModel_Resort', 'r')
            ->where('r.resort_title = ?')
            ->orWhere('r.resort_alias = ?');

        return $this->getSingleFound($qb, array($str, $str));
    }

    /**
     * @param bool $enabled
     * @return array
     */
    public function getAll($enabled = false)
    {
        $qb = $this->getQueryBuilder()
            ->select('r')
            ->from('DomainObjectModel_Resort', 'r')
            ->orderBy('r.resort_title', 'ASC');

        $placeholders = array();

        if ($enabled) {
            $qb->andWhere('r.resort_status = ?');
            $placeholders = array('ENABLED');
        }

        return $this->getArrayResult($qb, $placeholders);
    }

    /**
     * @param int $country_id
     * @param boolean $is_enabled
     * @return array
     */
    public function getByCountryId($country_id, $is_enabled = false)
    {
        $qb = $this->getQueryBuilder()
            ->select('r')
            ->from('DomainObjectModel_Resort', 'r')
            ->where('r.country_id = ?');

        $placeholders = array($country_id);

        if ($is_enabled) {
            $qb->andWhere('r.resort_status = ?');
            $placeholders[] = 'ENABLED';
        }

        return $this->getArrayResult($qb, $placeholders);
    }

    public function suggest($query, array $filter = array())
    {
        $qb = $this->getQueryBuilder()
                   ->select('r')
                   ->from('DomainObjectModel_Resort', 'r')
                   ->where('r.resort_status = ?')
                   ->offset(0)
                   ->limit(500);

        $placeholders = array(
            'ENABLED',
        );

        if (!empty($filter['country_id'])) {
            $qb->andWhere('r.country_id = ?');

            $placeholders[] = $filter['country_id'];
        }

        return $this->getArrayResult($qb, $placeholders);
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('r')
            ->from('DomainObjectModel_Resort', 'r')
            ->where('r.resort_id = ?');

        $res = $this->getArrayResult($qb, array($id));

        return empty($res[0]) ? array() : $res[0];
    }
}