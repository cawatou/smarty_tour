<?php

DxFactory::import('DxFile');
DxFactory::import('DxFile_Image');

class DxFile_Thumbnail extends DxFile
{
    // Errors constants
    const ERROR_PATH_NOT_FOUND   = 3001;
    const ERROR_INVALID_VALUE    = 3002;

    // Master contstants
    const MASTER_RESIZE       = 'RESIZE';
    const MASTER_CROP_PRECISE = 'CROP';
    const MASTER_CROP_AUTO    = 'RESIZECROP';

    const CROP_LT = 'LT';
    const CROP_LM = 'LM';
    const CROP_LB = 'LB';
    const CROP_CT = 'CT';
    const CROP_CM = 'CM';
    const CROP_CB = 'CB';
    const CROP_RT = 'RT';
    const CROP_RM = 'RM';
    const CROP_RB = 'RB';

    protected $crop_types = array(
        self::CROP_LT => array(DxFile_Image::POSITION_LEFT, DxFile_Image::POSITION_TOP),
        self::CROP_LM => array(DxFile_Image::POSITION_LEFT, DxFile_Image::POSITION_MIDDLE),
        self::CROP_LB => array(DxFile_Image::POSITION_LEFT, DxFile_Image::POSITION_BOTTOM),
        self::CROP_CT => array(DxFile_Image::POSITION_CENTER, DxFile_Image::POSITION_TOP),
        self::CROP_CM => array(DxFile_Image::POSITION_CENTER, DxFile_Image::POSITION_MIDDLE),
        self::CROP_CB => array(DxFile_Image::POSITION_CENTER, DxFile_Image::POSITION_BOTTOM),
        self::CROP_RT => array(DxFile_Image::POSITION_RIGHT, DxFile_Image::POSITION_TOP),
        self::CROP_RM => array(DxFile_Image::POSITION_RIGHT, DxFile_Image::POSITION_MIDDLE),
        self::CROP_RB => array(DxFile_Image::POSITION_RIGHT, DxFile_Image::POSITION_BOTTOM),
    );

    protected $master = self::MASTER_CROP_AUTO;
    protected $crop   = self::CROP_CM;

    protected $fill_color = '#FFFFFF';
    protected $fill_transparent = true;

    /** @var int quality thumbnail */
    protected $thumb_quality = 80;

    /** @var null relative path to the watermarks*/
    protected $watermark_path = null;

    /** @var string relative path to the directory with the source file */
    protected $files_dir      = '/static/files';

    /** @var string relative path to the directory with the thumbnail file */
    protected $thumbs_dir     = '/static/thumbs';

    protected $image_path   = null;
    protected $thumb_width  = null;
    protected $thumb_height = null;

    /**
     * @static
     * @param $image_path
     * @param $width
     * @param $height
     * @return DxFile_Thumbnail
     */
    public static function factory($image_path, $width = null, $height = null)
    {
        return new DxFile_Thumbnail($image_path, $width, $height);
    }

    /**
     * @param $image_path
     * @param $width
     * @param $height
     */
    public function __construct($image_path, $width = null, $height = null)
    {
        $this->image_path   = self::makeRelativePath($image_path);
        $this->thumb_width  = $width;
        $this->thumb_height = $height;
    }

    /**
     * @param $master
     * @return DxFile_Thumbnail
     */
    public function setMaster($master)
    {
        $this->master = $master;
        return $this;
    }

    /**
     * @return string
     */
    public function getMaster()
    {
        return $this->master;
    }

    /**
     * @param $crop
     * @return DxFile_Thumbnail
     */
    public function setCrop($crop)
    {
        $this->crop = $crop;
        return $this;
    }

    /**
     * @return string
     */
    public function getCrop()
    {
        return $this->crop;
    }

    /**
     * @param $files_dir
     * @return DxFile_Thumbnail
     */
    public function setFilesDir($files_dir)
    {
        $this->files_dir = self::makeRelativePath($files_dir);
        return $this;
    }

    /**
     * @param $thumbs_dir
     * @return DxFile_Thumbnail
     */
    public function setThumbsDir($thumbs_dir)
    {
        $this->thumbs_dir = self::makeRelativePath($thumbs_dir);
        return $this;
    }

    /**
     * @param $watermark_path
     * @return DxFile_Thumbnail
     */
    public function setWatermarkPath($watermark_path)
    {
        $this->watermark_path = self::makeRelativePath($watermark_path);
        return $this;
    }

    /**
     * @param $color string
     * @return DxFile_Thumbnail
     */
    public function setFillColor($color)
    {
        $this->fill_color = $color;
        return $this;
    }

    /**
     * @return string
     */
    public function getFillColor()
    {
        return $this->fill_color;
    }

    /**
     * @param $is_transparent bool
     * @return DxFile_Thumbnail
     */
    public function setFillTransparent($is_transparent)
    {
        $this->fill_transparent = $is_transparent;
        return $this;
    }

