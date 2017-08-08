<?php

dxFactory::import('Form');

class Form_Backend_StaffCategory extends Form_Backend
{
    /** @var DomainObjectModel_StaffCategory */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_StaffCategory|null $form_model
     */
    public function setModel(DomainObjectModel_StaffCategory $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_StaffCategory|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_StaffCategory|null
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
        if (is_null($m)) {
            return false;
        }

        $map = array(
            'staff_category_title'  => array(
                'method' => 'setTitle',
                'value'  => mb_substr($data['staff_category_title'], 0, 255),
            ),
            'staff_category_description'  => array(
                'method' => 'setDescription',
                'value'  => empty($data['staff_category_description']) ? null : $data['staff_category_description'],
            ),
            'staff_category_status' => array(
                'method' => 'setStatus',
                'value'  => $data['staff_category_status'] == 'ENABLED' ? 'ENABLED' : 'DISABLED',
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

        if ($this->getId() == 'staff_category_add') {
            $qnt = DxFactory::getSingleton('DomainObjectQuery_StaffCategory')->getMaxQnt();
            $m->setQnt($qnt + 3);
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
        return $this->smarty->fetch('backend/form/staff_category.tpl.php');
    }
}