<?php

dxFactory::import('Form');

class Form_Backend_Seo extends Form_Backend
{
    /** @var DomainObjectModel_Seo */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Seo|null $form_model
     */
    public function setModel(DomainObjectModel_Seo $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Seo|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Seo|null
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

        $request = null;
        if (!empty($data['seo_request'])) {
            $url = DxApp::getComponent(DxApp::ALIAS_URL);
            $request = preg_replace('~^[^/]+://(.+)$~', '$1', trim($data['seo_request']));
            $request = preg_split('~\?~', $request);

            $request = str_replace($url->getHost() . $url->getBase(), '', $request[0]);
            $request = trim($request, '/');
            $request = '/' . $request;
        }

        $map = array(
            'seo_request'  => array(
                'method' => 'setRequest',
                'value'  => $request,
            ),
            'seo_title'   => array(
                'method' => 'setTitle',
                'value'  => empty($data['seo_title']) ? null : $data['seo_title'],
            ),
            'seo_keywords'   => array(
                'method' => 'setKeywords',
                'value'  => empty($data['seo_keywords']) ? null : $data['seo_keywords'],
            ),
            'seo_description'  => array(
                'method' => 'setDescription',
                'value'  => empty($data['seo_description']) ? null : $data['seo_description'],
            ),
            'seo_status' => array(
                'method' => 'setStatus',
                'value'  => $data['seo_status'] == 'ENABLED' ? 'ENABLED' : 'DISABLED',
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

        if (empty($errors)) {
            /** @var $q DomainObjectQuery_Seo */
            $q = DxFactory::getInstance('DomainObjectQuery_Seo');
            $o = $q->findByRequest($request);
            if (!is_null($o) && $m->getId() != $o->getId()) {
                $errors['seo_request'] = 'ALREADY_EXIST';
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
        $this->setFormData($this->getEnvData('_POST'));
        return $this->smarty->fetch('backend/form/seo.tpl.php');
    }
}