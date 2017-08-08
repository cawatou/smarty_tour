<?php
dxFactory::import('Form_Backend');

class Form_Backend_Resort extends Form_Backend
{
    /** @var DomainObjectModel_Resort */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Resort|null $form_model
     */
    public function setModel(DomainObjectModel_Resort $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Resort|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Resort|null
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
            'resort_title' => array(
                'method' => 'setTitle',
                'value'  => $data['resort_title'],
            ),
            'gallery_id' => array(
                'method' => 'setGalleryId',
                'value'  => empty($data['gallery_id']) ? null : $data['gallery_id'],
            ),
            'country_id' => array(
                'method' => 'setCountryId',
                'value'  => $data['country_id'],
            ),
            'resort_alias' => array(
                'method' => 'setAlias',
                'value'  => trim(mb_strtolower($data['resort_alias'])),
            ),
            'resort_keywords' => array(
                'method' => 'setKeywords',
                'value'  => empty($data['resort_keywords']) ? null : trim($data['resort_keywords']),
            ),
            'resort_description' => array(
                'method' => 'setDescription',
                'value'  => empty($data['resort_description']) ? null : trim($data['resort_description']),
            ),
            'resort_brief' => array(
                'method' => 'setBrief',
                'value'  => empty($data['resort_brief']) ? null : trim($data['resort_brief']),
            ),
            'resort_content' => array(
                'method' => 'setContent',
                'value'  => empty($data['resort_content']) ? null : trim($data['resort_content']),
            ),
            'resort_status' => array(
                'method' => 'setStatus',
                'value'  => $data['resort_status'],
            ),
        );

        if (empty($map['resort_alias']['value']) && !empty($map['resort_title']['value'])) {
            DxFactory::import('Utils_NameMaker');
            $map['resort_alias']['value'] = Utils_NameMaker::cyrillicToLatin($map['resort_title']['value'], true);
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
                $errors['resort_alias'] = 'ALREADY_EXISTS';
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

        /** @var $q_pctry DomainObjectQuery_Country */
        $q_pctry = DxFactory::getSingleton('DomainObjectQuery_Country');

        $this->smarty->assign(
            array(
                'country_list' => $q_pctry->findAll(true),
                'gallery_list' => $q_gallery->findByCategory('RESORT'),
            )
        );

        unset($q_pctry, $q_gallery);

        return $this->smarty->fetch('backend/form/resort.tpl.php');
    }
}