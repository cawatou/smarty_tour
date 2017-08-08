<?php

DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Gallery extends DomainObjectQuery implements DataListQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_Gallery|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('g')
            ->from('DomainObjectModel_Gallery', 'g')
            ->where('g.gallery_id = ?');

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
            ->select('g')
            ->from('DomainObjectModel_Gallery', 'g')
            ->orderBy('g.created', 'DESC');

        if (!empty($search_params['gallery_category'])) {
            $qb->andWhere('g.gallery_category = ?');
            $placeholders[] = $search_params['gallery_category'];
        }
        if (!empty($search_params['gallery_status'])) {
            $qb->andWhere('g.gallery_status = ?');
            $placeholders[] = $search_params['gallery_status'];
        }

        if (empty($order_params)) {
            $qb->orderBy('g.gallery_date', 'DESC');
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
     * @param string $alias
     * @return DomainObjectModel_Gallery
     */
    public function findByAlias($alias)
    {
        $qb = $this->getQueryBuilder()
            ->select('g')
            ->from('DomainObjectModel_Gallery', 'g')
            ->where('g.gallery_alias = ?')
            ->andWhere('g.gallery_status = ?');

        return $this->getSingleFound($qb, array($alias, 'ENABLED'));
    }

    /**
     * @param bool $enabled
     * @return DomainObjectModel_Gallery[]
     */
	public function findAll($enabled = false)
	{
        $qb = $this->getQueryBuilder()
            ->select('g')
            ->from('DomainObjectModel_Gallery', 'g')
            ->orderBy('g.gallery_title', 'ASC');

        $placeholders = array();

        if ($enabled) {
            $qb->andWhere('g.gallery_status = ?');
            $placeholders = array('ENABLED');
        }

        return $this->getMultiFound($qb, $placeholders);
	}

    /**
     * @return DomainObjectModel_Gallery|null
     */
    public function findByHighlight()
    {
        $qb = $this->getQueryBuilder()
            ->select('g')
            ->from('DomainObjectModel_Gallery', 'g')
            ->where('g.gallery_is_highlight = ?');

        return $this->getMultiFound($qb, array(1));
    }

    /**
     * @param $category
     * @param null $limit
     * @return DomainObjectModel_Gallery[]|null
     */
    public function findByCategory($category, $limit = null)
    {
        $qb = $this->getQueryBuilder()
            ->select('g')
            ->from('DomainObjectModel_Gallery', 'g')
            ->andWhere('g.gallery_status = ?')
            ->andWhere('g.gallery_category = ?')
            ->orderBy('g.gallery_date', 'DESC');

        if (!is_null($limit)) {
            $qb->offset(0)->limit($limit);
        }

        return $this->getMultiFound($qb, array('ENABLED', $category));
    }

    /**
     * @param $category
     * @param null $limit
     * @return array|null
     */
    public function getByCategory($category, $limit = null)
    {
        $qb = $this->getQueryBuilder()
            ->select('g')
            ->from('DomainObjectModel_Gallery', 'g')
            ->andWhere('g.gallery_status = ?')
            ->andWhere('g.gallery_category = ?')
            ->orderBy('g.gallery_date', 'DESC');

        if ($limit !== null) {
            $qb->offset(0)->limit($limit);
        }

        return $this->getArrayResult($qb, array('ENABLED', $category));
    }
}