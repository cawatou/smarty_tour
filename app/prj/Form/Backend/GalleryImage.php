<?php

dxFactory::import('Form_Backend');

class Form_Backend_GalleryImage extends Form_Backend
{
    /** @var DomainObjectModel_GalleryImage */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_GalleryImage|null $form_model
     */
    public function setModel(DomainObjectModel_GalleryImage $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_GalleryImage|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_GalleryImage|null
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

        $paths = array();
        if (empty($data['gallery_image_path'])) {
            $paths[] = '';
        } else {
            $_paths = array_unique(explode(';', $data['gallery_image_path']));;
            foreach ($_paths as $path) {
                if (empty($path)) continue;

                try {
                    DxFactory::invoke('DxFile_Image', 'createByPath', array($path));
                    $paths[] = $path;
                } catch (DxException $e) {
                    $img_errors = array(
                        DxFile_Image::ERROR_IMAGE_NOT_FOUND   => 'IMAGE_NOT_FOUND',
                        DxFile_Image::ERROR_IMAGE_LOAD        => 'IMAGE_NOT_LOAD',
                        DxFile_Image::ERROR_IMAGE_UNSUPPORTED => 'IMAGE_UNSUPPORTED',
                    );

                    $errors['gallery_image_path'] = array_key_exists($e->getCode(), $img_errors) ? $img_errors[$e->getCode()] : 'NOT_VALID';
                    break;
                }
            }
        }

        if (empty($errors)) {
            $qnt = time();
            foreach ($paths as $i => $path) {
                $qnt++;
                $map = array(
                    'gallery_id' => array(
                        'method' => 'setGalleryId',
                        'value'  => $data['gallery_id'],
                    ),
                    'gallery_image_path' => array(
                        'method' => 'setPath',
                        'value'  => $path,
                    ),
                    'gallery_image_title' => array(
                        'method' => 'setTitle',
                        'value'  => empty($data['gallery_image_title']) ? null : $data['gallery_image_title'],
                    ),
                    'gallery_image_link'   => array(
                        'method' => 'setLink',
                        'value'  => empty($data['gallery_image_link']) ? null : preg_replace('~^[^/]+://(.+)$~', '$1', $data['gallery_image_link']),
                    ),
                    'gallery_image_description' => array(
                        'method' => 'setDescription',
                        'value'  => empty($data['gallery_image_description']) ? null : $data['gallery_image_description'],
                    ),
                    'gallery_image_status' => array(
                        'method' => 'setStatus',
                        'value'  => $data['gallery_image_status'],
                    ),

                    'gallery_image_qnt' => array(
                        'method' => 'setQnt',
                        'value'  => $qnt,
                    ),
                );

                if ($i == 0) {
                    $o = $m;
                } else {
                    $o = DxFactory::getInstance('DomainObjectModel_GalleryImage');
                }

                if ($this->getId() == 'gallery_image_edit') {
                    unset($map['gallery_image_qnt']);
                }

                foreach ($map as $key => $val) {
                    try {
                        call_user_func(array($o, $val['method']), $val['value']);
                    } catch (DxException $e) {
                        if ($e->getCode() == DomainObjectModel::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT) {
                            $errors[$key] = 'INVALID_FORMAT';
                        } else {
                            $errors[$key] = 'NOT_VALID';
                        }
                    }
                }
                if (!empty($errors)) break;
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
        /** @var $q DomainObjectQuery_Gallery */
        $q = DxFactory::getSingleton('DomainObjectQuery_Gallery');

		$this->smarty->assign(array(
			'gallery_list' => $q->findAll(),
		));

    	return $this->smarty->fetch('backend/form/gallery_image.tpl.php');
    }
}