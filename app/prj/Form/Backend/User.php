<?php

dxFactory::import('Form_Backend');

class Form_Backend_User extends Form_Backend
{
    /** @var DomainObjectModel_User */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_User|null $form_model
     */
    public function setModel(DomainObjectModel_User $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_User|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_User|null
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
            'user_name'   => array(
                'method' => 'setName',
                'value'  => empty($data['user_name']) ? null : $data['user_name'],
            ),
            'user_role'   => array(
                'method' => 'setRole',
                'value'  => empty($data['user_role']) ? null : $data['user_role'],
            ),
            'user_status' => array(
                'method' => 'setStatus',
                'value'  => !empty($data['user_status']) && $data['user_status'] == 'ENABLED' ? 'ENABLED' : 'DISABLED',
            ),
            'user_froms' => array(
                'method' => 'setFroms',
                'value'  => empty($data['user_froms']) ? null : $data['user_froms'],
            ),
        );

        if ($this->getId() == 'user_add') {
            $map['user_login'] = array(
                'method' => 'setLogin',
                'value'  => empty($data['user_login']) ? null : $data['user_login'],
            );

            $map['user_visited'] = array(
                'method' => 'setVisited',
                'value'  => null,
            );
        }

        if ($map['user_role']['value'] == 'OPERATOR' || $map['user_role']['value'] == 'SELLER') {
            $map['office_id'] = array(
                'method' => 'setOfficeId',
                'value'  => empty($data['office_id']) ? null : $data['office_id'],
            );
        } elseif ($map['user_role']['value'] == 'DIRECTOR') {
            $map['subdivision_id'] = array(
                'method' => 'setSubdivisionId',
                'value'  => empty($data['subdivision_id']) ? null : $data['subdivision_id'],
            );
        }

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

        if ($m->getRole() == 'OPERATOR' && !$m->getOfficeId()) {
            $errors['office_id'] = 'NOT_VALID';
        }

        if ($m->getRole() == 'DIRECTOR' && !$m->getSubdivisionId()) {
            $errors['subdivision_id'] = 'NOT_VALID';
        }

        if (($this->getId() == 'user_edit' && !empty($data['user_password'])) || $this->getId() == 'user_add') {
            if (!preg_match('~^[^\s]{6,}$~msu', $data['user_password'])) {
                $errors['user_password'] = 'SHORT_PASSWORD';
            }

            $identifier = DomainObjectModel_User::createIdentifier($m->getLogin(), $data['user_password']);

            $m->setIdentifier($identifier);
        }


        if (empty($errors)) {
            if (!$m->isUniqueLogin()) {
                $errors['user_login'] = 'LOGIN_ALREADY_EXISTS';
            }
        }

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        if ($m->getRole() == 'OPERATOR' || $m->getRole() == 'SELLER') {
            if ($m->getOffice() && $m->getOffice()->getCity()) {
                $m->setSubdivisionId($m->getOffice()->getCity()->getSubdivisionId());
            }
        }

        $this->getDomainObjectManager()->flush();

        return true;
    }

    /**
     * @return string
     */
    public function draw()
    {
        $assign = array(
            'departures' => $this->getModel()->getFromAll(),
        );

        /** @var DomainObjectQuery_Office $q_o */
        $q_o = DxFactory::getInstance('DomainObjectQuery_Office');

        if ($this->getContext()->getCurrentUser()->getRole() == 'DIRECTOR') {
            $this->getModel()->setSubdivisionId($this->getContext()->getCurrentUser()->getSubdivisionId());

            $offices = $this->getContext()->getCurrentUser()->getSubdivisionOffices();
        } else {
            $offices = $q_o->findAll(true);

            /** @var DomainObjectQuery_Subdivision $q_s */
            $q_s = DxFactory::getInstance('DomainObjectQuery_Subdivision');

            $subdivisions = $q_s->findAll(true);

            $assign['subdivisions'] = $subdivisions;
        }

        $offices_array = array();

        foreach ($offices as $office) {
            $offices_array[$office->getCity()->getTitle()][$office->getId()] = $office;
        }

        $assign['offices_array'] = $offices_array;

        if (!empty($assign)) {
            $this->smarty->assign($assign);
        }

        return $this->smarty->fetch('backend/form/user.tpl.php');
    }
}