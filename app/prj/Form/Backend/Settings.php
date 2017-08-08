<?php

dxFactory::import('Form_Backend');

class Form_Backend_Settings extends Form_Backend
{
    protected $models = array();
    protected function getModels()
    {
        if (empty($this->models)) {
            /** @var $q DomainObjectQuery_Settings */
            $q = DxFactory::getInstance('DomainObjectQuery_Settings');
            $models = $q->findAll();
            foreach ($models as $model) {
                $this->models[$model->getGroup()][] = $model;
            }

        }
        return $this->models;
    }

    /**
     * @return bool
     */
    protected function process()
    {
        $data   = $this->getEnvData('_POST');
        $errors = array();

        $type_to_set = array(
            'INT'    => 'setValInt',
            'BOOL'   => 'setValBool',
            'STRING' => 'setValString',
            'TEXT'   => 'setValText',
            'FILE'   => 'setValString',
        );

        $settings = $this->getModels();
        foreach ($settings as $group => $list) {
            foreach ($list as $item) {
                if (!isset($data['settings'][$item->getKey()])) continue;
                try {
                    DxFactory::invoke($item, $type_to_set[$item->getType()], array(empty($data['settings'][$item->getKey()]) ? null : $data['settings'][$item->getKey()]));
                } catch (DxException $e) {
                    if ($e->getCode() == DomainObjectModel::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT) {
                        $errors["settings_{$item->getKey()}"] = 'INVALID_FORMAT';
                    } else {
                        $errors["settings_{$item->getKey()}"] = 'NOT_VALID';
                    }
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
        $this->smarty->assign(array(
            'settings' => $this->getModels(),
            'types'    => DomainObjectModel_Settings::getTypes(),
        ));

        return $this->smarty->fetch('backend/form/settings.tpl.php');
    }
}