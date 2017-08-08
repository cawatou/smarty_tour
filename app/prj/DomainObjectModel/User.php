<?php
/**
 * @method int getId()
 * @method string getLogin()
 * @method string getIdentifier()
 * @method string getName()
 * @method string getRole()
 * @method string getIp()
 * @method string getStatus()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 *
 * @method setId(int $arg)
 * @method setLogin(string $arg)
 * @method setIdentifier(string $arg)
 * @method setName(string $arg)
 * @method setRole(string $arg)
 * @method setIp(string $arg)
 * @method setStatus(string $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_User extends DomainObjectModel_BaseUser
{
    /** @var string */
    protected $field_prefix = 'user';

    protected static $roles_list = array(
        'DEVELOPER' => 'Разработчик',
        'ADMIN'     => 'Администратор',
        'DIRECTOR'  => 'Директор',
        'OPERATOR'  => 'Сотрудник',
        'SELLER'    => 'Продавец',
    );

    protected $director_cities  = null;
    protected $director_offices = null;
    protected $neighbor_users   = null;

    protected function validateField($field = null)
    {
        if ($field === null || $field === 'user_login') {
            if (empty($this->user_login)) {
                throw new DxException("Invalid 'user_login'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            } elseif (!filter_var($this->user_login, FILTER_VALIDATE_EMAIL)) {
                throw new DxException("Invalid 'user_login'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field === 'user_identifier') {
            if (empty($this->user_identifier) || strlen($this->user_identifier) > 32) {
                throw new DxException("Invalid 'user_identifier'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'user_name') {
            if (empty($this->user_name)) {
                throw new DxException("Invalid 'user_name'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'user_role') {
            if (empty($this->user_role) || !in_array($this->user_role, array_keys(self::getRolesList()))) {
                throw new DxException("Invalid 'user_role'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'user_status') {
            if (empty($this->user_status) || !in_array($this->user_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'user_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasAccessor('user_visited', 'getVisited');

        $this->hasColumn('user_froms', 'array');
    }

    /**
     * @return DxDateTime
     */
    public function getVisited()
    {
        return new DxDateTime($this->getFieldValue('user_visited', true));
    }

    /**
     * @param DxDateTime|null $dt
     * @return void
     */
    public function setVisited(DxDateTime $dt = null)
    {
        if ($dt === null) {
            $dt = new DxDateTime();
        }

        $this->setFieldValue('user_visited', $dt->toUTC()->getMySQLDateTime());
    }

    /**
     * @static
     * @return array
     */
    static public function getRolesList()
    {
        return self::$roles_list;
    }

    /**
     * @return null
     */
    public function getRoleTitle()
    {
        $roles = $this->getRolesList();

        return isset($roles[$this->getRole()]) ? $roles[$this->getRole()] : null;
    }

    /**
     * @static
     * @param string $login
     * @param string $password
     * @return string
     */
    static public function createIdentifier($login, $password)
    {
        return md5($login . $password);
    }

    /**
     * @return bool
     */
    public function isUniqueLogin()
    {
        /** @var DomainObjectQuery_User $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_User');
        $u = $q->findByLogin($this->getLogin());

        if (!$u) {
            return true;
        }

        if (!$this->getId()) {
            return false;
        }

        if ($this->getId() == $u->getId()) {
            return true;
        }

        return false;
    }

    public function getFromAll()
    {
        DxFactory::import('DomainObjectModel_Product');

        $product_departures = DomainObjectModel_Product::getFromAll();

        $user_departures = $this->getFroms();

        if (empty($user_departures)) {
            $user_departures = array();

            foreach ($product_departures as $departure_id => $dep) {
                $user_departures[] = array(
                    'departure_id'    => $departure_id,
                    'departure_title' => $dep['title_from'],
                    'is_shown'        => true,
                );
            }

            return $user_departures;
        }

        foreach ($user_departures as $k => $user_dep) {
            if (empty($user_dep['departure_id']) || empty($product_departures[$user_dep['departure_id']])) {
                unset($user_departures[$k]);

                continue;
            }

            $user_departures[$k]['departure_title'] = $product_departures[$user_dep['departure_id']]['title_from'];
        }

        foreach ($product_departures as $departure_id => $dep) {
            $is_found = false;

            foreach ($user_departures as $user_dep) {
                if ($user_dep['departure_id'] == $departure_id) {
                    $is_found = true;

                    break;
                }
            }

            if (!$is_found) {
                $user_departures[] = array(
                    'departure_id'    => $departure_id,
                    'departure_title' => $dep['title_from'],
                    'is_shown'        => false,
                );
            }
        }

        return $user_departures;
    }

    /**
     * @return int|null
     */
    public function getOfficeId()
    {
        return is_object($id = $this->getFieldValue('office_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Office $c
     */
    public function setOffice(DomainObjectModel_Office $c)
    {
        $this->Office = $c;
    }

    /**
     * @return DomainObjectModel_Office|null
     */
    public function getOffice()
    {
        return is_numeric($this->getOfficeId()) ? $this->Office : null;
    }

    /**
     * @return int|null
     */
    public function getSubdivisionId()
    {
        return is_object($id = $this->getFieldValue('subdivision_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Subdivision $s
     */
    public function setSubdivision(DomainObjectModel_Subdivision $s)
    {
        $this->Subdivision = $s;
    }

    /**
     * @return DomainObjectModel_Subdivision|null
     */
    public function getSubdivision()
    {
        return is_numeric($this->getSubdivisionId()) ? $this->Subdivision : null;
    }

    /**
     * @return null|array
     */
    public function getSubdivisionCities()
    {
        if ($this->getRole() !== 'DIRECTOR') {
            return null;
        }

        if ($this->director_cities) {
            return $this->director_cities;
        }

        $offices = $this->getSubdivisionOffices();

        $city_ids = array();

        foreach ($offices as $office) {
            if (!$office->getCityId()) {
                continue;
            }

            $city_ids[$office->getCityId()] = true;
        }

        $city_ids = array_keys($city_ids);

        if (empty($city_ids)) {
            $this->director_cities = array();

            return $this->director_cities;
        }

        /** @var DomainObjectQuery_City $q_c */
        $q_c = DxFactory::getSingleton('DomainObjectQuery_City');

        $this->director_cities = $q_c->findByIds($city_ids);

        return $this->director_cities;
    }

    /**
     * @return null|array
     */
    public function getSubdivisionOffices()
    {
        if ($this->getRole() !== 'DIRECTOR') {
            return null;
        }

        if ($this->director_offices) {
            return $this->director_offices;
        }

        /** @var DomainObjectQuery_Office $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_Office');

        $this->director_offices = $q->findBySubdivisionId($this->getSubdivisionId());

        return $this->director_offices;
    }

    /**
     * Returns array of user objects from all offices (for DIRECTOR), from same office (for OPERATOR), current user (for SELLER) or all users (for ADMIN, DEVELOPER)
     *
     * @return null|array
     */
    public function getNeighborUsers()
    {
        if ($this->neighbor_users !== null) {
            return $this->neighbor_users;
        }

        if ($this->getRole() == 'SELLER') {
            $this->neighbor_users = array(
                $this,
            );

            return $this->neighbor_users;
        }

        /** @var DomainObjectQuery_User $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_User');

        if ($this->getRole() == 'OPERATOR') {
            //$this->neighbor_users = $q->findByRoleAndOffice('OPERATOR', $this->getOfficeId());
            $this->neighbor_users = array(
                $this,
            );

            return $this->neighbor_users;
        }

        if ($this->getRole() == 'ADMIN' || $this->getRole() == 'DEVELOPER') {
            $this->neighbor_users = $q->findAll(true);

            return $this->neighbor_users;
        }

        $office_ids = array();

        foreach ($this->getSubdivisionOffices() as $office) {
            $office_ids[] = $office->getId();
        }

        $this->neighbor_users = $q->findByOfficeIds($office_ids);

        return $this->neighbor_users;
    }
}