<?php
/**
 * @method int getId()
 * @method string getTitle()
 * @method string getAlias()
 * @method string getStatus()
 * @method string getSmsGroup()
 * @method string getEmailGroup()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 *
 * @method setId(int $arg)
 * @method setTitle(string $arg)
 * @method setAlias(string $arg)
 * @method setStatus(string $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_City extends DomainObjectModel_BaseCity
{
    /** @var string */
    protected $field_prefix = 'city';
//    protected $map_subscription = array(
//        // Новосибирск
//        7 => array(
//            'email' => 0,
//        ),
//        // Омск
//        6 => array(
//            'email' => 1,
//        ),
//        // Красноярск
//        12 => array(
//            'email' => 2,
//        ),
//        // Москва
//        10 => array(
//            'email' => 3,
//        ),
//        // Бийск
//        15 => array(
//            'email' => 4,
//        ),
//        // Бердск
//        16 => array(
//            'email' => 5,
//        ),
//        // Барнаул
//        17 => array(
//            'email' => 6,
//        ),
//        // Екатеринбург
//        14 => array(
//            'email' => 7,
//        ),
//        // Пермь
//        22 => array(
//            'email' => 8,
//        ),
//        // Новокузнецк
//        8 => array(
//            'email' => 9,
//        ),
//        // Кемерово
//        13 => array(
//            'email' => 10,
//        ),
//        // Томск
//        1 => array(
//            'email' => 11,
//        ),
//        // Северск
//        4 => array(
//            'email' => 11,
//        ),
//        // Прокопьевск
//        5 => array(
//            'email' => 12,
//        ),
//        // Ленинск-кузнецкий
//        11 => array(
//            'email' => 13,
//        ),
//        // Стрежевой
//        3 => array(
//            'email' => 14,
//        ),
//        // Сургут
//        2 => array(
//            'email' => 15,
//        ),
//        // Нижневартовск
//        9 => array(
//            'email' => 16,
//        ),
//        // Абакан
//        18 => array(
//            'email' => 17,
//        ),
//        // Горно-Алтайск
//        19 => array(
//            'email' => 18,
//        ),
//        // Тюмень
//        20 => array(
//            'email' => 19,
//        ),
//        // Челябинск
//        21 => array(
//            'email' => 20,
//        ),
//        // Нижний тагил
//        23 => array(
//            'email' => 21,
//        ),
//        // Курган
//        24 => array(
//            'email' => 22,
//        ),
//        // Междуреченск
//        25 => array(
//            'email' => 23,
//        ),
//        // Белово
//        26 => array(
//            'email' => 24,
//        ),
//        // Анджеро-Судженск
//        27 => array(
//            'email' => 25,
//        ),
//        // Ачинск
//        28 => array(
//            'email' => 26,
//        ),
//        // Канск
//        29 => array(
//            'email' => 27,
//        ),
//        // Кызыл
//        30 => array(
//            'email' => 28,
//        ),
//        // Уфа
//        31 => array(
//            'email' => 29,
//        ),
//        // Магнитогорск
//        32 => array(
//            'email' => 30,
//        ),
//        // Юрга
//        33 => array(
//            'email' => 31,
//        ),
//        // Асино
//        34 => array(
//            'email' => 32,
//        ),
//        // Санкт-Петербург
//        35 => array(
//            'email' => 33,
//        ),
//        // Казань
//        36 => array(
//            'email' => 34,
//        ),
//        // Воронеж
//        37 => array(
//            'email' => 35,
//        ),
//        // Нижний Новгород
//        38 => array(
//            'email' => 36,
//        ),
//        // Иркутск
//        39 => array(
//            'email' => 37,
//        ),
//        // Братск
//        40 => array(
//            'email' => 38,
//        ),
//    );
    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field == 'city_title') {
            if (empty($this->city_title)) {
                throw new DxException("Invalid 'city_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'city_alias') {
            if (empty($this->city_alias)) {
                throw new DxException("Invalid 'city_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }

            if (preg_match('~[^a-z0-9_-]+~u', $this->city_alias)) {
                throw new DxException("Invalid 'city_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field == 'city_status') {
            if (!is_string($this->city_status) || !in_array($this->city_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'city_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'city_email') {
            if ($this->city_email !== null) {
                if (!is_string($this->city_email)) {
                    throw new DxException("Invalid 'city_email'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
                }

                if (!filter_var($this->city_email, FILTER_VALIDATE_EMAIL)) {
                    throw new DxException("Invalid 'city_email'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
                }
            }
        }

        if ($field === null || $field == 'city_top_news') {
            if (!empty($this->city_top_news) && !is_string($this->city_top_news)) {
                throw new DxException("Invalid 'city_top_news'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'city_sletat_data') {
            if ($this->city_sletat_data !== null && !is_array($this->city_sletat_data)) {
                throw new DxException("Invalid 'city_sletat_data'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @param string $field
     * @return bool
     */
    public function isUnique($field = 'title')
    {
        /** @var $q DomainObjectQuery_City */
        $q = DxFactory::getSingleton('DomainObjectQuery_City');
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
     * @return null|string
     */
    public function getUrl($prefix = 'tours', $as_cmd = false)
    {
        if (!$this->getAlias()) {
            return null;
        }

        return $as_cmd ? ".{$prefix}.{$this->getAlias()}" : '/'. $prefix .'/'. $this->getAlias() .'/';
    }

    /**
     * @return string
     */
    public function getToggleUrl()
    {
        $url = DxApp::getComponent(DxApp::ALIAS_URL);
        return $url->url("/city/{$this->getId()}");
    }

    /**
     * @return null|string
     */
    public function getContactsUrl()
    {
        if (!$this->getAlias()) {
            return null;
        }

        return '/contact/'. $this->getAlias() .'/';
    }

    public function getTitleParental()
    {
        $title = $this->getTitle();

        switch (mb_strtolower($title)) {
            case 'ленинск-кузнецкий':
                    return 'Ленинск-Кузнецкого';
            case 'пермь':
                    return 'Перми';
            case 'стрежевой':
                    return 'Стрежевого';
            case 'кемерово':
            case 'белово':
            case 'асино':
                    return $title;
            case 'москва':
                    return 'Москвы';
            case 'нижний тагил':
                    return 'Нижнего Тагила';
            case 'юрга':
                    return 'Юрги';
            case 'уфа':
                    return 'Уфы';
            case 'тюмень':
                    return 'Тюмени';
            case 'казань':
                    return 'Казани';
            default:
                    return $title .'а';
        }
    }

    public function getTitleIn()
    {
        $title = $this->getTitle();

        switch (mb_strtolower($title)) {
            case 'ленинск-кузнецкий':
                    return 'Ленинск-Кузнецком';
            case 'пермь':
                    return 'Перми';
            case 'стрежевой':
                    return 'Стрежевом';
            case 'кемерово':
            case 'белово':
            case 'асино':
                    return $title;
            case 'москва':
                    return 'Москве';
            case 'нижний тагил':
                    return 'Нижнем Тагиле';
            case 'юрга':
                    return 'Юрге';
            case 'уфа':
                    return 'Уфе';
            case 'тюмень':
                    return 'Тюмени';
            case 'казань':
                    return 'Казани';
            default:
                    return $title .'е';
        }
    }

    /**
     * @return null
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasColumn('city_city_ids',               'array');
        $this->hasColumn('city_sletat_data',            'array');
        $this->hasColumn('city_departure_list',         'array');
        $this->hasColumn('city_similar_product_cities', 'array');
    }

    public function getFromAll()
    {
        DxFactory::import('DomainObjectModel_Product');

        $product_departures = DomainObjectModel_Product::getFromAll();

        $city_departures = $this->getDepartureList();

        $i = 0;

        if (empty($city_departures)) {
            $city_departures = array();

            foreach ($product_departures as $departure_id => $dep) {
                $city_departures[] = array(
                    'departure_id'         => $departure_id,
                    'departure_title'      => $dep['title_from'],
                    'departure_title_flat' => $dep['title'],
                    'qnt'                  => $i++,
                );
            }

            return $city_departures;
        }

        foreach ($city_departures as $k => $city_dep) {
            if (empty($city_dep['departure_id']) || empty($product_departures[$city_dep['departure_id']])) {
                unset($city_departures[$k]);

                continue;
            }

            if ($i < $city_dep['qnt']) {
                $i = $city_dep['qnt'];
            }

            $city_departures[$k]['departure_title']      = $product_departures[$city_dep['departure_id']]['title_from'];
            $city_departures[$k]['departure_title_flat'] = $product_departures[$city_dep['departure_id']]['title'];
        }

        foreach ($product_departures as $departure_id => $dep) {
            $is_found = false;

            foreach ($city_departures as $city_dep) {
                if ($city_dep['departure_id'] == $departure_id) {
                    $is_found = true;

                    break;
                }
            }

            if (!$is_found) {
                $city_departures[] = array(
                    'departure_id'         => $departure_id,
                    'departure_title'      => $dep['title_from'],
                    'departure_title_flat' => $dep['title'],
                    'qnt'                  => $i++,
                );
            }
        }

        return $city_departures;
    }

    /**
     * @param int $city_id
     * @return boolean
     */
    public function isNearbyCityId($city_id)
    {
        $city_ids = $this->getCityIds();

        if (empty($city_ids) || empty($city_ids[$city_id])) {
            return false;
        }

        return true;
    }

    /**
     * @param int $city_id
     * @return boolean
     */
    public function isTourFromCityId($city_id)
    {
        $city_ids = $this->getSimilarProductCities();

        if (empty($city_ids) || empty($city_ids[$city_id])) {
            return false;
        }

        return true;
    }

    public function getNearbyCityQnt($city_id)
    {
        $city_ids = $this->getCityIds();

        if (empty($city_ids) || empty($city_ids[$city_id])) {
            return false;
        }

        return $city_ids[$city_id];
    }

//    /**
//     * @return null
//     */
//    public function getEmailSubscriptionId()
//    {
//
//        if (empty($this->map_subscription[$this->getId()])) {
//            return null;
//        }
//
//        if (!array_key_exists('email', $this->map_subscription[$this->getId()]) && !array_key_exists('email', $this->map_subscription[$this->getTitle()])) {
//            return null;
//        }
//
//        if (array_key_exists('email', $this->map_subscription[$this->getId()])) {
//            return $this->map_subscription[$this->getId()]['email'];
//        }
//
//        return $this->map_subscription[$this->getTitle()]['email'];
//    }

    /**
     * @return null
     */
    public function getSmsSubscriptionId()
    {
    /*
        if (empty($this->map_subscription[$this->getId()])) {
            return null;
        }

        if (!array_key_exists('sms', $this->map_subscription[$this->getId()]) && !array_key_exists('sms', $this->map_subscription[$this->getTitle()])) {
            return null;
        }

        if (array_key_exists('sms', $this->map_subscription[$this->getId()])) {
            return $this->map_subscription[$this->getId()]['sms'];
        }

        return $this->map_subscription[$this->getTitle()]['sms'];
*/
        return $this->getSmsGroup();

    }

    /**
     * @param null $key
     * @param null $default
     * @return null
     */
    public function getSletatData($key = null, $default = null)
    {
        $data = parent::getSletatData();

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
    public function setSletatData($data, $key = null)
    {
        if (empty($data)) {
            return parent::setSletatData(null);
        }

        if ($key === null) {
            $_data = $data;
        } else {
            $_data = $this->getSletatData();
            $_data[$key] = $data;
        }

        return parent::setSletatData($_data);
    }
}