<?php

/**
 * @method  int getId()
 * @method  string getTitle()
 * @method  string getDescription()
 * @method  int getQnt()
 * @method  string getStatus()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 * @method  DomainObjectModel_Staff[] getStaffs()
 * 
 * @method  setId(int $arg)
 * @method  setTitle(string $arg)
 * @method  setDescription(string $arg)
 * @method  setQnt(int $arg)
 * @method  setStatus(string $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_StaffCategory extends DomainObjectModel_BaseStaffCategory
{
    /** @var string */
    protected $field_prefix = 'staff_category';

    /** @var null|array */
    protected $staffs = null;

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if (is_null($field) || $field == 'staff_category_title') {
            if (empty($this->staff_category_title)) {
                throw new DxException("Invalid 'staff_category_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'staff_category_status') {
            if (empty($this->staff_category_status) || !in_array($this->staff_category_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'staff_category_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @param null $status
     * @return array
     */
    public function getStaffs($status = null)
    {
        if (is_null($this->staffs)) {
            $this->staffs = array();
            /** @var $img DomainObjectModel_Staff */
            foreach ($this->Staff as $staff) {
                $this->staffs[] = $staff;
            }
        }

        if (!is_null($status)) {
            $result = $this->staffs;
            foreach ($result as $i => $o) {
                if ($o->getStatus() != $status) {
                    unset($result[$i]);
                }
            }
            return $result;
        }
        return $this->staffs;
    }
}
