<?php
dxFactory::import('Form_Backend');

class Form_Backend_Companion extends Form_Backend
{
    /** @var DomainObjectModel_Companion */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Companion|null $form_model
     */
    public function setModel(DomainObjectModel_Companion $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Companion|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Companion|null
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

        if ($m === null) {
            return false;
        }

        $map = array(
            'companion_notes' => array(
                'method' => 'setNotes',
                'value'  => empty($data['companion_notes']) ? null : $data['companion_notes'],
            ),
            'companion_agency_notes' => array(
                'method' => 'setAgencyNotes',
                'value'  => empty($data['companion_agency_notes']) ? null : $data['companion_agency_notes'],
            ),
            'companion_status' => array(
                'method' => 'setStatus',
                'value'  => $data['companion_status'] == 'ENABLED' ? 'ENABLED' : 'DISABLED',
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
    	return $this->smarty->fetch('backend/form/companion.tpl.php');
    }
}