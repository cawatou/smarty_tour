<?php

/**
 * Internationalization (i18n) class. Provides language loading and translation
 * methods without dependencies on [gettext](http://php.net/gettext).
 *
 * To use in Smarty templates, use the following
 *
 *     Display a translated message
 *     {'Hello, world'|t}
 *     or
 *     {'Hello, world'|t:'tag.hello'}
 *
 *     With parameter replacement
 *     {'Hello, :user'|t:null:[':user'=>$username]};
 *
 */

abstract class I18n
{
    /** @var string    source language: en-US, es-ES, zh-CN as per RFC 3066 */
    protected $source = 'ru-RU';

    /** @var string    target language: en-US, es-ES, zh-CN as per RFC 3066 */
    protected $target = 'ru-RU';

    /** @var array    the list of supported languages */
    protected $supported = array('ru-RU');

    /** @var array    cache of loaded languages  */
    protected $haystack = array();

    /**
     * @param null|string $source
     * @param null|string $target
     */
    public function __construct($source = null, $target = null)
    {
        $this->setSource($source);
        $this->setTarget($target);
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param null|string $source
     * @return I18n
     */
    public function setSource($source = null)
    {
        $source = $this->getValidLocale($source);
        if (!is_null($source)) {
            $this->source = $source;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param null|string $target
     * @return I18n
     */
    public function setTarget($target = null)
    {
        $target = $this->getValidLocale($target);
        if (!is_null($target)) {
            $this->target = $target;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getSupported()
    {
        return $this->supported;
    }

    /**
     * @param $supported
     */
    public function setSupported($supported)
    {
        $this->supported = $supported;
    }

    /**
     * @param string $string
     * @param null|string $tag
     * @param array $values
     * @param null|string $source
     * @return string
     */
    public function translate($string, $tag = null, $values = array(), $source = null)
    {
        $string = $this->get($string, $tag, $source);
        return empty($values) ? $string : strtr($string, $values);
    }

    /**
     * @param $string
     * @param null|string $tag
     * @param null|string $source
     * @return string
     */
    public function get($string, $tag = null, $source = null)
    {
        $source = is_null($source) ? $this->source : $source;
        $needle = is_null($tag) ? $string : $tag;

        if ($source == $this->target) {
            return $string;
        }
        $this->load($source, $this->target);

        if (!isset($this->haystack["{$source}_{$this->target}"][$needle])) {
            $this->push($string, $tag, $source, $this->target);
            return $string;
        }
        return $this->haystack["{$source}_{$this->target}"][$needle];
    }

    /**
     * @param $locale
     * @return null|string
     */
    protected function getValidLocale($locale)
    {
        $locale = str_replace(array(' ', '_'), '-', trim($locale));
        if (empty($locale)) {
            return null;
        }

        $subtags = explode('-', $locale);
        if (count($subtags) != 2) {
            return null;
        }

        $locale = strtolower($subtags[0]) . '-' . strtoupper($subtags[1]);
        if (!in_array($locale, $this->getSupported())) {
            return null;
        }

        return $locale;
    }

    /**
     * @abstract
     * @param $source
     * @param $target
     */
    abstract protected function load($source, $target);

    /**
     * @abstract
     * @param $string
     * @param $tag
     * @param $source
     * @param $target
     */
    abstract protected function push($string, $tag, $source, $target);
}