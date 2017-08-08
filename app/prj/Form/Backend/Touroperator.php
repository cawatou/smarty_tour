<?php
dxFactory::import('Form_Backend');

class Form_Backend_Touroperator extends Form_Backend
{
    /** @var DomainObjectModel_Touroperator */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Touroperator|null $form_model
     */
    public function setModel(DomainObjectModel_Touroperator $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Touroperator|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Touroperator|null
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
        $data   = $this->getEnvData('_POST');
        $errors = array();

        $m = $this->getModel();

        if ($m === null) {
            return false;
        }

        $map = array(
            'touroperator_title' => array(
                'method' => 'setTitle',
                'value'  => empty($data['touroperator_title']) ? null : trim($data['touroperator_title']),
            ),
            'touroperator_status' => array(
                'method' => 'setStatus',
                'value'  => empty($data['touroperator_status']) || $data['touroperator_status'] != 'ENABLED' ? 'DISABLED' : 'ENABLED',
            ),
        );

        foreach ($map as $key => $val) {
            try {
                DxFactory::invoke($m, $val['method'], array($val['value']));
            } catch (DxException $e) {
                if ($e->getCode() == DomainObjectModel::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT) {
                    $errors[$key] = 'INVALID_FORMAT';
                } else {
                    $errors[$key] = 'NOT_VALID';
                }
            }
        }

        if (empty($errors)) {
            if (!$m->isUnique()) {
                $errors['touroperator_title'] = 'ALREADY_EXISTS';
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
        return $this->smarty->fetch('backend/form/touroperator.tpl.php');
    }
}