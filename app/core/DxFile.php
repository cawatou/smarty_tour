<?php

class DxFile
{
    /**
     * Path to file does not include the file name
     * @var string
     */
    protected $fpath;

    /**
     * File name
     * @var string
     */
    protected $fname;

    /**
     * @param string $fpath
     * @param string $fname
     */
    public function __construct($fpath, $fname)
    {
        $this->fpath = $fpath;
        $this->fname = $fname;
    }

    /**
     * @return string
     */
    public function getRelativePath()
    {
        return self::makeRelativePath($this->getFullPath());
    }

    /**
     * @return string
     */
    public function getFullPath()
    {
        return $this->fpath . DS . $this->fname;
    }

    /**
     * @return void
     */
    public function remove()
    {
        $path = $this->getFullPath();
        if (is_dir($path)) {
            self::removeDir($path);
        } elseif (is_file($path)) {
            self::removeFile($path);
        }
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return filesize($this->getFullPath());
    }

    /**
     * @return string
     */
    public function getType()
    {
        $path_info = pathinfo($this->getFullPath());
        return empty($path_info['extension']) ? '' : $path_info['extension'];
    }

    /**
     * @static
     * @param $src_path
     * @internal param string $path
     * @return DxFile
     * @throws DxException
     */
    public static function createByPath($src_path)
    {
        $path = self::makeFullPath($src_path);
        if (!is_dir($path) && !is_file($path)) {
            throw new DxException('Invalid path');
        }

        if (is_file($path)) {
            $path_info = pathinfo($path);
            return new DxFile($path_info['dirname'], $path_info['basename']);
        }
    }

    /**
     * @static
     * @param $path
     * @return mixed
     */
    public static function makeRelativePath($path)
    {
        $path = self::cleanPath($path, DS);
        return str_replace(ROOT, '', $path);
    }

    /**
     * @static
     * @param $path
     * @return mixed
     */
    public static function makeFullPath($path)
    {
        $path = self::cleanPath($path, DS);
        return str_replace(DS . DS, DS, ROOT . DS . str_replace(ROOT, '', $path));
    }

    /**
     * @static
     * @param $path
     * @param string $ds
     * @return mixed
     */
    public static function cleanPath($path, $ds = '/')
    {
        return rtrim(preg_replace('~\/+~', $ds, str_replace('\\', '/', $path)), $ds);
    }

    /**
     * @static
     * @param $path
     * @param array $stop_load
     * @return array
     * @throws DxException
     */
    public static function readDirFiles($path, $stop_load = array())
    {
        $path = self::makeFullPath($path);

        if (!is_dir($path)) {
            throw new DxException("Path does not exist '{$path}'");
        }

        $files = array();
        if ($objects = scandir($path)) {
            foreach ($objects as $object) {
                $subpath = $path . DS . $object;
                if (!in_array($object, $stop_load)) {
                    $stat = stat($subpath);
                    if (is_dir($subpath)) {
                        $tmp = array(
                            'type' => 'DIR',
                            'mode' => substr(decoct($stat['mode']), 1)
                        );
                    } else {
                        $path_info = pathinfo($subpath);
                        $tmp       = array(
                            'type'      => 'FILE',
                            'file_name' => $path_info['filename'],
                            'file_ext'  => empty($path_info['extension']) ? '' : $path_info['extension'],
                            'size'      => $stat['size'],
                            'mode'      => substr(decoct($stat['mode']), 2),
                            'is_img'    => !empty($path_info['extension']) && in_array(strtolower($path_info['extension']), array('jpg', 'jpeg', 'gif', 'png')),
                        );
                    }
                    $tmp['name'] = $object;
                    $tmp['path'] = $subpath;
                    $tmp['time'] = $stat['mtime'];

                    $files["{$tmp['type']}_" . (90000000000 - $stat['mtime']) . "_{$object}"] = $tmp;
                }
            }
        }

        ksort($files);
        return array_values($files);
    }

