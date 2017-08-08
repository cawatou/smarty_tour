<?php

/**
 * @method  int getId()
 * @method  int getPublicationId()
 * @method  string getPath()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 * @method  DomainObjectModel_Publication getPublication()
 * 
 * @method  setId(int $arg)
 * @method  setPublicationId(int $arg)
 * @method  setPath(string $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 * @method  setPublication(DomainObjectModel_Publication $arg)
 */
class DomainObjectModel_PublicationImage extends DomainObjectModel_BasePublicationImage
{
    /** @var string */
    protected $field_prefix = 'publication_image';

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if (is_null($field) || $field == 'publication_image_path') {
            if (empty($this->publication_image_path)) {
                throw new DxException("Invalid 'publication_image_path'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'publication_id') {
            if (!is_numeric($this->publication_id)) {
                throw new DxException("Invalid 'publication_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return int|null
     */
    public function getPublicationId()
    {
        return is_object($id = $this->getFieldValue('publication_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Publication $p
     */
    public function setPublication(DomainObjectModel_Publication $p)
    {
        $this->Publication = $p;
    }

    /**
     * @return DomainObjectModel_Publication|null
     */
    public function getPublication()
    {
        return is_numeric($this->getPublicationId()) ? $this->Publication : null;
    }
}
