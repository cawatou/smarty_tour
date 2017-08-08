<?php
DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Country extends DomainObjectQuery implements DataListQuery
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
            ->select('c')
            ->from('DomainObjectModel_Country', 'c');

        if (!empty($search_params['country_id'])) {
            $qb->andWhere('c.country_id = ?');
            $placeholders[] = $search_params['country_id'];
        }

        if (!empty($search_params['country_status'])) {
            $qb->andWhere('c.country_status = ?');
            $placeholders[] = $search_params['country_status'];
        }

        if (!empty($search_params['country_title'])) {
            $qb->andWhere('c.country_title LIKE ?');
            $placeholders[] = '%'. $search_params['country_title'] .'%';
        }

        if (empty($order_params)) {
            $qb->orderBy('c.country_title', 'ASC');
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
     * @return DomainObjectModel_Country|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Country', 'c')
            ->where('c.country_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findByIds(array $ids)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Country', 'c')
            ->where('c.country_id IN (?)');

        $placeholders = array(implode(', ', $ids));

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param string $alias
     * @return DomainObjectModel_Country|null
     */
    public function findByAlias($alias)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Country', 'c')
            ->where('c.country_alias = ?');

        return $this->getSingleFound($qb, array($alias));
    }

    /**
     * @param string $external_id
     * @return DomainObjectModel_Country|null
     */
    public function findByExternalId($external_id)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Country', 'c')
            ->where('c.country_external_id = ?');

        return $this->getSingleFound($qb, array($external_id));
    }

    /**
     * @param array $external_ids
     * @return array
     */
    public function findByExternalIds(array $external_ids)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Country', 'c')
            ->where('c.country_external_id IN (?)');

        return $this->getMultiFound($qb, array(implode(', ', $external_ids)));
    }

    /**
     * @param string $str
     * @return DomainObjectModel_Country|null
     */
    public function findByTitleOrAlias($str)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Country', 'c')
            ->where('c.country_title = ?')
            ->orWhere('c.country_alias = ?');

        return $this->getSingleFound($qb, array($str, $str));
    }

    /**
     * @param bool $enabled
     * @return DomainObjectModel_Country[]
     */
    public function findAll($enabled = false)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Country', 'c')
            ->orderBy('c.country_title', 'ASC');

        $placeholders = array();

        if ($enabled) {
            $qb->andWhere('c.country_status = ?');
            $placeholders = array('ENABLED');
        }

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param bool $enabled
     * @return array
     */
    public function getAll($enabled = false)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Country', 'c')
            ->orderBy('c.country_title', 'ASC');

        $placeholders = array();

        if ($enabled) {
            $qb->andWhere('c.country_status = ?');
            $placeholders = array('ENABLED');
        }

        return $this->getArrayResult($qb, $placeholders);
    }

    public function suggest($query)
    {
        $qb = $this->getQueryBuilder()
                   ->select('c')
                   ->from('DomainObjectModel_Country', 'c')
                   ->where('c.country_status = ?')
                   ->offset(0)
                   ->limit(100);

        $placeholders = array(
            'ENABLED',
        );

        return $this->getArrayResult($qb, $placeholders);
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Country', 'c')
            ->where('c.country_id = ?');

        $res = $this->getArrayResult($qb, array($id));

        return empty($res[0]) ? array() : $res[0];
    }

    /**
     * @return array
     */
    public function getByVisaDays()
    {
        $qb = $this->getQueryBuilder()
            ->select('c')
            ->from('DomainObjectModel_Country', 'c')
            ->where('c.country_visa_days IS NOT NULL')
            ->andWhere('c.country_status = ?');

        return $this->getArrayResult($qb, array('ENABLED'));
    }
}