<?php
dxFactory::import('Form_Backend');

class Form_Backend_City extends Form_Backend
{
    /** @var DomainObjectModel_City */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_City|null $form_model
     */
    public function setModel(DomainObjectModel_City $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_City|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_City|null
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

        $m = $this->getModel();

        if ($m === null) {
            return false;
        }

        $departure_list = array();
        foreach ($data['city_departure_list'] as $v) {
            $departure_list[] = array(
                'departure_id' => $v['departure_id'],
                'qnt'          => (int)$v['qnt'],
                'is_hidden'    => empty($v['is_hidden']) ? 0 : 1,
            );
        }
        $departure_list = $this->sortDepartures($departure_list);

        $city_ids = array();
        $i = 0;
        foreach ($data['city_city_ids'] as $city_id => $v) {
            if (!empty($v['is_shown'])) {
                $i++;
                $city_ids[$city_id] = empty($v['qnt']) ? $i : (int)$v['qnt'];
            }
        }
        asort($city_ids);

        $similar_city_ids = array();
        $i = 0;

        foreach ($data['city_similar_product_cities'] as $city_id => $v) {
            if (!empty($v['is_shown'])) {
                $similar_city_ids[$v['departure_id']] = 1;
            }
        }

        asort($similar_city_ids);

        $map = array(
            'city_title' => array(
                'method' => 'setTitle',
                'value'  => empty($data['city_title']) ? null : trim($data['city_title']),
            ),
            'city_alias' => array(
                'method' => 'setAlias',
                'value'  => empty($data['city_alias']) ? null : trim(mb_strtolower($data['city_alias'])),
            ),
            'city_status' => array(
                'method' => 'setStatus',
                'value'  => empty($data['city_status']) || $data['city_status'] != 'ENABLED' ? 'DISABLED' : 'ENABLED',
            ),

            'city_departure_list' => array(
                'method' => 'setDepartureList',
                'value'  => empty($departure_list) ? null : $departure_list,
            ),
            'city_city_ids' => array(
                'method' => 'setCityIds',
                'value'  => empty($city_ids) ? null : $city_ids,
            ),
            'city_similar_product_cities' => array(
                'method' => 'setSimilarProductCities',
                'value'  => empty($similar_city_ids) ? null : $similar_city_ids,
            ),
            'city_email'   => array(
                'method' => 'setEmail',
                'value'  => empty($data['city_email']) ? null : mb_strtolower(trim($data['city_email'])),
            ),
            'city_sms_group' => array(
                'method' => 'setSmsGroup',
                'value'  => empty($data['city_sms_group']) ? null : trim($data['city_sms_group']),
            ),
            'city_email_group' => array(
                'method' => 'setEmailGroup',
                'value'  => empty($data['city_email_group']) ? null : trim($data['city_email_group']),
            ),
            'city_vk_group' => array(
                'method' => 'setVkGroup',
                'value'  => empty($data['city_vk_group']) ? null : $data['city_vk_group'],
            ),
            'city_odnkl_group' => array(
                'method' => 'setOdnklGroup',
                'value'  => empty($data['city_odnkl_group']) ? null : $data['city_odnkl_group'],
            ),
            'city_insta_group' => array(
                'method' => 'setInstaGroup',
                'value'  => empty($data['city_insta_group']) ? null : $data['city_insta_group'],
            ),
            'city_top_news'   => array(
                'method' => 'setTopNews',
                'value'  => empty($data['city_top_news']) ? null : $data['city_top_news'],
            ),
			
			'city_facebook_group'   => array(
                'method' => 'setFacebookGroup',
                'value'  => empty($data['city_facebook_group']) ? null : $data['city_facebook_group'],
            ),
        );

        if (empty($map['city_alias']['value']) && !empty($map['city_title']['value'])) {
            DxFactory::import('Utils_NameMaker');
            $map['city_alias']['value'] = Utils_NameMaker::cyrillicToLatin($map['city_title']['value'], true);
        }

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

        if (empty($errors)) {
            if (!$m->isUnique('alias')) {
                $errors['city_alias'] = 'ALREADY_EXISTS';
            }
        }

        if (empty($errors) && $m->getId()) {
            /** @var DomainObjectQuery_Office $q_of */
            /** Commented because different offices can have different subdivisions
            $q_of = DxFactory::getSingleton('DomainObjectQuery_Office');

            $subdivision_offices = $q_of->findByCityId($m->getId());

            foreach ($subdivision_offices as $office) {
                $office->setSubdivisionId($m->getSubdivisionId());
                $office->setSubdivisionName($m->getSubdivision()->getTitle());
            }**/
        }

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        //http://vk.com/club64359756
        if ($m->getVkGroup() !== null) {
            if (!is_numeric($m->getVkGroup())) {
                $group_id = trim($m->getVkGroup(), ' /.:');

                if (substr($group_id, 0, 4) == 'club') {
                    $m->setVkGroup(
                        substr($group_id, 4, strlen($group_id) - 4)
                    );
                } else {
                    if (strpos($group_id, 'https://vk.com/') !== 0 && strpos($group_id, 'http://vk.com/') !== 0) {
                        $group_id = 'https://vk.com/'. $group_id;
                    }

                    $m->setVkGroup($this->getGroupId($group_id));
                }
            }
        }

        $this->getDomainObjectManager()->flush();

        return true;
    }

    /**
     * @return string
     */
    public function draw()
    {
        /** @var DomainObjectQuery_City $q_city */
        $q_city = DxFactory::getSingleton('DomainObjectQuery_City');

        $city_list = $q_city->findAll(true);

        if ($this->getModel()->getCityId()) {
            foreach ($city_list as $k => $c) {
                if ($c->getId() == $this->getModel()->getCityId()) {
                    unset($city_list[$k]);
                }
            }
        }

        /** @var DomainObjectQuery_Subdivision $q_sub */
        $q_sub = DxFactory::getSingleton('DomainObjectQuery_Subdivision');

        $subdivisions = array();

        if ($this->getContext()->getCurrentUser()->getRole() !== 'DIRECTOR') {
            $subdivisions = $q_sub->findAll(true);
        }

        $this->smarty->assign(
            array(
                'city_list'        => $city_list,
                'subdivision_list' => $subdivisions,
                'departures'       => $this->getModel()->getFromAll(),
            )
        );

        unset($q_city, $city_list);

        return $this->smarty->fetch('backend/form/city.tpl.php');
    }

    /**
     * @param array $departures
     * @return array
     */
    public function sortDepartures(array $departures)
    {
        $qnts = array();

        foreach ($departures as $departure) {
            $qnts[] = empty($departure['qnt']) ? 100000 : (int)$departure['qnt'];
        }

        array_multisort($qnts, SORT_ASC, $departures);

        return $departures;
    }

    public function getGroupId()
    {
        return null;
    }
}