<?php
dxFactory::import('Form_Backend');

class Form_Backend_Subdivision extends Form_Backend
{
    /** @var DomainObjectModel_Subdivision */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Subdivision|null $form_model
     */
    public function setModel(DomainObjectModel_Subdivision $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Subdivision|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Subdivision|null
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
            'subdivision_title' => array(
                'method' => 'setTitle',
                'value'  => empty($data['subdivision_title']) ? null : trim($data['subdivision_title']),
            ),
            'subdivision_alias' => array(
                'method' => 'setAlias',
                'value'  => empty($data['subdivision_alias']) ? null : trim(mb_strtolower($data['subdivision_alias'])),
            ),
            'subdivision_status' => array(
                'method' => 'setStatus',
                'value'  => empty($data['subdivision_status']) || $data['subdivision_status'] != 'ENABLED' ? 'DISABLED' : 'ENABLED',
            ),
        );

        /*
        if (empty($map['subdivision_alias']['value']) && !empty($map['subdivision_title']['value'])) {
            DxFactory::import('Utils_NameMaker');
            $map['subdivision_alias']['value'] = Utils_NameMaker::cyrillicToLatin($map['subdivision_title']['value'], true);
        }*/

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

        /*
        if (empty($errors)) {
            if (!$m->isUnique('alias')) {
                $errors['subdivision_alias'] = 'ALREADY_EXISTS';
            }
        }
         */

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
        return $this->smarty->fetch('backend/form/subdivision.tpl.php');
    }
}