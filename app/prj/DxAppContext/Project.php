<?php

DxFactory::import('DxAppContext');

class DxAppContext_Project extends DxAppContext
{
    const DX_APPCONTEXT_ERROR_UNKNOWN_METHOD = 401;

    /** @var DomainObjectModel_City */
    protected $user_city = null;

    /** @var array */
    private $css = array();

    /** @var array */
    private $js = array();

    /** @var string */
    private $city_session_id = 'user_city_id';

    public function __call($name, $arguments)
    {
        if (!preg_match("/^(set|get)[A-Z]\w+$/", $name)) {
            throw new DxException('Unknown method call', self::DX_APPCONTEXT_ERROR_UNKNOWN_METHOD);
        }

        $field = preg_replace('~([A-Z])~', '_\1', substr($name, 3));
        $field = preg_replace('~^_~', '', strtolower($field));

        if (strpos($name, 'set') !== false) {
            $this->data[$field] = $arguments[0];
        } else {
            return array_key_exists($field, $this->data) ? $this->data[$field] : null;
        }
    }

    public function __construct(array $params)
    {
        parent::__construct($params);

        if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
            $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
            $this->setData('uri', $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        } else {
            $this->setData('uri', '');
        }
    }

    /**
     * @param string|null $css
     * @return DxAppContext_Project
     */
    public function addCss($css = null)
    {
        if (!is_null($css)) {
            $this->css = array_merge($this->css, (array)$css);
        }

        return $this;
    }

    /**
     * @param string|null $css
     * @return DxAppContext_Project
     */
    public function removeCss($css = null)
    {
        if (is_null($css)) {
            $this->css = array();
        } else {
            foreach ($this->css as $key => $val) {
                if ($val == $css) {
                    unset($this->css[$key]);
                }
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getCss()
    {
        $out = array();
        if (!empty($this->css) && is_array($this->css)) {
            $url = DxApp::getComponent(DxApp::ALIAS_URL);

            foreach ($this->css as $css) {
                $out[] = $url->css($css);
            }
        }

        return $out;
    }

    /**
     * @param string|null $js
     * @return DxAppContext_Project
     */
    public function addJs($js = null)
    {
        if (!is_null($js)) {
            $this->js = array_merge($this->js, (array)$js);
        }

        return $this;
    }

    /**
     * @param string|null $js
     * @return DxAppContext_Project
     */
    public function removeJs($js = null)
    {
        if (is_null($js)) {
            $this->js = array();
        } else {
            foreach ($this->js as $key => $val) {
                if ($val == $js) {
                    unset($this->js[$key]);
                }
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getJs()
    {
        $out = array();
        if (!empty($this->js) && is_array($this->js)) {
            $url = DxApp::getComponent(DxApp::ALIAS_URL);

            foreach ($this->js as $js) {
                $out[] = $url->js($js);
            }
        }

        return $out;
    }

    /**
     * @return null|string
     */
    public function getCurrentLocale()
    {
        if (DxApp::existComponent(DxConstant_Project::ALIAS_I18N)) {
            /** @var $i18n I18n_Project */
            $i18n = DxApp::getComponent(DxConstant_Project::ALIAS_I18N);
            return $i18n->getTarget();
        }
        return null;
    }

    /**
     * @param $keywords
     */
    public function setPageKeywords($keywords)
    {
        $keywords = preg_replace('~[\r\n\s]+~', ' ', $keywords);
        $this->setData('page_keywords', trim($keywords));
    }

    /**
     * @param $description
     */
    public function setPageDescription($description)
    {
        $description = preg_replace('~[\r\n\s]+~', ' ', $description);
        $this->setData('page_description', trim($description));
    }

    /**
     * @return bool
     */
    public function defineCity()
    {
        $city = $this->getCity();

        if (!empty($city)) {
            return true;
        }

        /** @var Utils_UserLocator $locator */
        $locator = DxFactory::getInstance('Utils_UserLocator');

        /** @var DomainObjectQuery_City $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_City');

        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $located = $locator->get($_SERVER['REMOTE_ADDR']);

            if (!empty($located['city'])) {
                $city = $q->findByTitle(mb_strtolower(trim($located['city'])));

                if (!empty($city)) {
                    $this->setCity($city);

                    return;
                }
            }
        }

        $this->setCity($q->findDefault());
    }

    /**
     * @param DomainObjectModel_City $city
     * @param bool                   $is_constant
     * @return $this
     */
    public function setCity(DomainObjectModel_City $city, $is_constant = true)
    {
        $this->user_city = $city;

        if ($is_constant) {
            $_SESSION[$this->city_session_id] = $city->getId();
        }

        return $this;
    }

    /**
     * @return DomainObjectModel_City|null
     */
    public function getCity()
    {
        if ($this->user_city !== null) {
            return $this->user_city;
        }

        if (empty($_SESSION[$this->city_session_id]) && !empty($_COOKIE[$this->city_session_id])) {
            $_SESSION[$this->city_session_id] = $_COOKIE[$this->city_session_id];
        }

        if (!empty($_SESSION[$this->city_session_id])) {
            /** @var $q DomainObjectQuery_City */
            $q = DxFactory::getSingleton('DomainObjectQuery_City');
            $this->user_city = $q->findById($_SESSION[$this->city_session_id]);
        }

        return $this->user_city;
    }

    /**
     * @param $command
     * @return bool
     */
    public function userCanNot($command = null)
    {
        $command = $command === null ? $this->getCurrentCommand() : $command;

        if ($this->getCurrentUser() === null || $command === null) {
            return false;
        }

        return $this->getCurrentUser()->canNot($command);
    }

    /**
     * @param $command
     * @return bool
     */
    public function userCanCreate($command = null)
    {
        $command = $command === null ? $this->getCurrentCommand() : $command;

        if ($this->getCurrentUser() === null || $command === null) {
            return false;
        }

        return $this->getCurrentUser()->canCreate($command);
    }

    /**
     * @param $command
     * @return bool
     */
    public function userCanView($command = null)
    {
        $command = $command === null ? $this->getCurrentCommand() : $command;

        if ($this->getCurrentUser() === null || $command === null) {
            return false;
        }

        return $this->getCurrentUser()->canView($command);
    }

    /**
     * @param $command
     * @return bool
     */
    public function userCanEdit($command = null)
    {
        $command = $command === null ? $this->getCurrentCommand() : $command;

        if ($this->getCurrentUser() === null || $command === null) {
            return false;
        }

        return $this->getCurrentUser()->canEdit($command);
    }

    /**
     * @param $command
     * @return bool
     */
    public function userCanDelete($command = null)
    {
        $command = $command === null ? $this->getCurrentCommand() : $command;

        if ($this->getCurrentUser() === null || $command === null) {
            return false;
        }

        return $this->getCurrentUser()->canDelete($command);
    }
}