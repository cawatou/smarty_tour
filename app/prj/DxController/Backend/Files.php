<?php

DxFactory::import('DxController_Backend');
DxFactory::import('DxFile');

class DxController_Backend_Files extends DxController_Backend
{
    protected $default_path = '/';
    protected $current_path = null;
    protected $current_cmd = null;
    protected $stop_load = array('.', '..', '.svn', '.htaccess', 'empty');
    protected $stop_create = array('.', '..', '.svn', '.htaccess', 'empty');
    protected $dir_tree = array();
    protected $full_files_path = null;
    protected $full_thumbs_path = null;

    /** @var array */
    protected $cmd_method = array(
        '.adm.files'        => 'index',
        '.adm.files-mce'    => 'mce',
        '.adm.files-dialog' => 'dialog',
        '.adm.files-multi'  => 'multi',
    );

    /**
     * @return null|string
     */
    protected function getCurrentPath()
    {
        return empty($this->current_path) ? $this->default_path : $this->current_path;
    }

    /**
     * @param $current_path
     */
    protected function setCurrentPath($current_path)
    {
        $this->checkPath($current_path);
        $this->current_path = $current_path;
    }

    /**
     * @return mixed|null
     */
    protected function getFullCurrentPath()
    {
        return $this->getFullFilesPath($this->getCurrentPath());
    }

    /**
     * @param null $path
     * @return mixed|null
     * @throws DxException
     */
    protected function getFullFilesPath($path = null)
    {
        if (is_null($this->full_files_path)) {
            $config = DxApp::config('url', 'static');
            if (empty($config['files'])) {
                throw new DxException("Warning: 'files' path is not set");
            }

            $this->full_files_path = DxFile::makeFullPath($config['files']);
        }

        if (is_null($path)) {
            return $this->full_files_path;
        } else {
            return DxFile::cleanPath($this->full_files_path . '/'. $path, DS);
        }
    }

    /**
     * @param null $path
     * @return mixed|null
     * @throws DxException
     */
    protected function getFullThumbsPath($path = null)
    {
        if (is_null($this->full_thumbs_path)) {
            $config = DxApp::config('url', 'static');
            if (empty($config['thumbs'])) {
                throw new DxException("Warning: 'thumbs' path is not set");
            }

            $this->full_thumbs_path = DxFile::makeFullPath($config['thumbs']);
        }

        if (is_null($path)) {
            return $this->full_thumbs_path;
        } else {
            return DxFile::cleanPath($this->full_thumbs_path . '/'. $path, DS);
        }
    }

