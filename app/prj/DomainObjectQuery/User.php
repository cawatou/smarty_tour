<?php

DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_User extends DomainObjectQuery implements DataListQuery
{
    /**
     * @param array $params
     */
    public function initByListParams(array &$params = array())
    {
        $search_params = empty($params[Form_Filter::FILTER_SEARCH_PARAMS]) ? array() : $params[Form_Filter::FILTER_SEARCH_PARAMS];
        $order_params  = empty($params[Form_Filter::FILTER_ORDER_PARAMS]) ? array() : $params[Form_Filter::FILTER_ORDER_PARAMS];
        $placeholders  = array();

        $qb = $this->getQueryBuilder()
            ->select('u')
            ->from('DomainObjectModel_User', 'u');

        if (!empty($search_params['user_role'])) {
            $qb->andWhere('u.user_role = ?');
            $placeholders[] = $search_params['user_role'];
        }

        if (!empty($search_params['user_roles'])) {
            $qb->andWhereIn('u.user_role', $search_params['user_roles']);
            $placeholders = array_merge($placeholders, $search_params['user_roles']);
        }

        if (!empty($params['currentUser']) && $params['currentUser'] == 'DIRECTOR' && !empty($search_params['office_ids'])) {
            $qb->andWhere('u.subdivision_id = ? OR u.office_id IN (' . implode(', ', array_fill(0, count($search_params['office_ids']), '?')) . ')');

            $placeholders[] = $search_params['subdivision_id'];
            $placeholders = array_merge($placeholders, $search_params['office_ids']);
        } else {
            if (!empty($search_params['subdivision_id'])) {
                $qb->andWhere('u.subdivision_id = ?');
                $placeholders[] = $search_params['subdivision_id'];
            }

            if (!empty($search_params['office_ids'])) {
                $qb->andWhereIn('u.office_id', $search_params['office_ids']);
                $placeholders = array_merge($placeholders, $search_params['office_ids']);
            }
        }

        if (!empty($search_params['subdivision_ids'])) {
            $qb->andWhereIn('u.subdivision_id', $search_params['subdivision_ids']);
            $placeholders = array_merge($placeholders, $search_params['subdivision_ids']);
        }

        if (!empty($search_params['office_id'])) {
            $qb->andWhere('u.office_id = ?');
            $placeholders[] = $search_params['office_id'];
        }

        if (empty($order_params)) {
            $qb->orderBy('u.user_status', 'DESC')
               ->addOrderBy('u.user_name', 'ASC');
        } else {
            foreach ($order_params as $field => $condition) {
                $qb->addOrderBy($field, $condition);
            }
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
     * @param int $id
     * @return DomainObjectController_User|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('u')
            ->from('DomainObjectModel_User', 'u')
            ->where('u.user_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param array $ids
     * @return DomainObjectController_User[]|array
     */
    public function &findByIds(array $ids)
    {
        $qb = $this->getQueryBuilder()
            ->select('u')
            ->from('DomainObjectModel_User', 'u')
            ->whereIn('u.user_id', $ids);

        return $this->getMultiFound($qb, $ids);
    }

    /**
     * @param array $ids
     * @return DomainObjectController_User[]|array
     */
    public function findByOfficeIds(array $ids)
    {
        $qb = $this->getQueryBuilder()
            ->select('u')
            ->from('DomainObjectModel_User', 'u')
            ->whereIn('u.office_id', $ids)
            ->andWhere('u.user_status = ?');

        return $this->getMultiFound($qb, array_merge($ids, array('ENABLED')));
    }

    /**
     * @param string $login
     * @return DomainObjectController_User|null
     */
    public function findByLogin($login)
    {
        $qb = $this->getQueryBuilder()
            ->select('u')
            ->from('DomainObjectModel_User', 'u')
            ->where('u.user_login = ?');

        return $this->getSingleFound($qb, array($login));
    }

    /**
     * @param $identifier
     * @return DomainObjectController_User|null
     */
    public function findByIdentifier($identifier)
    {
        $qb = $this->getQueryBuilder()
            ->select('u')
            ->from('DomainObjectModel_User', 'u')
            ->where('u.user_identifier = ?');

        return $this->getSingleFound($qb, array($identifier));
    }

    /**
     * @return array
     */
    public function findByRole($role)
    {
        $qb = $this->getQueryBuilder()
            ->select('u')
            ->from('DomainObjectModel_User', 'u')
            ->andWhere('u.user_role = ?');

        return $this->getMultiFound($qb, array($role));
    }

    /**
     * @param string $role
     * @param int    $officeId
     * @return array
     */
    public function findByRoleAndOffice($role, $officeId)
    {
        $qb = $this->getQueryBuilder()
            ->select('u')
            ->from('DomainObjectModel_User', 'u')
            ->andWhere('u.user_role = ?')
            ->andWhere('u.office_id = ?')
            ->andWhere('u.user_status = ?');

        return $this->getMultiFound($qb, array($role, $officeId, 'ENABLED'));
    }

    /**
     * @param bool $enabled
     * @return DomainObjectModel_User[]
     */
    public function findAll($enabled = false)
    {
        $qb = $this->getQueryBuilder()
            ->select('u')
            ->from('DomainObjectModel_User', 'u')
            ->orderBy('u.created', 'ASC');

        $placeholders = array();

        if ($enabled) {
            $qb->andWhere('u.user_status = ?');
            $placeholders = array('ENABLED');
        }

        return $this->getMultiFound($qb, $placeholders);
    }

    /**
     * @param string $id
     * @return DomainObjectController_User[]|array
     */
    public function findByOfficeId($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('u')
            ->from('DomainObjectModel_User', 'u')
            ->where('u.office_id = ?')
            ->andWhere('u.user_status = ?');

        return $this->getMultiFound($qb, array($id, 'ENABLED'));
    }
}