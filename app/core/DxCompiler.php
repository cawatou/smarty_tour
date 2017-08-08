<?php

class DxCompiler
{
    /**
     * @static
     * @param string $dir
     * @param string $cmpl_dir
     * @param string $extension
     * @throws DxException
     */
    public static function compile($dir, $cmpl_dir, $extension = 'php')
    {
        if (!function_exists('bcompiler_write_header')) {
            throw new DxException("Invalid PECL 'bcompiler' extension.");
        }

        $items = self::dirToArray($dir, true);

        foreach ($items as &$item) {
            if (is_dir($item) || !preg_match("~\.{$extension}$~", $item)) {
                continue;
            }

            $new_dirmame = str_replace($dir, $cmpl_dir, dirname($item));
            if (!is_dir($new_dirmame)) {
                mkdir($new_dirmame, 0777);
            }

            $fh = fopen(str_replace($dir, $cmpl_dir, $item), 'w');

            bcompiler_write_header($fh);
            bcompiler_write_file($fh, $item);
            bcompiler_write_footer($fh);

            fclose($fh);
        }
    }

    /**
     * @static
     * @param string $dir
     * @param bool   $recursive
     * @return array
     */
    public static function dirToArray($dir, $recursive = false)
    {
        $items = array();
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($dir . '/' . $file)) {
                        if ($recursive) {
                            $items = array_merge($items, self::dirToArray($dir . '/' . $file, $recursive));
                        }

                        $items[] = preg_replace('/\/\//si', '/', $file = $dir . '/' . $file);
                    } else {
                        $items[] = preg_replace('/\/\//si', '/', $file = $dir . '/' . $file);
                    }
                }
            }
            closedir($handle);
        }

        return $items;
    }

    /**
     * @static
     * @param string $dir
     * @param int $mode
     * @param bool $recursive
     */
    public static function changeMode($dir, $mode = 0777, $recursive = false)
    {
        chmod($dir, $mode);

        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($dir . '/' . $file) && $recursive) {
                        self::changeMode($dir . '/' . $file, $mode, $recursive);
                    }
                }
            }
            closedir($handle);
        }
    }
}