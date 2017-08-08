<?php

dxFactory::import('I18n');

class I18n_Project extends I18n
{
    /**
     * @param $source
     * @param $target
     * @return bool
     */
    protected function load($source, $target)
    {
        if (isset($this->haystack["{$source}_{$target}"])) return false;

        /** @var $q DomainObjectQuery_I18n */
        $q = DxFactory::getSingleton('DomainObjectQuery_I18n');
        $res = $q->getHaystack($source, $target);

        foreach ($res as $line) {
            if (!empty($line['i18n_target_string'])) {
                $key = empty($line['i18n_source_tag']) ? $line['i18n_source_string'] : $line['i18n_source_tag'];
                $this->haystack["{$source}_{$target}"][$key] = $line['i18n_target_string'];
            }
        }

        return true;
    }

    /**
     * @param $string
     * @param $tag
     * @param $source
     * @param $target
     * @return bool
     */
    protected function push($string, $tag, $source, $target)
    {
        if (empty($string) && empty($tag)) return false;

        /** @var $q DomainObjectQuery_I18n */
        $q = DxFactory::getSingleton('DomainObjectQuery_I18n');
        if (!empty($tag)) {
            $line = $q->findByTag($tag, $source, $target);
        } else {
            $line = $q->findByString($string, $source, $target);
        }

        if (!empty($line)) return false;

        /** @var $line DomainObjectModel_I18n */
        $line = DxFactory::getInstance('DomainObjectModel_I18n');
        $line->setSourceLocale($source);
        $line->setSourceString($string);
        $line->setSourceTag(empty($tag) ? null : $tag);
        $line->setTargetLocale($target);
        $line->save();

        return true;
    }

    /**
     * @param $locale
     * @return bool
     */
    public function setBackendLocale($locale)
    {
        $locale = $this->getValidLocale($locale);
        if (is_null($locale)) {
            return false;
        }

        $_SESSION['DX.BACKEND_LANG'] = $locale;
        return false;
    }

    /**
     * @return null
     */
    public function getBackendLocale()
    {
        return empty($_SESSION['DX.BACKEND_LANG']) ? null : $_SESSION['DX.BACKEND_LANG'];
    }
}