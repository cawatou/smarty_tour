<?php

class DomainObjectQuery_Block extends DomainObjectQuery
{
    /**
     * @param int $id
     * @return DomainObjectController_Block|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('b')
            ->from('DomainObjectModel_Block', 'b')
            ->where('b.block_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $qb = $this->getQueryBuilder()
            ->select('b')
            ->from('DomainObjectModel_Block', 'b')
            ->orderBy('b.block_title', 'ASC');

        $placeholders = array();

        return $this->getArrayResult($qb, $placeholders);
    }

    /**
     * @param string $alias
     * @return DomainObjectController_Block|null
     */
    public function findByAlias($alias)
    {
        $qb = $this->getQueryBuilder()
            ->select('b')
            ->from('DomainObjectModel_Block', 'b')
            ->where('b.block_alias = ?');

        return $this->getSingleFound($qb, array($alias));
    }
    /**
     * @param string $alias
     * @return array|null
     */
    public function getByAlias($alias)
    {
        $qb = $this->getQueryBuilder()
            ->select('b')
            ->from('DomainObjectModel_Block', 'b')
            ->where('b.block_alias = ?');

        return $this->getArrayResult($qb, array($alias));
    }

    public function findForList()
    {
        $qb = $this->getQueryBuilder()
            ->select('b')
            ->from('DomainObjectModel_Block', 'b')
            ->orderBy('b.block_qnt', 'ASC');

        $placeholders = array();

        $res = $this->getMultiFound($qb, $placeholders);
        $list = array();
        foreach ($res as $o) {
            $list[$o->getCategory()][] = $o;
        }
        return $list;
    }

    /**
     * @return int
     */
    public function getMaxQnt()
    {
        $qb = $this->getQueryBuilder()
            ->select('MAX(b.block_qnt) as b')
            ->from('DomainObjectModel_Block', 'b');

        $res = $this->getArrayResult($qb, array());
        return empty($res[0]['m']) ? 0 : $res[0]['m'];
    }

    public function getAllGrouped()
    {
        $_blocks = $this->getAll();
        $blocks  = array();

        foreach ($_blocks as $_block) {
            $blocks[$_block['block_category']][$_block['block_alias']] = $_block['block_content'];
        }

        unset($_blocks);

        return $blocks;
    }
}
