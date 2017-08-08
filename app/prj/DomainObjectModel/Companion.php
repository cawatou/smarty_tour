<?php
/**
 * @method int getId()
 * @method string getLocation()
 * @method float getPrice()
 * @method int getUserName()
 * @method int getUserEmail()
 * @method int getUserPhone()
 * @method string getUserIp()
 * @method string getUserGender()
 * @method string getTargetGender()
 * @method DxDateTime getDateFrom()
 * @method DxDateTime getDateTo()
 * @method string getNotes()
 * @method string getAgencyNotes()
 * @method string getExtendedData()
 * @method string getStatus()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 *
 * @method setId(int $arg)
 * @method setLocation(string $arg)
 * @method setPrice(float $arg)
 * @method setUserName(int $arg)
 * @method setUserEmail(int $arg)
 * @method setUserPhone(int $arg)
 * @method setUserIp(string $arg)
 * @method setUserGender(string $arg)
 * @method setTargetGender(string $arg)
 * @method setDateFrom(DxDateTime $arg)
 * @method setDateTo(DxDateTime $arg)
 * @method setNotes(string $arg)
 * @method setAgencyNotes(string $arg)
 * @method setExtendedData(string $arg)
 * @method setStatus(string $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Companion extends DomainObjectModel_BaseCompanion
{
    /** @var string */
    protected $field_prefix = 'companion';

    /**
     * @var array
     *
     * @protected
     *
     * @static
     */
    static protected $user_genders = array(
        'MALE'   => 'Мужчина',
        'FEMALE' => 'Женщина',
    );

    /**
     * @var array
     *
     * @protected
     *
     * @static
     */
    static protected $target_genders = array(
        'MALE'    => 'Мужчина',
        'FEMALE'  => 'Женщина',
        'UNKNOWN' => 'Не важно',
    );

    /**
     * @return array
     *
     * @static
     */
    static public function getUserGenders()
    {
        return self::$user_genders;
    }

    /**
     * @return array
     *
     * @static
     */
    static public function getTargetGenders()
    {
        return self::$target_genders;
    }

    public function getUserGenderAsString()
    {
        $genders = $this->getUserGenders();

        return $genders[$this->getUserGender()];
    }

    public function getTargetGenderAsString($parent_case = false)
    {
        $genders = $this->getTargetGenders();

        if ($parent_case) {
            $genders['MALE']   = 'Мужчину';
            $genders['FEMALE'] = 'Женщину';
        }

        return $genders[$this->getTargetGender()];
    }

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'companion_user_name') {
            if (!is_string($this->companion_user_name) || $this->companion_user_name === '') {
                throw new DxException("Invalid 'companion_user_name'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_user_age') {
            if (!is_numeric($this->companion_user_age) || $this->companion_user_age <= 0) {
                throw new DxException("Invalid 'companion_user_city'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_user_city') {
            if (!is_string($this->companion_user_city) || $this->companion_user_city === '') {
                throw new DxException("Invalid 'companion_user_city'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_user_phone') {
            if (!is_string($this->companion_user_phone) || $this->companion_user_phone === '') {
                throw new DxException("Invalid 'companion_user_phone'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_user_email') {
            if (empty($this->companion_user_email) || filter_var($this->companion_user_email, FILTER_VALIDATE_EMAIL) === false) {
                throw new DxException("Invalid 'companion_user_email'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field === 'companion_location') {
            if (empty($this->companion_location)) {
                throw new DxException("Invalid 'companion_location'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_date_from') {
            if (empty($this->companion_date_from) || !is_a($this->companion_date_from, 'DxDateTime')) {
                throw new DxException("Invalid 'companion_date_from'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_date_to') {
            if (empty($this->companion_date_to) || !is_a($this->companion_date_to, 'DxDateTime')) {
                throw new DxException("Invalid 'companion_date_to'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_daynum_from') {
            if (!is_numeric($this->companion_daynum_from) || $this->companion_daynum_from <= 0) {
                throw new DxException("Invalid 'companion_daynum_from'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_daynum_to') {
            if (!is_numeric($this->companion_daynum_to) || $this->companion_daynum_to <= 0) {
                throw new DxException("Invalid 'companion_daynum_to'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_user_gender') {
            if (empty($this->companion_user_gender) || !in_array($this->companion_user_gender, array_keys($this->getUserGenders()))) {
                throw new DxException("Invalid 'companion_user_gender'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_target_gender') {
            if (empty($this->companion_target_gender) || !in_array($this->companion_target_gender, array_keys($this->getTargetGenders()))) {
                throw new DxException("Invalid 'companion_target_gender'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_status') {
            if (empty($this->companion_status) || !in_array($this->companion_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'companion_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_price') {
            if (empty($this->companion_price)) {
                throw new DxException("Invalid 'companion_price'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_notes') {
            if (!empty($this->companion_notes) && !is_string($this->companion_notes)) {
                throw new DxException("Invalid 'companion_notes'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'companion_extended_data') {
            if ($this->companion_extended_data !== null && !is_array($this->companion_extended_data)) {
                throw new DxException('Invalid \'companion_extended_data\'', self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return DxDateTime
     */
    public function getDateFrom()
    {
        if (!$this->getFieldValue('companion_date_from', true)) {
            return new DxDateTime();
        }

        return new DxDateTime($this->getFieldValue('companion_date_from', true));
    }

    /**
     * @param null|DxDateTime $companion_date_from
     * @return null
     */
    public function setDateFrom(DxDateTime $companion_date_from = null)
    {
        if ($companion_date_from === null) {
            $companion_date_from = new DxDateTime();
        }

        $this->setFieldValue('companion_date_from', $companion_date_from->toUTC()->getMySQLDateTime());
    }

    /**
     * @return DxDateTime
     */
    public function getDateTo()
    {
        if (!$this->getFieldValue('companion_date_to', true)) {
            return new DxDateTime();
        }

        return new DxDateTime($this->getFieldValue('companion_date_to', true));
    }

    /**
     * @param null|DxDateTime $companion_date_to
     * @return null
     */
    public function setDateTo(DxDateTime $companion_date_to = null)
    {
        if ($companion_date_to === null) {
            $companion_date_to = new DxDateTime();
        }

        $this->setFieldValue('companion_date_to', $companion_date_to->toUTC()->getMySQLDateTime());
    }

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasAccessor('companion_date_from', 'getDateFrom');
        $this->hasAccessor('companion_date_to',   'getDateTo');

        $this->hasColumn('companion_extended_data', 'array');
    }

    public function isActive()
    {
        if ($this->getDateTo()->format('Ymd') >= date('Ymd')) {
            return true;
        }

        return false;
    }

    /**
     * @param string|null $key
     * @param null $default
     * @return mixed
     */
    public function getExtendedData($key = null, $default = null)
    {
        $data = parent::getExtendedData();

        if (null === $key) {
            return $data;
        }

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

        if (null === $key) {
            $_data = $data;
        } else {
            $_data = $this->getExtendedData();
            $_data[$key] = $data;
        }

        return parent::setExtendedData($_data);
    }
}