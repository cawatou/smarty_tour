<?php defined('DX_CFG_DIR') or die('No direct access allowed');

DxFactory::import('DxACL');

return array(
    DxCommand::CMD_DEFAULT => array(
        'controller'   => 'DxController_Frontend_Master',
        'title'        => 'Главная',
        'use_for_menu' => true,
    ),

    DxCommand::CMD_NOT_FOUND => array(
        'controller'   => 'DxController_Frontend',
        'title'        => '404 Ошибка',
        'use_for_menu' => true,
    ),

    DxCommand::CMD_AUTH_ERROR => array(
        'controller' => 'DxController_Default'
    ),

    '.sys.models' => array(
        'controller'          => 'DxController_System',
        'disabled_components' => array(DxConstant_Project::ALIAS_SESSION, DxConstant_Project::ALIAS_SMARTY)
    ),

    '.adm' => array(
        'controller' => 'DxController_Backend_Master',
    ),

    '.adm.restore' => array(
        'controller' => 'DxController_Backend_Master',
    ),

    '.adm.signInAs' => array(
        'controller' => 'DxController_Backend_Master',
        'roles'      => array('DEVELOPER'),
    ),

    '.adm.signOut' => array(
        'controller' => 'DxController_Backend_Master',
        'roles' => array('DEVELOPER', 'ADMIN', 'DIRECTOR', 'OPERATOR', 'SELLER'),
    ),

    '.adm.user' => array(
        'controller' => 'DxController_Backend_Module_User',
        'roles'      => array('DEVELOPER', 'ADMIN', 'DIRECTOR'),
    ),

    '.adm.menu' => array(
        'controller' => 'DxController_Backend_Module_Menu',
        'roles'      => array('DEVELOPER', 'ADMIN'),
    ),

    '.adm.page' => array(
        'controller' => 'DxController_Backend_Module_Page',
        'roles' => array('DEVELOPER', 'ADMIN'),
    ),

    '.adm.faq' => array(
        'controller' => 'DxController_Backend_Module_Faq',
        'roles'      => array('DEVELOPER', 'ADMIN', 'DIRECTOR', 'OPERATOR'),
    ),

    '.adm.feedback' => array(
        'controller' => 'DxController_Backend_Module_Feedback',
        'roles'      => array('DEVELOPER', 'ADMIN', 'DIRECTOR', 'OPERATOR'),
    ),

    '.adm.order' => array(
        'controller' => 'DxController_Backend_Module_Order',
        'roles'      => array('DEVELOPER', 'ADMIN', 'SELLER'),
    ),

    '.adm.companion' => array(
        'controller' => 'DxController_Backend_Module_Companion',
        'roles'      => array('DEVELOPER', 'ADMIN', 'DIRECTOR', 'OPERATOR'),
    ),

    '.adm.files' => array(
        'controller' => 'DxController_Backend_Files',
        'roles'      => array('DEVELOPER', 'ADMIN', 'DIRECTOR', 'OPERATOR'),
    ),

    '.adm.files-mce' => array(
        'controller' => 'DxController_Backend_Files',
        'roles'      => array('DEVELOPER', 'ADMIN', 'DIRECTOR', 'OPERATOR'),
    ),

    '.adm.files-dialog' => array(
        'controller' => 'DxController_Backend_Files',
        'roles'      => array('DEVELOPER', 'ADMIN', 'DIRECTOR', 'OPERATOR'),
    ),

    '.adm.files-multi' => array(
        'controller' => 'DxController_Backend_Files',
        'roles'      => array('DEVELOPER', 'ADMIN', 'DIRECTOR', 'OPERATOR'),
    ),

    '.adm.publication' => array(
        'controller' => 'DxController_Backend_Module_Publication',
        'roles'      => array('DEVELOPER', 'ADMIN', 'DIRECTOR', 'OPERATOR'),
    ),

    '.adm.product' => array(
        'controller' => 'DxController_Backend_Module_Product',
        'roles'      => array('DEVELOPER', 'ADMIN', 'DIRECTOR', 'OPERATOR'),
    ),

    '.adm.gallery.image' => array(
        'controller' => 'DxController_Backend_Module_GalleryImage',
        'roles' => array('DEVELOPER', 'ADMIN'),
    ),

    '.adm.gallery.category' => array(
        'controller' => 'DxController_Backend_Module_GalleryCategory',
        'roles' => array('DEVELOPER', 'ADMIN'),
    ),

    '.adm.block' => array(
        'controller' => 'DxController_Backend_Module_Block',
        'roles' => array('DEVELOPER', 'ADMIN'),
    ),

    '.adm.request' => array(
        'controller' => 'DxController_Backend_Module_Request',
        'roles' => array('DEVELOPER', 'ADMIN', 'DIRECTOR', 'OPERATOR', 'SELLER'),
    ),

    '.adm.settings' => array(
        'controller' => 'DxController_Backend_Module_Settings',
        'roles' => array('DEVELOPER', 'ADMIN'),
    ),

    '.adm.staff' => array(
        'controller' => 'DxController_Backend_Module_Staff',
        'roles' => array('DEVELOPER', 'ADMIN', 'DIRECTOR'),
    ),

    '.adm.seo' => array(
        'controller' => 'DxController_Backend_Module_Seo',
        'roles' => array('DEVELOPER', 'ADMIN'),
    ),

    '.adm.country' => array(
        'controller' => 'DxController_Backend_Module_Country',
        'roles' => array('DEVELOPER', 'ADMIN'),
    ),

    '.adm.resort' => array(
        'controller' => 'DxController_Backend_Module_Resort',
        'roles' => array('DEVELOPER', 'ADMIN'),
    ),

    '.adm.hotel' => array(
        'controller' => 'DxController_Backend_Module_Hotel',
        'roles' => array('DEVELOPER', 'ADMIN'),
    ),

    '.adm.city' => array(
        'controller' => 'DxController_Backend_Module_City',
        'roles' => array('DEVELOPER', 'ADMIN', 'DIRECTOR'),
    ),

    '.adm.touroperator' => array(
        'controller' => 'DxController_Backend_Module_Touroperator',
        'roles' => array('DEVELOPER', 'ADMIN'),
    ),

    '.adm.subdivision' => array(
        'controller' => 'DxController_Backend_Module_Subdivision',
        'roles' => array('DEVELOPER', 'ADMIN'),
    ),

    '.adm.office' => array(
        'controller' => 'DxController_Backend_Module_Office',
        'roles' => array('DEVELOPER', 'ADMIN', 'DIRECTOR'),
    ),

    '.adm.staff' => array(
        'controller' => 'DxController_Backend_Module_Staff',
        'roles' => array('DEVELOPER', 'ADMIN', 'DIRECTOR'),
    ),

    '.content' => array(
        'controller' => 'DxController_Frontend_Content',
    ),

    '.content.gallery' => array(
        'controller' => 'DxController_Frontend_Content',
    ),

    '.content.special.weather' => array(
        'controller'   => 'DxController_Frontend_Content',
        'use_for_menu' => true,
        'title'        => 'Страница: Погода',
    ),

    '.turistam.oformlenie_zagranpasporta' => array(
        'controller' => 'DxController_Frontend_Content',
    ),

    '.faq' => array(
        'controller'   => 'DxController_Frontend_Faq',
        'use_for_menu' => true,
        'title'        => 'Вопрос-ответ',
    ),

    '.faq.captcha' => array(
        'controller' => 'DxController_Frontend_Captcha',
    ),

    '.companion' => array(
        'controller'   => 'DxController_Frontend_Companion',
        'use_for_menu' => true,
        'title'        => 'Поиск попутчика',
    ),
	
	'.franchise' => array(
        'controller'   => 'DxController_Frontend_Franchise',
        'use_for_menu' => true,
        'title'        => 'Франчайзинг',
    ),

    '.imagi' => array(
        'controller' => 'DxController_Frontend_Master',
    ),

    '.city' => array(
        'controller' => 'DxController_Frontend_Master',
    ),

    '.order.create' => array(
        'controller' => 'DxController_Frontend_Order',
    ),

    '.order.view' => array(
        'controller' => 'DxController_Frontend_Order',
    ),

    '.contacts' => array(
        'controller'   => 'DxController_Frontend_Contacts',
        'use_for_menu' => true,
        'title'        => 'Контакты',
    ),

    '.import.sletat.countries' => array(
        'controller' => 'DxController_Frontend_Import',
    ),

    '.import.sletat.resorts' => array(
        'controller' => 'DxController_Frontend_Import',
    ),

    '.import.sletat.hotels' => array(
        'controller' => 'DxController_Frontend_Import',
    ),

    '.import.merge.companion' => array(
        'controller' => 'DxController_Frontend_Import',
    ),

    '.import.merge.hotels' => array(
        'controller' => 'DxController_Frontend_Import',
    ),

    '.import.merge.news' => array(
        'controller' => 'DxController_Frontend_Import',
    ),

    '.import.merge.faq' => array(
        'controller' => 'DxController_Frontend_Import',
    ),

    '.import.merge.feedback' => array(
        'controller' => 'DxController_Frontend_Import',
    ),

    '.ajax.suggest.countries' => array(
        'controller' => 'DxController_Frontend_AjaxHelper',
    ),

    '.ajax.suggest.resorts' => array(
        'controller' => 'DxController_Frontend_AjaxHelper',
    ),

    '.ajax.suggest.hotels' => array(
        'controller' => 'DxController_Frontend_AjaxHelper',
    ),

    '.ajax.countries' => array(
        'controller' => 'DxController_Frontend_AjaxHelper',
    ),

    '.ajax.resorts' => array(
        'controller' => 'DxController_Frontend_AjaxHelper',
    ),

    '.ajax.hotels' => array(
        'controller' => 'DxController_Frontend_AjaxHelper',
    ),

    '.search' => array(
        'controller'   => 'DxController_Frontend_Search',
        'use_for_menu' => true,
        'title'        => 'Подбор тура Онлайн',
    ),

    '.search.train' => array(
        'controller'   => 'DxController_Frontend_Search',
        'use_for_menu' => true,
        'title'        => 'ЖД билеты',
    ),

    '.search.hotel' => array(
        'controller'   => 'DxController_Frontend_Search',
        'use_for_menu' => true,
        'title'        => 'Расширенный поиск отелей',
    ),

    '.signup.sms' => array(
        'controller' => 'DxController_Frontend_Signup',
    ),

    '.tours' => array(
        'controller' => 'DxController_Frontend_Master',
    ),

    '.tours.details' => array(
        'controller' => 'DxController_Frontend_Master',
    ),

    '.ads' => array(
        'controller' => 'DxController_Frontend_Master',
    ),

    '.russia' => array(
        'controller' => 'DxController_Frontend_Russia',
    ),

    '.russia.details' => array(
        'controller' => 'DxController_Frontend_Russia',
    ),

    '.russia.ads' => array(
        'controller' => 'DxController_Frontend_Russia',
    ),

    '.request' => array(
        'controller'   => 'DxController_Frontend_Request',
        'use_for_menu' => true,
        'title'        => 'Заявка на тур',
    ),

    '.howtobuy' => array(
        'controller'   => 'DxController_Frontend_Content',
        'use_for_menu' => true,
        'title'        => 'Как купить тур on-line',
    ),

    '.order' => array(
        'controller'   => 'DxController_Frontend_Request',
    ),

    '.turistam' => array(
        'controller'   => 'DxController_Frontend_Content',
        'use_for_menu' => true,
        'title'        => 'Туристам',
    ),

    '.feedback' => array(
        'controller' => 'DxController_Frontend_Feedback',
    ),

    '.feedback.captcha' => array(
        'controller' => 'DxController_Frontend_Captcha',
    ),

    '.hotel.details' => array(
        'controller' => 'DxController_Frontend_Hotel',
    ),

    '.modal.complain' => array(
        'controller' => 'DxController_Frontend_Modal',
    ),

    '.modal.callback' => array(
        'controller' => 'DxController_Frontend_Modal',
    ),

    '.callback.captcha' => array(
        'controller' => 'DxController_Frontend_Captcha',
    ),

    '.news' => array(
        'controller' => 'DxController_Frontend_Publication',
    ),

    '.news.details' => array(
        'controller' => 'DxController_Frontend_Publication',
    ),

    '.uploader' => array(
        'controller' => 'DxController_Frontend_Uploader',
    ),

    '.payonline.success' => array(
        'controller' => 'DxController_Frontend_Payonline',
    ),

    '.payonline.fail' => array(
        'controller' => 'DxController_Frontend_Payonline',
    ),

    ////////////////// vvv DEMO vvv //////////////
    '.payonline' => array(
        'controller' => 'DxController_Frontend_DemoPayonline',
    ),

    '.payonline.success.cb' => array(
        'controller' => 'DxController_Frontend_DemoPayonline',
    ),

    '.payonline.fail.cb' => array(
        'controller' => 'DxController_Frontend_DemoPayonline',
    ),

    '.payonline.search' => array(
        'controller' => 'DxController_Frontend_DemoPayonline',
    ),

    '.payonline.confirm' => array(
        'controller' => 'DxController_Frontend_DemoPayonline',
    ),

    '.payonline.cancel' => array(
        'controller' => 'DxController_Frontend_DemoPayonline',
    ),

    '.payonline.refund' => array(
        'controller' => 'DxController_Frontend_DemoPayonline',
    ),
    ////////////////// ^^^ DEMO ^^^ //////////////

    '.recheck.hotels' => array(
        'controller' => 'DxController_Frontend_Import',
    ),

    '.import.sletat.orders' => array(
        'controller' => 'DxController_Frontend_Sletat',
    ),
	
	'.import.sletat.msk' => array(
        'controller' => 'DxController_Frontend_SletatMsk',
    ),
);