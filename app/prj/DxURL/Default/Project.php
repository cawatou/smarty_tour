<?php

DxFactory::import('DxURL_Default');

class DxURL_Default_Project extends DxURL_Default
{
    /**
     * @param string $path
     * @return string
     */
    public function files($path)
    {
        return $this->getStatic('files', $path);
    }

    /**
     * @return string
     */
    public function main()
    {
        return $this->protocol . $this->host . $this->base . '/';
    }

    /**
     * @param string $path
     * @param bool   $is_cmd
     * @return string
     */
    public function url($path, $is_cmd = false)
    {
        if ($is_cmd) {
            $path = str_replace('.', '/', $path);
        }
        $path = trim($path, '/');
        if (empty($path)) {
            return $this->main();
        }

        $suffix = '';
        if (strpos($path, '#') !== false) {
            $path = explode('#', $path);
            $suffix = '#' . trim($path[1], '/');
            $path = trim($path[0], '/');
        }

        return $this->protocol . $this->host . $this->base . '/' . $path . '/' . $suffix;
    }

    /**
     * @param $rel_path
     * @return string
     */
    public function full($rel_path)
    {
        $path = trim($rel_path, '/');
        return $this->protocol . $this->host . $this->base . '/' . $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function bootstrap($path)
    {
        return $this->getStatic('bootstrap', $path);
    }

    /**
     * @param string      $route
     * @param null|string $suffix
     * @return string
     */
    public function adm($route = '', $suffix = null)
    {
        return $this->cmd(".adm{$route}", $suffix);
    }

    /**
     * @param string $cmd
     * @param null $suffix
     * @return string
     */
    public function cmd($cmd = '', $suffix = null)
    {
        return $this->url($cmd, true) . (!empty($suffix) ? $suffix : '');
    }

    /**
     * @param $img_path
     * @param null $width
     * @param null $height
     * @param bool $watermark
     * @return string
     */
    public function thumb($img_path, $width = null, $height = null, $watermark = false)
    {
        DxFactory::import('DxFile_Thumbnail');
        try {
            $thumb = DxFile_Thumbnail::factory($img_path, $width, $height);
            $thumb
                ->setFilesDir($this->params['static']['files'])
                ->setThumbsDir($this->params['static']['thumbs']);

            $thumb_path = $thumb->isExists();
            if (is_null($thumb_path)) {
                $config_path = DxFile::makeFullPath($this->params['static']['thumbs'] . DS . 'config.ini');
                if (!is_null($config_path) && is_readable($config_path) && ($config = parse_ini_file($config_path, true)) !== false) {
                    if (!empty($config[$img_path])) {
                        $thumb
                            ->setMaster($config[$img_path]['master'])
                            ->setCrop($config[$img_path]['crop'])
                            ->setFillColor($config[$img_path]['fill_color'])
                            ->setFillTransparent($config[$img_path]['fill_transparent'])
                            ->setThumbQuality($config[$img_path]['thumb_quality']);
                    }
                }

                $thumb_path = $thumb->getThumb();
            }
            $thumb_path = DxFile::cleanPath(DxFile::makeRelativePath($thumb_path));
            return $this->protocol . $this->host . $this->base . $thumb_path;
        } catch (Exception $e) {
            return $this->img('/error.jpg');
        }
    }

    /**
     * @param mixed  $value
     * @param string $type
     * @return string
     */
    public function urlByType($value, $type = 'EMPTY')
    {
        if ($type == 'EMPTY') {
            return '#';
        } elseif ($type == 'PAGE') {
            return $this->url(DomainObjectModel_Page::getPathById($value));
        } elseif ($type == 'LINK') {
            if (preg_match('~^http*~', $value)) {
                return $value;
            } else {
                return $this->url($value);
            }
        } elseif ($type == 'CMD') {
            if ($value == DxCommand::CMD_DEFAULT) {
                return $this->main();
            }
            return $this->url($value, true);
        }
    }
}