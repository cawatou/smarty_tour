<?php
/**
 * @method int getId()
 * @method string getStatus()
 * @method string getUserName()
 * @method string getUserPhone()
 * @method string getUserEmail()
 * @method string getUserIp()
 * @method string getMessage()
 * @method string getAnswer()
 * @method int getIsHighlight()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 *
 * @method setId(int $arg)
 * @method setStatus(string $arg)
 * @method setUserName(string $arg)
 * @method setUserPhone(string $arg)
 * @method setUserEmail(string $arg)
 * @method setUserIp(string $arg)
 * @method setMessage(string $arg)
 * @method setAnswer(string $arg)
 * @method setIsHighlight(int $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Faq extends DomainObjectModel_BaseFaq
{
    /** @var string */
    protected $field_prefix = 'faq';

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'faq_user_name') {
            if (empty($this->faq_user_name) || !is_string($this->faq_user_name)) {
                throw new DxException("Invalid 'faq_user_name'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'faq_user_email') {
            if (!empty($this->faq_user_email)) {
                if (!is_string($this->faq_user_email)) {
                    throw new DxException("Invalid 'faq_user_email'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
                }

                if (!filter_var($this->faq_user_email, FILTER_VALIDATE_EMAIL)) {
                    throw new DxException("Invalid 'faq_user_email'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
                }
            }
        }

        if ($field === null || $field === 'faq_user_phone') {
            if (!empty($this->faq_user_phone) && !is_string($this->faq_user_phone)) {
                throw new DxException("Invalid 'faq_user_phone'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'faq_status') {
            if (empty($this->faq_status) || !in_array($this->faq_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'faq_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'faq_message') {
            if (empty($this->faq_message) || !is_string($this->faq_message)) {
                throw new DxException("Invalid 'faq_message'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'city_id') {
            if ($this->city_id !== null && !is_numeric($this->city_id)) {
                throw new DxException("Invalid 'city_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
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

        $this->hasColumn('faq_extended_data', 'array');
    }

    /**
     * @return int|null
     */
    public function getCityId()
    {
        return is_object($id = $this->getFieldValue('city_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_City $c
     */
    public function setCity(DomainObjectModel_City $c)
    {
        $this->City = $c;
    }

    /**
     * @return DomainObjectModel_City|null
     */
    public function getCity()
    {
        return is_numeric($this->getCityId()) ? $this->City : null;
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

    /**
     * @return int|null
     */
    public function getStaffAnswerId()
    {
        return is_object($id = $this->getFieldValue('staff_answer_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Staff $s
     */
    public function setStaffAnswer(DomainObjectModel_Staff $s)
    {
        $this->Staff = $s;
    }

    /**
     * @return DomainObjectModel_Staff|null
     */
    public function getStaffAnswer()
    {
        return is_numeric($this->getStaffAnswerId()) ? $this->Staff : null;
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