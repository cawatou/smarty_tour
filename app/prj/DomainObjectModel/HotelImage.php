<?php
/**
 * @method int getId()
 * @method int getHotelId()
 * @method string getPath()
 * @method string getType()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 * @method DomainObjectModel_Hotel getHotel()
 *
 * @method setId(int $arg)
 * @method setHotelId(int $arg)
 * @method setPath(string $arg)
 * @method setType(string $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 * @method setHotel(DomainObjectModel_Hotel $arg)
 */
class DomainObjectModel_HotelImage extends DomainObjectModel_BaseHotelImage
{
    /** @var string */
    protected $field_prefix = 'hotel_image';

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'hotel_image_path') {
            if (empty($this->hotel_image_path)) {
                throw new DxException("Invalid 'hotel_image_path'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'hotel_id') {
            if (!is_numeric($this->hotel_id)) {
                throw new DxException("Invalid 'hotel_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'hotel_image_type') {
            if (empty($this->hotel_image_type) || !in_array($this->hotel_image_type, array('USER', 'OPERATOR', 'AGENCY'))) {
                throw new DxException("Invalid 'hotel_image_type'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return int|null
     */
    public function getHotelId()
    {
        return is_object($id = $this->getFieldValue('hotel_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Hotel $h
     */
    public function setHotel(DomainObjectModel_Hotel $h)
    {
        $this->Hotel = $h;
    }

    /**
     * @return DomainObjectModel_Hotel|null
     */
    public function getHotel()
    {
        return is_numeric($this->getHotelId()) ? $this->Hotel : null;
    }
}
