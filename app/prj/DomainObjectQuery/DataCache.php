<?php

class DomainObjectQuery_DataCache extends DomainObjectQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_DataCache|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('dc')
            ->from('DomainObjectModel_DataCache', 'dc')
            ->where('dc.data_cache_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param string $alias
     * @return array
     */
    public function findByAlias($alias)
    {
        $qb = $this->getQueryBuilder()
            ->select('dc')
            ->from('DomainObjectModel_DataCache', 'dc')
            ->where('dc.data_cache_alias = ?');

        return $this->getSingleFound($qb, array($alias));
    }
}
