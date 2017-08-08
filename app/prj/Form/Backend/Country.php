<?php
dxFactory::import('Form_Backend');

class Form_Backend_Country extends Form_Backend
{
    /** @var DomainObjectModel_Country */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Country|null $form_model
     */
    public function setModel(DomainObjectModel_Country $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Country|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Country|null
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
            'country_title' => array(
                'method' => 'setTitle',
                'value'  => trim($data['country_title']),
            ),
            'gallery_id' => array(
                'method' => 'setGalleryId',
                'value'  => empty($data['gallery_id']) ? null : $data['gallery_id'],
            ),
            'country_alias' => array(
                'method' => 'setAlias',
                'value'  => trim(mb_strtolower($data['country_alias'])),
            ),
            'country_keywords' => array(
                'method' => 'setKeywords',
                'value'  => empty($data['country_keywords']) ? null : trim($data['country_keywords']),
            ),
            'country_description' => array(
                'method' => 'setDescription',
                'value'  => empty($data['country_description']) ? null : trim($data['country_description']),
            ),
            'country_brief' => array(
                'method' => 'setBrief',
                'value'  => empty($data['country_brief']) ? null : trim($data['country_brief']),
            ),
            'country_content' => array(
                'method' => 'setContent',
                'value'  => empty($data['country_content']) ? null : trim($data['country_content']),
            ),
            'country_visa_days' => array(
                'method' => 'setVisaDays',
                'value'  => empty($data['country_visa_days']) || $data['country_visa_days'] <= 0 ? null : (int)$data['country_visa_days'],
            ),
            'country_status' => array(
                'method' => 'setStatus',
                'value'  => $data['country_status'],
            ),
        );

        if (empty($map['country_alias']['value']) && !empty($map['country_title']['value'])) {
            DxFactory::import('Utils_NameMaker');
            $map['country_alias']['value'] = Utils_NameMaker::cyrillicToLatin($map['country_title']['value'], true);
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
                $errors['country_alias'] = 'ALREADY_EXISTS';
            }
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
        /** @var $q_gallery DomainObjectQuery_Gallery */
        $q_gallery = DxFactory::getSingleton('DomainObjectQuery_Gallery');

        $this->smarty->assign(
            array(
                'gallery_list' => $q_gallery->findByCategory('COUNTRY'),
            )
        );

        unset($q_gallery);

        return $this->smarty->fetch('backend/form/country.tpl.php');
    }
}