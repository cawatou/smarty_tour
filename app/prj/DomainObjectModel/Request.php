<?php
/**
 * @method int getId()
 * @method string getType()
 * @method string getStatus()
 * @method string getUserName()
 * @method string getUserPhone()
 * @method string getUserEmail()
 * @method string getUserIp()
 * @method string getMessage()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 *
 * @method setId(int $arg)
 * @method setType(string $arg)
 * @method setStatus(string $arg)
 * @method setUserName(string $arg)
 * @method setUserPhone(string $arg)
 * @method setUserEmail(string $arg)
 * @method setUserIp(string $arg)
 * @method setMessage(string $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Request extends DomainObjectModel_BaseRequest
{
    /** @var string */
    protected $field_prefix = 'request';
    /** @var array */
    protected static $types = array(
        'REQUEST'      => 'Заявка на подбор тура',
        'ORDER'        => 'Заявка на покупку',
        'ORDER_SHORT'  => 'Заявка на покупку выбранного тура',
        'CALLBACK'     => 'Заявка на обратный звонок',
        'SLETAT_ORDER' => 'Заявка с поисковика на сайте',
		'SLETAT_ONLINE' => 'Заявка sletat online',
    );

    protected static $types_map = array(
        'SELLER' => array(
            'ORDER_SHORT'  => 'Заявка на покупку',
            'SLETAT_ORDER' => 'Заявка с поисковика на сайте',
        ),
        'OPERATOR' => array(
            'REQUEST'      => 'Заявка на подбор тура',
            'ORDER'        => 'Заявка на покупку',
            'ORDER_SHORT'  => 'Покупка тура (оплата в офисе)',
            'CALLBACK'     => 'Заявка на обратный звонок',
            'SLETAT_ORDER' => 'Заявка с поисковика на сайте',
			'SLETAT_ONLINE' => 'Заявка с поисковика на сайте',
        ),
        'DIRECTOR' => array(
            'REQUEST'      => 'Заявка на подбор тура',
            'ORDER'        => 'Заявка на покупку',
            'ORDER_SHORT'  => 'Покупка тура (оплата в офисе)',
            'CALLBACK'     => 'Заявка на обратный звонок',
            'SLETAT_ORDER' => 'Заявка с поисковика на сайте',
			'SLETAT_ONLINE' => 'Заявка с поисковика на сайте',
        ),
    );

    /**
     * @static
     * @return array
     */
    public static function getTypes()
    {
        return self::$types;
    }

    /**
     * @static
     * @return array
     */
    public static function getTypesByRole($role)
    {
        if (isset(self::$types_map[$role])) {
            return self::$types_map[$role];
        }

        return self::$types;
    }

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'request_user_ip') {
            if (empty($this->request_user_ip)) {
                throw new DxException("Invalid 'request_user_ip'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'request_answer') {
            if (!empty($this->request_answer) && !is_string($this->request_answer)) {
                throw new DxException("Invalid 'request_answer'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'request_user_name') {
            if (empty($this->request_user_name)) {
                throw new DxException("Invalid 'request_user_name'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'request_user_phone') {
            if (empty($this->request_user_phone)) {
                throw new DxException("Invalid 'request_user_phone'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'request_user_email') {
            if (!empty($this->request_user_email) && !filter_var($this->request_user_email, FILTER_VALIDATE_EMAIL)) {
                throw new DxException("Invalid 'request_user_email'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field === 'request_status') {
            if (empty($this->request_status) || !in_array($this->request_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'request_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'request_type') {
            if (!is_string($this->request_type) || !array_key_exists($this->request_type, self::getTypes())) {
                throw new DxException("Invalid 'request_type'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'office_id') {
            if ($this->office_id !== null && !is_numeric($this->office_id)) {
                throw new DxException("Invalid 'office_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'user_id') {
            if ($this->user_id !== null && !is_numeric($this->user_id)) {
                throw new DxException("Invalid 'user_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasColumn('request_extended_data', 'array');
    }

    /**
     * @param string|null $key
     * @param null $default
     * @return mixed
     */
    public function getExtendedData($key = null, $default = null)
    {
        $data = parent::getExtendedData();

        if ($key === null) {
            return $data;
        }
		
		//throw new Exception(var_dump($data);

        return isset($data[$key]) ? $data[$key] : $default;
    }

    /**
     * @param mixed       $data
     * @param string|null $key
     * @return mixed
     */
    public function setExtendedData($data, $key = null)
    {
        if (empty($data)) {
            return parent::setExtendedData(null);
        }

        if ($key === null) {
            $_data = $data;
        } else {
            $_data = $this->getExtendedData();
            $_data[$key] = $data;
        }

        return parent::setExtendedData($_data);
    }

    /**
     * @return DomainObjectModel_Office|null
     */
    public function getOfficeViaExtended()
    {
        $office_id = $this->getExtendedData('office');

        if (empty($office_id)) {
            return null;
        }

        /** @var $q DomainObjectQuery_Office */
        $q = DxFactory::getSingleton('DomainObjectQuery_Office');

        $office = $q->findById($office_id);

        if (empty($office)) {
            return null;
        }

        return $office;
    }

    /**
     * @return int|null
     */
    public function getOfficeId()
    {
        return is_object($id = $this->getFieldValue('office_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @return DomainObjectModel_Office|null
     */
    public function getOffice()
    {
        return is_numeric($this->getOfficeId()) ? $this->Office : null;
    }

    /**
     * @param DomainObjectModel_Office $m
     * @return DomainObjectModel_Feedback
     */
    public function setOffice(DomainObjectModel_Office $m)
    {
        $this->Office = $m;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getUserId()
    {
        return is_object($id = $this->getFieldValue('user_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_User $c
     */
    public function setUser(DomainObjectModel_User $c)
    {
        $this->User = $c;
    }

    /**
     * @return DomainObjectModel_User|null
     */
    public function getUser()
    {
        return is_numeric($this->getUserId()) ? $this->User : null;
    }
}