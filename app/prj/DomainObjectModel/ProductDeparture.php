<?php
/**
 * @method int getId()
 * @method string getHotels()
 * @method int getDaynum()
 * @method DomainObjectModel_Product[] getProducts()
 *
 * @method setId(int $arg)
 * @method setHotels(string $arg)
 * @method setDaynum(int $arg)
 */
class DomainObjectModel_ProductDeparture extends DomainObjectModel_BaseProductDeparture
{
    /** @var string */
    protected $field_prefix = 'product_departure';

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field == 'product_departure_date') {
            if (!is_a($this->product_departure_date, 'DxDateTime')) {
                throw new DxException("Invalid 'product_departure_date'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'product_departure_date_back') {
            if ($this->product_departure_date_back !== null && !is_a($this->product_departure_date_back, 'DxDateTime')) {
                throw new DxException("Invalid 'product_departure_date_back'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'product_departure_hotels') {
            if ($this->product_departure_hotels !== null && !is_array($this->product_departure_hotels)) {
                throw new DxException("Invalid 'product_departure_hotels'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'product_departure_daynum') {
            if (empty($this->product_departure_daynum)) {
                throw new DxException("Invalid 'product_departure_daynum'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'product_departure_operator') {
            if (!empty($this->product_departure_operator) && !is_string($this->product_departure_operator)) {
                throw new DxException("Invalid 'product_departure_operator'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field === 'product_id') {
            if (!is_numeric($this->product_id)) {
                throw new DxException("Invalid 'product_id'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return null
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasAccessor('product_departure_date',      'getDate');
        $this->hasAccessor('product_departure_date_back', 'getDateBack');

        $this->hasColumn('product_departure_hotels', 'array');
    }

    /**
     * @return int|null
     */
    public function getProductId()
    {
        return is_object($id = $this->getFieldValue('product_id')) ? 0 : (is_numeric($id) ? $id : null);
    }

    /**
     * @param DomainObjectModel_Product $p
     */
    public function setProduct(DomainObjectModel_Product $p)
    {
        $this->Product = $p;
    }

    /**
     * @return DomainObjectModel_Product|null
     */
    public function getProduct()
    {
        return is_numeric($this->getProductId()) ? $this->Product : null;
    }

    /**
     * @return DxDateTime
     */
    public function getDate()
    {
        return new DxDateTime($this->getFieldValue('product_departure_date', true));
    }

    /**
     * @param DxDateTime $product_departure_date
     * @return void
     */
    public function setDate(DxDateTime $product_departure_date)
    {
        $this->setFieldValue('product_departure_date', $product_departure_date->toUTC()->getMySQLDateTime());
    }

    /**
     * @return null|DxDateTime
     */
    public function getDateBack()
    {
        if (!$this->getFieldValue('product_departure_date_back', true)) {
            return null;
        }

        return new DxDateTime($this->getFieldValue('product_departure_date_back', true));
    }

    /**
     * @param DxDateTime|null $product_departure_date_back
     * @return null
     */
    public function setDateBack(DxDateTime $product_departure_date_back = null)
    {
        if (!$product_departure_date_back) {
            $this->setFieldValue('product_departure_date_back', null);
        } else {
            $this->setFieldValue('product_departure_date_back', $product_departure_date_back->toUTC()->getMySQLDateTime());
        }
    }

    /**
     * @return array
     */
    public function getModelsHotels()
    {
        $hotels = $this->getHotels();

        if (empty($hotels)) {
            return array();
        }

        $ids = array();

        foreach ($hotels as $hotel) {
            if (!empty($hotel['id'])) {
                $ids[] = $hotel['id'];
            }
        }

        $ids = array_unique($ids);

        if (empty($ids)) {
            return array();
        }

        /** @var $q DomainObjectQuery_Hotel */
        $q = DxFactory::getSingleton('DomainObjectQuery_Hotel');
        $hotels = $q->findByIds($ids);

        $result = array();

        foreach ($hotels as $hotel) {
            $result[$hotel->getId()] = $hotel;
        }

        return $result;
    }

    public function getSalePrice($price, $discount = null)
    {
        $price = str_replace(array(',', ' '), array('.', ''), $price);

        $price = abs((float)$price);

        if ($price < 0.1) {
            return 0;
        }

        if ($discount === null) {
            return $price;
        }

        $discount = abs((float)$discount);

        if ($discount < 0.1) {
            return $price / 2;
        }

        return ($price - ($price * $discount) / 100) / 2;
    }

    public function getOrderedHotels(DomainObjectModel_Product $product, array $discounts, $default_discount, $default_promo)
    {
        $hotels = $this->getHotels();

        if (!$product->getAttributes('is_sort_hotels')) {
            return $hotels;
        }

        $hotel_prices = array();

        foreach ($hotels as $k => $hotel) {
            $hotel_price = $hotel['sale_price'];

            if (!$product->getIsDiscountApplied()) {
                continue;
            }

            $isAnyFitting = false;
            $type = 'DISCOUNT';

            if ($product->getIsHighlight()) {
                $type = 'PROMO';
            } elseif (!empty($hotel['is_promoprice'])) {
                $type = 'PROMO';
            }

            $usableDiscount = null;

            if (!empty($discounts[$type])) {
                foreach ($discounts[$type] as $discount) {
                    $isFitting = $product->isDiscountFitting($discount, $hotel_price, $this->getProduct());

                    if (!$isFitting) {
                        continue;
                    }

                    $isAnyFitting = true;

                    $usableDiscount = $discount;

                    break;
                }
            }

            if (!$isAnyFitting) {
                if ($type == 'DISCOUNT') {
                    if ($default_discount !== null && $default_discount->getPercent() > 0) {
                        $usableDiscount = $default_discount;
                    }
                } else {
                    if ($default_promo !== null && $default_promo->getPercent() > 0) {
                        $usableDiscount = $default_promo;
                    }
                }
            }

            $hotel_price = $product->calculatePriceWithDiscount($hotel_price, $usableDiscount);

            $hotels[$k]['sale_price'] = $hotels[$k]['price'] = $hotel_price;
        }

        $hotel_prices = $hotels;

        if (!empty($hotel_prices)) {
            $hotel_first = array_shift($hotel_prices);
        }

        if (!empty($hotel_prices)) {
            $hotel_second = array_shift($hotel_prices);
        }

        if (!empty($hotel_prices)) {
            uasort($hotel_prices, array($this, 'sortableHotelPrice'));
        }

        if (!empty($hotel_second)) {
            array_unshift($hotel_prices, $hotel_second);
        }

        if (!empty($hotel_first)) {
            array_unshift($hotel_prices, $hotel_first);
        }

        return $hotel_prices;
    }

    public function sortableHotelPrice($a, $b)
    {
        if ($a['price'] == $b['price']) {
            return 0;
        }

        return ($a['price'] < $b['price']) ? -1 : 1;
    }
}