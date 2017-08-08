<?php
class DomainObjectModel_Discount extends DomainObjectModel_BaseDiscount
{
    /** @var string */
    protected $field_prefix = 'discount';

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'touroperator_id') {
            if ($this->touroperator_id !== null && $this->touroperator_id <= 0) {
                throw new DxException("Invalid 'touroperator_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return int|null
     */
    public function getTouroperatorId()
    {
        return is_object($id = $this->getFieldValue('touroperator_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Touroperator $s
     */
    public function setTouroperator(DomainObjectModel_Touroperator $s)
    {
        $this->Touroperator = $s;
    }

    /**
     * @return DomainObjectModel_Touroperator|null
     */
    public function getTouroperator()
    {
        return is_numeric($this->getTouroperatorId()) ? $this->Touroperator : null;
    }

    /**
     * @return int|null
     */
    public function getCountryId()
    {
        return is_object($id = $this->getFieldValue('country_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Country $s
     */
    public function setCountry(DomainObjectModel_Country $s)
    {
        $this->Country = $s;
    }

    /**
     * @return DomainObjectModel_Country|null
     */
    public function getCountry()
    {
        return is_numeric($this->getCountryId()) ? $this->Country : null;
    }
}