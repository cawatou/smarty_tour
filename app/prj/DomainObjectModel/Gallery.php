<?php

/**
 * @method int getId()
 * @method string getTitle()
 * @method string getAlias()
 * @method string getDescription()
 * @method string getCategory()
 * @method int getIsHighlight()
 * @method string getStatus()
 * @method string getCoverPath()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 * @method DomainObjectModel_GalleryImage[] getImages()
 *
 * @method setId(int $arg)
 * @method setTitle(string $arg)
 * @method setAlias(string $arg)
 * @method setDescription(string $arg)
 * @method setCategory(string $arg)
 * @method setIsHighlight(int $arg)
 * @method setStatus(string $arg)
 * @method setCoverPath()
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Gallery extends DomainObjectModel_BaseGallery
{
    /** @var string */
    protected $field_prefix = 'gallery';

    /**
     * @var array
     *
     * @static
     */
    protected static $categories = array(
        'COUNTRY' => array(
            'title' => 'Страны',
            'url'   => '',
        ),

        'RESORT' => array(
            'title' => 'Курорты',
            'url'   => '',
        ),

        'HOTEL_AGENCY' => array(
            'title' => 'Отели (фото агентства)',
            'url'   => '',
        ),

        'HOTEL_OPERATOR' => array(
            'title' => 'Отели (фото туроператора)',
            'url'   => '',
        ),

        'HOTEL_TOURISTS' => array(
            'title' => 'Отели (фото туристов)',
            'url'   => '',
        ),

        'CYCLING' => array(
            'title' => 'Галереи для страниц',
            'url'   => '',
        ),

        /*
        'OTHER' => array(
            'title' => 'Другое',
            'url'   => '',
        ),
         */
    );

    /** @var null|array */
    protected $gallery_images = null;

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if (is_null($field) || $field == 'gallery_title') {
            if (empty($this->gallery_title)) {
                throw new DxException("Invalid 'gallery_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'gallery_date') {
            if (empty($this->gallery_date) || !is_a($this->gallery_date, 'DxDateTime')) {
                throw new DxException("Invalid 'gallery_date'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'gallery_alias') {
            if (empty($this->gallery_alias)) {
                throw new DxException("Invalid 'gallery_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
			} elseif (preg_match('~[^a-z0-9_-]+~u', $this->gallery_alias)) {
                throw new DxException("Invalid 'gallery_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if (is_null($field) || $field == 'gallery_status') {
            if (empty($this->gallery_status) || !in_array($this->gallery_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'product_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if (is_null($field) || $field == 'gallery_category') {
            if (!is_string($this->gallery_category) || !array_key_exists($this->gallery_category, self::getCategories())) {
                throw new DxException("Invalid 'gallery_category'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->hasAccessor('gallery_date', 'getDate');
        $this->getTable()
            ->getRelation('GalleryImage')
            ->offsetSet('cascade', array('delete'));

        $this->hasMany('DomainObjectModel_GalleryImage as GalleryImage', array(
            'local' => 'gallery_id',
            'foreign' => 'gallery_id',
            'orderBy' => 'gallery_image_qnt ASC'));
    }

    /**
     * @static
     * @return array
     */
    public static function getCategories()
    {
        return self::$categories;
    }

    /**
     * @param bool $full_path
     * @return mixed
     */
    public function getCategoryUrl($full_path = true)
    {
        if (!isset(self::$categories[$this->getCategory()])) {
            return null;
        }
        return $full_path ? DxApp::getComponent(DxApp::ALIAS_URL)->url(self::$categories[$this->getCategory()]['url']) : self::$categories[$this->getCategory()]['url'];
    }

    /**
     * @return null
     */
    public function getCategoryName()
    {
        return isset(self::$categories[$this->getCategory()]) ? self::$categories[$this->getCategory()]['title'] : null;
    }

    /**
     * @return DxDateTime
     */
    public function getDate()
    {
        if (!$this->getFieldValue('gallery_date', true)) {
            return new DxDateTime();
        } else {
            return new DxDateTime($this->getFieldValue('gallery_date', true));
        }
    }

    /**
     * @param null|DxDateTime $gallery_date
     * @return void
     */
    public function setDate(DxDateTime $gallery_date = null)
    {
        if (is_null($gallery_date)) {
            $gallery_date = new DxDateTime();
        }

        $this->setFieldValue('gallery_date', $gallery_date->toUTC()->getMySQLDateTime());
    }

    /**
     * @return bool
     */
    public function isUniqueAlias()
    {
        /** @var $q DomainObjectQuery_Gallery */
        $q = DxFactory::getSingleton('DomainObjectQuery_Gallery');
        $c = $q->findByAlias($this->getAlias());

        if (!$c) {
            return true;
        } else {
            if (!$this->getId()) {
                return false;
            } elseif ($this->getId() == $c->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return null|string
     */
    public function getCoverPath()
    {
        if (!is_null($this->getCover())) {
            return $this->getCover();
        }

        $images = $this->getImages();
        return !isset($images[0]) ? null : $images[0]->getPath();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = $this->getCategoryUrl(false);
        if (is_null($url)) {
            return null;
        }
        $url .= '/' . $this->getAlias();
        return DxApp::getComponent(DxApp::ALIAS_URL)->url($url);
    }

    /**
     * @param null $status
     * @return array
     */
    public function getImages($status = null)
    {
        if (is_null($this->gallery_images)) {
            $this->gallery_images = array();
            /** @var $img DomainObjectModel_GalleryImage */
            foreach ($this->GalleryImage as $img) {
                $this->gallery_images[] = $img;
            }
        }

        if (!is_null($status)) {
            $result = $this->gallery_images;
            foreach ($result as $i => $img) {
                if ($img->getStatus() != $status) {
                    unset($result[$i]);
                }
            }
            return $result;
        }
        return $this->gallery_images;
    }
}
