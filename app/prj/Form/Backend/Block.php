<?php

dxFactory::import('Form_Backend');

class Form_Backend_Block extends Form_Backend
{
    /** @var DomainObjectModel_Block */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Block|null $form_model
     */
    public function setModel(DomainObjectModel_Block $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Block|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Block|null
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
            'block_name' => array(
                'method' => 'setName',
                'value'  => empty($data['block_name']) ? '' : mb_substr($data['block_name'], 0, 255),
            ),
            'block_category' => array(
                'method' => 'setCategory',
                'value'  => $data['block_category'],
            ),
            'block_title' => array(
                'method' => 'setTitle',
                'value'  => empty($data['block_title']) ? null : mb_substr($data['block_title'], 0, 255),
            ),			
            'block_alias' => array(
                'method' => 'setAlias',
                'value'  => empty($data['block_alias']) ? null : mb_strtolower($data['block_alias']),
            ),
            'block_content' => array(
                'method' => 'setContent',
                'value'  => $data['block_content'],
            ),
            'block_type' => array(
                'method' => 'setType',
                'value'  => $data['block_type'],
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

        if ($this->getId() == 'block_add') {
            $qnt = DxFactory::getSingleton('DomainObjectQuery_Block')->getMaxQnt();
            $m->setQnt($qnt + 5);
        }
		
        if (empty($errors)) {
            if (!$m->isUniqueAlias()) {
                $errors['block_alias'] = 'ALREADY_EXISTS';
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
    	return $this->smarty->fetch('backend/form/block.tpl.php');
    }
}