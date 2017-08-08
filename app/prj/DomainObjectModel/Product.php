<?php

/**
 * @method  int getId()
 * @method  int getUserId()
 * @method  int getLinkedId()
 * @method  string getTitle()
 * @method  string getAlias()
 * @method  float getPrice()
 * @method  float getDiscountPrice()
 * @method  float getLinkedPrice()
 * @method  float getLinkedDiscountPrice()
 * @method  string getCountryName()
 * @method  string getResortName()
 * @method  string getOperator()
 * @method  string getBrief()
 * @method  string getContent()
 * @method  string getNotes()
 * @method  string getPayableIncludes()
 * @method  string getPayableExcludes()
 * @method  int getIsHighlight()
 * @method  int getIsDiscountApplied()
 * @method  string getCover()
 * @method  int getFromId()
 * @method  string getStatus()
 * @method  int getQnt()
 * @method  DxDateTime getCreated()
 * @method  DxDateTime getUpdated()
 * @method  DomainObjectModel_Product[] getProducts()
 * @method  DomainObjectModel_User getUser()
 * @method  DomainObjectModel_Order[] getOrders()
 * @method  DomainObjectModel_ProductDeparture[] getProductDepartures()
 * @method  DomainObjectModel_ProductImage[] getProductImages()
 *
 * @method  setId(int $arg)
 * @method  setUserId(int $arg)
 * @method  setLinkedId(int $arg)
 * @method  setTitle(string $arg)
 * @method  setAlias(string $arg)
 * @method  setCountryId(int $arg)
 * @method  setCountryName(string $arg)
 * @method  setResortId(int $arg)
 * @method  setResortName(string $arg)
 * @method  setTouroperatorId(int $arg)
 * @method  setOperator(string $arg)
 * @method  setBrief(string $arg)
 * @method  setContent(string $arg)
 * @method  setNotes(string $arg)
 * @method  setPayableIncludes(string $arg)
 * @method  setPayableExcludes(string $arg)
 * @method  setIsHighlight(int $arg)
 * @method  setIsDiscountApplied(int $arg)
 * @method  setCover(string $arg)
 * @method  setFromId(int $arg)
 * @method  setStatus(string $arg)
 * @method  setQnt(int $arg)
 * @method  setCreated(DxDateTime $arg)
 * @method  setUpdated(DxDateTime $arg)
 * @method  setUser(DomainObjectModel_User $arg)
 */

class DomainObjectModel_Product extends DomainObjectModel_BaseProduct
{
    const EXPIRATION_LIMIT = '- 5 days';

    /** @var string */
    protected $field_prefix = 'product';

    /** @var null|array */
    protected $product_images = null;

    /** @var null|array */
    protected $product_departures = null;

    /** @var null|array */
    protected $product_linked = null;

    /** @var null|array */
    protected $hotel_discounts = null;

