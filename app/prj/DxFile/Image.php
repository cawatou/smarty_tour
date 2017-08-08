<?php

DxFactory::import('DxFile');

abstract class DxFile_Image extends DxFile
{
    // Errors constants
    const ERROR_IMAGE_NOT_FOUND   = 2001;
    const ERROR_IMAGE_UNSUPPORTED = 2002;
    const ERROR_IMAGE_LOAD        = 2003;
    const ERROR_IMAGE_SAVE        = 2004;
    const ERROR_IMAGE_PROCESS     = 2005;

    // Resizing constraints
    const RESIZE_NONE    = 1;
    const RESIZE_WIDTH   = 2;
    const RESIZE_HEIGHT  = 3;
    const RESIZE_AUTO    = 4;
    const RESIZE_INVERSE = 5;
    const RESIZE_PRECISE = 6;

    // Position constraints
    const POSITION_TOP    = 1;
    const POSITION_MIDDLE = 2;
    const POSITION_BOTTOM = 3;
    const POSITION_LEFT   = 4;
    const POSITION_CENTER = 5;
    const POSITION_RIGHT  = 6;

    static protected $supported_types = array(
        IMAGETYPE_GIF,
        IMAGETYPE_JPEG,
        IMAGETYPE_PNG,
    );

    /**
     * @var  integer  image width
     */
    public $width;

    /**
     * @var  integer  image height
     */
    public $height;

    /**
     * @var  integer  one of the IMAGETYPE_* constants
     */
    public $type;

    /**
     * @var  string  mime type of the image
     */
    public $mime;

    /**
     * @param string $fpath
     * @param string $fname
     * @throws DxException
     */
    public function __construct($fpath, $fname)
    {
        parent::__construct($fpath, $fname);

        if (!file_exists($this->getFullPath())) {
            throw new DxException("File '{$this->getFullPath()}' not exists", self::ERROR_IMAGE_NOT_FOUND);
        }

        if (($info = @getimagesize($this->getFullPath())) === false) {
            throw new DxException("Not an image: '{$this->getFullPath()}'", self::ERROR_IMAGE_LOAD);
        }

        if (!in_array($info[2], self::$supported_types)) {
            throw new DxException("Image type is not supported '{$this->getFullPath()}'", self::ERROR_IMAGE_UNSUPPORTED);
        }

       // Store the image information
       $this->width  = $info[0];
       $this->height = $info[1];
       $this->type   = $info[2];
       $this->mime   = image_type_to_mime_type($this->type);
    }

    /**
     * @static
     * @return bool
     */
    protected static function imagickIsAvailable()
    {
        return extension_loaded('imagick') && class_exists('Imagick');
    }

    /**
     * @static
     * @return bool
     */
    protected static function gdIsAvailable()
    {
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            //echo "GD is either not installed or not enabled, check your configuration";
            return false;
        }

        if (defined('GD_VERSION')) {
            // Get the version via a constant, available in PHP 5.2.4+
            $version = GD_VERSION;
        } else {
            // Get the version information
            $info = gd_info();
            // Extract the version number
            preg_match('/\d+\.\d+(?:\.\d+)?/', $info['GD Version'], $matches);
            // Get the major version
            $version = $matches[0];
        }

        if (!version_compare($version, '2.0.1', '>=')) {
            //echo "Image_GD requires GD version 2.0.1 or greater, you have {$version}";
            return false;
        }

