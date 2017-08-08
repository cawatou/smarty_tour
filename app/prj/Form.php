<?php

abstract class Form
{
    protected $from_id = null;
    protected $form_url = null;
    protected $env_data = array();
    protected $form_data = array();
    protected $ctx = null;

    /** @var null|Smarty */
    protected $smarty = null;

    public $errors = array();
    public $successful = false;

    /**
     * @param null $form_id
     */
    public function __construct($form_id = null)
    {
        $this->form_id = $form_id;

        foreach (array('_POST', '_GET', '_REQUEST', '_FILES', '_SESSION') as $env_array_name) {
            $this->env_data[$env_array_name] = array();

            $tmp = "\${'$env_array_name'}";
            eval("\$tmp = $tmp;");

            foreach ($tmp as $key => $value) {
                if (strpos($key, $form_id) === 0) {
                    $this->env_data[$env_array_name][substr($key, strlen($form_id) + 1)] = $value;
                }
            }
        }

        if (!empty($this->env_data['_SESSION']['successful'])) {
            unset($_SESSION[$this->encodeName($this->form_id, 'successful')]);
            $this->successful = true;
        }

        $this->smarty = DxApp::getComponent(DxConstant_Project::ALIAS_SMARTY, true);
        $this->smarty->assign(array(
            '__f' => $this
        ));
    }

    /**
     * @return bool|void
     */
    public function isProcessed()
    {
        if ($this->isSubmited()) {
            return $this->process();
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isSubmited()
    {
        return isset($this->env_data['_POST']['__send']);
    }

    /**
     * @param string $form_id
     * @param mixed  $name
     * @return string
     */
    public static final function encodeName($form_id, $name)
    {
        return "{$form_id}_{$name}";
    }

    /**
     * @param string $name
     * @return string
     */
    public final function encode($name)
    {
        return self::encodeName($this->form_id, $name);
    }

    /**
     * @param bool $successful
     */
    public final function setSuccessful($successful = true)
    {
        $this->successful = $successful;
        $_SESSION[self::encodeName($this->form_id, 'successful')] = $successful;
    }

    /**
     * @param null|string $key
     * @param null|string $default
     * @return mixed
     */
    public final function getEnvData($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->env_data;
        }
        return !isset($this->env_data[$key]) ? $default : $this->env_data[$key];
    }

    /**
     * @return mixed
     */
    public final function getId()
    {
        return $this->form_id;
    }

    /**
     * @param mixed $key
     * @param mixed $default
     * @return array|null
     */
    public final function getError($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->errors;
        } elseif (isset($this->errors[$key])) {
            return $this->errors[$key];
        }
        return $default;
    }

    /**
     * @param mixed $key
     * @param mixed $default
     * @return array|null
     */
    public final function e($key = null, $default = null)
    {
        return $this->getError($key, $default);
    }

    /**
     * @param array $form_data
     */
    public function setFormData($form_data = array())
    {
        $this->form_data = $form_data;
    }

    /**
     * @param mixed $key
     * @param mixed $default
     * @return array|null
     */
    public function getFormData($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->form_data;
        } elseif (isset($this->form_data[$key])) {
            return $this->form_data[$key];
        }
        return $default;
    }

    /**
     * @param mixed $key
     * @param mixed $default
     * @return array|null
     */
    public function v($key = null, $default = null)
    {
        return $this->getFormData($key, $default);
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->form_url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->form_url;
    }

    /**
     * @abstract
     * @return bool
     */
    protected abstract function process();

    /**
     * @abstract
     * @return string
     */
    public abstract function draw();

    public function setContext(DxAppContext $ctx)
    {
        $this->ctx = $ctx;

        return $this;
    }

    public function getContext()
    {
        if ($this->ctx) {
            return $this->ctx;
        }

        return DxApp::getComponent(DxApp::ALIAS_APP_CONTEXT);
    }
}