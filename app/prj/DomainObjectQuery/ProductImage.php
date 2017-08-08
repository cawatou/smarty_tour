<?php

class DomainObjectQuery_ProductImage extends DomainObjectQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_ProductImage|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('pi')
            ->from('DomainObjectModel_ProductImage', 'pi')
            ->where('pi.product_image_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param $product_id
     * @return DomainObjectModel_ProductImage|null
     */
    public function findCurrentCover($product_id)
    {
        $qb = $this->getQueryBuilder()
            ->select('pi')
            ->from('DomainObjectModel_ProductImage', 'pi')
            ->where('pi.product_image_is_cover = ?')
            ->andWhere('pi.product_id = ?');

        return $this->getSingleFound($qb, array(1, $product_id));
    }
}
