<?php
DxFactory::import('Utils');
DxFactory::import('DxException');

class Utils_UserLocator extends Utils
{
    protected $handle_cidr   = null;
    protected $handle_cities = null;

    protected $fsize_cidr   = null;
    protected $fsize_cities = null;

    public function __construct($file_cidr = false, $file_cities = false)
    {
        if (!$file_cidr) {
            $file_cidr = DX_VAR_DIR . DS .'ipgeobase'. DS .'cidr_optim.txt';
        }

        if (!$file_cities) {
            $file_cities = DX_VAR_DIR . DS .'ipgeobase'. DS .'cities.txt';
        }

        $this->handle_cidr   = @fopen($file_cidr, 'r');
        $this->handle_cities = @fopen($file_cities, 'r');

        if (!$this->handle_cidr) {
            throw new DxException('Can\'t open CIDR data file "'. $file_cidr .'"');
        }

        if (!$this->handle_cities) {
            throw new DxException('Can\'t open cities data file "'. $file_cidr .'"');
        }

        $this->fsize_cidr   = filesize($file_cidr);
        $this->fsize_cities = filesize($file_cities);
    }

    protected function getCityByIdx($idx)
    {
        rewind($this->handle_cities);

        while (!feof($this->handle_cities)) {
            $str = fgets($this->handle_cities);
            $arRecord = explode("\t", trim($str));

            if ($arRecord[0] == $idx) {
                return array(
                    'city'     => $arRecord[1],
                    'region'   => $arRecord[2],
                    'district' => $arRecord[3],
                    'lat'      => $arRecord[4],
                    'lng'      => $arRecord[5],
                );
            }
        }

        return false;
    }

    public function get($ip)
    {
        $ip = sprintf('%u', ip2long($ip));

        rewind($this->handle_cidr);

        $rad = floor($this->fsize_cidr / 2);
        $pos = $rad;

        while (fseek($this->handle_cidr, $pos, SEEK_SET) != -1) {
            if ($rad) {
                $str = fgets($this->handle_cidr);
            } else {
                rewind($this->handle_cidr);
            }

            $str = fgets($this->handle_cidr);

            if (!$str) {
                return null;
            }

            $arRecord = explode("\t", trim($str));

            $rad = floor($rad / 2);

            if (!$rad && ($ip < $arRecord[0] || $ip > $arRecord[1])) {
                return null;
            }

            if ($ip < $arRecord[0]) {
                $pos -= $rad;
            } elseif ($ip > $arRecord[1]) {
                $pos += $rad;
            } else {
                $result = array('range' => $arRecord[2], 'cc' => $arRecord[3]);

                if ($arRecord[4] != '-' && $cityResult = $this->getCityByIdx($arRecord[4])) {
                    $result += $cityResult;
                }

                foreach ($result as $k => $v)  {
                    $result[$k] = iconv('CP1251', 'UTF-8', $v);
                }

                return $result;
            }
        }

        return null;
    }
}