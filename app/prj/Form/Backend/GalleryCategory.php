<?php

dxFactory::import('Form_Backend');

class Form_Backend_GalleryCategory extends Form_Backend
{
    /** @var DomainObjectModel_Gallery */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Gallery|null $form_model
     */
    public function setModel(DomainObjectModel_Gallery $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Gallery|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Gallery|null
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
        $data = $this->getEnvData('_POST');
        $errors = array();

        $m = $this->getModel();
        if (is_null($m)) {
            return false;
        }

        $map = array(
            'gallery_date'   => array(
                'method' => 'setDate',
                'value'  => new DxDateTime($data['gallery_date']),
            ),
            'gallery_title' => array(
                'method' => 'setTitle',
                'value'  => empty($data['gallery_title']) ? '' : mb_substr($data['gallery_title'], 0, 255),
            ),			
            'gallery_alias' => array(
                'method' => 'setAlias',
                'value'  => empty($data['gallery_alias']) ? null : mb_strtolower($data['gallery_alias']),
            ),
            'gallery_description' => array(
                'method' => 'setDescription',
                'value'  => empty($data['gallery_description']) ? null : $data['gallery_description'],
            ),
            'gallery_status' => array(
                'method' => 'setStatus',
                'value'  => $data['gallery_status'],
            ),
            'gallery_cover'  => array(
                'method' => 'setCover',
                'value'  => empty($data['gallery_cover']) ? null : $data['gallery_cover'],
            ),
            'gallery_category' => array(
                'method' => 'setCategory',
                'value'  => $data['gallery_category'],
            ),
            'gallery_is_highlight' => array(
                'method' => 'setIsHighlight',
                'value'  => empty($data['gallery_is_highlight']) ? 0 : 1,
            ),
        );

        if (empty($map['gallery_alias']['value']) && !empty($map['gallery_title']['value'])) {
            DxFactory::import('Utils_NameMaker');
            $map['gallery_alias']['value'] = Utils_NameMaker::cyrillicToLatin($map['gallery_title']['value'], true);
        }

        foreach ($map as $key => $val) {
            try {
                call_user_func(array($m, $val['method']), $val['value']);
            } catch (DxException $e) {
                if ($e->getCode() == DomainObjectModel::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT) {
                    $errors[$key] = 'INVALID_FORMAT';
                } else {
                    $errors[$key] = 'NOT_VALID';
                }
            }
        }
		
        if (empty($errors)) {
            if (!$m->isUniqueAlias()) {
                $errors['gallery_alias'] = 'ALREADY_EXISTS';
            }
        }

        if (!empty($data['gallery_cover'])) {
            try {
                DxFactory::invoke('DxFile_Image', 'createByPath', array(ROOT . $data['gallery_cover']));
            } catch (DxException $e) {
                $img_errors = array(
                    DxFile_Image::ERROR_IMAGE_NOT_FOUND   => 'IMAGE_NOT_FOUND',
                    DxFile_Image::ERROR_IMAGE_LOAD        => 'IMAGE_NOT_LOAD',
                    DxFile_Image::ERROR_IMAGE_UNSUPPORTED => 'IMAGE_UNSUPPORTED',
                );

                $errors['gallery_cover'] = array_key_exists($e->getCode(), $img_errors) ? $img_errors[$e->getCode()] : 'NOT_VALID';
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
    	return $this->smarty->fetch('backend/form/gallery_category.tpl.php');
    }
}