    /**
     * @param stirng $cmd = null
     * @return mixed
     */
    protected function init($cmd = null)
    {
        $current_path = empty($_REQUEST['path']) || $_REQUEST['path'] == $this->default_path ? $this->default_path : DxFile::cleanPath($_REQUEST['path'], '/');
        $this->setCurrentPath($current_path);

        if (isset($_REQUEST['history']) && !empty($_SESSION['dx.files.history'])) {
            try {
                $this->checkPath($_SESSION['dx.files.history']);
                $this->getUrl()->redirect($this->getUrl()->cmd($cmd, "?path={$_SESSION['dx.files.history']}"));
            } catch (DxException $e) {
            }
        }

        $this->current_cmd = $cmd;
        $this->dir_tree      = $this->makeStorageDirTree();

        $op = $this->getContext()->getCurrentCommand()->getArguments('op', 'list');
        if ($op == 'list') {
            $_SESSION['dx.files.history'] = $current_path;
        }

        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'op'         => $op,
            'path'       => $this->getCurrentPath(),
            'path_parts' => explode('/', trim($this->getCurrentPath(), '/')),
            'cmd'        => $this->current_cmd,
            'backlight'  => empty($_REQUEST['backlight']) ? array() : explode(',', $_REQUEST['backlight']),
            'restrict'   => empty($_REQUEST['restrict']) ? false: true,
        ));

        $method = 'op' . ucwords($op);
        return $this->$method();
    }

    /**
     * @return string
     */
    protected function index()
    {
        $html = $this->init('.adm.files');
        return $this->wrap($html);
    }

    /**
     * @return string
     */
    protected function mce()
    {
        $html = $this->init('.adm.files-mce');
        return $this->wrap($html, array(), 'DIALOG');
    }

    /**
     * @return string
     */
    protected function dialog()
    {
        $html = $this->init('.adm.files-dialog');
        return $this->wrap($html, array(), 'DIALOG');
    }

    /**
     * @return string
     */
    protected function multi()
    {
        $html = $this->init('.adm.files-multi');
        return $this->wrap($html, array(), 'DIALOG');
    }

    /**
     * @param $name
     * @param $arguments
     * @throws DxException
     */
    public function __call($name, $arguments)
    {
        throw new DxException("Unknown operation '{$name}'");
    }

    /**
     * @return string
     */
    protected function opList()
    {
        $files = DxFile::readDirFiles($this->getFullCurrentPath(), $this->stop_load);
        foreach ($files as &$file) {
            $file['path'] = DxFile::cleanPath(str_replace($this->getFullFilesPath(), '', $file['path']), '/');
            $file['url']  = $this->getUrl()->files($file['path']);
            $file['uri']  = str_replace($this->getUrl()->main(), '/', $file['url']);
        }
        unset($file);

        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'files'       => $files,
            'is_writable' => is_writable($this->getFullCurrentPath()),
        ));

        if ($this->current_cmd == '.adm.files') {
            return $smarty->fetch('backend/files_list.tpl.php');
        } elseif ($this->current_cmd == '.adm.files-mce') {
            return $smarty->fetch('backend/files_list_mce.tpl.php');
        } elseif ($this->current_cmd == '.adm.files-dialog') {
            return $smarty->fetch('backend/files_list_dialog.tpl.php');
        } elseif ($this->current_cmd == '.adm.files-multi') {
            return $smarty->fetch('backend/files_list_multi.tpl.php');
        }
    }

    /**
     * @return string
     */
    protected function opAddFile()
    {
        set_time_limit(3600);
        ini_set('memory_limit', '128M');
        //below following settings are specified in php.ini or .htaccess
        //ini_set('upload_max_filesize', '50M');
        //ini_set('post_max_size', '100M');
        //ini_set('max_file_uploads', '40');

        $smarty = $this->getSmarty();

        /** @var $form Form_Backend_FilesUpload */
        $form = DxFactory::getInstance('Form_Backend_FilesUpload', array('add_file'));
        $form->setCmd($this->current_cmd . '.addFile');

        if ($form->isProcessed()) {
            $data  = $form->getEnvData('_POST');
            $files = $form->getEnvData('_FILES');

            $this->checkPath($data['path']);

            $backlight = '';
            $_files = array();
            foreach ($files['files']['tmp_name'] as $k => $file) {
                $file_name = $this->modifyFileName($files['files']['name'][$k]);
                $_files[] = array(
                    'src_path' => $file,
                    'src_name' => $files['files']['name'][$k],
                    'dst_name' => $file_name,
                );
                $backlight .= (!empty($backlight) ? ',' : '') . $file_name;
            }

            $uploaded = DxFactory::invoke('DxFile_Upload', 'createByRequest', array($_files, $this->getFullFilesPath($data['path'])));

            if (!empty($data['image_compress'])) {
                foreach ($uploaded as $file) {
                    try {
                        /** @var DxFile_Image $image */
                        $image = DxFactory::invoke('DxFile_Image', 'createByPath', array($file->getFullPath()));
                        if ($image->width > 1000) {
                            $image->resize(1000, null, DxFile_Image::RESIZE_WIDTH);
                            $image->commit($file->getFullPath(), 75);
                        }
                    } catch (DxException $e) {
                        continue;
                    }
                }
            }
            $url = $this->getUrl()->cmd($this->current_cmd, "?path={$data['path']}&backlight={$backlight}");
            $this->getUrl()->redirect($url);
        }
        $form->setDirTree($this->dir_tree);
        $form->setFormData(array('path' => $this->getCurrentPath()));

        $smarty->assign(array(
            'form_html' => $form->draw(),
        ));

        return $smarty->fetch('backend/files_manage.tpl.php');
    }

    /**
     * @return string
     */
    protected function opAddFolder()
    {
        /** @var $form Form_Backend_FilesFolder */
        $form = DxFactory::getInstance('Form_Backend_FilesFolder', array('add_folder'));
        $form->setCmd($this->current_cmd . '.addFolder');
        $form->setDirTree($this->dir_tree);
        $form->setFormData(array('path' => $this->getCurrentPath()));

        if ($form->isProcessed()) {
            $data = $form->getEnvData('_POST');
            $data['name'] = mb_strtolower($data['name']);
            $this->checkPath($data['path']);

            DxFile::createDir($this->getFullFilesPath($data['path'] . DS . $data['name']));

            $url = $this->getUrl()->cmd($this->current_cmd, "?path={$data['path']}&backlight={$data['name']}");
            $this->getUrl()->redirect($url);
        }

        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'form_html' => $form->draw(),
        ));
        return $smarty->fetch('backend/files_manage.tpl.php');
    }

    /**
     * @return string
     */
    protected function opRename()
    {
        $this->checkPath($this->getCurrentPath(), true);

        $src_path = $this->getFullCurrentPath();
        $src_info = pathinfo($src_path);

        $form_data = array(
            'name' => $src_info['filename'],
        );

        if (is_file($src_path)) {
            $form_data['ext'] = empty($src_info['extension']) ? '' : $src_info['extension'];
            /** @var $form Form_Backend_FilesRename */
            $form = DxFactory::getInstance('Form_Backend_FilesRename', array('rename_file'));
        } else {
            $form_data['name'] = $src_info['basename'];
            /** @var $form Form_Backend_FilesRename */
            $form = DxFactory::getInstance('Form_Backend_FilesRename', array('rename_folder'));
        }

        $form->setCmd($this->current_cmd . '.rename', "?path={$this->getCurrentPath()}");
        $form->setFormData($form_data);

        if ($form->isProcessed()) {
            $data = $form->getEnvData('_POST');
            if (is_file($src_path)) {
                $dst_name = $data['name'] . (empty($src_info['extension']) ? '' : ".{$src_info['extension']}");
            } else {
                $dst_name = $data['name'];
            }
            $dst_path = $src_info['dirname'] . DS . mb_strtolower($dst_name);
            DxFile::renameDirOrFile($src_path, $dst_path);

            $path = str_replace($this->getFullFilesPath(), '', $src_info['dirname']);
            $url  = $this->getUrl()->cmd($this->current_cmd, "?path={$path}&backlight={$dst_name}");
            $this->getUrl()->redirect($url);
        }

        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'form_html' => $form->draw(),
        ));
        return $smarty->fetch('backend/files_manage.tpl.php');
    }

    /**
     * @return void
     */
    protected function opDelete()
    {
        $this->checkPath($this->getCurrentPath(), true);

        $src_path = $this->getFullCurrentPath();

        if (is_dir($src_path)) {
            DxFile::removeDir($src_path);
        } elseif (is_file($src_path)) {
            DxFile::removeFile($src_path);
            try {
                DxFactory::import('DxFile_Thumbnail');
                DxFile_Thumbnail::cleanThumbs($src_path, $this->getFullThumbsPath(), $this->getFullFilesPath());
            } catch(DxException $e) {
            }
        }

        $url = $this->getUrl()->cmd($this->current_cmd, '?path=' . dirname($this->getCurrentPath()));
        $this->getUrl()->redirect($url);
    }

    /**
     * @return void
     */
    protected function opPreview()
    {
        $this->checkPath($this->getCurrentPath(), true);

        $thumbs_dir = $this->getFullThumbsPath();
        if (!is_writable($thumbs_dir)) {
            throw new dxException("Operation is not available: thumbs dir not writable");
        }

        $thumbs_config_path = $this->getFullThumbsPath('/config.ini');
        if (!file_exists($thumbs_config_path)) {
            file_put_contents($thumbs_config_path, '');
        }

        $config = parse_ini_file($thumbs_config_path, true);
        if ($config === false) {
            throw new dxException("Operation is not available: thumbs/config.ini can not be read");
        }

        DxFactory::import('DxFile_Thumbnail');
        $src_path = DxFile::makeRelativePath($this->getFullCurrentPath());
        $thumb = DxFile_Thumbnail::factory($src_path);

        $form_data = array(
            'master'           => empty($config[$src_path]['master']) ? $thumb->getMaster() : $config[$src_path]['master'],
            'crop'             => empty($config[$src_path]['crop']) ? $thumb->getCrop() : $config[$src_path]['crop'],
            'fill_color'       => empty($config[$src_path]['fill_color']) ? $thumb->getFillColor() : $config[$src_path]['fill_color'],
            'fill_transparent' => !isset($config[$src_path]['fill_transparent']) ? $thumb->getFillTransparent() : $config[$src_path]['fill_transparent'],
            'thumb_quality'    => !isset($config[$src_path]['thumb_quality']) ? $thumb->getThumbQuality() : $config[$src_path]['thumb_quality'],
        );

        /** @var $form Form_Backend_FilesPreview */
        $form = DxFactory::getInstance('Form_Backend_FilesPreview', array('preview_file'));
        $form->setCmd($this->current_cmd . '.preview', "?path={$this->getCurrentPath()}");
        $form->setFormData($form_data);
        if ($form->isProcessed()) {
            $thumb->cleanThumbs();

            $data = $form->getEnvData('_POST');
            $config[$src_path] = array(
                'master'           => $data['master'],
                'crop'             => $data['crop'],
                'fill_color'       => $data['fill_color'],
                'fill_transparent' => empty($data['fill_transparent']) ? false : true,
                'thumb_quality'    => $data['thumb_quality'],
            );

            $string = '';
            foreach($config as $sec => $args) {
                $string .= "[{$sec}]\n";
                foreach ($args as $k => $v) {
                    $string .= "{$k} = {$v}\n";
                }
                file_put_contents($thumbs_config_path, $string);
            }
            $src_info = pathinfo($this->getFullCurrentPath());
            $path = str_replace($this->getFullFilesPath(), '', $src_info['dirname']);
            $url  = $this->getUrl()->cmd($this->current_cmd, "?path={$path}&backlight={$src_info['basename']}");
            $this->getUrl()->redirect($url);
        }

        /** @var Smarty $smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'form_html' => $form->draw(),
        ));
        return $smarty->fetch('backend/files_manage.tpl.php');
    }

    /**
     * @return array
     */
    protected function makeStorageDirTree()
    {
        $storage_tree[] = array(
            'name'  => 'files',
            'level' => 1,
            'path'  => $this->default_path,
        );

        $dir_tree = DxFile::readDirTree($this->getFullFilesPath(), $this->stop_load);
        foreach ($dir_tree as $dir) {
            $dir['path']    = DxFile::cleanPath(str_replace($this->getFullFilesPath(), '', $dir['path']), '/');
            $dir['level']   = substr_count($dir['path'], '/') + 1;
            $storage_tree[] = $dir;
        }

        return $storage_tree;
    }

    /**
     * @param $checked_path
     * @param bool $not_default_path
     * @return bool
     * @throws DxException
     */
    protected function checkPath($checked_path, $not_default_path = false)
    {
        $checked_full_path = realpath($this->getFullFilesPath($checked_path));
        $files_full_path = $this->getFullFilesPath();

        if (!file_exists($checked_full_path)) {
            throw new DxException("Path does not exist '{$checked_path}'");
        } elseif (!is_readable($checked_full_path)) {
            throw new DxException("No permission to read the '{$checked_path}'");
        } elseif (strpos($checked_full_path, $files_full_path) === false) {
            throw new DxException("No access to '{$checked_path}'");
        }

        if ($not_default_path && $checked_full_path == $files_full_path) {
            throw new DxException("No possible path for this operation '{$checked_path}'");
        }

        return true;
    }

    /**
     * @param string $file_name
     * @return string
     */
    protected function modifyFileName($file_name)
    {
        $path_info = pathinfo($file_name);
        DxFactory::import('Utils_NameMaker');
        $ext = mb_strtolower(empty($path_info['extension']) ? '' : ".{$path_info['extension']}");
        return Utils_NameMaker::cyrillicToLatin($path_info['filename'], true) . $ext;
    }
}