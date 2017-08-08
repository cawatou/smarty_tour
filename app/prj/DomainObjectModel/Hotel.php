<?php
/**
 * @method int getId()
 * @method string getTitle()
 * @method string getExternalId()
 * @method int getStars()
 * @method string getMessage()
 * @method string getCountryTitle()
 * @method string getDescription()
 * @method string getResortTitle()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 *
 * @method setId(int $arg)
 * @method setTitle(string $arg)
 * @method setExternalId(string $arg)
 * @method setStars(int $arg)
 * @method setMessage(string $arg)
 * @method setDescription(string $arg)
 * @method setCountryId(int $arg)
 * @method setCountryTitle(string $arg)
 * @method setResortId(int $arg)
 * @method setResortTitle(string $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Hotel extends DomainObjectModel_BaseHotel
{
    /** @var string */
    protected $field_prefix = 'hotel';

    /** @var array */
    protected static $hotel_description_parts = array(
        'GENERAL'          => 'Общие услуги',
        'COMFORT'          => 'Удобства в номерах',
        'SPORT'            => 'Спорт',
        'HEALTH'           => 'Здоровье и красота',
        'FOOD'             => 'Питание',
        'INTERNET'         => 'Интернет',
        'BUSINESS'         => 'Бизнес-услуги',
        'SPECIAL_NUMBERS'  => 'Специальные номера',
        'FOR_CLOTH'        => 'Услуги по чистке одежды',
        'FOR_CHILDREN'     => 'Услуги для детей',
        'TRANSPORT'        => 'Транспорт',
        'ENTERTAINMENT'    => 'Развлечения',
        'PARKING'          => 'Парковка',
        'WATER_RECREATION' => 'Отдых на воде',
        'SMOKING'          => 'Курение',
        'BEACH'            => 'Пляж',
        'BEACH_TYPE'       => 'Тип пляжа',
        'HOTEL_TYPE'       => 'Тип отеля',
    );

    /** @var array */
    protected static $nutrition_types = array(
        'BED_BREAKFAST' => array(
            'title' => 'Завтраки',
            'code'  => 'BB',
        ),
        'ALL_INCLUSIVE' => array(
            'title' => 'Всё включено',
            'code'  => 'AI',
        ),
        'ULTRA_INCLUSIVE' => array(
            'title' => 'Ультра всё включено',
            'code'  => 'UAI',
        ),
        'ROOM_ONLY' => array(
            'title' => 'Без питания',
            'code'  => 'RO',
        ),
        'HALF_BOARD' => array(
            'title' => 'Завтрак и ужин',
            'code'  => 'HB',
        ),
        'FULL_BOARD' => array(
            'title' => 'Завтрак, обед, ужин',
            'code'  => 'FB',
        ),
        'ROOM_ONLY_BREAKFAST' => array(
            'title' => 'Без питания или завтраки',
            'code'  => 'ROBB',
        ),
        'ROOM_ONLY_HALF_BOARD' => array(
            'title' => 'Без питания или завтрак и ужин',
            'code'  => 'ROHB',
        ),
        'ROOM_ONLY_ALL_INCLUSIVE' => array(
            'title' => 'Без питания или всё включено',
            'code'  => 'ROAL',
        ),
        'BED_BREAKFAST_HALF_BOARD' => array(
            'title' => 'Завтраки или завтрак и ужин',
            'code'  => 'BBHB',
        ),
        'BED_BREAKFAST_ALL_INCLUSIVE' => array(
            'title' => 'Завтраки или все включено',
            'code'  => 'BBAI',
        ),
    );

    /** @var null|array */
    protected $hotel_images = null;

    /** @var array */
    protected static $list_stars = array(
        '1*' => array(
            'title' => "&#9733;",
            'id'    => '1*',
        ),
        '2*' => array(
            'title' => "&#9733;&#9733;",
            'id'    => '2*',
        ),
        '3*' => array(
            'title' => "&#9733;&#9733;&#9733;",
            'id'    => '3*',
        ),
        '4*' => array(
            'title' => "&#9733;&#9733;&#9733;&#9733;",
            'id'    => '4*',
        ),
        '5*' => array(
            'title' => "&#9733;&#9733;&#9733;&#9733;&#9733;",
            'id'    => '5*',
        ),
        'Apts' => array(
            'title' => 'Апартаменты',
            'id'    => 'Apts',
        ),
        'HV-1' => array(
            'title' => 'Тур. деревня',
            'id'    => 'HV-1',
        ),
        'HV-2' => array(
            'title' => 'Тур. деревня, ухудш.',
            'id'    => 'HV-2',
        ),
        'Villas' => array(
            'title' => 'Вилла',
            'id'    => 'Villas',
        ),
    );

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field == 'hotel_title') {
            if (empty($this->hotel_title)) {
                throw new DxException("Invalid 'hotel_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'hotel_message') {
            if ($this->hotel_message !== null && empty($this->hotel_message)) {
                throw new DxException("Invalid 'hotel_message'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'hotel_stars') {
            if ($this->hotel_stars !== null && empty($this->hotel_stars)) {
                throw new DxException("Invalid 'hotel_stars'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'country_id') {
            if (!is_numeric($this->country_id)) {
                throw new DxException("Invalid 'country_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'resort_id') {
            if (!is_numeric($this->resort_id)) {
                throw new DxException("Invalid 'resort_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'gallery_agency_id') {
            if ($this->gallery_agency_id !== null && !is_numeric($this->gallery_agency_id)) {
                throw new DxException("Invalid 'gallery_agency_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'gallery_tourists_id') {
            if ($this->gallery_tourists_id !== null && !is_numeric($this->gallery_tourists_id)) {
                throw new DxException("Invalid 'gallery_tourists_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'gallery_operator_id') {
            if ($this->gallery_operator_id !== null && !is_numeric($this->gallery_operator_id)) {
                throw new DxException("Invalid 'gallery_operator_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasColumn('hotel_extended_data',    'array');
        $this->hasColumn('hotel_description_data', 'array');
    }

    /**
     * @param string|null $key
     * @param null $default
     * @return mixed
     */
    public function getExtendedData($key = null, $default = null)
    {
        $data = parent::getExtendedData();

        if (null === $key) {
            return $data;
        }

        return isset($data[$key]) ? $data[$key] : $default;
    }

    /**
     * @param mixed       $data
     * @param string|null $key
     * @return mixed
     */
    public function setExtendedData($data, $key = null)
    {
        if (empty($data)) {
            return parent::setExtendedData(null);
        }

        if ($key === null) {
            return parent::setExtendedData($data);
        }

        $_data = $this->getExtendedData();

        $_data[$key] = $data;

        return parent::setExtendedData($_data);
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
     * @return DomainObjectModel_Hotel
     */
    public function setCountry(DomainObjectModel_Country $c)
    {
        $this->Country = $c;

        return $this;
    }

    /**
     * @return array
     *
     * @static
     */
    public static function getNutritionTypes()
    {
        return self::$nutrition_types;
    }

    /**
     * @param $id
     * @param null $key
     * @return mixed
     *
     * @static
     */
    public static function obtainNutritionType($id, $key = null)
    {
        $types = self::getNutritionTypes();
        if (!array_key_exists($id, $types)) {
            return null;
        }

        if (null === $key) {
            return $types[$id];
        }

        return isset($types[$id][$key]) ? $types[$id][$key] : null;
    }

    /**
     * @return array
     *
     * @static
     */
    public static function getHotelStars()
    {
        return self::$list_stars;
    }

    /**
     * @return null|string
     */
    static public function getStarsTitle($stars, $key = 'title')
    {
        $storage = self::getHotelStars();

        if (!array_key_exists($stars, $storage)) {
            return null;
        }

        return $storage[$stars][$key];
    }

    /**
     * @return int|null
     */
    public function getResortId()
    {
        return is_object($id = $this->getFieldValue('resort_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @return DomainObjectModel_Resort|null
     */
    public function getResort()
    {
        return is_numeric($this->getResortId()) ? $this->Resort : null;
    }

    /**
     * @param DomainObjectModel_Resort $r
     * @return DomainObjectModel_Hotel
     */
    public function setResort(DomainObjectModel_Resort $r)
    {
        $this->Resort = $r;

        return $this;
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

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = DxApp::getComponent(DxApp::ALIAS_URL);

        return $url->url("/hotel/{$this->getId()}/");
    }

    /**
     * @param null $hotel_title
     * @return mixed
     *
     * @static
     */
    public static function generateSignature($hotel_title = null)
    {
        if (empty($hotel_title)) {
            return null;
        }

        DxFactory::import('Utils_NameMaker');

        $signature = preg_replace('~\d{1}\*$~', '', trim($hotel_title));
        $signature = Utils_NameMaker::cyrillicToLatin($signature, true);
        $signature = preg_replace('~[^a-z0-9]~', '', $signature);

        return $signature;
    }

    public function getFeedbacks($as_array = false)
    {
        $feedbacks = array();

        foreach ($this->Feedback as $feedback) {
            if ($feedback->getStatus() == 'DISABLED') {
                continue;
            }

            $feedbacks[$feedback->getId()] = ($as_array ? $feedback->toArray() : $feedback);
        }

        return $feedbacks;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        if ($this->hotel_images !== null) {
            return $this->hotel_images;
        }

        $this->hotel_images = array();

        if ($this->getGalleryAgency() !== null) {
            foreach ($this->getGalleryAgency()->getImages() as $image) {
                $this->hotel_images[] = $image->getPath();
            }
        }

        if ($this->getGalleryOperator() !== null) {
            foreach ($this->getGalleryOperator()->getImages() as $image) {
                $this->hotel_images[] = $image->getPath();
            }
        }

        if ($this->getGalleryTourists() !== null) {
            foreach ($this->getGalleryTourists()->getImages() as $image) {
                $this->hotel_images[] = $image->getPath();
            }
        }

        return $this->hotel_images;
    }

    public function getCover()
    {
        $images = $this->getImages();

        return current($images);
    }

    /**
     * @return int|null
     */
    public function getGalleryAgencyId()
    {
        return is_object($id = $this->getFieldValue('gallery_agency_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @return DomainObjectModel_Gallery|null
     */
    public function getGalleryAgency()
    {
        return is_numeric($this->getGalleryAgencyId()) ? $this->Gallery : null;
    }

    /**
     * @param DomainObjectModel_Gallery $g
     * @return DomainObjectModel_Hotel
     */
    public function setGalleryAgency(DomainObjectModel_Gallery $g)
    {
        $this->Gallery = $g;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getGalleryOperatorId()
    {
        return is_object($id = $this->getFieldValue('gallery_operator_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @return DomainObjectModel_Gallery|null
     */
    public function getGalleryOperator()
    {
        return is_numeric($this->getGalleryOperatorId()) ? $this->Gallery_4 : null;
    }

    /**
     * @param DomainObjectModel_Gallery $g
     * @return DomainObjectModel_Hotel
     */
    public function setGalleryOperator(DomainObjectModel_Gallery $g)
    {
        $this->Gallery_4 = $g;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getGalleryTouristsId()
    {
        return is_object($id = $this->getFieldValue('gallery_tourists_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @return DomainObjectModel_Gallery|null
     */
    public function getGalleryTourists()
    {
        return is_numeric($this->getGalleryTouristsId()) ? $this->Gallery_5 : null;
    }

    /**
     * @param DomainObjectModel_Gallery $g
     * @return DomainObjectModel_Hotel
     */
    public function setGalleryTourists(DomainObjectModel_Gallery $g)
    {
        $this->Gallery_5 = $g;

        return $this;
    }

    /**
     * @param string|null $key
     * @param null $default
     * @return mixed
     */
    public function getDescriptionData($key = null, $default = null)
    {
        $data = parent::getDescriptionData();

        $parts = $this->getDescriptionParts();

        foreach ($parts as $part_id => $part_name) {
            if (empty($data[$part_id])) {
                $data[$part_id] = array(
                    'title'   => $part_name,
                    'options' => array(),
                );
            }
        }

        if ($key === null) {
            return $data;
        }

        return isset($data[$key]) ? $data[$key] : $default;
    }

    /**
     * @param mixed       $data
     * @param string|null $key
     * @return mixed
     */
    public function setDescriptionData($data, $key = null)
    {
        if (empty($data)) {
            return parent::setDescriptionData(null);
        }

        if ($key === null) {
            return parent::setDescriptionData($data);
        }

        $_data = $this->getDescriptionData();

        $_data[$key] = $data;

        return parent::setDescriptionData($_data);
    }

    static public function getDescriptionParts()
    {
        return self::$hotel_description_parts;
    }

    static public function getDescriptionPartTitle($id)
    {
        $parts = self::getDescriptionParts();

        if (empty($parts[$id])) {
            return null;
        }

        return $parts[$id];
    }
}