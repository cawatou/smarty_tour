<?php

DxFactory::import('Utils');

class Utils_NameMaker extends Utils
{
    /**
     * @static
     * @param string $str
     * @param bool $tolower
     * @return string
     */
    public static function cyrillicToLatin($str, $tolower = false)
    {
        $trans_table = self::getTranslateTable();
        $str         = str_replace(array_keys($trans_table), array_values($trans_table), $str);

        $str = self::clean($str);

        if ($tolower) {
            $str = strtolower($str);
        }

        return $str;
    }

    /**
     * @static
     * @param string $str
     * @return string
     */
    protected static function clean($str)
    {
        $str = preg_replace('/\^/si', '', $str);
        $str = preg_replace('/[^\w\d]/si', '-', $str);
        $str = preg_replace('/-+/si', '-', $str);
        $str = preg_replace('/-+$/si', '', $str);
        $str = preg_replace('/^-+/si', '', $str);
        return $str;
    }

    /**
     * @static
     * @return array
     */
    protected static function getTranslateTable()
    {
        return array(
            'а'  => 'a',
            'б'  => 'b',
            'в'  => 'v',
            'г'  => 'g',
            'д'  => 'd',
            'е'  => 'e',
            'ё'  => 'e',
            'ж'  => 'zh',
            'з'  => 'z',
            'и'  => 'i',
            'й'  => 'i',
            'к'  => 'k',
            'л'  => 'l',
            'м'  => 'm',
            'н'  => 'n',
            'о'  => 'o',
            'п'  => 'p',
            'р'  => 'r',
            'с'  => 's',
            'т'  => 't',
            'у'  => 'u',
            'ф'  => 'f',
            'х'  => 'h',
            'ц'  => 'c',
            'ч'  => 'ch',
            'ш'  => 'sh',
            'щ'  => 'shch',
            'ы'  => 'y',
            'э'  => 'e',
            'ю'  => 'yu',
            'я'  => 'ya',
            'ъ'  => '^',
            'ь'  => '^',
            'А'  => 'A',
            'Б'  => 'B',
            'В'  => 'V',
            'Г'  => 'G',
            'Д'  => 'D',
            'Е'  => 'E',
            'Ё'  => 'E',
            'Ж'  => 'Zh',
            'З'  => 'Z',
            'И'  => 'I',
            'Й'  => 'I',
            'К'  => 'K',
            'Л'  => 'L',
            'М'  => 'M',
            'Н'  => 'N',
            'О'  => 'O',
            'П'  => 'P',
            'Р'  => 'R',
            'С'  => 'S',
            'Т'  => 'T',
            'У'  => 'U',
            'Ф'  => 'F',
            'Х'  => 'H',
            'Ц'  => 'C',
            'Ч'  => 'Ch',
            'Ш'  => 'Sh',
            'Щ'  => 'Shch',
            'Ы'  => '^',
            'Э'  => 'E',
            'Ю'  => 'Yu',
            'Я'  => 'Ya',
            'Ъ'  => '^',
            'Ь'  => '^',
        );
    }

    /**
     * @static
     * @param $name
     * @return mixed|null|string
     */
    public static function makeFileName($name)
    {
        if (empty($name)) {
            return null;
        }

        $name = preg_replace_callback('/(.{2})/i', array('Utils_NameMaker', 'rusCheck'), $name);
        $name = preg_replace_callback('/(.{1})/i', array('Utils_NameMaker', 'rusCheck'), $name);

        $name = self::clean($name);
        $name = strtolower($name);

        return $name;
    }

    /**
     * @static
     * @param array $matches
     * @return mixed
     */
    protected static function rusCheck($matches)
    {
        $trans_table = self::getTranslateTable();

        if (!empty($trans_table[$matches[1]])) {
            return $trans_table[$matches[1]];
        }

        return $matches[1];
    }

    /**
     * @param string $file_name
     * @return string
     * @static
     */
    public static function modifyFileName($file_name)
    {
        $path_info = pathinfo($file_name);
        $ext = mb_strtolower(empty($path_info['extension']) ? '' : ".{$path_info['extension']}");
        return self::makeFileName($path_info['filename']) . $ext;
    }
}