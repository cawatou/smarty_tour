<?php
/**
 * @method int getId()
 * @method string getTitle()
 * @method string getAlias()
 * @method string getKeywords()
 * @method string getDescription()
 * @method string getBrief()
 * @method string getContent()
 * @method int getExternalId()
 * @method string getStatus()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 *
 * @method setId(int $arg)
 * @method setTitle(string $arg)
 * @method setAlias(string $arg)
 * @method setKeywords(string $arg)
 * @method setDescription(string $arg)
 * @method setBrief(string $arg)
 * @method setContent(string $arg)
 * @method setGalleryId(int $arg)
 * @method setExternalId(int $arg)
 * @method setStatus(string $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
  */
class DomainObjectModel_Country extends DomainObjectModel_BaseCountry
{
    /** @var string */
    protected $field_prefix = 'country';

    /** @var null|array */
    protected $resorts = null;

    /** @var null|array */
    protected $tours = null;

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'country_title') {
            if (empty($this->country_title)) {
                throw new DxException("Invalid 'country_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'gallery_id') {
            if ($this->gallery_id !== null && !is_numeric($this->gallery_id)) {
                throw new DxException("Invalid 'gallery_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'country_alias') {
            if (empty($this->country_alias)) {
                throw new DxException("Invalid 'country_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }

            if (preg_match('~[^a-z0-9_-]+~u', $this->country_alias)) {
                throw new DxException("Invalid 'country_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field === 'country_keywords') {
            if ($this->country_keywords !== null && empty($this->country_keywords)) {
                throw new DxException("Invalid 'country_keywords'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'country_description') {
            if ($this->country_description !== null && empty($this->country_description)) {
                throw new DxException("Invalid 'country_description'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'country_brief') {
            if ($this->country_brief !== null && !is_string($this->country_brief)) {
                throw new DxException("Invalid 'country_brief'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'country_status') {
            if (!is_string($this->country_status) || !in_array($this->country_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'country_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'country_visa_days') {
            if ($this->country_visa_days !== null && !is_numeric($this->country_visa_days)) {
                throw new DxException("Invalid 'country_visa_days'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @param string $field
     * @return bool
     */
    public function isUnique($field = 'title')
    {
        /** @var $q DomainObjectQuery_Country */
        $q = DxFactory::getSingleton('DomainObjectQuery_Country');
        $p = $q->findByTitleOrAlias($field === 'title' ? $this->getTitle() : $this->getAlias());

        if (!$p) {
            return true;
        }


        if (!$this->getId()) {
            return false;
        }

        if ($this->getId() == $p->getId()) {
            return true;
        }

        return false;
    }

    /**
     * @return int|null
     */
    public function getGalleryId()
    {
        return is_object($id = $this->getFieldValue('gallery_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @return DomainObjectModel_Gallery|null
     */
    public function getGallery()
    {
        return is_numeric($this->getGalleryId()) ? $this->Gallery : null;
    }

    /**
     * @param DomainObjectModel_Gallery $g
     */
    public function setGallery(DomainObjectModel_Gallery $g)
    {
        $this->Gallery = $g;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = DxApp::getComponent(DxApp::ALIAS_URL);

        return $url->url("/countries/{$this->getAlias()}");
    }

    /**
    * @return array
    */
    public function getResorts()
    {
        if ($this->resorts !== null) {
            return $this->resorts;
        }

        $this->resorts = array();

        /** @var $resort DomainObjectModel_Resort */
        foreach ($this->Resort as $resort) {
            if ($resort->getStatus() !== 'ENABLED') {
                continue;
            }

            $this->resorts[] = $resort;
        }

        return $this->resorts;
    }

    /**
    * @return array
    */
    public function getTours()
    {
        if ($this->tours !== null) {
            return $this->tours;
        }

        $this->tours = array();

        /** @var $tour DomainObjectModel_Tour */
        foreach ($this->Tour as $tour) {
            if ($tour->getStatus() !== 'ENABLED') {
                continue;
            }

            $this->tours[] = $tour;
        }

        return $this->tours;
    }

    /**
     * @return array|null
     */
    public function getImages()
    {
        /** @var $gallery DomainObjectModel_Gallery */
        $gallery = $this->getGallery();

        if ($gallery !== null) {
            return null;
        }

        return $gallery->getImages('ENABLED');
    }
}