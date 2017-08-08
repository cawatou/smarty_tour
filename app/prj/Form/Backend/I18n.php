<?php

dxFactory::import('Form_Backend');

class Form_Backend_I18n extends Form_Backend
{
    /** @var DomainObjectModel_I18n */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_I18n|null $form_model
     */
    public function setModel(DomainObjectModel_I18n $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_I18n|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_I18n|null
     */
    public function m()
    {
        return $this->getModel();
    }

    /**
     * @return bool
     */
    protected function process()
    {
        $data = $this->getEnvData('_POST');
        $errors = array();

        $m = $this->getModel();
        if (is_null($m)) {
            return false;
        }

        $map = array(
            'i18n_source_string' => array(
                'method' => 'setSourceString',
                'value'  => $data['i18n_source_string'],
            ),
            'i18n_target_string' => array(
                'method' => 'setTargetString',
                'value'  => $data['i18n_target_string'] ? $data['i18n_target_string'] : null,
            ),
        );

        foreach ($map as $key => $val) {
            try {
                call_user_func(array($m, $val['method']), $val['value']);
            } catch (DxException $e) {
                if ($e->getCode() == DomainObjectModel::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT) {
                    $errors[$key] = 'INVALID_FORMAT';
                } else {
                    $errors[$key] = 'NOT_VALID';
                }
            }
        }

        if (!empty($errors)) {
        	$this->errors = $errors;
            $this->getDomainObjectManager()->rollback();
        	return false;
        }

        $this->getDomainObjectManager()->flush();
        return true;
    }

    /**
     * @return string
     */
    public function draw()
    {
    	return $this->smarty->fetch('backend/form/i18n.tpl.php');
    }
}