<?php
/**
 * @method  int getId()
 * @method  string getTitle()
 * @method  string getAlias()
 * @method  string getKeywords()
 * @method  string getDescription()
 * @method  string getBrief()
 * @method  string getContent()
 * @method  int getGalleryId()
 * @method  int getExternalId()
 * @method  string getStatus()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 * @method  DomainObjectModel_Gallery getGallery()
 *
 * @method  setId(int $arg)
 * @method  setTitle(string $arg)
 * @method  setAlias(string $arg)
 * @method  setKeywords(string $arg)
 * @method  setDescription(string $arg)
 * @method  setBrief(string $arg)
 * @method  setContent(string $arg)
 * @method  setGalleryId(int $arg)
 * @method  setExternalId(int $arg)
 * @method  setStatus(string $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 * @method  setGallery(DomainObjectModel_Gallery $arg)
 */
class DomainObjectModel_Resort extends DomainObjectModel_BaseResort
{
    /** @var string */
    protected $field_prefix = 'resort';

    /** @var null|array */
    protected $resort_weathers = null;

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'resort_title') {
            if (empty($this->resort_title)) {
                throw new DxException("Invalid 'resort_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'gallery_id') {
            if ($this->gallery_id !== null && !is_numeric($this->gallery_id)) {
                throw new DxException("Invalid 'gallery_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'country_id') {
            if ($this->country_id !== null && !is_numeric($this->country_id)) {
                throw new DxException("Invalid 'country_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'resort_alias') {
            if (empty($this->resort_alias)) {
                throw new DxException("Invalid 'resort_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }

            if (preg_match('~[^a-z0-9_-]+~u', $this->resort_alias)) {
                throw new DxException("Invalid 'resort_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field === 'resort_keywords') {
            if ($this->resort_keywords !== null && empty($this->resort_keywords)) {
                throw new DxException("Invalid 'resort_keywords'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'resort_description') {
            if ($this->resort_description !== null && empty($this->resort_description)) {
                throw new DxException("Invalid 'resort_description'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'resort_brief') {
            if ($this->resort_brief !== null && !is_string($this->resort_brief)) {
                throw new DxException("Invalid 'resort_brief'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'resort_status') {
            if (!is_string($this->resort_status) || !in_array($this->resort_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'resort_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @param string $field
     * @return bool
     */
    public function isUnique($field = 'title')
    {
        /** @var $q DomainObjectQuery_Resort */
        $q = DxFactory::getSingleton('DomainObjectQuery_Resort');
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
     * @return int|null
     */
    public function getCountryId()
    {
        return is_object($id = $this->getFieldValue('country_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @return DomainObjectModel_Country|null
     */
    public function getCountry()
    {
        return is_numeric($this->getCountryId()) ? $this->Country : null;
    }

    /**
     * @param DomainObjectModel_Country $c
     */
    public function setCountry(DomainObjectModel_Country $c)
    {
        $this->Country = $c;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = DxApp::getComponent(DxApp::ALIAS_URL);

        return $url->url("/resorts/{$this->getId()}/");
    }

    /**
     * @return array
     */
    public function getCountryImages()
    {
        /** @var $gallery DomainObjectModel_Gallery */
        $gallery = $this->getCountry()->getGallery();

        if ($gallery === null) {
            return null;
        }

        return $gallery->getImages('ENABLED');
    }
}