<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Settings extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.settings' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'settings_id';

        $this->CMD      = '.adm.settings';
        $this->CMD_LIST = '.settings.list';
        $this->CMD_ADD  = '.settings.add';
        $this->CMD_EDIT = '.settings.edit';

        $this->FORM_ADD  = 'settings_add';
        $this->FORM_EDIT = 'settings_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Settings';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Settings';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Settings';

        $this->TMPL_GROUP  = 'settings';
        $this->TMPL_LIST   = 'backend/settings_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/settings_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 30;
    }

    /**
     * @return void
     */
    protected function opAdd()
    {
        throw new DxException("Unknown operation 'add'");

        if (!$this->canCreate()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }
    }

    /**
     * @return void
     */
    protected function opEdit()
    {
        throw new DxException("Unknown operation 'edit'");

        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }
    }

    /**
     * @return void
     */
    protected function opStatus()
    {
        throw new DxException("Unknown operation 'status'");

        if (!$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }
    }

    protected function opList()
    {
        if (!$this->canView() || !$this->canEdit()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var $form Form_Backend_Settings */
        $form = DxFactory::getInstance($this->FORM_CONTROLLER, array($this->FORM_EDIT));
        $form->setCmd('.settings');
        if ($form->isProcessed()) {
            $form->setSuccessful();
            $this->getUrl()->redirect($form->getUrl());
        }

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'form_html'  => $form->draw(),
        ));

        return $smarty->fetch($this->TMPL_MANAGE);
    }
}