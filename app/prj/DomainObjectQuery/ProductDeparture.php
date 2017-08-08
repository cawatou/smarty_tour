<?php

class DomainObjectQuery_ProductDeparture extends DomainObjectQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_ProductDeparture|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('pd')
            ->from('DomainObjectModel_ProductDeparture', 'pd')
            ->where('pd.product_departure_id = ?');

        return $this->getSingleFound($qb, array($id));
    }
}
