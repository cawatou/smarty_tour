<?php

/**
 * @method  int getId()
 * @method  string getName()
 * @method  string getSignature()
 * @method  string getPosition()
 * @method  string getEmail()
 * @method  string getPhone()
 * @method  string getSkype()
 * @method  string getIcq()
 * @method  string getPhoto()
 * @method  string getDescription()
 * @method  string getStatus()
 * @method  int getQnt()
 * @method  int getIsHighlight()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 * @method  DomainObjectModel_Office getOffice()
 *
 * @method  setId(int $arg)
 * @method  setName(string $arg)
 * @method  setSignature(string $arg)
 * @method  setPosition(string $arg)
 * @method  setEmail(string $arg)
 * @method  setPhone(string $arg)
 * @method  setSkype(string $arg)
 * @method  setIcq(int $arg)
 * @method  setPhoto(string $arg)
 * @method  setDescription(string $arg)
 * @method  setStatus(string $arg)
 * @method  setQnt(int $arg)
 * @method  setIsHighlight(int $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 * @method  setOffice(DomainObjectModel_Office $arg)
 */
class DomainObjectModel_Staff extends DomainObjectModel_BaseStaff
{
    /** @var string */
    protected $field_prefix = 'staff';

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'staff_name') {
            if (empty($this->staff_name)) {
                throw new DxException("Invalid 'staff_name'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'office_id') {
            if (empty($this->office_id)) {
                throw new DxException("Invalid 'office_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'staff_email') {
            if (!empty($this->staff_email) && filter_var($this->staff_email, FILTER_VALIDATE_EMAIL) === false) {
                throw new DxException("Invalid 'staff_email'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field === 'staff_skype') {
            if (!empty($this->staff_skype) && !is_string($this->staff_skype)) {
                throw new DxException("Invalid 'staff_skype'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'staff_icq') {
            if (!empty($this->staff_icq) && !is_string($this->staff_icq)) {
                throw new DxException("Invalid 'staff_icq'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'staff_status') {
            if (empty($this->staff_status) || !in_array($this->staff_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'staff_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return int|null
     */
    public function getOfficeId()
    {
        return is_object($id = $this->getFieldValue('office_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Office $o
     */
    public function setOffice(DomainObjectModel_Office $o)
    {
        $this->Office = $o;
    }

    /**
     * @return DomainObjectModel_Office|null
     */
    public function getOffice()
    {
        return is_numeric($this->getOfficeId()) ? $this->Office : null;
    }

    /**
     * @param sting|null $staff_name
     */
    public function setSignature($staff_name = null)
    {
        if ($staff_name === null) {
            $staff_name = $this->getFieldValue('staff_name');
        }

        DxFactory::import('Utils_NameMaker');
        $this->setFieldValue('staff_signature', Utils_NameMaker::cyrillicToLatin($staff_name, true));
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        $url = '/staff/' . $this->getSignature();
        return DxApp::getComponent(DxApp::ALIAS_URL)->url($url);
    }

    public function preInsert($event)
    {
        parent::preInsert($event);

        $this->clearCaches();
    }

    public function preUpdate($event)
    {
        parent::preUpdate($event);

        $this->clearCaches();
    }

    public function clearCaches()
    {
        DxApp::getComponent(DxConstant_Project::ALIAS_SMARTY)->clearAllCache();
    }
}