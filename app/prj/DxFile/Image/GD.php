<?php
/**
 * Support for image manipulation using [GD](http://php.net/GD).
 */
DxFactory::import('DxFile_Image');

class DxFile_Image_GD extends DxFile_Image {

	// Is GD bundled or separate?
	protected $bundled = null;

    // Temporary image resource
    protected $gd = null;

    /**
     * @param string $fpath
     * @param string $fname
     */
    public function __construct($fpath, $fname)
    {
        parent::__construct($fpath, $fname);

        if (defined('GD_BUNDLED')) {
            // Get the version via a constant, available in PHP 5.
            $this->bundled = GD_BUNDLED;
        } else {
            // Get the version information
            $info = gd_info();
            // Extract the bundled status
            $this->bundled = (bool) preg_match('/\bbundled\b/i', $info['GD Version']);
        }
    }

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
        if (is_null($this->gd)) {
            switch ($this->type) {
                case IMAGETYPE_JPEG:
                    $create = 'imagecreatefromjpeg';
                    break;
                case IMAGETYPE_GIF:
                    $create = 'imagecreatefromgif';
                    break;
                case IMAGETYPE_PNG:
                    $create = 'imagecreatefrompng';
                    break;
            }

            if (!isset($create) || !function_exists($create)) {
                throw new DxException('Installed GD does not support ' . image_type_to_extension($this->type, FALSE) . ' images', self::ERROR_IMAGE_LOAD);
            }

            // Open the temporary image
            $this->gd = $create($this->getFullPath());
            // Preserve transparency when saving
            imagesavealpha($this->gd, TRUE);
        }
        return $this->gd;
    }

    /**
     * Create an empty image with the given width and height.
     *
     * @param   integer   $width   image width
     * @param   integer   $height  image height
     * @return  resource
     */
    protected function newImage($width, $height)
    {
        // Create an empty image
        $image = imagecreatetruecolor($width, $height);

        // Do not apply alpha blending
        imagealphablending($image, false);

        // Save alpha levels
        imagesavealpha($image, true);

        return $image;
    }

    /**
     *
     */
    protected function clearImage()
    {
        if (is_resource($this->gd)) {
            // Free all resources
            imagedestroy($this->gd);
            $this->gd = null;
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
        $this->readImage();
        $dst_path = $this->makeFullPath($dst_path);

        // Get the extension of the file
        $extension = strtolower(pathinfo($dst_path, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                // Save a JPG file
                $save = 'imagejpeg';
                $type = IMAGETYPE_JPEG;
                break;
            case 'gif':
                // Save a GIF file
                $save = 'imagegif';
                $type = IMAGETYPE_GIF;

                // GIFs do not a quality setting
                $quality = null;
                break;
            case 'png':
                // Save a PNG file
                $save = 'imagepng';
                $type = IMAGETYPE_PNG;

                // Use a compression level of 9 (does not affect quality!)
                $quality = 9;
                break;
            default:
                throw new DxException("Installed GD does not support $extension images", DxFile_Image::ERROR_IMAGE_PROCESS);
                break;
        }

        // Save the image to a file
        $status = isset($quality) ? $save($this->gd, $dst_path, $quality) : $save($this->gd, $dst_path);

        if ($status) {
            // Reset the image type and mime type
            $this->type = $type;
            $this->mime = image_type_to_mime_type($type);
            return true;
        }

        return false;
    }

    /**
     * Execute a resize.
     *
     * @param $width
     * @param $height
     * @return bool
     */
	protected function doResize($width, $height)
	{
        // Loads image if not yet loaded
        $this->readImage();

		// Presize width and height
		$pre_width = $this->width;
		$pre_height = $this->height;

		// Test if we can do a resize without resampling to speed up the final resize
		if ($width > ($this->width / 2) && $height > ($this->height / 2)) {
			// The maximum reduction is 10% greater than the final size
			$reduction_width  = round($width  * 1.1);
			$reduction_height = round($height * 1.1);

			while ($pre_width / 2 > $reduction_width && $pre_height / 2 > $reduction_height) {
				// Reduce the size using an O(2n) algorithm, until it reaches the maximum reduction
				$pre_width /= 2;
				$pre_height /= 2;
			}

			// Create the temporary image to copy to
			$image = $this->newImage($pre_width, $pre_height);

			if (imagecopyresized($image, $this->gd, 0, 0, 0, 0, $pre_width, $pre_height, $this->width, $this->height))
			{
				// Swap the new image for the old one
				imagedestroy($this->gd);
				$this->gd = $image;
			}
		}

		// Create the temporary image to copy to
		$image = $this->newImage($width, $height);

		// Execute the resize
		if (imagecopyresampled($image, $this->gd, 0, 0, 0, 0, $width, $height, $pre_width, $pre_height)) {
			// Swap the new image for the old one
			imagedestroy($this->gd);
			$this->gd = $image;

			// Reset the width and height
			$this->width  = imagesx($image);
			$this->height = imagesy($image);

            return true;
		}

        return false;
	}

    /**
     * Execute a crop.
     *
     * @param $width
     * @param $height
     * @param $offset_x
     * @param $offset_y
     * @return bool
     */
	protected function doCrop($width, $height, $offset_x, $offset_y)
	{
		// Create the temporary image to copy to
		$image = $this->newImage($width, $height);

		// Loads image if not yet loaded
		$this->readImage();

		// Execute the crop
		if (imagecopyresampled($image, $this->gd, 0, 0, $offset_x, $offset_y, $width, $height, $width, $height)) {
			// Swap the new image for the old one
			imagedestroy($this->gd);
			$this->gd = $image;

			// Reset the width and height
			$this->width  = imagesx($image);
			$this->height = imagesy($image);

            return true;
		}

        return false;
	}

    /**
     * Execute a watermarking.
     *
     * @param DxFile_Image $watermark
     * @param $offset_x
     * @param $offset_y
     * @param $opacity
     * @return bool
     * @throws DxException
     */
	protected function doWatermark(DxFile_Image $watermark, $offset_x, $offset_y, $opacity)
	{
		if (!self::$bundled) {
			throw new DxException('This method requires imagelayereffect, which is only available in the bundled version of GD', self::ERROR_IMAGE_PROCESS);
		}

		// Loads image if not yet loaded
		$this->readImage();

		// Create the watermark image resource
		$overlay = $watermark->readImage();

		imagesavealpha($overlay, true);

		// Get the width and height of the watermark
		$width  = imagesx($overlay);
		$height = imagesy($overlay);

		if ($opacity < 100) {
			// Convert an opacity range of 0-100 to 127-0
			$opacity = round(abs(($opacity * 127 / 100) - 127));

			// Allocate transparent gray
			$color = imagecolorallocatealpha($overlay, 127, 127, 127, $opacity);

			// The transparent image will overlay the watermark
			imagelayereffect($overlay, IMG_EFFECT_OVERLAY);

			// Fill the background with the transparent color
			imagefilledrectangle($overlay, 0, 0, $width, $height, $color);
		}

		// Alpha blending must be enabled on the background!
		imagealphablending($this->gd, true);

		if (imagecopy($this->gd, $overlay, $offset_x, $offset_y, 0, 0, $width, $height)) {
			// Destroy the overlay image
			imagedestroy($overlay);
            return true;
		}

        return false;
	}

    /**
     * @param $r
     * @param $g
     * @param $b
     * @param $opacity
     * @param $width
     * @param $height
     */
	protected function doBackground($r, $g, $b, $opacity, $width = null, $height = null)
	{
		// Loads image if not yet loaded
		$this->readImage();

        $width = empty($width) || $width < $this->width ? $this->width : $width;
        $height = empty($height) || $height < $this->height ? $this->height : $height;

		// Convert an opacity range of 0-100 to 127-0
		$opacity = round(abs(($opacity * 127 / 100) - 127));

		// Create a new background
		$background = $this->newImage($width, $height);

		// Allocate the color
		$color = imagecolorallocatealpha($background, $r, $g, $b, $opacity);

		// Fill the image with white
		imagefilledrectangle($background, 0, 0, $width, $height, $color);

		// Alpha blending must be enabled on the background!
		imagealphablending($background, true);


        $top  = abs($width - $this->width) / 2;
        $left = abs($height - $this->height) / 2;

		// Copy the image onto a white background to remove all transparency
		if (imagecopy($background, $this->gd, $top, $left, 0, 0, $this->width, $this->height)) {
			// Swap the new image for the old one
			imagedestroy($this->gd);
			$this->gd = $background;

            return true;
		}

        return false;
	}
}
