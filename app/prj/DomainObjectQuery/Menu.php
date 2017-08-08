<?php

class DomainObjectQuery_Menu extends DomainObjectQuery
{
    /**
     * @param int $id
     * @return DomainObjectModel_Menu|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('m')
            ->from('DomainObjectModel_Menu', 'm')
            ->where('m.menu_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param array $params
     * @return array
     */
    public function &getTree(array $params = array())
    {
        $qb = $this->getQueryBuilder()
            ->select('m')
            ->from('DomainObjectModel_Menu', 'm')
            ->orderBy('m.lft', 'ASC');

        $placeholders = array();
        if (array_key_exists('root_id', $params)) {
            $qb->andWhere('root_id = ?');
            $placeholders[] = $params['root_id'];
        }

        if (array_key_exists('level >=', $params)) {
            $qb->andWhere('level >= ?');
            $placeholders[] = $params['level >='];
        }

        $res =& $this->getArrayResult($qb->setParameters($placeholders));

        $lft     = array();
        $rgt     = array();
        $result  = array();

        foreach ($res as $k => $n) {
            if (!isset($result[$n['root_id']])) {
                $result[$n['root_id']] = array();
                $lft[$n['root_id']]    = array();
                $rgt[$n['root_id']]    = array();
            }

            $lft[$n['root_id']][$n['lft']] = 1;
            $rgt[$n['root_id']][$n['rgt']] = 1;

            if (isset($rgt[$n['root_id']][$n['rgt'] + 1])) {
                $n['next_sibling'] = 0;
            } else {
                $n['next_sibling'] = 1;
            }

            if (isset($rgt[$n['root_id']][$n['lft'] - 1])) {
                $n['prev_sibling'] = 1;
            } else {
                $n['prev_sibling'] = 0;
            }

            if ($n['level'] == 0) {
                $n['next_sibling'] = 0;
                $n['prev_sibling'] = 0;
            }

            $result[$n['root_id']][] = $n;
        }

        foreach ($result as &$nodes) {
            $parents = array();
            foreach ($nodes as $k => &$n) {
                if (!array_key_exists($n['level'] - 1, $parents)) {
                    $parents[$n['level'] - 1] = null;
                }

                if (array_key_exists($k + 1, $nodes) && $n['level'] < $nodes[$k + 1]['level']) {
                    $parents[$n['level']] = $n['menu_id'];
                }

                $n['parent_id'] = $parents[$n['level'] - 1];
            }
        }

        return $result;
    }

    /**
     * @param string $type
     * @param mixed  $value
     * @return DomainObjectModel_Menu[]|array
     */
    public function findByTypeAndValue($type, $value = null)
    {
        $qb = $this->getQueryBuilder()
            ->select('m')
            ->from('DomainObjectModel_Menu', 'm')
            ->where('m.menu_type = ?');

        $placeholders = array($type);

        if (!is_null($value)) {
            $qb->andWhere('m.menu_value = ?');
            $placeholders[] = $value;
        }

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param string $alias
     * @return DomainObjectModel_Menu|null
     */
    public function findByAlias($alias)
    {
        $qb = $this->getQueryBuilder()
            ->select('m')
            ->from('DomainObjectModel_Menu', 'm')
            ->where('m.menu_alias = ?');

        return $this->getSingleFound($qb, array($alias));
    }
}
