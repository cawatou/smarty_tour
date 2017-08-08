<?php defined('DX_CFG_DIR') or die('No direct access allowed');

DxFactory::import('DxACL');

return array(
/*
    '.adm.signOut' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'OPERATOR'  => DxACL::CAN_ALL
    ),
*/
    '.adm.user' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL,
    ),

    '.adm.product.discounts' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
    ),

    '.adm.product.promoprice' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
    ),

    '.adm.product.ads' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL,
    ),

    '.adm.menu' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
    ),

    '.adm.files' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL,
        'OPERATOR'  => DxACL::CAN_NOT,
    ),

    '.adm.files-dialog' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL,
        'OPERATOR'  => DxACL::CAN_VIEW,
    ),

    '.adm.files-multi' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL,
        'OPERATOR'  => DxACL::CAN_VIEW,
    ),

    '.adm.files-mce' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL,
        'OPERATOR'  => DxACL::CAN_VIEW,
    ),

    '.adm.page' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
    ),

    '.adm.block' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
    ),

    '.adm.companion' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL ^ DxACL::CAN_DELETE,
        'OPERATOR'  => DxACL::CAN_ALL ^ DxACL::CAN_DELETE,
    ),

    '.adm.faq' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL,
        'OPERATOR'  => DxACL::CAN_ALL ^ DxACL::CAN_DELETE,
    ),

    '.adm.feedback' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL ^ DxACL::CAN_DELETE,
        'OPERATOR'  => DxACL::CAN_NOT,
    ),

    '.adm.request' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL,
        'OPERATOR'  => DxACL::CAN_ALL ^ DxACL::CAN_DELETE,
        'SELLER'    => DxACL::CAN_ALL ^ DxACL::CAN_DELETE,
    ),

    '.adm.product' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL,
        'OPERATOR'  => DxACL::CAN_ALL,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.order' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_NOT,
        'OPERATOR'  => DxACL::CAN_NOT,
        'SELLER'    => DxACL::CAN_ALL ^ DxACL::CAN_DELETE,
    ),

    '.adm.gallery.image' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
    ),

    '.adm.gallery.category' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
    ),

    '.adm.city' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL ^ DxACL::CAN_DELETE,
    ),

    '.adm.touroperator' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
    ),

    '.adm.subdivision' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_NOT,
        'OPERATOR'  => DxACL::CAN_NOT,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.office' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL,
        'OPERATOR'  => DxACL::CAN_NOT,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.staff' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL,
        'OPERATOR'  => DxACL::CAN_NOT,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.country' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_VIEW,
        'OPERATOR'  => DxACL::CAN_VIEW,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.resort' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'OPERATOR'  => DxACL::CAN_VIEW,
        'OPERATOR'  => DxACL::CAN_VIEW,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.publication' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_ALL,
        'OPERATOR'  => DxACL::CAN_NOT,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.settings' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_NOT,
        'OPERATOR'  => DxACL::CAN_NOT,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.hotel' => array(
        'DEVELOPER' => DxACL::CAN_ALL,
        'ADMIN'     => DxACL::CAN_ALL,
        'DIRECTOR'  => DxACL::CAN_VIEW,
        'OPERATOR'  => DxACL::CAN_VIEW,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.menu_group.structure' => array(
        'DEVELOPER' => DxACL::CAN_VIEW,
        'ADMIN'     => DxACL::CAN_VIEW,
        'DIRECTOR'  => DxACL::CAN_VIEW,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.menu_group.gallery' => array(
        'DEVELOPER' => DxACL::CAN_VIEW,
        'ADMIN'     => DxACL::CAN_VIEW,
        'DIRECTOR'  => DxACL::CAN_NOT,
        'OPERATOR'  => DxACL::CAN_NOT,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.menu_group.book' => array(
        'DEVELOPER' => DxACL::CAN_VIEW,
        'ADMIN'     => DxACL::CAN_VIEW,
        'DIRECTOR'  => DxACL::CAN_NOT,
        'OPERATOR'  => DxACL::CAN_NOT,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.menu_group.staff' => array(
        'DEVELOPER' => DxACL::CAN_VIEW,
        'ADMIN'     => DxACL::CAN_VIEW,
        'DIRECTOR'  => DxACL::CAN_VIEW,
        'OPERATOR'  => DxACL::CAN_NOT,
        'SELLER'    => DxACL::CAN_NOT,
    ),

    '.adm.menu_group.messages' => array(
        'DEVELOPER' => DxACL::CAN_VIEW,
        'ADMIN'     => DxACL::CAN_VIEW,
        'DIRECTOR'  => DxACL::CAN_VIEW,
        'OPERATOR'  => DxACL::CAN_VIEW,
        'SELLER'    => DxACL::CAN_VIEW,
    ),
);