<?php
DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Subdivision extends DomainObjectQuery implements DataListQuery
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
            ->select('s')
            ->from('DomainObjectModel_Subdivision', 's');

        if (!empty($search_params['subdivision_id'])) {
            $qb->andWhere('s.subdivision_id = ?');
            $placeholders[] = $search_params['subdivision_id'];
        }

        if (!empty($search_params['subdivision_status'])) {
            $qb->andWhere('s.subdivision_status = ?');
            $placeholders[] = $search_params['subdivision_status'];
        }

        if (!empty($search_params['subdivision_title'])) {
            $qb->andWhere('s.subdivision_title LIKE ?');
            $placeholders[] = '%'. $search_params['subdivision_title'] .'%';
        }

        if (empty($order_params)) {
            $qb->orderBy('s.subdivision_title', 'ASC');
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
     * @return DomainObjectModel_Subdivision|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Subdivision', 's')
            ->where('s.subdivision_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findByIds(array $ids)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Subdivision', 's')
            ->where('s.subdivision_id IN (?)');

        $placeholders = array(implode(', ', $ids));

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param string $alias
     * @return DomainObjectModel_Subdivision|null
     */
    public function findByAlias($alias)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Subdivision', 's')
            ->where('s.subdivision_alias = ?');

        return $this->getSingleFound($qb, array($alias));
    }

    /**
     * @param string $str
     * @return DomainObjectModel_Subdivision|null
     */
    public function findByTitle($str)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Subdivision', 's')
            ->where('s.subdivision_title LIKE ?');

        return $this->getSingleFound($qb, array('%'. $str .'%'));
    }

    /**
     * @param string $str
     * @return DomainObjectModel_Subdivision|null
     */
    public function findByTitleOrAlias($str)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Subdivision', 's')
            ->where('s.subdivision_title = ?')
            ->orWhere('s.subdivision_alias = ?');

        return $this->getSingleFound($qb, array($str, $str));
    }

    /**
     * @param bool $enabled
     * @return DomainObjectModel_Subdivision[]
     */
    public function findAll($enabled = false)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Subdivision', 's')
            ->orderBy('s.subdivision_title', 'ASC');

        $placeholders = array();

        if ($enabled) {
            $qb->andWhere('s.subdivision_status = ?');
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
            ->select('s')
            ->from('DomainObjectModel_Subdivision', 's')
            ->orderBy('s.subdivision_title', 'ASC');

        $placeholders = array();

        if ($enabled) {
            $qb->andWhere('s.subdivision_status = ?');
            $placeholders = array('ENABLED');
        }

        $qb->orderBy('s.subdivision_title', 'ASC');

        return $this->getArrayResult($qb, $placeholders);
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Subdivision', 's')
            ->where('s.subdivision_id = ?');

        $res = $this->getArrayResult($qb, array($id));

        return empty($res[0]) ? array() : $res[0];
    }
}