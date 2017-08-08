<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Block extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.block' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'block_id';

        $this->CMD      = '.adm.block';
        $this->CMD_LIST = '.block.list';
        $this->CMD_ADD  = '.block.add';
        $this->CMD_EDIT = '.block.edit';

        $this->FORM_ADD  = 'block_add';
        $this->FORM_EDIT = 'block_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Block';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Block';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Block';

        $this->TMPL_GROUP  = 'block';
        $this->TMPL_LIST   = 'backend/block_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/block_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 50;
    }

    /**
     * @return string
     */
    protected function opList()
    {
        if (!$this->canView()) {
            throw new DxException('Access denied', DxApp::DX_APP_ERROR_AUTHORIZATION);
        }

        /** @var $q  DomainObjectQuery_Block */
        $q = DxFactory::getSingleton($this->DOMAIN_OBJECT_QUERY);

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'list' => $q->findForList(),
        ));

        return $smarty->fetch($this->TMPL_LIST);
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
}