    /**
     * @return string
     */
    public function getFillTransparent()
    {
        return $this->fill_transparent;
    }

    /**
     * @param $quality
     * @return DxFile_Thumbnail
     */
    public function setThumbQuality($quality)
    {
        $this->thumb_quality = $quality;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbQuality()
    {
        return $this->thumb_quality;
    }

    /**
     * @return null|string
     */
    public function isExists()
    {
        $thumb_path = $this->getThumbPath();
        if (file_exists($thumb_path)) {
            return $thumb_path;
        }
        return null;
    }

    /**
     * @return string
     * @throws DxException
     */
    public function getThumbPath()
    {
        if (empty($this->thumb_width) && empty($this->thumb_height)) {
            throw new DxException("Not set width and height", self::ERROR_INVALID_VALUE);
        }

        $image_path = DxFile::makeFullPath($this->image_path);
        $files_dir  = DxFile::makeFullPath($this->files_dir);
        $thumbs_dir = DxFile::makeFullPath($this->thumbs_dir);

        if (!is_dir($files_dir) || !file_exists($files_dir)) {
            throw new DxException("The path to files does not exist: {$files_dir}", self::ERROR_PATH_NOT_FOUND);
        }

        if (!is_dir($thumbs_dir) || !file_exists($thumbs_dir)) {
            throw new DxException("The path to thumbs does not exist: {$thumbs_dir}", self::ERROR_PATH_NOT_FOUND);
        }

        $image_rel_path = str_replace($files_dir, '', $image_path);

        $_image_rel_path = explode(DS, $image_rel_path);
        $image_name    = end($_image_rel_path);
        $image_rel_dir = trim(dirname($image_rel_path), "/\\");

        $thumb_dir  = self::cleanPath($thumbs_dir . DS . $image_rel_dir . DS . "{$this->thumb_width}x{$this->thumb_height}", DS);
        self::createDir($thumb_dir);

        return $thumb_dir . DS . $image_name;
    }

    /**
     * @return mixed|null
     * @throws DxException
     */
    public function getThumb()
    {
        if (is_null($this->thumb_width) || is_null($this->thumb_height)) {
            $this->master = self::MASTER_RESIZE;
        }    
    
        $thumb_path = $this->getThumbPath();
        $image_path = DxFile::makeFullPath($this->image_path);

        if (!file_exists($image_path)) {
            return $thumb_path;
        }

        /** @var $img DxFile_Image */
        $img = DxFactory::invoke('DxFile_Image', 'createByPath', array($image_path));

        if ($this->master == self::MASTER_RESIZE) {
            $img->resize($this->thumb_width, $this->thumb_height, DxFile_Image::RESIZE_AUTO);
        } elseif ($this->master == self::MASTER_CROP_AUTO) {
            $img->resize($this->thumb_width, $this->thumb_height, DxFile_Image::RESIZE_INVERSE);
            $img->crop($this->thumb_width, $this->thumb_height, $this->crop_types[$this->crop][0], $this->crop_types[$this->crop][1]);
        } elseif ($this->master == self::MASTER_CROP_PRECISE) {
            $img->crop($this->thumb_width, $this->thumb_height, $this->crop_types[$this->crop][0], $this->crop_types[$this->crop][1]);
        } else {
            throw new DxException("Unknown to the master preview", self::ERROR_INVALID_VALUE);
        }

        $background_color = $this->fill_color;
        $opacity = $this->fill_transparent && in_array($img->type, array(IMAGETYPE_GIF, IMAGETYPE_PNG)) ? 0 : 100;

        $img->background($background_color, $opacity, $this->thumb_width, $this->thumb_height);


        if (!is_null($this->watermark_path)) {
            $img->watermark($this->watermark_path);
        }

        $img->commit($thumb_path, $this->thumb_quality);

        return $thumb_path;
    }

    /**
     * @return bool
     */
    public function cleanThumbs()
    {
        $image_path = DxFile::makeFullPath($this->image_path);
        $files_dir  = DxFile::makeFullPath($this->files_dir);
        $thumbs_dir = DxFile::makeFullPath($this->thumbs_dir);

        $image_rel_path = str_replace($files_dir, '', $image_path);

        $image_name    = (end(explode(DS, $image_rel_path)));
        $image_rel_dir = trim(dirname($image_rel_path), "/\\");

        $thumbs_sub_dir = self::cleanPath($thumbs_dir . DS . $image_rel_dir, DS);
        if (file_exists($thumbs_sub_dir)) {
            $dirs = self::readDir($thumbs_sub_dir, false);
            foreach ($dirs as $dir) {
                if (is_dir($dir)) {
                    $file = self::cleanPath($dir . DS . $image_name, DS);
                    self::removeFile($file);
                }
            }
        }
        return true;
    }
}