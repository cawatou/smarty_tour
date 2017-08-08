<?php

/**
 * @method  int getId()
 * @method  int getProductId()
 * @method  int getIsCover()
 * @method  string getPath()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 * @method  DomainObjectModel_Product getProduct()
 *
 * @method  setId(int $arg)
 * @method  setProductId(int $arg)
 * @method  setIsCover(int $arg)
 * @method  setPath(string $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 * @method  setProduct(DomainObjectModel_Product $arg)
 */
class DomainObjectModel_ProductImage extends DomainObjectModel_BaseProductImage
{
    /** @var string */
    protected $field_prefix = 'product_image';

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if (is_null($field) || $field == 'product_image_path') {
            if (empty($this->product_image_path)) {
                throw new DxException("Invalid 'product_image_path'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'product_image_is_cover') {
            if (!is_numeric($this->product_image_is_cover) || (int)$this->product_image_is_cover < 0) {
                throw new DxException("Invalid 'product_image_is_cover'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'product_id') {
            if (!is_numeric($this->product_id)) {
                throw new DxException("Invalid 'product_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return int|null
     */
    public function getProductId()
    {
        return is_object($id = $this->getFieldValue('product_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Product $p
     */
    public function setProduct(DomainObjectModel_Product $p)
    {
        $this->Product = $p;
    }

    /**
     * @return DomainObjectModel_Product|null
     */
    public function getProduct()
    {
        return is_numeric($this->getProductId()) ? $this->Product : null;
    }
}