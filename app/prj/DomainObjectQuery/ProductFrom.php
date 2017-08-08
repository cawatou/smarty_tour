<?php

class DomainObjectQuery_ProductFrom extends DomainObjectQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_ProductFrom|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('pf')
            ->from('DomainObjectModel_ProductFrom', 'pf')
            ->where('pf.product_from_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @return DomainObjectModel_ProductFrom[]
     */
    public function findAll()
    {
        $qb = $this->getQueryBuilder()
            ->select('pf')
            ->from('DomainObjectModel_ProductFrom', 'pf');

        $placeholders = array();

        return $this->getMultiFound($qb, $placeholders);
    }
}
