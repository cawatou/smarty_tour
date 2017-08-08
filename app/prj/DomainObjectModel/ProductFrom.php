<?php

/**
 * @method  int getId()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 *
 * @method  setId(int $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_ProductFrom extends DomainObjectModel_BaseProductFrom
{
    /** @var string */
    protected $field_prefix = 'product_from';

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'product_from_date') {
            if (empty($this->product_from_date) || !is_a($this->product_from_date, 'DxDateTime')) {
                throw new DxException("Invalid 'product_from_date'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return null
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasAccessor('product_from_date', 'getDate');
    }

    /**
     * @return DxDateTime
     */
    public function getDate()
    {
        if (!$this->getFieldValue('product_from_date', true)) {
            return new DxDateTime();
        }

        return new DxDateTime($this->getFieldValue('product_from_date', true));
    }

    /**
     * @param null|DxDateTime $product_from_date
     * @return null
     */
    public function setDate(DxDateTime $product_from_date = null)
    {
        if ($product_from_date === null) {
            $product_from_date = new DxDateTime();
        }

        $this->setFieldValue('product_from_date', $product_from_date->toUTC()->getMySQLDateTime());
    }
}