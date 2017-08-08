<?php
DxFactory::import('DxController_Frontend');
DxFactory::import('DxFile');
DxFactory::import('DxFile_Upload');

class DxController_Frontend_Uploader extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.uploader' => 'index',
    );

    /**
     * @return string
     */
    protected function index()
    {
        $op = $this->getContext()->getCurrentCommand()->getArguments('op');

        $res = null;

        if ($op === 'feedback-hotel') {
            $res = $this->uploadFeedbackHotel();
        }

        if (empty($res)) {
            $res = array('code' => 'NONE');
        }

        return json_encode($res);
    }

    protected function uploadFeedbackHotel()
    {
        $res = array(
            'code' => 'ERROR',
        );

        DxFactory::import('Utils_NameMaker');

        if (empty($_FILES['files'])) {
            return $res;
        }

        $res['code'] = 'OK';

        foreach ($_FILES['files']['tmp_name'] as $k => $file) {
            $dst_name = time() . '_' . $_FILES['files']['name'][$k];

            $_files[] = array(
                'src_path' => $file,
                'src_name' => $_FILES['files']['name'][$k],
                'dst_name' => Utils_NameMaker::modifyFileName($dst_name),
            );
        }

        $config = DxApp::config('url', 'static');
        $full_files_path = DxFile::makeFullPath($config['files']);
        $full_files_path = DxFile::cleanPath($full_files_path . DS .'upload'. DS . date('Y') . DS . date('m'), DS);
        DxFile::createDir($full_files_path);
        $new_files = DxFile_Upload::createByRequest($_files, $full_files_path);

        foreach ($new_files as $k => $file) {
            $relative = $file->makeRelativePath($file->getFullPath());

            $relative = explode('/', $relative);
            $filename = end($relative);

            try {
                $image = DxFactory::invoke('DxFile_Image', 'createByPath', array($file->getFullPath()));

                $image->resize(1000, null, DxFile_Image::RESIZE_WIDTH);

                $image->commit($file->getFullPath(), 75);
            } catch (DxException $e) {
                $res['files'][$_FILES['files']['name'][$k]]['code'] = 'ERROR';

                $file->removeFile($file->getFullPath());

                continue;
            }

            $res['files'][$_FILES['files']['name'][$k]] = array(
                'code'      => 'OK',
                'name'      => $filename,
                'full_path' => str_replace('\\', '/', $file->getRelativePath($file->getFullPath())),
            );
        }

        return $res;
    }
}