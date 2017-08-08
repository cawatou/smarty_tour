<?php

DxFactory::import('Form_Backend');

class Form_Backend_FilesRename extends Form_Backend
{
    /**
     * @return bool
     */
    protected function process()
    {
        $data = $this->getEnvData('_POST');
        $errors = array();

        if (empty($data['name']) || !preg_match('~^[\.a-zA-Z0-9_-]+$~msu', $data['name'])) {
            $errors['name'] = 'INVALID_VALUE';
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
        return $this->smarty->fetch('backend/form/files_rename.tpl.php');
    }
}