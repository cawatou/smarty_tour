<?php

DxFactory::import('Form_Backend');

class Form_Backend_FilesFolder extends Form_Backend
{
    protected $dir_tree = array();

    /**
     * @return bool
     */
    protected function process()
    {
        $data   = $this->getEnvData('_POST');
        $errors = array();

        $fields = array('path');
        foreach ($fields as $field) {
            if (!preg_match('~^[^\s]{1,}$~msu', $data[$field])) {
                $errors[$field] = 'SHORT_VALUE';
            }
        }

        if (empty($data['name']) || !preg_match('~^[\.a-zA-Z0-9_-]+$~msu', $data['name'])) {
            $errors['name'] = 'INVALID_VALUE';
        }

        if (!empty($errors)) {
            $this->errors = $errors;
            return false;
        }

        return true;
    }

    public function setDirTree($dir_tree)
    {
        $this->dir_tree = $dir_tree;
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
        $this->smarty->assign(array(
            'dir_tree'  => $this->dir_tree,
        ));
        return $this->smarty->fetch('backend/form/files_folder.tpl.php');
    }
}