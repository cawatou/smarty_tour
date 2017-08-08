<?php

class DomainObjectQuery_Page extends DomainObjectQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_Page|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Page', 'p')
            ->where('p.page_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param string $path
     * @return DomainObjectModel_Page|null
     */
    public function findByPath($path)
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Page', 'p')
            ->where('p.page_path = ?');

        return $this->getSingleFound($qb, array($path));
    }

    /**
     * @return array
     */
    public function &getTree()
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Page', 'p')
            ->orderBy('p.lft', 'ASC');

        $res =& $this->getArrayResult($qb);

        $lft = array();
        $rgt = array();

        foreach ($res as $k => &$n) {
            $lft[$n['lft']] = 1;
            $rgt[$n['rgt']] = 1;

            if (isset($rgt[$n['rgt'] + 1])) {
                $res[$k]['next_sibling'] = 0;
            } else {
                $res[$k]['next_sibling'] = 1;
            }

            if (isset($rgt[$n['lft'] - 1])) {
                $res[$k]['prev_sibling'] = 1;
            } else {
                $res[$k]['prev_sibling'] = 0;
            }
        }

        return $res;
    }

    /**
     * @return array
     */
    public function getChildrens(DomainObjectModel_Page $page, $level = null)
    {
        $qb = $this->getQueryBuilder()
            ->select('p')
            ->from('DomainObjectModel_Page', 'p')
            ->where('p.lft > ?')
            ->andWhere('p.rgt < ?')
            ->orderBy('p.lft', 'ASC');

        $placeholders = array(
            $page->getLft(),
            $page->getRgt(),
        );

        if ($level > 0) {
            $qb->andWhere('p.level = ?');

            $placeholders[] = $level;
        }

        $res = $this->getMultiFound($qb, $placeholders);

        return $res;
    }

    /**
     * @param string $from
     * @param string $to
     * @param int    $lft
     * @param int    $rgt
     */
    public function replacePath($from, $to, $lft, $rgt)
    {
        $qb = $this->getQueryBuilder()
            ->update('DomainObjectModel_Page', 'p')
            ->set('page_path', "REPLACE(page_path, ?, ?)")
            ->where('lft > ? AND rgt < ?');

        $this->getScalarResult($qb, array($from, $to, $lft, $rgt));
    }
}