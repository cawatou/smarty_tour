<?php

DxFactory::import('DxFile');

class DxFile_Upload
{
    /**
     * @static
     * @param array $files
     * @param $dst_dir
     * @return array
     * @throws DxException
     *
     * $files[] = array(
     *     'src_path' => $_FILES['input_name']['tmp_name'], // /tmp/php5Wx0aJ
     *     'src_name' => $_FILES['input_name']['name'],     // 400.png
     *     'dst_name' => 'example.png',
     * );
     */
    public static function createByRequest(array $files, $dst_dir)
    {
        $dst_dir = DxFile::makeFullPath($dst_dir);
        DxFile::createDir($dst_dir);

        $result = array();

        // Fix for possible exception and/or warning
        if(!is_writeable($dst_dir)) {
            return $result;
        }

        foreach ($files as $file) {
            if (empty($file['src_name']) && empty($file['src_path'])) {
                continue;
            }

            if (empty($file['dst_name'])) {
                $path_info = pathinfo($file['src_name']);
                $file['dst_name'] = self::generateUniqueName() . (empty($path_info['extension']) ? '' : ".{$path_info['extension']}");
            }

            $file['dst_path'] = $dst_dir . DS . $file['dst_name'];

            if (move_uploaded_file($file['src_path'], $file['dst_path']) === false) {
                throw new DxException('Cannot be moved for some reason');
            }

            $file = new DxFile($dst_dir, $file['dst_name']);
            $result[] = $file;
        }

        return $result;
    }

    /**
     * @static
     * @return string
     */
    public static function generateUniqueName()
    {
        return md5(uniqid());
    }
}