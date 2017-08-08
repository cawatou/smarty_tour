<?php

class DomainObjectQuery_ProductCategory extends DomainObjectQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_ProductCategory|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('pc')
            ->from('DomainObjectModel_ProductCategory', 'pc')
            ->where('pc.product_category_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param string $alias
     * @return DomainObjectModel_ProductCategory|null
     */
    public function findByAlias($alias)
    {
        $qb = $this->getQueryBuilder()
            ->select('pc')
            ->from('DomainObjectModel_ProductCategory', 'pc')
            ->where('pc.product_category_alias = ?');

        return $this->getSingleFound($qb, array($alias));
    }

    /**
     * @param string $str
     * @return DomainObjectModel_ProductCategory|null
     */
    public function findByTitleOrAlias($str)
    {
        $qb = $this->getQueryBuilder()
            ->select('pc')
            ->from('DomainObjectModel_ProductCategory', 'pc')
            ->where('pc.product_category_title = ?')
            ->orWhere('pc.product_category_alias = ?');

        return $this->getSingleFound($qb, array($str, $str));
    }

    /**
     * @param array $params
     * @return array
     */
    public function &getTree(array $params = array())
    {
        $qb = $this->getQueryBuilder()
            ->select('pc')
            ->from('DomainObjectModel_ProductCategory', 'pc')
            ->orderBy('pc.lft', 'ASC');

        $placeholders = array();

        if (array_key_exists('lft >=', $params)) {
            $qb->andWhere('pc.lft >= ?');
            $placeholders[] = $params['lft >='];
        }

        if (array_key_exists('rgt <=', $params)) {
            $qb->andWhere('pc.rgt <= ?');
            $placeholders[] = $params['rgt <='];
        }

        if (array_key_exists('level >=', $params)) {
            $qb->andWhere('pc.level >= ?');
            $placeholders[] = $params['level >='];
        }

        $res =& $this->getArrayResult($qb->setParameters($placeholders));

        if (array_key_exists('enabled', $params)) {
            $enabled = array();

            $lft = 0;
            $rgt = 0;
            foreach ($res as &$n) {
                if ($n['product_category_status'] == 'DISABLED') {
                    $lft = $n['lft'];
                    $rgt = $n['rgt'];
                } else {
                    if ($n['lft'] > $lft && $n['rgt'] < $rgt) {
                        continue;
                    } else {
                        $enabled[] = $n;
                    }
                }
            }

            $res =& $enabled;
        }

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
     * @param $category_id
     * @return array
     */
    public function getCategoriesIds($category_id)
    {
        $c = $this->findById($category_id);
        if ($c->getStatus() == 'DISABLED') {
            return array(-1);
        } elseif ($c->getContainsProducts()) {
            return array($c->getId());
        }

        $params = array(
            'enabled' => true,
            'lft >='  => $c->getLft(),
            'rgt <='  => $c->getRgt()
        );

        $tree =& $this->getTree($params);
        $ids  = array();
        foreach ($tree as &$v) {
            if ($v['product_category_status'] == 'ENABLED' && $v['product_category_contains_products']) {
                $ids[] = $v['product_category_id'];
            }
        }

        if (empty($ids)) {
            $ids = array(-1);
        }

        return $ids;
    }
}