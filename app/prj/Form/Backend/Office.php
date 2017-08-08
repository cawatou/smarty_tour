<?php
dxFactory::import('Form');

class Form_Backend_Office extends Form_Backend
{
    /** @var DomainObjectModel_Office */
    protected $form_model = null;

    /**
     * @param DomainObjectModel_Office|null $form_model
     */
    public function setModel(DomainObjectModel_Office $form_model = null)
    {
        $this->form_model = $form_model;
    }

    /**
     * @return DomainObjectModel_Office|null
     */
    public function getModel()
    {
        return $this->form_model;
    }

    /**
     * @return DomainObjectModel_Office|null
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
            'city_id' => array(
                'method' => 'setCityId',
                'value'  => empty($data['city_id']) ? null : $data['city_id'],
            ),
            'subdivision_id' => array(
                'method' => 'setSubdivisionId',
                'value'  => empty($data['subdivision_id']) ? null : $data['subdivision_id'],
            ),
            'related_city_id' => array(
                'method' => 'setRelatedCityId',
                'value'  => empty($data['related_city_id']) ? null : $data['related_city_id'],
            ),
            'office_title'  => array(
                'method' => 'setTitle',
                'value'  => empty($data['office_title']) ? null : mb_substr($data['office_title'], 0, 255),
            ),
            'office_display_name'  => array(
                'method' => 'setDisplayName',
                'value'  => empty($data['office_display_name']) ? null : mb_substr($data['office_display_name'], 0, 255),
            ),
            'office_address'  => array(
                'method' => 'setAddress',
                'value'  => empty($data['office_address']) ? null : $data['office_address'],
            ),
            'office_email'   => array(
                'method' => 'setEmail',
                'value'  => empty($data['office_email']) ? null : mb_strtolower(trim($data['office_email'])),
            ),
            'office_status' => array(
                'method' => 'setStatus',
                'value'  => $data['office_status'] === 'ENABLED' ? 'ENABLED' : 'DISABLED',
            ),
            'office_alias'  => array(
                'method' => 'setAlias',
                'value'  => empty($data['office_alias']) ? null : $data['office_alias'],
            ),
            'office_phone'  => array(
                'method' => 'setPhone',
                'value'  => empty($data['office_phone']) ? null : substr($data['office_phone'], 0, 255),
            ),
            'office_metro'  => array(
                'method' => 'setMetro',
                'value'  => empty($data['office_metro']) ? null : substr($data['office_metro'], 0, 255),
            ),
            'office_schedule' => array(
                'method' => 'setSchedule',
                'value'  => empty($data['office_schedule']) ? null : $data['office_schedule'],
            ),

            'office_is_pay_cash' => array(
                'method' => 'setIsPayCash',
                'value'  => empty($data['office_is_pay_cash']) ? 0 : 1,
            ),
            'office_is_pay_cashless' => array(
                'method' => 'setIsPayCashless',
                'value'  => empty($data['office_is_pay_cashless']) ? 0 : 1,
            ),
            'office_is_pay_installment' => array(
                'method' => 'setIsPayInstallment',
                'value'  => empty($data['office_is_pay_installment']) ? 0 : 1,
            ),
            'office_is_pay_credit' => array(
                'method' => 'setIsPayCredit',
                'value'  => empty($data['office_is_pay_credit']) ? 0 : 1,
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

        if (empty($errors) && $m->getTitle(true) !== null) {
            $m->setAlias();

            /** @var $q DomainObjectQuery_Office */
            $q = DxFactory::getInstance('DomainObjectQuery_Office');

            $_m = $q->findByAlias($m->getAlias());

            if ($_m !== null && $m->getId() != $_m->getId()) {
                $errors['office_title'] = 'ALREADY_EXISTS';
            }
        }

        $sletat_data = array();

        foreach ($data['office_sletat_data'] as $k => $v) {
            if (empty($v['value']) || ($k == 'port' && $v['value'] <= 0)) {
                continue;
            }

            $sletat_data[$k] = $v;
        }

        $m->setSletatData($sletat_data);

        if (empty($errors)) {
            if ($m->getSubdivisionId() === null) {
                $errors['subdivision_id'] = 'NOT_VALID';
            }
        }

        if ($this->getId() === 'office_add') {
            $qnt = DxFactory::getSingleton('DomainObjectQuery_Office')->getMaxQnt();
            $m->setQnt($qnt + 3);
        }

        if (!empty($errors)) {
            $this->errors = $errors;
            $this->getDomainObjectManager()->rollback();

            return false;
        }

        $m->setCityName($m->getCity()->getTitle());

        if ($m->getRelatedCityId()) {
            $m->setRelatedCityName($m->getRelatedCity()->getTitle());
        }

        if ($m->getSubdivision()) {
            $m->setSubdivisionName($m->getSubdivision()->getTitle());

            if ($m->getId()) {
                $q_u = DxFactory::getSingleton('DomainObjectQuery_User');

                $subdivision_users = $q_u->findByOfficeId($m->getId());

                foreach ($subdivision_users as $user) {
                    $user->setSubdivisionId($m->getSubdivisionId());
                }
            }
        }

        $this->smarty->clearCache('frontend/include/sidebars/side_left_office.tpl.php', 'SIDEBAR_OFFICE_'. $m->getCity()->getId());

        $this->getDomainObjectManager()->flush();

        return true;
    }

    /**
     * @return string
     */
    public function draw()
    {
        /** @var DomainObjectQuery_City $q_city */
        $q_city = DxFactory::getSingleton('DomainObjectQuery_City');

        /** @var DomainObjectQuery_Subdivision $q_sub */
        $q_sub = DxFactory::getSingleton('DomainObjectQuery_Subdivision');

        $subdivisions = array();

        if ($this->getContext()->getCurrentUser()->getRole() !== 'DIRECTOR') {
            $subdivisions = $q_sub->findAll(true);
        }

        $cities = $q_city->findAll(true);

        $existingSletatData = $this->getModel()->getSletatData();

        $defaultSletatData = array(
            'host' => array(
                'title' => 'Хост (IMAP)',
                'value' => null,
                'help'  => 'Хост для доступ к IMAP<br>Например, для пользователей «Яндекс почты», адрес будет «imap.yandex.ru»',
            ),
            'port' => array(
                'title' => 'Порт',
                'value' => 993,
                'help'  => 'Порт для доступа к IMAP, по умолчанию - 993',
            ),
            'user' => array(
                'title' => 'Пользователь',
                'value' => null,
                'help'  => 'Имя пользователя, для доступа к почтовому ящику.<br><b>Внимание:</b> Для пользователей «Яндекс. Почта для домена», нужно указать «Имя@Сайт», например «user@moihottur.ru»',
            ),
            'password' => array(
                'title' => 'Пароль',
                'value' => null,
                'help'  => null,
            ),
        );

        if (!$existingSletatData) {
            $existingSletatData = $defaultSletatData;
        } else {
            foreach ($defaultSletatData as $name => $sletatData) {
                if (empty($existingSletatData[$name])) {
                    $existingSletatData[$name] = $sletatData;
                } else {
                    $existingSletatData[$name]['title'] = $sletatData['title'];
                    $existingSletatData[$name]['help']  = $sletatData['help'];
                }
            }
        }

        $this->getModel()->setSletatData($existingSletatData);

        $this->smarty->assign(
            array(
                'city_list'        => $cities,
                'subdivision_list' => $subdivisions,
            )
        );

        unset($q_city);

        return $this->smarty->fetch('backend/form/office.tpl.php');
    }
}