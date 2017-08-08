<?php

DxFactory::import('Form_Backend');

class Form_Backend_FilesPreview extends Form_Backend
{
    /**
     * @return bool
     */
    protected function process()
    {
        $data   = $this->getEnvData('_POST');
        $errors = array();

        if (empty($data['fill_color']) || !preg_match('~^#[a-f0-9]{6}$~i', $data['fill_color'])) {
            $errors['fill_color'] = 'INVALID_VALUE';
        }

        if (empty($data['crop']) || !in_array($data['crop'], array('LT', 'CT', 'RT', 'LM', 'CM', 'RM', 'LB', 'CB', 'RB'))) {
            $errors['crop'] = 'INVALID_VALUE';
        }

        if (empty($data['master']) || !in_array($data['master'], array('RESIZE', 'CROP', 'RESIZECROP'))) {
            $errors['master'] = 'INVALID_VALUE';
        }

        $data['thumb_quality'] = (int)$data['thumb_quality'];
        if (!isset($data['thumb_quality']) || $data['thumb_quality'] < 50 || $data['thumb_quality'] > 100) {
            $errors['thumb_quality'] = 'INVALID_VALUE';
        }

        if (!empty($errors)) {
            $this->errors = $errors;
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function draw()
    {
        $data = $this->getEnvData('_POST');
        if (!empty($data)) {
            $this->setFormData($data);
        }

        return $this->smarty->fetch('backend/form/files_preview.tpl.php');
    }
}