    /**
     * @var array
     *
     * @protected
     *
     * @static
     */
    static protected $departure_from = array(
        1 => array(
            'title'      => 'Абакан',
            'title_from' => 'из Абакана',
            'alias'      => 'abakan',
        ),
        2 => array(
            'title'      => 'Барнаул',
            'title_from' => 'из Барнаула',
            'alias'      => 'barnaul',
        ),
        /*
        3 => array(
            'title'      => 'Бердск',
            'title_from' => 'из Бердска',
            'alias'      => 'berdsk',
        ),

        4 => array(
            'title'      => 'Бийск',
            'title_from' => 'из Бийска',
            'alias'      => 'bijsk',
        ),
        */
        5 => array(
            'title'      => 'Екатеринбург',
            'title_from' => 'из Екатеринбурга',
            'alias'      => 'ekaterinburg',
        ),
        6 => array(
            'title'      => 'Кемерово',
            'title_from' => 'из Кемерово',
            'alias'      => 'kemerovo',
        ),
        7 => array(
            'title'      => 'Красноярск',
            'title_from' => 'из Красноярска',
            'alias'      => 'krasnoyarsk',
        ),
        /*
        8 => array(
            'title'      => 'Ленинск-Кузнецкий',
            'title_from' => 'из Ленинск-Кузнецкого',
            'alias'      => 'leninsk-kuz',
        ),
         */
        9 => array(
            'title'      => 'Москва',
            'title_from' => 'из Москвы',
            'alias'      => 'moskva',
        ),
        10 => array(
            'title'      => 'Нижневартовск',
            'title_from' => 'из Нижневартовска',
            'alias'      => 'nvartovsk',
        ),
        11 => array(
            'title'      => 'Новокузнецк',
            'title_from' => 'из Новокузнецка',
            'alias'      => 'novokuznetsk',
        ),
        12 => array(
            'title'      => 'Новосибирск',
            'title_from' => 'из Новосибирска',
            'alias'      => 'novosibirsk',
        ),
        13 => array(
            'title'      => 'Омск',
            'title_from' => 'из Омска',
            'alias'      => 'omsk',
        ),
        /*
        14 => array(
            'title'      => 'Прокопьевск',
            'title_from' => 'из Прокопьевска',
            'alias'      => 'prokopevsk',
        ),
         */
        /*
        15 => array(
            'title'      => 'Северск',
            'title_from' => 'из Северска',
            'alias'      => 'seversk',
        ),
         */
        /*
        16 => array(
            'title'      => 'Стрежевой',
            'title_from' => 'из Стрежевого',
            'alias'      => 'strezh',
        ),
         */
        17 => array(
            'title'      => 'Сургут',
            'title_from' => 'из Сургута',
            'alias'      => 'surgut',
        ),
        18 => array(
            'title'      => 'Томск',
            'title_from' => 'из Томска',
            'alias'      => 'tomsk',
        ),
        19 => array(
            'title'      => 'Тюмень',
            'title_from' => 'из Тюмени',
            'alias'      => 'tumen',
        ),
        /*
        20 => array(
            'title'      => 'Горно-Алтайск',
            'title_from' => 'из Горно-Алтайска',
            'alias'      => 'galtaysk',
        ),
         */
    );

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'product_title') {
            if (empty($this->product_title)) {
                throw new DxException("Invalid 'product_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'product_alias') {
            if (!is_null($this->product_alias) && preg_match('~[^a-z0-9_-]+~u', $this->product_alias)) {
                throw new DxException("Invalid 'product_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field === 'product_price') {
            if (!is_numeric($this->product_price)) {
                throw new DxException("Invalid 'product_price'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }

            if ((int)$this->product_price <= 0) {
                throw new DxException("Invalid 'product_price'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'product_discount_price') {
            if (!is_null($this->product_discount_price) && (!is_numeric($this->product_discount_price) || (int)$this->product_discount_price < 0)) {
                throw new DxException("Invalid 'product_discount_price'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field === 'country_id') {
            if (!is_numeric($this->country_id)) {
                throw new DxException("Invalid 'country_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'resort_id') {
            if (!empty($this->resort_id) && !is_numeric($this->resort_id)) {
                throw new DxException("Invalid 'resort_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'product_brief') {
            if ($this->product_brief !== null && empty($this->product_brief)) {
                throw new DxException("Invalid 'product_brief'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'product_content') {
            if ($this->product_content !== null && empty($this->product_content)) {
                throw new DxException("Invalid 'product_content'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'product_attributes') {
            if ($this->product_attributes !== null && !is_array($this->product_attributes)) {
                throw new DxException("Invalid 'product_attributes'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'product_get_via') {
            if ($this->product_get_via !== null && !is_array($this->product_get_via)) {
                throw new DxException("Invalid 'product_get_via'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'product_from_id') {
            if (empty($this->product_from_id) || !array_key_exists($this->product_from_id, self::getFromAll())) {
                throw new DxException("Invalid 'product_from_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'product_status') {
            if (empty($this->product_status) || !in_array($this->product_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'product_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'product_is_highlight') {
            if (!is_numeric($this->product_is_highlight) || (int)$this->product_is_highlight < 0) {
                throw new DxException("Invalid 'product_is_highlight'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'product_is_discount_applied') {
            if (!is_numeric($this->product_is_discount_applied) || (int)$this->product_is_discount_applied < 0) {
                throw new DxException("Invalid 'product_is_discount_applied'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'product_linked_id') {
            if ($this->product_linked_id !== null && !is_numeric($this->product_linked_id)) {
                throw new DxException("Invalid 'product_linked_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->hasColumn('product_attributes',       'array');
        $this->hasColumn('product_get_via',          'array');
        $this->hasColumn('product_payable_includes', 'array');
        $this->hasColumn('product_payable_excludes', 'array');

        $this->getTable()
            ->getRelation('ProductImage')
            ->offsetSet('cascade', array('delete'));
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        if ($price === null || !is_numeric($price) && !is_float($price)) {
            $this->setFieldValue('product_price', null);
        } else {
            $this->setFieldValue('product_price', (float)number_format(str_replace(',', '.', $price), 2, '.', ''));
        }
    }

    /**
     * @param float $discount_price
     */
    public function setDiscountPrice($discount_price)
    {
        if ($discount_price === null || !is_numeric($discount_price) && !is_float($discount_price)) {
            $this->setFieldValue('product_discount_price', null);
        } else {
            $this->setFieldValue('product_discount_price', (float)number_format(str_replace(',', '.', $discount_price), 2, '.', ''));
        }
    }

    /**
     * @return float
     */
    public function getSalePrice()
    {
        if ($this->getLinkedDiscountPrice() !== null) {
            return $this->getLinkedDiscountPrice();
        }

        return $this->getDiscountPrice();
    }

    /**
     * @return float
     */
    public function getCrossedPrice()
    {
        $price = $this->getPrice();

        if ($this->getLinkedPrice() !== null) {
            $price = $this->getLinkedPrice();
        }

        return $price;
    }

    /**
     * @return bool
     */
    public function isUniqueAlias()
    {
        /** @var $q DomainObjectQuery_Product */
        $q = DxFactory::getSingleton('DomainObjectQuery_Product');
        $p = $q->findByTitleOrAlias($this->getAlias());

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
     * @return string
     */
    public function getUrl()
    {
        $url = DxApp::getComponent(DxApp::ALIAS_URL);

        $suffix = 'tours';

        if ($this->getCountryId() == DomainObjectQuery_Product::COUNTRY_ID_RUSSIA) {
            $suffix = 'russia';
        }

        if ($this->getAlias()) {
            return $url->url('/'. $suffix .'/'. $this->getAlias());
        }

        return $url->url('/'. $suffix .'/'. $this->getId());
    }

    /**
     * @return string
     */
    public function getAdsUrl()
    {
        $url = DxApp::getComponent(DxApp::ALIAS_URL);

        if ($this->getCountryId() == DomainObjectQuery_Product::COUNTRY_ID_RUSSIA) {
            return $url->url('/russia/ads/'. $this->getFrom('alias'));
        }

        return $url->url('/ads/'. $this->getFrom('alias') .'/'. $this->getCountry()->getAlias());
    }

    /**
     * @return array
     */
    public function getImages()
    {
        if ($this->product_images !== null) {
            return $this->product_images;
        }

        $this->product_images = array();

        /** @var $img DomainObjectModel_ProductImage */
        foreach ($this->ProductImage as $img) {
            $this->product_images[] = $img;
        }

        return $this->product_images;
    }

    /**
     * Return either all "FROM" values or just one "FROM" value
     *
     * @param string|null $key
     * @return array|mixed|null
     */
    public function getFrom($key = null)
    {
        $froms   = self::getFromAll();
        $from_id = $this->getFromId();

        if (!array_key_exists($from_id, $froms)) {
            return null;
        }

        $active_from = $froms[$from_id];

        if ($key === null) {
            return $active_from;
        }

        return !array_key_exists($key, $active_from) ? null : $active_from[$key];
    }

    /**
     * Return either all "FROM" values or just one "FROM" value
     *
     * @param string|null $from_id
     * @param string|null $key
     * @return array|mixed|null
     *
     * @static
     */
    static public function getFromItem($from_id, $key = null)
    {
        $froms   = self::getFromAll();

        if (!array_key_exists($from_id, $froms)) {
            return null;
        }

        $active_from = $froms[$from_id];

        if ($key === null) {
            return $active_from;
        }

        return !array_key_exists($key, $active_from) ? null : $active_from[$key];
    }

    /**
     * Returns array of all possible product_from values
     *
     * @return array
     *
     * @static
     */
    public static function getFromAll()
    {
        return self::$departure_from;
    }

    /**
     * @return null
     */
    public function getFirstDeparture()
    {
        $deps = $this->getDepartures();
        return empty($deps) ? null : $deps[0];
    }

    /**
    * @return array
    */
    public function getDepartures()
    {
        if ($this->product_departures === null) {
            $this->product_departures = $this->ProductDeparture;
        }

        foreach ($this->product_departures as $k => $m) {
            if ($m->isRemoved()) {
                unset($this->product_departures[$k]);
            }
        }

        return $this->product_departures;
    }

    /**
     * @return array
     */
    public function getLinkedProducts()
    {
        if ($this->product_linked === null) {
            $this->product_linked = $this->Product;
        }

        return $this->product_linked;
    }

    /**
    * @return array
    */
    public function getFittingDiscounts()
    {
        if ($this->hotel_discounts !== null) {
            return $this->hotel_discounts;
        }

        /** @var DomainObjectQuery_Discount $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_Discount');

        $this->hotel_discounts = $q->findByProduct($this);

        return $this->hotel_discounts;
    }

    public function isDiscountFitting(DomainObjectModel_Discount $discount, $price, DomainObjectModel_Product $product = null)
    {
        if ($product !== null && !$product->getIsDiscountApplied()) {
            return false;
        }

        if ($discount->getCountryId() && $discount->getCountryId() != $this->getCountryId()) {
            return false;
        }

        if ($discount->getTouroperatorId() && $discount->getTouroperatorId() != $this->getTouroperatorId()) {
            return false;
        }

        if ($discount->getDepartureCityId() && $discount->getDepartureCityId() != $this->getFromId()) {
            return false;
        }

        if ($discount->getPriceMin() && $price < $discount->getPriceMin()) {
            return false;
        }

        if ($discount->getPriceMax() && $price > $discount->getPriceMax()) {
            return false;
        }

        return $discount->getPercent();
    }

    /**
     * @param float                           $price
     * @param DomainObjectModel_Discount|null $discount
     * @return float
     */
    public function calculatePriceWithDiscount($price, DomainObjectModel_Discount $discount = null)
    {
        if ($price <= 0) {
            return 0;
        }

        if ($discount) {
            $price = $price - (($price * $discount->getPercent()) / 100);
        }

        return floor(($price / 2) / 100) * 100;
    }

    /**
     * @param DomainObjectModel_ProductDeparture $pd
     */
    public function setDeparture(DomainObjectModel_ProductDeparture $pd)
    {
        $this->ProductDeparture[] = $pd;
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
     * @return DomainObjectModel_Product
     */
    public function setCountry(DomainObjectModel_Country $c)
    {
        $this->Country = $c;

        return $this;
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
     * @return DomainObjectModel_Product
     */
    public function setResort(DomainObjectModel_Resort $r)
    {
        $this->Resort = $r;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCountryCover()
    {
        /** @var DomainObjectModel_Gallery $gallery */
        $gallery = $this->getCountry()->getGallery();

        if ($gallery === null) {
            return null;
        }

        return $gallery->getCoverPath();
    }

    /**
     * @return array
     */
    public function getCountryImages()
    {
        /** @var DomainObjectModel_Gallery $gallery */
        $gallery = $this->getCountry()->getGallery();

        if ($gallery === null) {
            return null;
        }

        return $gallery->getImages('ENABLED');
    }

    /**
     * @return array
     */
    public function getResortImages()
    {
        if ($this->getResort() === null) {
            return null;
        }

        /** @var DomainObjectModel_Gallery $gallery */
        $gallery = $this->getResort()->getGallery();

        if ($gallery === null) {
            return null;
        }

        return $gallery->getImages('ENABLED');
    }

    /**
     * @return float|null
     */
    public function getDiscountPercent()
    {
        if ($this->getLinkedDiscountPrice() !== null) {
            $price    = $this->getLinkedPrice();
            $discount = $price - $this->getLinkedDiscountPrice();
        } else {
            $price    = $this->getPrice();
            $discount = $price - $this->getDiscountPrice();
        }

        if ($discount <= 0) {
            return null;
        }

        $percent = $discount * 100 / $price;

        return ceil($percent) * -1;

    }

    /**
     * @return DxDateTime|DxDateTime
     */
    public function getFromUpdate()
    {
        /** @var DomainObjectQuery_ProductFrom $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_ProductFrom');
        $m = $q->findById($this->getFromId());
        return $m === null ? new DxDateTime() : $m->getDate();
    }

    public function preDelete($event)
    {
        parent::preDelete($event);

        $this->clearCaches();
    }

    public function preInsert($event)
    {
        parent::preInsert($event);

        $this->clearCaches();
    }

    public function preUpdate($event)
    {
        parent::preUpdate($event);

        $this->clearCaches();
    }

    public function clearCaches()
    {
        $smarty = DxApp::getComponent(DxConstant_Project::ALIAS_SMARTY);

        $smarty->clearCache('frontend/master_main.tpl.php');
        $smarty->clearCache('frontend/master_russia.tpl.php');

        $id = $this->getId();

        if ($this->getLinkedId()) {
            $id = $this->getLinkedId();
        }

        $smarty->clearCache('frontend/master_tour.tpl.php', 'TOUR_'. $id);
        $smarty->clearCache('frontend/master_russia_tour.tpl.php', 'RUSTOUR_'. $id);

        $smarty->clearCache('frontend/master_tour.tpl.php', 'TOUR_'. $id .'_SEC');
        $smarty->clearCache('frontend/master_russia_tour.tpl.php', 'RUSTOUR_'. $id .'_SEC');
    }

    /**
     * Is hotel expired or not
     *
     * @note WARNING! This method is returning false at the moment, because client is a retarded retard
     *
     * @param string $name Hotel name to check
     * @return boolean
     */
    public function isHotelExpired($name)
    {
        // Retarded retart asked for this. After some time, said he do no need it anymore
        return false;

        $departure = current(current($this->getDepartures()));

        $hotel = null;

        if ($departure === null) {
            return false;
        }

        foreach ($departure->getHotels() as $h) {
            if ($h['name'] == $name) {
                $hotel = $h;

                break;
            }
        }

        if ($hotel === null) {
            return false;
        }

        if (empty($hotel['added_at'])) {
            return false;
        }

        $exp_date = new DxDateTime(self::EXPIRATION_LIMIT);

        if ($exp_date->difference($hotel['added_at']) > 0) {
            return true;
        }

        return false;
    }

    public function isHotelsRotten()
    {
        $departure = current(current($this->getDepartures()));

        if (empty($departure)) {
            return false;
        }

        if (!count($departure->getHotels())) {
            return false;
        }

        $total   = count($departure->getHotels());
        $expired = 0;

        $expiration_limit = $this->getHotelExpirationLimit();

        foreach ($departure->getHotels() as $hotel) {
            if ($this->isHotelExpired($hotel['name'])) {
                $expired++;
            }
        }

        if ($expired > $expiration_limit) {
            return true;
        }

        return false;
    }

    public function getHotelExpirationLimit()
    {
        $departure = current(current($this->getDepartures()));

        $total = count($departure->getHotels());

        return $total - (floor($total / 3));
    }

    public function encodeSourceData()
    {
        $departure = current(current($this->getDepartures()));

        $data = array();

        if (!$departure || count($departure->getHotels()) == 0) {
            return serialize($data);
        }

        foreach ($this->getDepartures() as $dep) {
            $data[] = $dep->toArray();
        }

        return serialize($data);
    }

    /**
     * @return int|null
     */
    public function getTouroperatorId()
    {
        return is_object($id = $this->getFieldValue('touroperator_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Touroperator $t
     */
    public function setTouroperator(DomainObjectModel_Touroperator $t)
    {
        $this->Touroperator = $t;
    }

    /**
     * @return DomainObjectModel_Touroperator|null
     */
    public function getTouroperator()
    {
        return is_numeric($this->getTouroperatorId()) ? $this->Touroperator : null;
    }

    /*
     * @param string|null $key
     * @param null $default
     * @return mixed
     */
    public function getAttributes($key = null, $default = null)
    {
        $data = parent::getAttributes();

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
    public function setAttributes($data, $key = null)
    {
        if (empty($data)) {
            return parent::setAttributes(null);
        }

        if (null === $key) {
            $_data = $data;
        } else {
            $_data = $this->getAttributes();
            $_data[$key] = $data;
        }

        return parent::setAttributes($_data);
    }

    /*
     * @param string|null $key
     * @param null $default
     * @return mixed
     */
    public function getGetVia($key = null, $default = null)
    {
        $data = parent::getGetVia();

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
    public function setGetVia($data, $key = null)
    {
        if (empty($data)) {
            return parent::setGetVia(null);
        }

        if (null === $key) {
            $_data = $data;
        } else {
            $_data = $this->getGetVia();
            $_data[$key] = $data;
        }

        return parent::setGetVia($_data);
    }
}