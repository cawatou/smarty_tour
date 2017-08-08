<?php

DxFactory::import('Form_Backend');

class Form_Backend_FilesUpload extends Form_Backend
{
    protected $dir_tree = array();

    /**
     * @return bool
     */
    protected function process()
    {
        $data = $this->getEnvData('_POST');
        $files = $this->getEnvData('_FILES');
        $errors = array();
        
        if (empty($files['files']['tmp_name']) || empty($files['files']['tmp_name'][0])) {
            $errors['files'] = 'EMPTY';
        }
        if (empty($data['path'])) {
            $errors['path'] = 'EMPTY';
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
            'dir_tree' => $this->dir_tree,
            'max_file_uploads' => (int)ini_get('max_file_uploads'),
            'max_upload' => min((int)ini_get('upload_max_filesize'), (int)ini_get('post_max_size'), (int)ini_get('memory_limit')),
        ));
    	return $this->smarty->fetch('backend/form/files_upload.tpl.php');
    }
}