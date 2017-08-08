<?php
/**
 * Support for image manipulation using [Imagick](http://php.net/Imagick).
 */
DxFactory::import('DxFile_Image');

class DxFile_Image_Imagick extends DxFile_Image
{
    /**
     * @var  Imagick  image magick object
     */
    protected $im = null;

    /**
     * Destroys the loaded image to free up resources.
     *
     * @return  void
     */
    public function __destruct()
    {
        $this->clearImage();
    }

    /**
     * @return Imagick|null
     */
    public function readImage()
    {
        if (is_null($this->im)) {
            $this->im = new Imagick;
            $this->im->readImage($this->getFullPath());
            if (!$this->im->getImageAlphaChannel()) {
                // Force the image to have an alpha channel
                $this->im->setImageAlphaChannel(Imagick::ALPHACHANNEL_SET);
            }
        }
        return $this->im;
    }

    /**
     *
     */
    protected function clearImage()
    {
        if (!is_null($this->im)) {
            $this->im->clear();
            $this->im->destroy();
            $this->im = null;
        }
    }

    /**
     * @param $dst_path
     * @param $quality
     * @return bool
     * @throws DxException
     */
    protected function doCommit($dst_path, $quality)
    {
        // Loads image if not yet loaded
        $this->readImage();

        $dst_path = $this->makeFullPath($dst_path);

        // Get the image format and type
        $extension = strtolower(pathinfo($dst_path, PATHINFO_EXTENSION));
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $format = 'jpg';
                $type = IMAGETYPE_JPEG;
            break;
            case 'gif':
                $format = 'gif';
                $type = IMAGETYPE_GIF;
            break;
            case 'png':
                $format = 'png';
                $type = IMAGETYPE_PNG;
            break;
            default:
                throw new DxException("Installed ImageMagick does not support {$extension} images", DxFile_Image::ERROR_IMAGE_PROCESS);
            break;
        }

        // Set the output image type
        $this->im->setFormat($format);

        // Set the output quality
        $this->im->setImageCompressionQuality($quality);

        if ($this->im->writeImage($dst_path)) {
            $this->type = $type;
            $this->mime = image_type_to_mime_type($type);
            return true;
        }

        return false;
    }

    /**
     * @param $width
     * @param $height
     * @return bool
     */
    protected function doResize($width, $height)
    {
        // Loads image if not yet loaded
        $this->readImage();

        if ($this->im->scaleImage($width, $height)) {
            // Reset the width and height
            $this->width = $this->im->getImageWidth();
            $this->height = $this->im->getImageHeight();
            return true;
        }

        return false;
    }

    /**
     * @param $width
     * @param $height
     * @param $offset_x
     * @param $offset_y
     * @return bool
     */
    protected function doCrop($width, $height, $offset_x, $offset_y)
    {
        // Loads image if not yet loaded
        $this->readImage();

        if ($this->im->cropImage($width, $height, $offset_x, $offset_y)) {
            // Reset the width and height
            $this->width = $this->im->getImageWidth();
            $this->height = $this->im->getImageHeight();

            // Trim off hidden areas
            $this->im->setImagePage($this->width, $this->height, 0, 0);
            return true;
        }

        return false;
    }

    /**
     * @param DxFile_Image $watermark
     * @param $offset_x
     * @param $offset_y
     * @param $opacity
     * @return bool
     */
    protected function doWatermark(DxFile_Image $watermark, $offset_x, $offset_y, $opacity)
    {
        // Loads image if not yet loaded
        $this->readImage();

        $watermark = $watermark->readImage();
        if ($watermark->getImageAlphaChannel() !== Imagick::ALPHACHANNEL_ACTIVATE) {
            // Force the image to have an alpha channel
            $watermark->setImageAlphaChannel(Imagick::ALPHACHANNEL_OPAQUE);
        }

        if ($opacity < 100) {
            // NOTE: Using setImageOpacity will destroy current alpha channels!
            $watermark->evaluateImage(Imagick::EVALUATE_MULTIPLY, $opacity / 100, Imagick::CHANNEL_ALPHA);
        }

        // Match the colorspace between the two images before compositing
        // $watermark->setColorspace($this->im->getColorspace());

        // Apply the watermark to the image
        if ($this->im->compositeImage($watermark, Imagick::COMPOSITE_DISSOLVE, $offset_x, $offset_y)) {
            return true;
        }
        return false;
    }

    /**
     * @param $r
     * @param $g
     * @param $b
     * @param $opacity
     * @param null $width
     * @param null $height
     * @return bool
     */
    protected function doBackground($r, $g, $b, $opacity, $width = null, $height = null)
    {
        // Loads image if not yet loaded
        $this->readImage();

        $width = empty($width) || $width < $this->width ? $this->width : $width;
        $height = empty($height) || $height < $this->height ? $this->height : $height;

        // Create a RGB color for the background
        $color = sprintf('rgb(%d, %d, %d)', $r, $g, $b);

        // Create a new image for the background
        $background = new Imagick;
        $background->newImage($width, $height, new ImagickPixel($color));

        if (!$background->getImageAlphaChannel()) {
            // Force the image to have an alpha channel
            $background->setImageAlphaChannel(Imagick::ALPHACHANNEL_SET);
        }

        // Clear the background image
        $background->setImageBackgroundColor(new ImagickPixel('transparent'));

        // NOTE: Using setImageOpacity will destroy current alpha channels!
        $background->evaluateImage(Imagick::EVALUATE_MULTIPLY, $opacity / 100, Imagick::CHANNEL_ALPHA);

        // Match the colorspace between the two images before compositing
        $background->setColorspace($this->im->getColorspace());

        $top  = abs($width - $this->width) / 2;
        $left = abs($height - $this->height) / 2;

        if ($background->compositeImage($this->im, Imagick::COMPOSITE_DISSOLVE, $top, $left)) {
            // Replace the current image with the new image
            $this->im = $background;
            return true;
        }

        return false;
    }
}