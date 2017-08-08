<?php

DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_GalleryImage extends DomainObjectQuery implements DataListQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_GalleryImage|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('gi')
            ->from('DomainObjectModel_GalleryImage', 'gi')
            ->where('gi.gallery_image_id = ?');

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
            ->select('gi', 'g')
            ->from('DomainObjectModel_GalleryImage', 'gi')
            ->innerJoin('gi.Gallery', 'g')
            ->orderBy('gi.gallery_image_qnt', 'ASC');

        if (!empty($search_params['gallery_id'])) {
            $qb->andWhere('g.gallery_id = ?');
            $placeholders[] = $search_params['gallery_id'];
        }

        if (!empty($search_params['gallery_alias'])) {
            $qb->andWhere('g.gallery_alias = ?');
            $placeholders[] = $search_params['gallery_alias'];
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
     * @param null $limit
     * @return DomainObjectModel_GalleryImage|null
     */
    public function &findByGalleryAlias($alias, $limit = null)
    {
        $qb = $this->getQueryBuilder()
            ->select('gi', 'g')
            ->from('DomainObjectModel_GalleryImage', 'gi')
            ->innerJoin('gi.Gallery', 'g')
            ->where('g.gallery_alias = ?')
            ->andWhere('gi.gallery_image_status = ?')
            ->orderBy('gi.gallery_image_qnt', 'ASC');

        if (!is_null($limit)) {
            $qb->offset(0)->limit($limit);
        }

        return $this->getMultiFound($qb, array($alias, 'ENABLED'));
    }

    /**
     * @param int $image_qnt
     * @param int $gallery_id
     * @return DomainObjectModel_GalleryImage|null
     */
    public function findLeftImage($image_qnt, $gallery_id)
    {
        $qb = $this->getQueryBuilder()
            ->select('gi')
            ->from('DomainObjectModel_GalleryImage', 'gi')
            ->where('gi.gallery_image_qnt < ?')
            ->andWhere('gi.gallery_id = ?')
            ->orderBy('gi.gallery_image_qnt', 'DESC')
            ->offset(0)
            ->limit(1);
        return $this->getSingleFound($qb, array($image_qnt, $gallery_id));
    }

    /**
     * @param int $image_qnt
     * @param int $gallery_id
     * @return DomainObjectModel_GalleryImage|null
     */
    public function findRightImage($image_qnt, $gallery_id)
    {
        $qb = $this->getQueryBuilder()
            ->select('gi')
            ->from('DomainObjectModel_GalleryImage', 'gi')
            ->where('gi.gallery_image_qnt > ?')
            ->andWhere('gi.gallery_id = ?')
            ->orderBy('gi.gallery_image_qnt', 'ASC')
            ->offset(0)
            ->limit(1);

        return $this->getSingleFound($qb, array($image_qnt, $gallery_id));
    }
}
