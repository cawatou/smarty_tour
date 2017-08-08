<?php
/**
 * @method int getId()
 * @method string getAlias()
 * @method string getEmail()
 * @method string getPhone()
 * @method string getStatus()
 * @method int getQnt()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 *
 * @method setId(int $arg)
 * @method setCityId(int $arg)
 * @method setTitle(string $arg)
 * @method setAddress(string $arg)
 * @method setEmail(string $arg)
 * @method setPhone(string $arg)
 * @method setStatus(string $arg)
 * @method setQnt(int $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Office extends DomainObjectModel_BaseOffice
{
    /** @var string */
    protected $field_prefix = 'office';

    /** @var null|array */
    protected $office_staffs = null;
    protected $cache_schedule_string = null;

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field == 'office_title') {
            if (!empty($this->office_title) && !is_string($this->office_title)) {
                throw new DxException("Invalid 'office_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'office_display_name') {
            if ($this->office_display_name !== null && !is_string($this->office_display_name)) {
                throw new DxException("Invalid 'office_display_name'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'office_phone') {
            if (empty($this->office_phone)) {
                throw new DxException("Invalid 'office_phone'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'office_address') {
            if (empty($this->office_address)) {
                throw new DxException("Invalid 'office_address'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'office_metro') {
            if (empty($this->office_metro) && $this->office_metro !== null) {
                throw new DxException("Invalid 'office_metro'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'office_email') {
            if (!empty($this->office_email) && filter_var($this->office_email, FILTER_VALIDATE_EMAIL) === false) {
                throw new DxException("Invalid 'office_email'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field == 'office_status') {
            if (empty($this->office_status) || !in_array($this->office_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'office_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'city_id') {
            if (empty($this->city_id)) {
                throw new DxException("Invalid 'city_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'related_city_id') {
            if ($this->related_city_id !== null && !is_numeric($this->related_city_id)) {
                throw new DxException("Invalid 'related_city_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'subdivision_id') {
            if ($this->subdivision_id !== null && !is_numeric($this->subdivision_id)) {
                throw new DxException("Invalid 'subdivision_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'office_sletat_data') {
            if ($this->office_sletat_data !== null && !is_array($this->office_sletat_data)) {
                throw new DxException("Invalid 'office_sletat_data'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @param sting|null $office_title
     */
    public function setAlias($office_title = null)
    {
        if ($office_title === null) {
            $office_title = $this->getFieldValue('office_title');
        }

        DxFactory::import('Utils_NameMaker');
        $this->setFieldValue('office_alias', Utils_NameMaker::cyrillicToLatin($office_title, true));
    }

    /**
     * @return null
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasColumn('office_schedule',    'array');
        $this->hasColumn('office_sletat_data', 'array');

        $this->hasMany(
            'DomainObjectModel_Staff as Staff',

            array(
                'local'   => 'office_id',
                'foreign' => 'office_id',
                'orderBy' => 'staff_qnt ASC',
            )
        );
    }

    /**
     * @param $day
     * @param null $param
     * @return null|object
     */
    public function getScheduleDay($day, $param = null)
    {
        $schedule = $this->getSchedule();

        if (empty($day)) {
            return null;
        }

        if ($param === null) {
            return empty($schedule[$day]) ? null : $schedule[$day];
        }

        if (!empty($schedule[$day][$param])) {
            if ($param == 'time') {
                return str_replace('-', '–', $schedule[$day][$param]);
            }

            return $schedule[$day][$param];
        }

        return null;
    }

    public function isSameDays(array $days)
    {
        $current = null;

        foreach ($days as $day) {
            if (null === $current) {
                $current = $this->getScheduleDay($day, 'time');

                continue;
            }

            if ($this->getScheduleDay($day, 'time') != $current) {
                return false;
            }
        }

        return true;
    }

    public function getScheduleAsString()
    {
        if ($this->cache_schedule_string !== null) {
            return $this->cache_schedule_string;
        }

        $schedule = $this->getSchedule();

        if (empty($schedule)) {
            return '';
        }

        $prev   = null;
        $unique = '';

        $day_map = array(
            1 => 'Пн',
            2 => 'Вт',
            3 => 'Ср',
            4 => 'Чт',
            5 => 'Пт',
            6 => 'Сб',
            7 => 'Вс',
        );

        $array = array();

        foreach ($day_map as $day_id => $dayname) {
            $sc = empty($schedule[$day_id]) ? null : $schedule[$day_id];

            if ($prev === null) {
                $prev = array(
                    'time' => $sc,
                    'day'  => $day_id,
                );

                $unique = $day_id;

                continue;
            }

            if ($prev['time'] != $sc) {
                if ($prev['day'] - $unique <= 1) {
                    $array[] = array(
                        'day_from'    => $unique,
                        'day_to'      => $prev['day'],
                        'day_from_t'  => $day_map[$unique],
                        'day_to_t'    => $day_map[$prev['day']],
                        'is_work_day' => $prev['time'] !== null,
                        'work_time'   => $prev['time'] === null ? 'Выходной' : $prev['time'],
                        'work_days'   => ($prev['day'] == $unique ? $day_map[$unique] : $day_map[$unique] .'-'. $day_map[$prev['day']]),
                    );
                } else {
                    $array[] = array(
                        'day_from'    => $unique,
                        'day_to'      => $prev['day'],
                        'day_from_t'  => $day_map[$unique],
                        'day_to_t'    => $day_map[$prev['day']],
                        'is_work_day' => $prev['time'] !== null,
                        'work_time'   => $prev['time'] === null ? 'Выходной' : $prev['time'],
                        'work_days'   => $day_map[$unique] .'-'. $day_map[$prev['day']],
                    );
                }

                $unique = $day_id;
            }

            $prev = array(
                'time' => $sc,
                'day'  => $day_id,
            );
        }

        // Same values for whole schedule week
        if ($unique == 1) {
            $array = array(
                array(
                    'day_from'    => 1,
                    'day_to'      => 7,
                    'day_from_t'  => $day_map[1],
                    'day_to_t'    => $day_map[7],
                    'is_work_day' => $sc !== null,
                    'work_time'   => $sc === null ? 'Выходной' : $sc,
                    'work_days'   => $day_map[1] .'-'. $day_map[7],
                ),
            );
        } else {
            $array[] = array(
                'day_from'    => $unique,
                'day_to'      => $prev['day'],
                'day_from_t'  => $day_map[$unique],
                'day_to_t'    => $day_map[$prev['day']],
                'is_work_day' => $prev['time'] !== null,
                'work_time'   => $prev['time'] === null ? 'Выходной' : $prev['time'],
                'work_days'   => $unique == $prev['day'] ? $day_map[$unique] : $day_map[$unique] .'-'. $day_map[$prev['day']],
            );
        }

        $string = array();

        foreach ($array as $ar) {
            $string[] = $ar['work_days'] .': '. $ar['work_time']['time'];
        }

        $string = implode("\r\n", $string);

        $this->cache_schedule_string = $string;

        return $string;
    }

    /**
     * @return bool
     */
    public function isEmptySchedule()
    {
        $res = true;

        foreach ($this->getSchedule() as $params) {
            if (!empty($params['checked'])) {
                    $res = false;
                break;
            }

            if (!empty($params['time'])) {
                    $res = false;
                break;
            }
        }

        return $res;
    }

    public function getTodaySchedule()
    {
        return $this->getScheduleDay(date('N'), 'time');
    }

    public function getStaffs()
    {
        if ($this->office_staffs === null) {
            $this->office_staffs = array();

            /** @var $staff DomainObjectModel_Staff */
            foreach ($this->Staff as $staff) {
                if ($staff->getStatus() != 'ENABLED') {
                    continue;
                }

                $this->office_staffs[] = $staff;
            }
        }

        return $this->office_staffs;
    }

    /**
     * @return array
     */
    public function getPhones()
    {
        $phones = explode(',', $this->getFieldValue('office_phone'));

        foreach ($phones as &$phone) {
            $phone = trim($phone);
        }
        unset($phone);

        return $phones;
    }

    /**
     * @param string $city
     * @return null|string
     */
    public function getGeocode($city = 'Томск')
    {
        if ($this->getAddress() === null) {
            return null;
        }

        if (strpos(mb_strtolower($this->getAddress()), mb_strtolower($city)) === false){
            return $city . ' ' . $this->getAddress();
        }

        return $this->getAddress();
    }

    /**
     * @param string $city
     * @return array|null|string
     */
    public function getGeopoint($city = 'Томск')
    {
        $cache_id = 'MAP_OFFICE_' . $this->getId();
        /** @var $cache Utils_Cacher */
        $cache = DxFactory::getInstance('Utils_Cacher', array($cache_id, 86400*30));
        $point = $cache->getCache();

        if (null === $point) {
            $geocode = $this->getGeocode($city);
            if (null === $geocode) return null;

            $options = array(
                'http' => array(
                    'method' => 'GET',
                    'timeout' => 20,
                    'header' => "Accept-language: en\r\n" .
                        "Cookie: foo=bar\r\n" .
                        "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
                )
            );
            $context = stream_context_create($options);

            sleep(rand(1,2));
            $data = @file_get_contents("http://geocode-maps.yandex.ru/1.x/?format=json&geocode={$geocode}", 0, $context);
            if (empty($data)) return null;

            $data = json_decode($data);
            if (empty($data)) return null;

            if ($data->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0) {
                $point = explode(' ', $data->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);
            }
            $cache->setCache($point);
        }

        return $point;
    }

    /**
     * @return int|null
     */
    public function getCityId()
    {
        return is_object($id = $this->getFieldValue('city_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_City $c
     */
    public function setCity(DomainObjectModel_City $c)
    {
        $this->City = $c;
    }

    /**
     * @return DomainObjectModel_City|null
     */
    public function getCity()
    {
        return is_numeric($this->getCityId()) ? $this->City : null;
    }

    /**
     * @return int|null
     */
    public function getRelatedCityId()
    {
        return is_object($id = $this->getFieldValue('related_city_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_City $c
     */
    public function setRelatedCity(DomainObjectModel_City $c)
    {
        $this->City_2 = $c;
    }

    /**
     * @return DomainObjectModel_City|null
     */
    public function getRelatedCity()
    {
        return is_numeric($this->getRelatedCityId()) ? $this->City_2 : null;
    }

    /**
     * @return int|null
     */
    public function getSubdivisionId()
    {
        return is_object($id = $this->getFieldValue('subdivision_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Subdivision $s
     */
    public function setSubdivision(DomainObjectModel_Subdivision $s)
    {
        $this->Subdivision = $s;
    }

    /**
     * @return DomainObjectModel_Subdivision|null
     */
    public function getSubdivision()
    {
        return is_numeric($this->getSubdivisionId()) ? $this->Subdivision : null;
    }

    /**
     * Return title if it is set
     * In other case, return city name
     *
     * @return string
     */
    public function getTitle($is_source = false)
    {
        $title = $this->getFieldValue('office_title');

        if ($is_source) {
            return $title;
        }

        if (empty($title)) {
            return $this->getCityName();
        }

        return $title;
    }

    /**
     * @param string $with_city Add city or not
     *
     * @return string
     */
    public function getAddress($with_city = false)
    {
        $address = $this->getFieldValue('office_address');

        if ($with_city) {
            return 'г. '. $this->getCityName() .', '. $address;
        }

        return $address;
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
        DxApp::getComponent(DxConstant_Project::ALIAS_SMARTY)->clearAllCache();
    }

    /*
     * @param string|null $key
     * @param null $default
     * @return mixed
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