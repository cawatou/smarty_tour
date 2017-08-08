<?php

DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Publication extends DomainObjectQuery implements DataListQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_Publication|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Publication', 'p')
            ->where('p.publication_id = ?');

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
            ->select('p')
            ->from('DomainObjectModel_Publication', 'p');

        if (!empty($search_params['publication_category'])) {
            $categories = (array)$search_params['publication_category'];
            $qb->andWhereIn('p.publication_category', $categories);
            $placeholders = array_merge($placeholders, $categories);
        }

        if (!empty($search_params['publication_status'])) {
            $qb->andWhere('p.publication_status = ?');
            $placeholders[] = $search_params['publication_status'];
        }

        if (!empty($search_params['publication_tags'])) {
            $qb->andWhere('p.publication_tags LIKE ?');
            $placeholders[] = "%{$search_params['publication_tags']}%";
        }

        if (!empty($search_params['is_active_date'])) {
            $qb->andWhere('p.publication_date <= NOW()');
        }

        if (!empty($search_params['publication_is_highlight'])) {
            $qb->andWhere('p.publication_is_highlight = ?');
            $placeholders[] = 1;
        }

        if (empty($order_params)) {
            $qb->orderBy('p.publication_date', 'DESC');
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
     * @param int $limit
     * @param string $category
     * @return DomainObjectModel_Publication[]|array
     */
    public function findLatest($limit = 3, $category = 'NEWS')
    {
        $categories = (array)$category;
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Publication', 'p')
            ->where('p.publication_status = ?')
            ->andWhereIn('p.publication_category', $categories)
            ->andWhere('p.publication_date <= NOW()')
            ->orderBy("p.publication_date", 'DESC')
            ->offset(0)
            ->limit($limit);

        $placeholders = array_merge(array('ENABLED'), $categories);
        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param array $params
     * @return DomainObjectModel_Publication[]|array
     */
    public function findByParams(array $params = array())
    {
        $search_params = empty($params[Form_Filter::FILTER_SEARCH_PARAMS]) ? array() : $params[Form_Filter::FILTER_SEARCH_PARAMS];
        $order_params  = empty($params[Form_Filter::FILTER_ORDER_PARAMS]) ? array() : $params[Form_Filter::FILTER_ORDER_PARAMS];
        $placeholders  = array();

        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Publication', 'p');

        if (!empty($search_params['publication_status'])) {
            $qb->andWhere('p.publication_status = ?');
            $placeholders[] = $search_params['publication_status'];
        }

        if (!empty($search_params['publication_category'])) {
            $qb->andWhere('p.publication_category = ?');
            $placeholders[] = $search_params['publication_category'];
        }

        if (!empty($search_params['is_active_date'])) {
            $qb->andWhere('p.publication_date <= NOW()');
        }

        if (!empty($search_params['publication_is_highlight'])) {
            $qb->andWhere('p.publication_is_highlight = ?');
            $placeholders[] = 1;
        }

        if (!empty($search_params['limit'])) {
            $qb->limit($search_params['limit']);
        }

        foreach ($order_params as $field => $direction) {
            $qb->orderBy('n.' . $field, $direction);
        }

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param string $signature
     * @return DomainObjectModel_Publication|null
     */
    public function findBySignature($signature)
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Publication', 'p')
            ->where('p.publication_signature = ?');

        return $this->getSingleFound($qb, array($signature));
    }

    /**
     * @param int $limit
     * @return DomainObjectModel_Publication|null
     */
    public function findByHighlight($limit = 1)
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Publication', 'p')
            ->where('p.publication_is_highlight = ?')
            ->andWhere('p.publication_status = ?')
            ->offset(0)
            ->limit($limit);

        return $this->getMultiFound($qb, array(1, 'ENABLED'));
    }
}