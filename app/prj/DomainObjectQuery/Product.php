<?php
DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_Product extends DomainObjectQuery implements DataListQuery
{
    const COUNTRY_ID_RUSSIA = 0;
    //const COUNTRY_ID_RUSSIA = 119;
    /**
     * @param array $params
     */
    public function initByListParams(array &$params = array())
    {
        $search_params = empty($params[Form_Filter::FILTER_SEARCH_PARAMS]) ? array() : $params[Form_Filter::FILTER_SEARCH_PARAMS];
        $order_params  = empty($params[Form_Filter::FILTER_ORDER_PARAMS])  ? array() : $params[Form_Filter::FILTER_ORDER_PARAMS];
        $placeholders  = array();

        $qb = $this->getQueryBuilder()
            ->select('p', 'pc', 'pi')
            ->from('DomainObjectModel_Product', 'p')
            ->innerJoin('p.Country', 'c')
            ->leftJoin('p.Resort', 'r')
            ->leftJoin('p.ProductImage', 'pi');

        if (!empty($search_params['country_id'])) {
            $qb->andWhere('p.country_id = ?');
            $placeholders[] = $search_params['country_id'];
        }

        if (!empty($search_params['tour_type'])) {
            if ($search_params['tour_type'] == 'RUSSIA') {
                $qb->andWhere('p.country_id = ?');
                $placeholders[] = self::COUNTRY_ID_RUSSIA;
            } else {
                $qb->andWhere('p.country_id <> ?');
                $placeholders[] = self::COUNTRY_ID_RUSSIA;
            }
        }

        if (!empty($search_params['user_id'])) {
            $qb->andWhere('p.user_id = ?');
            $placeholders[] = $search_params['user_id'];
        }

        if (!empty($search_params['product_from_id'])) {
            $qb->andWhere('p.product_from_id = ?');
            $placeholders[] = $search_params['product_from_id'];
        }

        if (!empty($search_params['product_from_ids'])) {
            $qb->andWhereIn('p.product_from_id', $search_params['product_from_ids']);
            $placeholders = array_merge($placeholders, $search_params['product_from_ids']);
        }

        if (!empty($search_params['country_ids'])) {
            $qb->andWhereIn('c.country_id', $search_params['country_ids']);
            $placeholders = array_merge($placeholders, $search_params['country_ids']);
        }

        if (!empty($search_params['resort_id'])) {
            $qb->andWhere('p.resort_id = ?');
            $placeholders[] = $search_params['resort_id'];
        }

        if (!empty($search_params['resort_ids'])) {
            $qb->andWhereIn('c.resort_id', $search_params['resort_ids']);
            $placeholders = array_merge($placeholders, $search_params['resort_ids']);
        }

        if (!empty($search_params['product_status'])) {
            $qb->andWhere('p.product_status = ?');
            $placeholders[] = $search_params['product_status'];
        }

        if (!empty($search_params['product_is_highlight'])) {
            $qb->andWhere('p.product_is_highlight = ?');
            $placeholders[] = $search_params['product_is_highlight'];
        }

        if (!empty($search_params['product_type'])) {
            $qb->andWhere('p.product_type = ?');
            $placeholders[] = $search_params['product_type'];
        }

        if (!empty($search_params['product_event'])) {
            $qb->andWhere('p.product_event = ?');
            $placeholders[] = $search_params['product_event'];
        }

        if (!empty($search_params['product_with_cover'])) {
            $qb->andWhere('pi.product_image_is_cover = ?');
            $placeholders[] = 1;
        }

        if (!empty($search_params['product_category_alias'])) {
            $qb->andWhere('pc.product_category_alias = ?');
            $placeholders[] = $search_params['product_category_alias'];
        }

        if (!empty($search_params['product_group_id'])) {
            $qb->andWhere('pgr.product_group_id = ?');
            $placeholders[] = $search_params['product_group_id'];
        }

        if (!empty($search_params['product_only_parent'])) {
            $qb->andWhere('p.product_linked_id IS NULL');
        }

        if (empty($order_params)) {
            $qb->orderBy('p.product_qnt', 'DESC');
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
     * @return DomainObjectModel_Product|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Product', 'p')
            ->where('p.product_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param array $ids
     * @param null $status
     * @return DomainObjectModel_Product[]|array
     */
    public function findByIds(array $ids, $status = null)
    {
        $ids = array_unique($ids);

        if (empty($ids)) {
            return array();
        }

        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Product', 'p')
            ->whereIn('p.product_id', $ids);

        if (!is_null($status)) {
            $qb->andWhere('p.product_status = ?');
            $ids = array_merge($ids, (array)$status);
        }

        return $this->getMultiFound($qb, $ids);
    }

    /**
     * @param string $str
     * @return DomainObjectModel_Product|null
     */
    public function findByTitleOrAlias($str)
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Product', 'p')
            ->where('p.product_title = ?')
            ->orWhere('p.product_alias = ?');

        return $this->getSingleFound($qb, array($str, $str));
    }

    /**
     * @param $str
     * @return DomainObjectModel_Product|null
     */
    public function findByIdOrAlias($str)
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Product', 'p')
            ->where('p.product_id = ?')
            ->orWhere('p.product_alias = ?');

        return $this->getSingleFound($qb, array($str, $str));
    }

    /**
     * @param int|null $limit
     * @param bool $randomly
     * @return DomainObjectModel_Product|null
     */
    public function findByHighlight($limit = 1, $randomly = false)
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Product', 'p')
            ->where('p.product_is_highlight = ?')
            ->andWhere('p.product_status = ?');

        if ($limit > 0) {
            $qb->offset(0)->limit($limit);
        }

        if ($randomly) {
            $qb->orderBy('RAND()', 'ASC');
        }

        return $this->getMultiFound($qb, array(1, 'ENABLED'));
    }

    /**
     * @return DomainObjectModel_Product[]|null
     */
    public function findForAdsList()
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Product', 'p')
            ->andWhere('p.product_status = ?')
            ->orderBy('p.product_price', 'ASC')
            ->groupBy('p.country_id, p.product_from_id');

        return $this->getMultiFound($qb, array('ENABLED'));
    }

    /**
     * @return DomainObjectModel_Product|null
     */
    public function findForAds($fromAlias, $countryAlias)
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Product', 'p')
            ->andWhere('p.product_status = ?')
            ->andWhere('p.product_from_id = ?')
            ->andWhere('p.country_id = ?')
            ->orderBy('p.product_price', 'ASC')
            ->offset(0)
            ->limit(1);

        $froms = DomainObjectModel_Product::getFromAll();

        $fromId = null;

        foreach ($froms as $fId => $f) {
            if ($fromAlias == $f['alias']) {
                $fromId = $fId;

                break;
            }
        }

        if ($fromId === null) {
            return null;
        }

        $q = DxFactory::getInstance('DomainObjectQuery_Country');

        $country = $q->findByAlias($countryAlias);

        if (!$country) {
            return null;
        }

        return $this->getSingleFound($qb, array('ENABLED', $fromId, $country->getId()));
    }

    /**
     * @return DomainObjectModel_Product|null
     */
    public function findForRussiaAds($fromAlias)
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Product', 'p')
            ->where('p.product_is_highlight = ?')
            ->andWhere('p.product_status = ?')
            ->andWhere('p.product_from_id = ?')
            ->andWhere('p.country_id = ?')
            ->groupBy('p.country_id, p.product_from_id');

        $froms = DomainObjectModel_Product::getFromAll();

        $fromId = null;

        foreach ($froms as $fId => $f) {
            if ($fromAlias == $f['alias']) {
                $fromId = $fId;

                break;
            }
        }

        if ($fromId === null) {
            return null;
        }

        return $this->getSingleFound($qb, array(1, 'ENABLED', $fromId, self::COUNTRY_ID_RUSSIA));
    }

    /**
     * @return int
     */
    public function getMaxQnt()
    {
        $qb = $this->getQueryBuilder()
            ->select('MAX(p.product_qnt) as m')
            ->from('DomainObjectModel_Product', 'p');

        $res = $this->getArrayResult($qb, array());
        return empty($res[0]['m']) ? 0 : $res[0]['m'];
    }

    public function findByParams(array $params, $limit = 4)
    {
        $search_params = empty($params[Form_Filter::FILTER_SEARCH_PARAMS]) ? array() : $params[Form_Filter::FILTER_SEARCH_PARAMS];
        $order_params  = empty($params[Form_Filter::FILTER_ORDER_PARAMS])  ? array() : $params[Form_Filter::FILTER_ORDER_PARAMS];
        $placeholders  = array();

        $qb = $this->getQueryBuilder()
            ->select('p', 'pc', 'pi')
            ->from('DomainObjectModel_Product', 'p')
            ->innerJoin('p.Country', 'c')
            ->leftJoin('p.Resort', 'r')
            ->leftJoin('p.ProductImage', 'pi');

        if (!empty($search_params['country_id'])) {
            $qb->andWhere('p.country_id = ?');
            $placeholders[] = $search_params['country_id'];
        }

        if (!empty($search_params['user_id'])) {
            $qb->andWhere('p.user_id = ?');
            $placeholders[] = $search_params['user_id'];
        }

        if (!empty($search_params['product_from_id'])) {
            $qb->andWhere('p.product_from_id = ?');
            $placeholders[] = $search_params['product_from_id'];
        }

        if (!empty($search_params['product_from_ids'])) {
            $qb->andWhereIn('p.product_from_id', $search_params['product_from_ids']);
            $placeholders = array_merge($placeholders, $search_params['product_from_ids']);
        }

        if (!empty($search_params['country_ids'])) {
            $qb->andWhereIn('c.country_id', $search_params['country_ids']);
            $placeholders = array_merge($placeholders, $search_params['country_ids']);
        }

        if (!empty($search_params['resort_id'])) {
            $qb->andWhere('p.resort_id = ?');
            $placeholders[] = $search_params['resort_id'];
        }

        if (!empty($search_params['resort_ids'])) {
            $qb->andWhereIn('c.resort_id', $search_params['resort_ids']);
            $placeholders = array_merge($placeholders, $search_params['resort_ids']);
        }

        if (!empty($search_params['product_status'])) {
            $qb->andWhere('p.product_status = ?');
            $placeholders[] = $search_params['product_status'];
        }

        if (!empty($search_params['product_is_highlight'])) {
            $qb->andWhere('p.product_is_highlight = ?');
            $placeholders[] = $search_params['product_is_highlight'];
        }

        if (!empty($search_params['product_type'])) {
            $qb->andWhere('p.product_type = ?');
            $placeholders[] = $search_params['product_type'];
        }

        if (!empty($search_params['product_event'])) {
            $qb->andWhere('p.product_event = ?');
            $placeholders[] = $search_params['product_event'];
        }

        if (!empty($search_params['product_with_cover'])) {
            $qb->andWhere('pi.product_image_is_cover = ?');
            $placeholders[] = 1;
        }

        if (!empty($search_params['product_category_alias'])) {
            $qb->andWhere('pc.product_category_alias = ?');
            $placeholders[] = $search_params['product_category_alias'];
        }

        if (!empty($search_params['product_group_id'])) {
            $qb->andWhere('pgr.product_group_id = ?');
            $placeholders[] = $search_params['product_group_id'];
        }

        if (empty($order_params)) {
            $qb->orderBy('RAND()', 'ASC');
        } else {
            foreach ($order_params as $field => $condition) {
                $qb->addOrderBy($field, $condition);
            }
        }

        $qb->offset(0)->limit($limit);

        return $this->getMultiFound($qb, $placeholders);
    }

    public function findMinPrice(array $search_params)
    {
        $qb = $this->getQueryBuilder()
            ->select('p', 'pc', 'pi')
            ->from('DomainObjectModel_Product', 'p')
            ->innerJoin('p.Country', 'c')
            ->leftJoin('p.Resort', 'r')
            ->leftJoin('p.ProductImage', 'pi')
            ->offset(0)
            ->limit(1);

        if (!empty($search_params['country_id'])) {
            $qb->andWhere('p.country_id = ?');
            $placeholders[] = $search_params['country_id'];
        }

        if (!empty($search_params['tour_type'])) {
            if ($search_params['tour_type'] == 'RUSSIA') {
                $qb->andWhere('p.country_id = ?');
                $placeholders[] = self::COUNTRY_ID_RUSSIA;
            } else {
                $qb->andWhere('p.country_id <> ?');
                $placeholders[] = self::COUNTRY_ID_RUSSIA;
            }
        }

        if (!empty($search_params['user_id'])) {
            $qb->andWhere('p.user_id = ?');
            $placeholders[] = $search_params['user_id'];
        }

        if (!empty($search_params['product_from_id'])) {
            $qb->andWhere('p.product_from_id = ?');
            $placeholders[] = $search_params['product_from_id'];
        }

        if (!empty($search_params['product_from_ids'])) {
            $qb->andWhereIn('p.product_from_id', $search_params['product_from_ids']);
            $placeholders = array_merge($placeholders, $search_params['product_from_ids']);
        }

        if (!empty($search_params['country_ids'])) {
            $qb->andWhereIn('c.country_id', $search_params['country_ids']);
            $placeholders = array_merge($placeholders, $search_params['country_ids']);
        }

        if (!empty($search_params['resort_id'])) {
            $qb->andWhere('p.resort_id = ?');
            $placeholders[] = $search_params['resort_id'];
        }

        if (!empty($search_params['resort_ids'])) {
            $qb->andWhereIn('c.resort_id', $search_params['resort_ids']);
            $placeholders = array_merge($placeholders, $search_params['resort_ids']);
        }

        if (!empty($search_params['product_status'])) {
            $qb->andWhere('p.product_status = ?');
            $placeholders[] = $search_params['product_status'];
        }

        if (!empty($search_params['product_is_highlight'])) {
            $qb->andWhere('p.product_is_highlight = ?');
            $placeholders[] = $search_params['product_is_highlight'];
        }

        if (!empty($search_params['product_type'])) {
            $qb->andWhere('p.product_type = ?');
            $placeholders[] = $search_params['product_type'];
        }

        if (!empty($search_params['product_event'])) {
            $qb->andWhere('p.product_event = ?');
            $placeholders[] = $search_params['product_event'];
        }

        if (!empty($search_params['product_with_cover'])) {
            $qb->andWhere('pi.product_image_is_cover = ?');
            $placeholders[] = 1;
        }

        if (!empty($search_params['product_category_alias'])) {
            $qb->andWhere('pc.product_category_alias = ?');
            $placeholders[] = $search_params['product_category_alias'];
        }

        if (!empty($search_params['product_group_id'])) {
            $qb->andWhere('pgr.product_group_id = ?');
            $placeholders[] = $search_params['product_group_id'];
        }

        $qb->andWhere('p.product_discount_price > 0');

        $qb->orderBy('p.product_discount_price', 'ASC');

        return $this->getSingleFound($qb, $placeholders);
    }
}