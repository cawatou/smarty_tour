<?php
dxFactory::import('Form_Backend');

class Form_Backend_Product extends Form_Backend
{
    /** @var DomainObjectModel_Product */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Product|null $form_model
     */
    public function setModel(DomainObjectModel_Product $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @param bool $new Return new model or not
     * @return DomainObjectModel_Product|null
     */
    public function getModel($new = false)
    {
        if ($new) {
            $this->form_model = DxFactory::getInstance('DomainObjectModel_Product');
        }

        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Product|null
     */
    public function m()
    {
        return $this->getModel();
    }

    /**
     * @return bool
     */
    protected function process()
    {
        $data   = $this->getEnvData('_POST');
        $errors = array();

        $m = $this->getModel($this->getId() == 'product_copy');

        if ($m === null) {
            return false;
        }

        $is_applied_discount_was = $m->getIsDiscountApplied();

        $map = array(
            'product_title' => array(
                'method' => 'setTitle',
                'value'  => empty($data['product_title']) ? null : $data['product_title'],
            ),
            'product_alias' => array(
                'method' => 'setAlias',
                'value'  => !empty($data['product_alias']) ? trim(mb_strtolower($data['product_alias'])) : null,
            ),
            'product_price' => array(
                'method' => 'setPrice',
                'value'  => empty($data['product_price']) || $data['product_price'] <= 0 ? null : str_replace(',', '.', $data['product_price']),
            ),
            'product_discount_price' => array(
                'method' => 'setDiscountPrice',
                'value'  => empty($data['product_discount_price']) ? null : str_replace(',', '.', $data['product_discount_price']),
            ),
            'product_operator' => array(
                'method' => 'setOperator',
                'value'  => null,
            ),
            'touroperator_id' => array(
                'method' => 'setTouroperatorId',
                'value'  => empty($data['touroperator_id']) ? null : $data['touroperator_id'],
            ),
            'product_brief' => array(
                'method' => 'setBrief',
                'value'  => !empty($data['product_brief']) ? $data['product_brief'] : null,
            ),
            'product_content' => array(
                'method' => 'setContent',
                'value'  => empty($data['product_content']) ? null : $data['product_content'],
            ),
            'product_notes' => array(
                'method' => 'setNotes',
                'value'  => empty($data['product_notes']) ? null : $data['product_notes'],
            ),
            'product_status' => array(
                'method' => 'setStatus',
                'value'  => empty($data['product_status']) || $data['product_status'] == 'DISABLED' ? 'DISABLED' : 'ENABLED',
            ),
            'product_is_highlight' => array(
                'method' => 'setIsHighlight',
                'value'  => empty($data['product_is_highlight']) ? 0 : 1,
            ),
            'product_is_discount_applied' => array(
                'method' => 'setIsDiscountApplied',
                'value'  => empty($data['product_is_discount_applied']) ? 0 : 1,
            ),
            'product_from_id' => array(
                'method' => 'setFromId',
                'value'  => empty($data['product_from_id']) ? null : $data['product_from_id'],
            ),
            'country_id' => array(
                'method' => 'setCountryId',
                'value'  => empty($data['country_id']) ? null : $data['country_id'],
            ),
            'resort_id' => array(
                'method' => 'setResortId',
                'value'  => empty($data['resort_id']) ? null : $data['resort_id'],
            ),
            'resort_name' => array(
                'method' => 'setResortName',
                'value'  => empty($data['resort_name']) ? null : $data['resort_name'],
            ),
            'product_linked_id' => array(
                'method' => 'setLinkedId',
                'value'  => empty($data['product_linked_id']) ? null : $data['product_linked_id'],
            ),
        );

        foreach ($map as $key => $val) {
            try {
                DxFactory::invoke($m, $val['method'], array($val['value']));
            } catch (DxException $e) {
                if ($e->getCode() == DomainObjectModel::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT) {
                    $errors[$key] = 'INVALID_FORMAT';
                } else {
                    $errors[$key] = 'NOT_VALID';
                }
            }
        }

        /** @var DomainObjectQuery_Product $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_Product');
        if ($m->getLinkedId() !== null) {
            $_m = $q->findById($m->getLinkedId());
            if ($_m === null) {
                $errors['product_linked_id'] = 'NOT_EXIST_ID';
            } elseif ($_m->getId() == $this->getId()) {
                $errors['product_linked_id'] = 'EQUIAL_ID';
            } elseif ($_m->getLinkedId() !== null) {
                $errors['product_linked_id'] = 'CAN_NOT';
            } elseif (count($_m->getDepartures()) == 2) {
                $errors['product_linked_id'] = 'TWO_DEPARTURES';
            }
        }

        if (!$m->getIsDiscountApplied()) {
            $m->setIsHighlight(0);
        }

        $attributes = array();

        foreach ($data['product_attributes']['fields'] as $k => $v) {
            $field = $v;
            $value = $data['product_attributes']['values'][$k];

            if (!empty($field) && !empty($value)) {
                $attributes[$field] = $value;
            }
        }

        $attributes['is_sort_hotels'] = !empty($data['product_attributes_is_sort_hotels']) ? true : false;

        $m->setAttributes(empty($attributes) ? null : $attributes);

        $get_via = array();

        if ($m->getCountryId() == DomainObjectQuery_Product::COUNTRY_ID_RUSSIA) {
            foreach ($data['product_get_via'] as $k => $v) {
                $value = $data['product_get_via'][$k];

                if (empty($value['title']) || empty($value['price'])) {
                    continue;
                }

                $get_via[] = $value;
            }
        }

        $m->setGetVia(empty($get_via) ? null : $get_via);

        $payable_includes = array();

        foreach ($data['product_payable_includes'] as $payable) {
            $payable = trim($payable);

            if (empty($payable)) {
                continue;
            }

            $payable_includes[] = $payable;
        }

        $payable_includes_implode = implode("\r\n", $payable_includes);
        $payable_includes = explode("\r\n", mb_strtoupper(mb_substr($payable_includes_implode, 0, 1, 'utf-8'), 'utf-8') . mb_substr($payable_includes_implode, 1, mb_strlen($payable_includes_implode, 'utf-8'), 'utf-8'));

        $payable_includes = array_filter($payable_includes);

        $m->setPayableIncludes(empty($payable_includes) ? null : $payable_includes);

        $payable_excludes = array();

        $src_data = empty($data['source_data']) ? array() : unserialize($data['source_data']);

        $existing_hotel_data = array();

        $src_data_hotel = current($src_data);

        if (empty($src_data_hotel['product_departure_hotels'])) {
            $src_data_hotel['product_departure_hotels'] = array();
        }

        foreach ($src_data_hotel['product_departure_hotels'] as $k => $v) {
            if (empty($v['added_at'])) {
                unset($src_data[$k]);

                continue;
            }

            $existing_hotel_data[$v['name']] = $v['added_at'];
        }

        foreach ($data['product_payable_excludes'] as $payable) {
            $payable = trim($payable);

            if (empty($payable)) {
                continue;
            }

            $payable_excludes[] = $payable;
        }

        $payable_excludes_implode = implode("\r\n", $payable_excludes);
        $payable_excludes = explode("\r\n", mb_strtoupper(mb_substr($payable_excludes_implode, 0, 1, 'utf-8'), 'utf-8') . mb_substr($payable_excludes_implode, 1, mb_strlen($payable_excludes_implode, 'utf-8'), 'utf-8'));

        $payable_excludes = array_filter($payable_excludes);

        $m->setPayableExcludes(empty($payable_excludes) ? null : $payable_excludes);

        $iteration               = 1;
        $min_price               = PHP_INT_MAX;
        $min_price_discount_type = 'DISCOUNT';
        $is_departure_changed    = false;
        $is_not_promoprice       = false;

        foreach ($m->getDepartures() as $dep) {
            $dep->remove();
        }

        $resorted_hotels = array();

        $result_hotels = array();

        foreach ($data['product_departure'] as $k_pd => $_product_departure) {
            // @todo!
            if (empty($_product_departure['product_departure_date'])) {
                continue;
            }

            /** @var DomainObjectModel_ProductDeparture $pd */
            $pd = DxFactory::getInstance('DomainObjectModel_ProductDeparture');

            /** @var DomainObjectQuery_Hotel $q_h */
            $q_h = DxFactory::getInstance('DomainObjectQuery_Hotel');

            DxFactory::import('DomainObjectModel_Hotel');

            foreach ($data['_product_departure_hotels'] as $k_hd => $hotel_data) {
                if (empty($hotel_data['name']) || !trim($hotel_data['name'])) {
                    continue;
                }

                if (empty($hotel_data['id']) && $m->getCountryId() !== null && $m->getResortId() !== null) {
                    $existing_hotel = $q_h->findBySignature(DomainObjectModel_Hotel::generateSignature($hotel_data['name']), $m->getCountryId(), $m->getResortId(), true);

                    if (!empty($existing_hotel)) {
                        $existing_hotel = current($existing_hotel);

                        $data['_product_departure_hotels'][$k_hd]['id'] = $existing_hotel['hotel_id'];
                    }
                }

                $hotel_data['name'] = trim($hotel_data['name']);

                if (!empty($existing_hotel_data[$hotel_data['name']])) {
                    $hotel_data['added_at'] = $existing_hotel_data[$hotel_data['name']];
                }

                $dep_sale_price = $pd->getSalePrice($hotel_data['price'][$k_pd]);

                if (empty($hotel_data['is_promoprice'][$k_pd]) || !$m->getIsDiscountApplied()) {
                    $is_not_promoprice = true;
                }

                $current_it_hotel = $data['product_departure'][$k_pd]['product_departure_hotels'][] = $data['product_departure_hotels'][] = array(
                    'id'              => empty($hotel_data['id']) ? null : (int)$hotel_data['id'],
                    'name'            => $hotel_data['name'],
                    'signature'       => uniqid(DomainObjectModel_Hotel::generateSignature($hotel_data['name'])),
                    'is_recommended'  => (empty($hotel_data['is_recommended']) ? 0 : 1),
                    'stars'           => (empty($hotel_data['stars']) ? null : $hotel_data['stars']),
                    'nutrition_type'  => (empty($hotel_data['nutrition_type']) ? null : $hotel_data['nutrition_type']),
                    'price'           => (float)str_replace(array(',', ' '), array('.', ''), $hotel_data['price'][$k_pd]),
                    'is_promoprice'   => (empty($hotel_data['is_promoprice'][$k_pd]) || !$m->getIsDiscountApplied()) ? 0 : 1,
                    'url'             => empty($hotel_data['url']) ? null : $hotel_data['url'],
                    'sale_price'      => $dep_sale_price,
                    'added_at'        => empty($hotel_data['added_at']) ? new DxDateTime : (is_a($hotel_data['added_at'], 'DxDateTime') ? $hotel_data['added_at'] : new DxDateTime($hotel_data['added_at'])),
                    'is_discountable' => true,
                );

                if (empty($result_hotels[$current_it_hotel['signature']])) {
                    $result_hotels[$current_it_hotel['signature']] = $current_it_hotel;

                    $result_hotels[$current_it_hotel['signature']]['price'] = $hotel_data['price'];
                    $result_hotels[$current_it_hotel['signature']]['is_promoprice'] = array();

                    foreach ($data['product_departure'] as $k_pd2 => $_product_departure2) {
                        $result_hotels[$current_it_hotel['signature']]['is_promoprice'][$k_pd2] = (empty($hotel_data['is_promoprice'][$k_pd2]) || !$m->getIsDiscountApplied()) ? 0 : 1;
                    }
                }
            }

            unset($data['product_departure'][$k_pd]['_product_departure_hotels']);

            if (empty($resorted_hotels)) {
                $resorted_hotels = $result_hotels;

                $hotel_first = $hotel_second = null;

                if (!empty($resorted_hotels)) {
                    $hotel_first = array_shift($resorted_hotels);
                }

                if (!empty($resorted_hotels)) {
                    $hotel_second = array_shift($resorted_hotels);
                }

                if ($attributes['is_sort_hotels'] && !empty($resorted_hotels)) {
                    uasort($resorted_hotels, array($this, 'sortableHotelPrice'));
                }

                if (!empty($hotel_second)) {
                    array_unshift($resorted_hotels, $hotel_second);
                }

                if (!empty($hotel_first)) {
                    array_unshift($resorted_hotels, $hotel_first);
                }
            }

            $_product_departure['product_departure_date'] = trim($_product_departure['product_departure_date'], '. ');

            if (strlen($_product_departure['product_departure_date']) == 5) {
                $_product_departure['product_departure_date'] .= '.'. date('Y');
            }

            $departure_date = new DxDateTime;

            if (!empty($_product_departure['product_departure_date'])) {
                try {
                    $departure_date = new DxDateTime($_product_departure['product_departure_date']);
                } catch (Exception $e) {
                    $errors['product_departure_'. $iteration .'_product_departure_date'] = 'INVALID_FORMAT';
                }
            }

            $departure_date_back = new DxDateTime;

            if (!empty($_product_departure['product_departure_date_back'])) {
                try {
                    $departure_date_back = new DxDateTime($_product_departure['product_departure_date_back']);
                } catch (Exception $e) {
                    $errors['product_departure_'. $iteration .'_product_departure_date_back'] = 'INVALID_FORMAT';
                }
            }

            $pd_map = array(
                'product_departure_is_datetime' => array(
                    'method' => 'setIsDatetime',
                    'value'  => empty($_product_departure['product_departure_is_datetime']) ? 0 : 1,
                ),
                'product_departure_date' => array(
                    'method' => 'setDate',
                    'value'  => $departure_date,
                ),
                'product_departure_date_back' => array(
                    'method' => 'setDateBack',
                    'value'  => $departure_date_back,
                ),
                'product_departure_daynum' => array(
                    'method' => 'setDaynum',
                    'value'  => (int)$_product_departure['product_departure_daynum'],
                ),
                'product_departure_nightnum' => array(
                    'method' => 'setNightnum',
                    'value'  => empty($_product_departure['product_departure_nightnum']) ? null : $_product_departure['product_departure_nightnum'],
                ),
                'product_departure_seats' => array(
                    'method' => 'setSeats',
                    'value'  => empty($_product_departure['product_departure_seats']) ? null : $_product_departure['product_departure_seats'],
                ),
                'product_departure_hotels' => array(
                    'method' => 'setHotels',
                    'value'  => empty($data['product_departure'][$k_pd]['product_departure_hotels']) ? null : $data['product_departure'][$k_pd]['product_departure_hotels'],
                ),
            );

            $_res_hot = $resorted_hotels;

            foreach ($resorted_hotels as $k => $h) {
                $_res_hot[$k]['price']         = (float)str_replace(array(',', ' '), array('.', ''), $h['price'][$k_pd]);
                $_res_hot[$k]['sale_price']    = $pd->getSalePrice($h['price'][$k_pd]);
                $_res_hot[$k]['is_promoprice'] = $h['is_promoprice'][$k_pd];
            }

            $pd_map['product_departure_hotels']['value'] = $_res_hot;

            foreach ($pd_map as $key => $pd_arr) {
                try {
                    $pd->$pd_arr['method']($pd_arr['value']);
                } catch (DxException $e) {
                    if ($e->getCode() == DomainObjectModel::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT) {
                        $errors['product_departure_'. $iteration .'_'. $key] = 'INVALID_FORMAT';
                    } else {
                        $errors['product_departure_'. $iteration .'_'. $key] = 'NOT_VALID';
                    }
                }
            }

            $iteration++;

            try {
                $m->setDeparture($pd);
            } catch (DxException $e) {
                $errors[$k_pd]['product_departure'] = 'NOT_VALID';

                break;
            }
        }

        if ($is_not_promoprice) {
            $m->setIsHighlight(0);
        }

        foreach ($src_data as $d) {
            $is_found = false;

            foreach ($m->getDepartures() as $_d) {
                if (
                    $d['product_departure_date']->format('Y-m-d H:i:s') == $_d->getDate()->format('Y-m-d H:i:s')
                        &&
                    $d['product_departure_daynum'] == $_d->getDaynum()
                ) {
                    $is_found = true;
                }
            }

            if (!$is_found) {
                $is_departure_changed = true;
            }
        }

        if (empty($errors)) {
            if (!$m->isUniqueAlias()) {
                $errors['product_alias'] = 'ALREADY_EXISTS';
            }

            /*
            if ($m->getPrice() <= $m->getDiscountPrice()) {
                $errors['product_price'] = 'INCORRECT';
            }*/
        }

        if (!empty($data['product_image'])) {
            $images = array_unique(explode(';', $data['product_image']));

            foreach ($images as $k => $i) {
                if (empty($i)) {
                    unset($images[$k]);

                    continue;
                }

                try {
                    DxFactory::invoke('DxFile_Image', 'createByPath', array(ROOT . $i));
                } catch (DxException $e) {
                    $img_errors = array(
                        DxFile_Image::ERROR_IMAGE_NOT_FOUND   => 'IMAGE_NOT_FOUND',
                        DxFile_Image::ERROR_IMAGE_LOAD        => 'IMAGE_NOT_LOAD',
                        DxFile_Image::ERROR_IMAGE_UNSUPPORTED => 'IMAGE_UNSUPPORTED',
                    );

                    $errors['product_image'] = array_key_exists($e->getCode(), $img_errors) ? $img_errors[$e->getCode()] : 'NOT_VALID';

                    break;
                }
            }

            if (!empty($images) && empty($errors['product_image'])) {
                $is_cover = count($m->getImages()) ? 0 : 1;

                foreach ($images as $i) {
                    try {
                        /** @var DomainObjectModel_ProductImage $pi */
                        $pi = DxFactory::getInstance('DomainObjectModel_ProductImage');
                        $pi->setIsCover($is_cover);
                        $pi->setProduct($m);
                        $pi->setPath($i);

                        $is_cover = 0;
                    } catch (DxException $e) {
                        $errors['product_image'] = 'NOT_VALID';
                    }
                }
            }
        }

        if (empty($data['product_cover'])) {
            if ($m->getCountry() !== null && $m->getCountryCover() !== null) {
                $data['product_cover'] = $m->getCountryCover();
            }
        }

        if (!empty($data['product_cover'])) {
            $cover = $data['product_cover'];

            try {
                DxFactory::invoke('DxFile_Image', 'createByPath', array(ROOT . $cover));
            } catch (DxException $e) {
                $img_errors = array(
                    DxFile_Image::ERROR_IMAGE_NOT_FOUND   => 'IMAGE_NOT_FOUND',
                    DxFile_Image::ERROR_IMAGE_LOAD        => 'IMAGE_NOT_LOAD',
                    DxFile_Image::ERROR_IMAGE_UNSUPPORTED => 'IMAGE_UNSUPPORTED',
                );

                $errors['product_cover'] = array_key_exists($e->getCode(), $img_errors) ? $img_errors[$e->getCode()] : 'NOT_VALID';
            }

            if (empty($errors['product_cover'])) {
                $m->setCover($cover);
            }
        }

        $current_user = $this->getContext()->getCurrentUser();

        if ($this->getId() == 'product_add' || $this->getId() == 'product_copy') {
            $qnt = DxFactory::getSingleton('DomainObjectQuery_Product')->getMaxQnt();
            $m->setQnt($qnt + 3);
        }

        if (empty($errors)) {
            if ($this->getModel()->isHotelsRotten()) {
                $errors['special_hotel_rotten'] = $this->getModel()->getHotelExpirationLimit();
            }
        }

        $m->setUserId($current_user->getId());
        $m->setUpdated();

        if (!empty($errors['product_price'])) {
            unset($errors['product_price']);
        }

        if (empty($errors)) {
            $discounted_price = $min_price;

            if ($m->getIsDiscountApplied()) {
                /** @var DomainObjectQuery_Discount $q_d */
                $q_d = DxFactory::getInstance('DomainObjectQuery_Discount');

                $default_discount = $q_d->findDefault('DISCOUNT');
                $default_promo    = $q_d->findDefault('PROMO');
                $discounts        = $m->getFittingDiscounts();

                $min_price = null;

                foreach ($m->getDepartures() as $dep) {
                    $orderedHotels = $dep->getOrderedHotels($m, $discounts, $default_discount, $default_promo);

                    foreach ($orderedHotels as $h) {
                        if ($h['sale_price'] > 0) {
                            if ($min_price !== null && $min_price > $h['sale_price']) {
                                $min_price = $h['sale_price'];
                            } elseif ($min_price === null) {
                                $min_price = $h['sale_price'];
                            }
                        }
                    }
                }
            } else {
                foreach ($result_hotels as $hotel) {
                    foreach ($hotel['price'] as $p_pd => $p) {
                        if ($p <= 0) {
                            continue;
                        }

                        if ($p > 0 && $min_price > $p) {
                            $min_price = $p;

                            $min_price_discount_type = empty($hotel[$p_pd]['is_promoprice']) ? 'DISCOUNT' : 'PROMO';
                        }
                    }
                }
            }

            try {
                $m->setDiscountPrice($min_price == PHP_INT_MAX ? null : $min_price);
            } catch (DxException $e) {
                $errors['product_discount_price'] = 'NOT_VALID';
            }

            if ($is_departure_changed || $is_applied_discount_was != $m->getIsDiscountApplied()) {
                try {
                    $m->setPrice($min_price == PHP_INT_MAX ? null : $min_price);
                } catch (DxException $e) {
                    $errors['product_price'] = 'NOT_VALID';
                }
            }

            if ($min_price == PHP_INT_MAX) {
                $errors['special_hotel_empty'] = 'EXACT';
            }
        }

        if (empty($errors)) {
            if ($this->getId() == 'product_add' || $m->getPrice() === null) {
                try {
                    $m->setPrice($min_price);
                } catch (DxException $e) {
                    $errors['product_price'] = 'NOT_VALID';
                }
            }
        }

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        if ($m->getCountry() !== null) {
            $m->setCountryName($m->getCountry()->getTitle());
        }

        if ($m->getResort() !== null) {
            $m->setResortName($m->getResort()->getTitle());
        }

        if ($m->getPrice() == $m->getDiscountPrice()) {
            //$m->setDiscountPrice(null);
        }

        $this->getDomainObjectManager()->flush();

        $this->countLinkedPrice($m);

        return true;
    }

    public function sortableHotelPrice($a, $b)
    {
        if (current($a['price']) == current($b['price'])) {
            return 0;
        }

        return (current($a['price']) < current($b['price'])) ? -1 : 1;
    }

    /**
     * @param $m
     * @return bool
     */
    public function countLinkedPrice($m)
    {
        $linked_id = $m->getLinkedId();
        $linked_products = $m->getLinkedProducts();

        /** @var DomainObjectQuery_Product $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_Product');

        if ($linked_id !== null) {
            $parent = $q->findById($linked_id);
            $res = $this->getLinkedPrice($parent);

            $parent->setLinkedPrice($res['price']);
            $parent->setLinkedDiscountPrice($res['discount']);

            $parent->save();

            return true;
        }

        if (count($linked_products)) {
            $res = $this->getLinkedPrice($m);
            $m->setLinkedPrice($res['price']);
            $m->setLinkedDiscountPrice($res['discount']);
            $m->save();

            return true;
        }

        $m->setLinkedPrice(null);
        $m->setLinkedDiscountPrice(null);
        $m->save();

        return true;
    }

    /**
     * @param $m
     * @return array
     */
    public function getLinkedPrice($m)
    {
        $min_price = $m->getDiscountPrice();
        $res = array(
            'price' => $m->getPrice(),
            'discount' => $m->getDiscountPrice(),
        );

        foreach ($m->getLinkedProducts() as $p) {
            if ($p->getDiscountPrice() < $min_price) {
                $min_price = $p->getDiscountPrice();
                $res = array(
                    'price' => $p->getPrice(),
                    'discount' => $p->getDiscountPrice(),
                );
            }
        }

        return $res;
    }

    /**
     * @return string
     */
    public function draw()
    {
        if (!$this->isSubmited() && $this->getId() === 'product_add') {
            $this->m()->setPayableIncludes(
                array(
                    'Авиаперелет',
                    'Трансфер аэропорт-курорт-аэропорт',
                    'Проживание в отеле указанной категории с указанным питанием',
                    'Страхование ответственности туроператора',
                    'Питание',
                    'Трансфер',
                    'Медицинская страховка',
                    'Услуги гида',
                )
            );

            $this->m()->setPayableExcludes(
                array(
                    'Оформление визы',
                    'Топливный сбор',
                )
            );
        }

        $this->setFormData($this->getEnvData('_POST'));

        /** @var DomainObjectQuery_Touroperator $q_to */
        $q_to = DxFactory::getSingleton('DomainObjectQuery_Touroperator');

        /** @var DomainObjectQuery_Country $q_cry */
        $q_cry = DxFactory::getSingleton('DomainObjectQuery_Country');

        $resorts = array();

        $from_all_list = $this->getContext()->getCurrentUser()->getUser()->getFromAll();

        $model = $this->getModel();

        if ($model && $model->getCountryId() > 0) {
            /** @var DomainObjectQuery_Resort $q_res */
            $q_res = DxFactory::getSingleton('DomainObjectQuery_Resort');

            $resorts = $q_res->getByCountryId($model->getCountryId(), true);
        }

        DxFactory::import('DxUser_Project');

        $city_list = array();

        if ($this->getContext()->getCurrentUser()->isUserInRoles(array(DxUser_Project::ROLE_ADMIN, DxUser_Project::ROLE_DEVELOPER))) {
            /** @var DomainObjectQuery_City $q_city */
            $q_city = DxFactory::getSingleton('DomainObjectQuery_City');

            $city_list = $q_city->getAll(false);
        }

        $defaultGetVia = array(
            'Самолет',
            'Поезд',
            'Автобус',
            'Микроавтобус',
        );

        $getViaCurrent = (array)$this->getModel()->getGetVia();
        $getViaResult = array();

        foreach ($defaultGetVia as $v) {
            $is_found = false;

            foreach ($getViaCurrent as $k => $existing) {
                if ($existing['title'] == $v) {
                    $is_found = true;

                    break;
                }
            }

            if (!$is_found) {
                $getViaResult[] = array(
                    'title' => $v,
                );
            } else {
                $getViaResult[] = $existing;
            }
        }

        $this->getModel()->setGetVia($getViaResult);

        $this->smarty->assign(
            array(
                'country_list'      => $q_cry->getAll(true),
                'touroperator_list' => $q_to->findAll(true),
                'resort_list'       => $resorts,
                'city_list'         => $city_list,
                'from_all_list'     => $from_all_list,
            )
        );

        return $this->smarty->fetch('backend/form/product.tpl.php');
    }
}