    /**
     * @static
     * @param $path
     * @param array $stop_load
     * @return array
     */    
    public static function readDirTree($path, $stop_load = array())
    {
        $path = self::makeFullPath($path);
        $dirs = array();
        if ($objects = scandir($path)) {
            foreach ($objects as $object) {
                $subpath = $path . DS . $object;
                if (is_dir($subpath) && !in_array($object, $stop_load)) {
                    $tmp    = array(
                        'name'  => $object,
                        'path'  => $subpath,
                    );
                    $dirs[] = $tmp;
                    $dirs   = array_merge($dirs, self::readDirTree($subpath, $stop_load));
                }
            }
        }
        return $dirs;
    }

    /**
     * @static
     * @param $path
     * @param bool $recursive
     * @return array
     */    
    public static function readDir($path, $recursive = true)
    {
        $path  = self::makeFullPath($path);
        $items = array();
        if ($objects = scandir($path)) {
            foreach ($objects as $object) {
                $subpath = $path . DS . $object;
                if ($object != '.' && $object != '..') {
                    if (is_dir($subpath) && $recursive) {
                        $items = array_merge($items, self::readDir($subpath, $recursive));
                    }
                    $items[] = self::cleanPath($subpath, DS);
                }
            }
        }
        return $items;
    }


    /**
     * @static
     * @param $path
     * @param int $mode
     * @return bool
     */
    public static function createDir($path, $mode = 0777)
    {
        $path = self::cleanPath($path, DS);
        if (!file_exists($path)) {
            $oldumask = umask(0);
            $res      = mkdir($path, $mode, true);
            umask($oldumask);
            return $res;
        }
        return null;
    }

    /**
     * @static
     * @param string $path
     * @return bool
     */
    public static function removeDir($path)
    {
        $path = self::cleanPath($path, DS);
        if (!is_dir($path)) {
            return false;
        }

        self::clearDir($path, true);
        return rmdir($path);
    }

    /**
     * @static
     * @param $dir
     * @param bool $recursive
     */
    public static function clearDir($dir, $recursive = false)
    {
        foreach (self::readDir($dir, $recursive) as $file) {
            if (is_file($file)) {
                unlink($file);
            } elseif (is_dir($file)) {
                rmdir($file);
            }
        }
    }

    /**
     * @static
     * @param string $file
     * @return bool
     */
    public static function removeFile($file)
    {
        $file = self::cleanPath($file, DS);
        if (!is_file($file)) {
            return false;
        }
        return unlink($file);
    }

    /**
     * @static
     * @param $src_path
     * @param $dst_path
     * @return bool
     */
    public static function renameDirOrFile($src_path, $dst_path)
    {
        return rename(DxFile::cleanPath($src_path, DS), DxFile::cleanPath($dst_path, DS));
    }

    /**
     * @static
     * @param $src_path
     * @param int    $mode
     * @param bool   $recursive
     * @internal param string $dir
     * @return bool
     */
    public static function changeMode($src_path, $mode = 0777, $recursive = false)
    {
		$src_path = DxFile::cleanPath($src_path, DS);
        $res = @chmod($src_path, $mode);
		if (is_dir($src_path) && $recursive) {		
			$objects = self::readDir($src_path, true);
			foreach ($objects as $object) {
				@chmod($object, $mode);
			}
		}
		return $res;
    }

    /**
     * @static
     * @param $size
     * @param null $sizes
     * @param null $format
     * @return array
     */    
    public static function sizeReadable($size, $sizes = null, $format = null)
    {
        if ($sizes === null) {
            $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        }

        if ($format === null) {
            $format = '%01.2f';
        }

        $i = 0;
        $current_size = 1024;
        while ($current_size < $size) {
            $i++;
            $current_size *= 1024;
        }

        return array(
            'size'          => $i > 0 ? sprintf($format, $size / ($current_size / 1024)) : $size,
            'size_in_bytes' => $size,
            'size_unit'     => $sizes[$i]
        );
    }
}