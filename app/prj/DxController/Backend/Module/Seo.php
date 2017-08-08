<?php

DxFactory::import('DxController_Backend_Module');

class DxController_Backend_Module_Seo extends DxController_Backend_Module
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.seo' => 'index',
    );

    /**
     * @return void
     */
    protected function setEnvVar()
    {
        $this->REQUEST_ID = 'seo_id';

        $this->CMD      = '.adm.seo';
        $this->CMD_LIST = '.seo.list';
        $this->CMD_ADD  = '.seo.add';
        $this->CMD_EDIT = '.seo.edit';

        $this->FORM_ADD  = 'seo_add';
        $this->FORM_EDIT = 'seo_edit';
        $this->FORM_CONTROLLER = 'Form_Backend_Seo';

        $this->DOMAIN_OBJECT_MODEL = 'DomainObjectModel_Seo';
        $this->DOMAIN_OBJECT_QUERY = 'DomainObjectQuery_Seo';

        $this->TMPL_GROUP  = 'seo';
        $this->TMPL_LIST   = 'backend/seo_list.tpl.php';
        $this->TMPL_MANAGE = 'backend/seo_manage.tpl.php';

        $this->ITEMS_PER_PAGE = 50;
    }
}