        return true;
    }

    /**
     * @static
     * @param $image_path
     * @return DxFile_Image
     * @throws DxException
     */
    public static function createByPath($image_path)
    {
        $image_path = self::makeFullPath($image_path);
        $path_info = pathinfo($image_path);

        if (self::imagickIsAvailable()) {
            DxFactory::import('DxFile_Image_Imagick');
            return new DxFile_Image_Imagick($path_info['dirname'], $path_info['basename']);
        } elseif (self::gdIsAvailable()) {
            DxFactory::import('DxFile_Image_GD');
            return new DxFile_Image_Gd($path_info['dirname'], $path_info['basename']);
        } else {
            throw new DxException("Imagick or GD2 is not istalled, or the extensions is not loaded", self::ERROR_IMAGE_PROCESS);
        }
    }

    /**
     * @param null $width_min
     * @param null $height_min
     * @param null $width_max
     * @param null $height_max
     * @return bool
     * @throws DxException
     */
    public function checkSize($width_min = null, $height_min = null, $width_max = null, $height_max = null)
    {
        if (!is_null($width_min) && $this->width < $width_min || !is_null($width_max) && $this->width > $width_max) {
            throw new DxException("Width of image is invalid", self::ERROR_IMAGE_PROCESS);
        } elseif (!is_null($height_min) && $this->height < $height_min || !is_null($height_max) && $this->height > $height_max) {
            throw new DxException("Height of image is invalid", self::ERROR_IMAGE_PROCESS);
        }
        return true;
    }

    /**
     * @param $weight_max
     * @return bool
     * @throws DxException
     */
    public function checkWeight($weight_max)
    {
        if (filesize($this->getFullPath()) > $weight_max) {
            throw new DxException("Image is too heavy", self::ERROR_IMAGE_PROCESS);
        }
        return true;
    }

    /**
     * @param null $dst_path
     * @param int $quality
     * @return mixed
     */
    public function commit($dst_path = null, $quality = 100)
    {
        if (is_null($dst_path)) {
            // Overwrite the file
            $dst_path = $this->getFullPath();
        } else {
            $dst_path = $this->makeFullPath($dst_path);
        }

        // The quality must be in the range of 1 to 100
        $quality = min(max($quality, 1), 100);

        return $this->doCommit($dst_path, $quality);
    }

    abstract protected function doCommit($dst_path, $quality);

    /**
     * @param $width
     * @param null $height
     * @param null $master
     * @return bool
     * @throws DxException
     */
    public function resize($width = null, $height = null, $master = null)
    {
        if (empty($width) && empty($height)) {
            return false;
        }

        if (is_null($master)) {
            // Choose the master dimension automatically
            $master = self::RESIZE_AUTO;
        } elseif ($master === self::RESIZE_WIDTH && !empty($width)) {
            $master = self::RESIZE_AUTO;
            $height = null;
        } elseif ($master === self::RESIZE_HEIGHT && !empty($height)) {
            $master = self::RESIZE_AUTO;
            $width = null;
        }

        if (empty($width)) {
            if ($master === self::RESIZE_NONE) {
                $width = $this->width;
            } else {
                // If width not set, master will be height
                $master = self::RESIZE_HEIGHT;
            }
        }

        if (empty($height)) {
            if ($master === self::RESIZE_NONE) {
                $height = $this->height;
            } else {
                // If height not set, master will be width
                $master = self::RESIZE_WIDTH;
            }
        }

        switch ($master) {
            case self::RESIZE_AUTO:
                // Choose direction with the greatest reduction ratio
                $master = ($this->width / $width) > ($this->height / $height) ? self::RESIZE_WIDTH : self::RESIZE_HEIGHT;
            break;
            case self::RESIZE_INVERSE:
                // Choose direction with the minimum reduction ratio
                $master = ($this->width / $width) > ($this->height / $height) ? self::RESIZE_HEIGHT : self::RESIZE_WIDTH;
            break;
        }

        switch ($master) {
            case self::RESIZE_WIDTH:
                // Recalculate the height based on the width proportions
                $height = $this->height * $width / $this->width;
            break;
            case self::RESIZE_HEIGHT:
                // Recalculate the width based on the height proportions
                $width = $this->width * $height / $this->height;
            break;
            case self::RESIZE_PRECISE:
                // Resize to precise size
                $ratio = $this->width / $this->height;
                if ($width / $height > $ratio) {
                    $height = $this->height * $width / $this->width;
                } else {
                    $width = $this->width * $height / $this->height;
                }
            break;
        }

        // Convert the width and height to integers, minimum value is 1px
        $width  = max(round($width), 1);
        $height = max(round($height), 1);

        return $this->doResize($width, $height);
    }

    abstract protected function doResize($width, $height);

    /**
     * @param $width
     * @param $height
     * @param null $offset_x
     * @param null $offset_y
     */
    public function crop($width, $height, $offset_x = null, $offset_y = null)
    {
        if (null === $width || $width > $this->width) {
            // Use the current width
            $width = $this->width;
        }

        if (null === $height || $height > $this->height) {
            // Use the current height
            $height = $this->height;
        }

        if (is_null($offset_x) || $offset_x == self::POSITION_CENTER) {
            $offset_x = round(($this->width - $width) / 2);
        } elseif ($offset_x == self::POSITION_RIGHT) {
            $offset_x = $this->width - $width;
        } elseif ($offset_x == self::POSITION_LEFT) {
            $offset_x = 0;
        }

        if (is_null($offset_y) || $offset_y == self::POSITION_MIDDLE) {
            $offset_y = round(($this->height - $height) / 2);
        } elseif ($offset_y == self::POSITION_BOTTOM) {
            $offset_y = $this->height - $height;
        } elseif ($offset_y == self::POSITION_TOP) {
            $offset_y = 0;
        }

        return $this->doCrop($width, $height, $offset_x, $offset_y);
    }

    abstract protected function doCrop($width, $height, $offset_x, $offset_y);

    /**
     * @param DxFile_Image $watermark
     * @param null $offset_x
     * @param null $offset_y
     * @param int $opacity
     */
    public function watermark(DxFile_Image $watermark, $offset_x = null, $offset_y = null, $opacity = 100)
    {
        if (is_null($offset_x) || $offset_x == self::POSITION_CENTER) {
            $offset_x = round(($this->width - $watermark->width) / 2);
        } elseif ($offset_x == self::POSITION_RIGHT) {
            $offset_x = $this->width - $watermark->width;
        } elseif ($offset_x == self::POSITION_LEFT) {
            $offset_x = 0;
        }

        if (is_null($offset_y) || $offset_y == self::POSITION_MIDDLE) {
            $offset_y = round(($this->height - $watermark->height) / 2);
        } elseif ($offset_y == self::POSITION_BOTTOM) {
            $offset_y = $this->height - $watermark->height;
        } elseif ($offset_y == self::POSITION_TOP) {
            $offset_y = 0;
        }

        // The opacity must be in the range of 1 to 100
        $opacity = min(max($opacity, 1), 100);

        $this->doWatermark($watermark, $offset_x, $offset_y, $opacity);
    }

    abstract protected function doWatermark(DxFile_Image $watermark, $offset_x, $offset_y, $opacity);

    /**
     * @param $color
     * @param int $opacity
     * @param null $width
     * @param null $height
     */
    public function background($color, $opacity = 100, $width = null, $height = null)
    {
        if ($color[0] === '#') {
            // Remove the pound
            $color = substr($color, 1);
        }

        if (strlen($color) === 3) {
            // Convert shorthand into longhand hex notation
            $color = preg_replace('/./', '$0$0', $color);
        }

        // Convert the hex into RGB values
        list ($r, $g, $b) = array_map('hexdec', str_split($color, 2));

        // The opacity must be in the range of 0 to 100
        $opacity = min(max($opacity, 0), 100);

        return $this->doBackground($r, $g, $b, $opacity, $width, $height);
    }

    abstract protected function doBackground($r, $g, $b, $opacity, $width = null, $height = null);

    protected function rgbToHex($rgb)
    {
        $r = str_pad((string)dechex($rgb[0]), 2, '0', STR_PAD_LEFT);
        $g = str_pad((string)dechex($rgb[1]), 2, '0', STR_PAD_LEFT);
        $b = str_pad((string)dechex($rgb[2]), 2, '0', STR_PAD_LEFT);
        return "#" . $r . $g . $b;
    }

    protected function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
        return array($r, $g, $b);
    }
}