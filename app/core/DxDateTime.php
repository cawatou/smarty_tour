<?php

class DxDateTime extends DateTime
{
    /**
     * @param null $dateTime
     * @param DateTimeZone|null $tz
     */
    public function __construct($dateTime = null, DateTimeZone $tz = null)
    {
        parent::__construct(is_null($dateTime) ? 'now' : $dateTime, is_null($tz) ? new DateTimeZone('UTC') : $tz);
    }

    /**
     * @static
     * @param int $timestamp
     * @return QDateTime
     */
    public static function createFromUnixTimestamp($timestamp)
    {
        return new DxDateTime(date('Y-m-d H:i:s', $timestamp), self::getDefaultTimeZone());
    }

    /**
     * @static
     * @return DateTimeZone
     */
    public static function getDefaultTimeZone()
    {
        return new DateTimeZone(date_default_timezone_get());
    }

    /**
     * @return DxDateTime
     */
    public function setDefaultTimeZone()
    {
        $this->setTimeZone(self::getDefaultTimeZone());
        return $this;
    }

    /**
     * @param DxDateTime $dt
     * @return int
     */
    public function difference(DxDateTime $dt)
    {
        return $this->getUnixTimeStamp() - $dt->getUnixTimeStamp();
    }

    /**
     * @return int
     */
    public function getUnixTimeStamp()
    {
        return $this->format('U');
    }

    /**
     * @return DxDateTime
     */
    public function toUTC()
    {
        $this->setTimeZone(new DateTimeZone('UTC'));
        return $this;
    }

    /**
     * @return string
     */
    public function getMySQLDateTime()
    {
        return $this->format('Y-m-d H:i:s');
    }

    /**
     * @param $format
     * @param string $locale
     * @return mixed
     */
    public function localeFormat($format, $locale = 'RU')
    {
        $res = $this->format($format);
        $english = array(
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', // l
            'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' , // D
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', // F
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', // M
        );
        $map['RU'] = array(
            'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье',
            'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс',
            'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь',
            'Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек',
        );
        $map['RU2'] = array(
            'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье',
            'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс',
            'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря',
            'Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек',
        );

        if (array_key_exists($locale, $map)) {
            return str_replace($english, $map[$locale], $res);
        }
        return $res;
    }
}