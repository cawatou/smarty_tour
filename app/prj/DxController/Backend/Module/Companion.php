<?php
DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Companion extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.companion' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'companion_id';

        $this->CMD      = '.adm.companion';
        $this->CMD_LIST = '.companion.list';
        $this->CMD_ADD  = '.companion.add';
        $this->CMD_EDIT = '.companion.edit';

        $this->FORM_ADD  = 'companion_add';
        $this->FORM_EDIT = 'companion_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Companion';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Companion';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Companion';

        $this->TMPL_GROUP  = null;
        $this->TMPL_LIST   = 'backend/companion_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/companion_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 15;
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
}