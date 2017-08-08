<?php
dxFactory::import('Form_Backend');

class Form_Backend_Hotel extends Form_Backend
{
    /** @var DomainObjectModel_Hotel */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Hotel|null $form_model
     */
    public function setModel(DomainObjectModel_Hotel $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Hotel|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Hotel|null
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

        $map = array(
            'hotel_title' => array(
                'method' => 'setTitle',
                'value'  => $data['hotel_title'],
            ),
            'gallery_agency_id' => array(
                'method' => 'setGalleryAgencyId',
                'value'  => empty($data['gallery_agency_id']) ? null : $data['gallery_agency_id'],
            ),
            'gallery_operator_id' => array(
                'method' => 'setGalleryOperatorId',
                'value'  => empty($data['gallery_operator_id']) ? null : $data['gallery_operator_id'],
            ),
            'gallery_tourists_id' => array(
                'method' => 'setGalleryTouristsId',
                'value'  => empty($data['gallery_tourists_id']) ? null : $data['gallery_tourists_id'],
            ),
            'hotel_message' => array(
                'method' => 'setMessage',
                'value'  => empty($data['hotel_message']) ? null : $data['hotel_message'],
            ),
            'hotel_stars' => array(
                'method' => 'setStars',
                'value'  => empty($data['hotel_stars']) ? null : $data['hotel_stars'],
            ),
            'hotel_status' => array(
                'method' => 'setStatus',
                'value'  => $data['hotel_status'] == 'ENABLED' ? 'ENABLED' : 'DISABLED',
            ),
            'country_id' => array(
                'method' => 'setCountryId',
                'value'  => empty($data['country_id']) ? null : $data['country_id'],
            ),
            'resort_id' => array(
                'method' => 'setResortId',
                'value'  => empty($data['resort_id']) ? null : $data['resort_id'],
            ),
        );

        if ($m->getExternalId() === null) {
            $map['hotel_website'] = array(
                'method' => 'setWebsite',
                'value'  => empty($data['hotel_website']) ? null : $data['hotel_website'],
            );

            $map['hotel_description'] = array(
                'method' => 'setDescription',
                'value'  => empty($data['hotel_description']) ? null : $data['hotel_description'],
            );

            $map['hotel_description_url'] = array(
                'method' => 'setDescriptionUrl',
                'value'  => empty($data['hotel_description_url']) ? null : $data['hotel_description_url'],
            );

            $description_data = array();

            if (!empty($data['hotel_description_data'])) {
                $parts = DomainObjectModel_Hotel::getDescriptionParts();

                foreach ($data['hotel_description_data'] as $data_id => $data_list) {
                    if (empty($parts[$data_id])) {
                        continue;
                    }

                    if (empty($description_data[$data_id])) {
                        $description_data[$data_id] = array(
                            'title'   => $parts[$data_id],
                            'options' => array(),
                        );
                    }

                    foreach ($data_list as $d) {
                        if (empty($d)) {
                            continue;
                        }

                        $description_data[$data_id]['options'][] = $d;
                    }

                    $description_data[$data_id]['options'] = array_unique($description_data[$data_id]['options']);
                }
            }

            $map['hotel_description_data'] = array(
                'method' => 'setDescriptionData',
                'value'  => empty($description_data) ? null : $description_data,
            );
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

        if ($m->getTitle() !== null) {
            $m->setSignature($m->generateSignature($m->getTitle()));
        }

        $urls = array();

        if (!empty($data['hotel_urls'])) {
            foreach ($data['hotel_urls'] as $v) {
                $v = trim($v);

                if (empty($v)) {
                    continue;
                }

                $urls[] = $v;
            }
        }

        if (empty($errors) && $m->getCountry() !== null) {
            $m->setCountryTitle($m->getCountry()->getTitle());
        }

        if (empty($errors) && $m->getResort() !== null) {
            $m->setResortTitle($m->getResort()->getTitle());
        }

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        $this->getDomainObjectManager()->flush();

        return true;
    }

    /**
     * @return string
     */
    public function draw()
    {
        /** @var $q_cry DomainObjectQuery_Country */
        $q_cry = DxFactory::getSingleton('DomainObjectQuery_Country');

        $this->setFormData($this->getEnvData('_POST'));

        $resorts = array();

        if ($this->m()->getCountryId() !== null && $this->m()->getCountryId() > 0) {
            /** @var $q_res DomainObjectQuery_Resort */
            $q_res = DxFactory::getSingleton('DomainObjectQuery_Resort');

            $resorts = $q_res->getByCountryId($this->m()->getCountryId());
        }

        /** @var $q_g DomainObjectQuery_Gallery */
        $q_g = DxFactory::getSingleton('DomainObjectQuery_Gallery');

        $description_datas  = (array)$this->getModel()->getDescriptionData();
        $_description_datas = DxApp::config('hotels');

        foreach ($_description_datas as $id => $datas) {
            if (empty($description_datas[$id])) {
                $description_datas[$id] = array(
                    'title'   => DomainObjectModel_Hotel::getDescriptionPartTitle($id),
                    'options' => array(),
                );
            }

            if ($this->getId() == 'hotel_add') {
                foreach ($datas as $d) {
                    $description_datas[$id]['options'][] = $d;
                }
            }

            $description_datas[$id]['options'] = array_unique($description_datas[$id]['options']);
        }

        $this->smarty->assign(
            array(
                'country_list' => $q_cry->getAll(false),
                'resort_list'  => $resorts,

                'description_datas' => $description_datas,

                'gallery_agency_list'   => $q_g->getByCategory('HOTEL_AGENCY'),
                'gallery_tourists_list' => $q_g->getByCategory('HOTEL_TOURISTS'),
                'gallery_operator_list' => $q_g->getByCategory('HOTEL_OPERATOR'),
            )
        );

        unset($q_cry);

        return $this->smarty->fetch('backend/form/hotel.tpl.php');
    }
}