<?php
/**
 * @method int getId()
 * @method string getStatus()
 * @method string getUserName()
 * @method string getUserPhone()
 * @method string getUserEmail()
 * @method string getUserIp()
 * @method string getMessage()
 * @method string getAnswer()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 *
 * @method setId(int $arg)
 * @method setStatus(string $arg)
 * @method setUserName(string $arg)
 * @method setUserPhone(string $arg)
 * @method setUserEmail(string $arg)
 * @method setUserIp(string $arg)
 * @method setMessage(string $arg)
 * @method setAnswer(string $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Feedback extends DomainObjectModel_BaseFeedback
{
    /** @var string */
    protected $field_prefix = 'feedback';

    /**
     * @var array
     *
     * @protected
     *
     * @static
     */
    static protected $complain_types = array(
        'OFFER'  => 'Предложение',
        'THANKS' => 'Благодарность',
        'ABUSE'  => 'Жалоба',
        'OTHER'  => 'Другое',
    );

    /**
     * @var array
     *
     * @protected
     *
     * @static
     */
    static protected $feedback_types = array(
        'PROPOSE'  => 'Отзыв об агентстве',
        'QUALITY'  => 'Контроль качества',
        'HOTEL'    => 'Отзыв об отеле',
    );

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'feedback_user_name') {
            if (empty($this->feedback_user_name) || !is_string($this->feedback_user_name)) {
                throw new DxException("Invalid 'feedback_user_name'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'feedback_message') {
            if (empty($this->feedback_message) || !is_string($this->feedback_message)) {
                throw new DxException("Invalid 'feedback_message'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'feedback_answer') {
            if (!empty($this->feedback_answer) && !is_string($this->feedback_answer)) {
                throw new DxException("Invalid 'feedback_answer'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'feedback_user_email') {
            if (!empty($this->feedback_user_email) && filter_var($this->feedback_user_email, FILTER_VALIDATE_EMAIL) === false) {
                throw new DxException("Invalid 'feedback_user_email'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }

        if ($field === null || $field === 'feedback_status') {
            if (empty($this->feedback_status) || !in_array($this->feedback_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'feedback_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'feedback_type') {
            if (empty($this->feedback_type) || !in_array($this->feedback_type, array_keys(self::getFeedbackTypes()))) {
                throw new DxException("Invalid 'feedback_type'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'office_id') {
            if ($this->office_id !== null && !is_numeric($this->office_id)) {
                throw new DxException("Invalid 'office_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'user_id') {
            if ($this->user_id !== null && !is_numeric($this->user_id)) {
                throw new DxException("Invalid 'user_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasColumn('feedback_extended_data', 'array');
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

        if (null === $key) {
            $_data = $data;
        } else {
            $_data = $this->getExtendedData();
            $_data[$key] = $data;
        }

        return parent::setExtendedData($_data);
    }

    /**
     * @return array
     *
     * @static
     */
    static public function getComplainTypes()
    {
        return self::$complain_types;
    }

    /**
     * @return array
     *
     * @static
     */
    static public function getFeedbackTypes()
    {
        return self::$feedback_types;
    }

    /**
     * @return int|null
     */
    public function getStaffId()
    {
        return is_object($id = $this->getFieldValue('staff_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @return DomainObjectModel_Staff|null
     */
    public function getStaff()
    {
        return is_numeric($this->getStaffId()) ? $this->Staff : null;
    }

    /**
     * @param DomainObjectModel_Staff $c
     * @return DomainObjectModel_Feedback
     */
    public function setStaff(DomainObjectModel_Staff $c)
    {
        $this->Staff = $c;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getOfficeId()
    {
        return is_object($id = $this->getFieldValue('office_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @return DomainObjectModel_Office|null
     */
    public function getOffice()
    {
        return is_numeric($this->getOfficeId()) ? $this->Office : null;
    }

    /**
     * @param DomainObjectModel_Office $m
     * @return DomainObjectModel_Feedback
     */
    public function setOffice(DomainObjectModel_Office $m)
    {
        $this->Office = $m;

        return $this;
    }

    /**
     * @return DomainObjectQuery_City|null
     */
    public function getCity()
    {
        $city_id = $this->getExtendedData('city_id');

        if (empty($city_id)) {
            return null;
        }

        /** @var $q DomainObjectQuery_City */
        $q = DxFactory::getSingleton('DomainObjectQuery_City');

        $city = $q->findById($city_id);

        if (empty($city)) {
            return null;
        }

        return $city;
    }

    public function getType($is_source = false)
    {
        $type = $this->getFieldValue('feedback_type');

        if ($is_source) {
            return $type;
        }

        $types = self::getFeedbackTypes();

        if (isset($types[$type])) {
            return $types[$type];
        }

        return null;
    }

    public function preInsert($event)
    {
        parent::preInsert($event);

        return $this->genericPreEvent($event);
    }

    public function preUpdate($event)
    {
        parent::preUpdate($event);

        return $this->genericPreEvent($event);
    }

    public function preDelete($event)
    {
        parent::preDelete($event);

        return $this->genericPreEvent($event);
    }

    protected function genericPreEvent($event)
    {
        /**
         * @var DomainObjectModel_Feedback $invoker
         */
        $invoker = $event->getInvoker();

        if ($invoker->getType(true) !== 'HOTEL') {
            return $event;
        }

        $this->recalculateHotelRatings($invoker);

        return $event;
    }

    public function recalculateHotelRatings($new_feedback)
    {
        /**
         * @var DomainObjectModel_Hotel $hotel
         */
        $hotel = $new_feedback->getHotel();

        if (!$hotel) {
            return null;
        }

        $feedbacks = $hotel->getFeedbacks(true);

        $feedbacks[] = $new_feedback->toArray();

        $ratings = array(
            'rating_territory' => array(
                'scores' => array(),
                'total'  => 0,
            ),
            'rating_service' => array(
                'scores' => array(),
                'total'  => 0,
            ),
            'rating_beach' => array(
                'scores' => array(),
                'total'  => 0,
            ),
            'rating_room' => array(
                'scores' => array(),
                'total'  => 0,
            ),
            'rating_food' => array(
                'scores' => array(),
                'total'  => 0,
            ),
            'rating_anim' => array(
                'scores' => array(),
                'total'  => 0,
            ),
        );

        $map = array_keys($ratings);

        foreach ($feedbacks as $feedback_id => $feedback) {
            // Only ENABLED feedbacks should be included into calculation
            if ($feedback['feedback_status'] == 'DISABLED') {
                continue;
            }

            foreach ($map as $key_res) {
                $ratings[$key_res]['scores'][$feedback_id] = $feedback['feedback_extended_data'][$key_res];

                $ratings[$key_res]['total'] += $feedback['feedback_extended_data'][$key_res];
            }
        }

        $total = 0;

        $total_parts = array();

        foreach ($ratings as $type => $score_arr) {
            if (count($score_arr['scores']) === 0) {
                continue;
            }

            $total_parts[$type] = $score_arr['total'] / count($score_arr['scores']);

            $total += $total_parts[$type];

            $hotel->setExtendedData($total_parts[$type], $type);
        }

        if (count($total_parts) === 0) {
            return;
        }

        $total = $total / count($total_parts);

        $hotel->setExtendedData($total, 'total_rating');

        $hotel->save();
    }

    /**
     * @return DomainObjectModel_Hotel|null
     */
    public function getHotel()
    {
        if ($this->getType(true) !== 'HOTEL') {
            return null;
        }

        $hotel = $this->Hotel;

        if (empty($hotel) || $hotel->getStatus() == 'DISABLED') {
            return null;
        }

        return $hotel;
    }

    public function getTotalHotelRating()
    {
        if ($this->getType(true) !== 'HOTEL') {
            return null;
        }

        $ratings = 0;

        $map = array(
            'rating_food',
            'rating_anim',
            'rating_territory',
            'rating_service',
            'rating_beach',
            'rating_room',
        );

        foreach ($map as $type) {
            $ratings += $this->getExtendedData($type);
        }

        return $ratings / count($map);
    }

    public function getRecommendedFor($separate = ', ')
    {
        $recommended = array();

        $keys = array(
            'recommend_family'          => 'семьи',
            'recommend_young'           => 'молодежи',
            'recommend_family_children' => 'семьи с детьми',
            'recommend_old'             => 'пенсионерам',
            'recommend_dont_ask'        => 'воздержусь',
            'recommend_no_opinion'      => 'не рекомендую',
        );

        foreach ($keys as $key => $key_name) {
            if ($this->getExtendedData($key)) {
                $recommended[] = $key_name;
            }
        }

        return implode($separate, $recommended);
    }

    /**
     * @return int|null
     */
    public function getUserId()
    {
        return is_object($id = $this->getFieldValue('user_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_User $c
     */
    public function setUser(DomainObjectModel_User $c)
    {
        $this->User = $c;
    }

    /**
     * @return DomainObjectModel_User|null
     */
    public function getUser()
    {
        return is_numeric($this->getUserId()) ? $this->User : null;
    }
}