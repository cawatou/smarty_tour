<?php
class DomainObjectQuery_OrderPayment extends DomainObjectQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_OrderPayment|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('op')
            ->from('DomainObjectModel_OrderPayment', 'op')
            ->where('op.order_payment_id = ?');

        return $this->getSingleFound($qb, array($id));
    }
}
