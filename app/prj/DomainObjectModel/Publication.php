<?php

/**
 * @method  int getId()
 * @method  string getTitle()
 * @method  string getSignature()
 * @method  string getCover()
 * @method  string getFile()
 * @method  string getYoutube()
 * @method  string getBrief()
 * @method  string getContent()
 * @method  string getCategory()
 * @method  string getStatus()
 * @method  int getIsHighlight()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 * @method  DomainObjectModel_PublicationImage[] getPublicationImages()
 *
 * @method  setId(int $arg)
 * @method  setTitle(string $arg)
 * @method  setSignature(string $arg)
 * @method  setCover(string $arg)
 * @method  setFile(string $arg)
 * @method  setYoutube(string $arg)
 * @method  setBrief(string $arg)
 * @method  setContent(string $arg)
 * @method  setCategory(string $arg)
 * @method  setTags(string $arg)
 * @method  setStatus(string $arg)
 * @method  setIsHighlight(int $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Publication extends DomainObjectModel_BasePublication
{
    /** @var string */
    protected $field_prefix = 'publication';

    /** @var array */
    protected static $categories = array(
        'NEWS' => array(
            'title'    => 'Новости',
            'form_tpl' => 'publication_common.tpl.php',
            'url'      => '/news',
        ),
        /*
        'ACTION' => array(
            'title'    => 'Акции',
            'form_tpl' => 'publication_common.tpl.php',
            'url'      => '/action',
        ),
        */
    );

    /** @var null|array */
    protected $publication_images = null;

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'publication_title') {
            if (empty($this->publication_title)) {
                throw new DxException("Invalid 'publication_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'publication_date') {
            if (empty($this->publication_date) || !is_a($this->publication_date, 'DxDateTime')) {
                throw new DxException("Invalid 'publication_date'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'publication_brief') {
            if (empty($this->publication_brief)) {
                throw new DxException("Invalid 'publication_brief'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'publication_content') {
            if (empty($this->publication_content)) {
                throw new DxException("Invalid 'publication_content'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'publication_status') {
            if (empty($this->publication_status) || !in_array($this->publication_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'publication_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'publication_category') {
            if (!is_string($this->publication_category) || !array_key_exists($this->publication_category, self::getCategories())) {
                throw new DxException("Invalid 'publication_category'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->hasAccessor('publication_date', 'getDate');
        $this->hasMany('DomainObjectModel_PublicationImage as PublicationImage', array(
            'local' => 'publication_id',
            'foreign' => 'publication_id',
            'orderBy' => 'publication_image_qnt ASC'));
        $this->getTable()
            ->getRelation('PublicationImage')
            ->offsetSet('cascade', array('delete'));
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
     * @return null
     */
    public function getCategoryName()
    {
        $categories = self::getCategories();
        return isset($categories[$this->getCategory()]) ? $categories[$this->getCategory()]['title'] : null;
    }

    /**
     * @param bool $full_path
     * @return mixed
     */
    public function getCategoryUrl($full_path = true)
    {
        $categories = self::getCategories();
        if (!isset($categories[$this->getCategory()])) {
            return null;
        }
        return $full_path ? DxApp::getComponent(DxApp::ALIAS_URL)->url($categories[$this->getCategory()]['url']) : $categories[$this->getCategory()]['url'];
    }

    /**
     * @return DxDateTime
     */
    public function getDate()
    {
        if (!$this->getFieldValue('publication_date', true)) {
            return new DxDateTime();
        } else {
            return new DxDateTime($this->getFieldValue('publication_date', true));
        }
    }

    /**
     * @param null|DxDateTime $publication_date
     * @return void
     */
    public function setDate(DxDateTime $publication_date = null)
    {
        if (is_null($publication_date)) {
            $publication_date = new DxDateTime();
        }

        $this->setFieldValue('publication_date', $publication_date->toUTC()->getMySQLDateTime());
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
        $url .= '/' . $this->getId() . '/' . $this->getSignature();
        return DxApp::getComponent(DxApp::ALIAS_URL)->url($url);
    }

    /**
     * @return array
     */
    public function getImages()
    {
        if (is_null($this->publication_images)) {
            $this->publication_images = array();

            /** @var $img DomainObjectModel_PublicationImage */
            foreach ($this->PublicationImage->getModels() as $img) {
                $this->publication_images[] = $img;
            }
        }

        return $this->publication_images;
    }

    public function getCoverFromImages()
    {
        $images = $this->getImages();
        /** @var $image DomainObjectModel_PublicationImage */
        foreach ($images as $image) {
            if ($image->getIsCover()) {
                return $image;
            }
        }
        return null;
    }

    public function getTags($as_array = false)
    {
        $tags = parent::getTags();
        if (!$as_array) {
            return $tags;
        }
        return preg_split('~\s*,\s*~', $tags);
    }

    public function getYoutubeData($as_object = false)
    {
        $youtube_code = parent::getYoutube();
        if (empty($youtube_code)) {
            return null;
        }

        DxFactory::import('Utils_YouTube');
        try {
            $yt = new Utils_YouTube($youtube_code);
            $res = array(
                'thumb'  => $yt->getHQThumbnail(),
                'iframe' => $yt->getIframeCode(),
            );
            return $as_object ? (object)$res : $res;
        } catch (DxException $e) {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getSourceLink()
    {
        $link = parent::getSourceLink();
        return empty($link) ? null : "http://{$link}";
    }

}
