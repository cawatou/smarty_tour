<?php

/**
 * @method  int getId()
 * @method  int getGalleryId()
 * @method  string getPath()
 * @method  string getTitle()
 * @method  string getDescription()
 * @method  string getStatus()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 * @method  DomainObjectModel_Gallery getGallery()
 * 
 * @method  setId(int $arg)
 * @method  setGalleryId(int $arg)
 * @method  setPath(string $arg)
 * @method  setTitle(string $arg)
 * @method  setDescription(string $arg)
 * @method  setStatus(string $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 * @method  setGallery(DomainObjectModel_Gallery $arg)
 */
class DomainObjectModel_GalleryImage extends DomainObjectModel_BaseGalleryImage
{
    /** @var string */
    protected $field_prefix = 'gallery_image';
	
    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if (is_null($field) || $field == 'gallery_image_path') {
            if (empty($this->gallery_image_path)) {
                throw new DxException("Invalid 'gallery_image_path'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
		
        if (is_null($field) || $field == 'gallery_id') {
            if (!is_numeric($this->gallery_id)) {
                throw new DxException("Invalid 'gallery_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'gallery_image_link') {
            if (!empty($this->gallery_image_link) && filter_var("http://{$this->gallery_image_link}", FILTER_VALIDATE_URL) === false) {
                throw new DxException("Invalid 'gallery_image_link'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if (is_null($field) || $field == 'gallery_image_status') {
            if (empty($this->gallery_image_status) || !in_array($this->gallery_image_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'gallery_image_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return int|null
     */
    public function getGalleryId()
    {
        return is_object($id = $this->getFieldValue('gallery_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Gallery $g
     */
    public function setGallery(DomainObjectModel_Gallery $g)
    {
        $this->Gallery = $g;
    }

    /**
     * @return DomainObjectModel_Gallery|null
     */
    public function getGallery()
    {
        return is_numeric($this->getGalleryId()) ? $this->Gallery : null;
    }

    /**
     * @return null|string
     */
    public function getLink()
    {
        $link = parent::getLink();
        return empty($link) ? null : "http://{$link}";
    }
}
