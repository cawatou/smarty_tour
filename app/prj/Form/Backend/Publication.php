<?php

dxFactory::import('Form');

class Form_Backend_Publication extends Form_Backend
{
    /** @var DomainObjectModel_Publication */
    protected $form_model = null;

    /** @var string|null */
    protected $template = null;

    /**
     * @param $template
     */
    public function setTemplate($template) {
        $this->template = $template;
    }

    /**
     * @param DomainObjectModel_Publication|null $form_model
     */
    public function setModel(DomainObjectModel_Publication $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Publication|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Publication|null
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
        if (is_null($m)) {
            return false;
        }

        $map = array(
            'publication_date'   => array(
                'method' => 'setDate',
                'value'  => new DxDateTime($data['publication_date'], DxDateTime::getDefaultTimeZone()),
            ),
            'publication_title'  => array(
                'method' => 'setTitle',
                'value'  => mb_substr($data['publication_title'], 0, 255),
            ),

            'publication_brief'  => array(
                'method' => 'setBrief',
                'value'  =>  $data['publication_brief'],
            ),
            'publication_content'   => array(
                'method' => 'setContent',
                'value'  => $data['publication_content'],
            ),
            'publication_status' => array(
                'method' => 'setStatus',
                'value'  => $data['publication_status'] == 'ENABLED' ? 'ENABLED' : 'DISABLED',
            ),
            'publication_cover'  => array(
                'method' => 'setCover',
                'value'  => empty($data['publication_cover']) ? null : mb_substr($data['publication_cover'], 0, 255),
            ),
            'publication_file'  => array(
                'method' => 'setFile',
                'value'  => empty($data['publication_file']) ? null : mb_substr($data['publication_file'], 0, 255),
            ),
            'publication_youtube'  => array(
                'method' => 'setYoutube',
                'value'  => empty($data['publication_youtube']) ? null : mb_substr($data['publication_youtube'], 0, 255),
            ),
            'publication_tags'  => array(
                'method' => 'setTags',
				'value'  => empty($data['publication_tags']) ? null : mb_strtolower(preg_replace('~\s*,\s*~', ', ', trim($data['publication_tags']))),
            ),
            'publication_category'  => array(
                'method' => 'setCategory',
                'value'  => $data['publication_category'],
            ),
            'publication_source_title'  => array(
                'method' => 'setSourceTitle',
                'value'  => empty($data['publication_source_title']) ? null : mb_substr($data['publication_source_title'], 0, 255),
            ),
            'publication_source_link'  => array(
                'method' => 'setSourceLink',
                'value'  => empty($data['publication_source_link']) ? null : preg_replace('~^[^/]+://(.+)$~', '$1', mb_substr($data['publication_source_link'], 0, 255)),
            ),
            'publication_is_highlight' => array(
                'method' => 'setIsHighlight',
                'value'  => empty($data['publication_is_highlight']) ? 0 : 1,
            ),
        );

        if (!empty($data['publication_youtube'])) {
            try {
                $yt = DxFactory::getInstance('Utils_YouTube', array($data['publication_youtube']));
                $map['publication_youtube']['value'] = $yt->getVideoId();
            } catch (dxException $e) {
                $errors['publication_youtube'] = 'NOT_VALID';
            }
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

        $m->setSignature(Utils_NameMaker::cyrillicToLatin($m->getTitle(), true));

        if (!empty($data['publication_cover'])) {
            try {
                DxFactory::invoke('DxFile_Image', 'createByPath', array(ROOT . $data['publication_cover']));
            } catch (DxException $e) {
                $img_errors = array(
                    DxFile_Image::ERROR_IMAGE_NOT_FOUND   => 'IMAGE_NOT_FOUND',
                    DxFile_Image::ERROR_IMAGE_LOAD        => 'IMAGE_NOT_LOAD',
                    DxFile_Image::ERROR_IMAGE_UNSUPPORTED => 'IMAGE_UNSUPPORTED',
                );

                $errors['publication_cover'] = array_key_exists($e->getCode(), $img_errors) ? $img_errors[$e->getCode()] : 'NOT_VALID';
            }
        }

        if (!empty($data['publication_file'])) {
            try {
                DxFactory::invoke('DxFile', 'createByPath', array(ROOT . $data['publication_file']));
            } catch (DxException $e) {

                $errors['publication_file'] = 'NOT_EXISTS';
            }
        }

        if (!empty($data['publication_image'])) {
            $images = array_unique(explode(';', $data['publication_image']));

            foreach ($images as $k => $i) {
                if (empty($i)) {
                    unset($images[$k]);
                    continue;
                }
                try {
                    DxFactory::invoke('DxFile_Image', 'createByPath', array($i));
                } catch (DxException $e) {
                    $img_errors = array(
                        DxFile_Image::ERROR_IMAGE_NOT_FOUND   => 'IMAGE_NOT_FOUND',
                        DxFile_Image::ERROR_IMAGE_LOAD        => 'IMAGE_NOT_LOAD',
                        DxFile_Image::ERROR_IMAGE_UNSUPPORTED => 'IMAGE_UNSUPPORTED',
                    );

                    $errors['publication_image'] = array_key_exists($e->getCode(), $img_errors) ? $img_errors[$e->getCode()] : 'NOT_VALID';
                    break;
                }
            }

            if (!empty($images) && empty($errors['publication_image'])) {
                $is_cover = count($m->getImages()) ? 0 : 1;
                $qnt = time();
                foreach ($images as $i) {
                    try {
                        /** @var $pi DomainObjectModel_PublicationImage */
                        $pi = DxFactory::getInstance('DomainObjectModel_PublicationImage');
                        $pi->setPublication($m);
                        $pi->setPath($i);
                        $pi->setIsCover($is_cover);
                        $pi->setQnt($qnt);
                        $is_cover = 0;
                        $qnt++;
                    } catch (DxException $e) {
                        $errors['publication_image'] = 'NOT_VALID';
                    }
                }
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
        $this->setFormData($this->getEnvData('_POST'));
        return $this->smarty->fetch("backend/form/{$this->template}");
    }
}