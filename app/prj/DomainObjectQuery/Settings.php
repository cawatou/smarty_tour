<?php

class DomainObjectQuery_Settings extends DomainObjectQuery
{
    protected $type_to_get = array(
        'INT'    => 'getValInt',
        'BOOL'   => 'getValBool',
        'STRING' => 'getValString',
        'TEXT'   => 'getValText',
        'FILE'   => 'getValString',
    );

    /**
     * @param int $id
     * @return DomainObjectModel_Settings|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Settings', 's')
            ->where('s.settings_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param $key
     * @return DomainObjectModel_Settings|null
     */
    public function findByKey($key)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Settings', 's')
            ->where('s.settings_key = ?');

        return $this->getSingleFound($qb, array($key));
    }

    /**
     * @return DomainObjectModel_Settings[]|null
     */
    public function findAll()
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Settings', 's')
            ->orderBy('s.settings_qnt', 'ASC');

        return $this->getMultiFound($qb, array());
    }

    /**
     * @param $group
     * @return array
     */
    public function getByGroup($group)
    {
        $qb = $this->getQueryBuilder()
            ->select('s')
            ->from('DomainObjectModel_Settings', 's')
            ->where('s.settings_group = ?');

        $rows = $this->getMultiFound($qb, array($group));

        $result = array();
        foreach ($rows as $i) {
            $m = $this->type_to_get[$i->getType()];
            $result[$i->getKey()] = $i->$m();
        }
        return $result;
    }

    /**
     * @param $key
     * @return null
     */
    public function getByKey($key)
    {
        $row = $this->findByKey($key);
        $result = null;
        if (!empty($row)) {
            $m = $this->type_to_get[$row->getType()];
            $result = $row->$m();
        }

        return $result;
    }
}
