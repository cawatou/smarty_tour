<?php defined('DX_CFG_DIR') or die('No direct access allowed');

return array(
    // User roles are allowed to control panel
    'allow_roles' => array('DEVELOPER', 'ADMIN', 'OPERATOR'),

    // Command to go after login in control panel
    'default_cmd' => array(
        'DEVELOPER' => '.adm.user',
        'ADMIN'     => '.adm.user',
        'DIRECTOR'  => '.adm.product',
        'OPERATOR'  => '.adm.product',
        'SELLER'    => '.adm.order',
    ),

    // Name of site is displayed in the header of control panel
    'title'       => 'Мой горящий тур',

    // Activate the settings
    'settings'    => true,

    // Available sections for the control panel
    'sections'    => array(
        array(
            'cmd'   => '.adm.user',
            'title' => 'Пользователи',
        ),

        array(
            'cmd'   => '.adm.files',
            'title' => 'Файлы',
        ),

        array(
            'title' => 'Структура и контент',
            'cmd'   => '.adm.menu_group.structure',
            'related' => array(
                '.adm.menu' => array(
                    'title' => 'Меню',
                ),
                '.adm.block' => array(
                    'title' => 'Блоки',
                ),
                '.adm.page' => array(
                    'title' => 'Страницы',
                ),
                '.adm.publication' => array(
                    'title' => 'Публикации',
                ),
            ),
        ),

        array(
            'title' => 'Галереи',
            'cmd'   => '.adm.menu_group.gallery',
            'related' => array(
                '.adm.gallery.image' => array(
                    'title' => 'Изображения',
                ),
                '.adm.gallery.category' => array(
                    'title' => 'Категории',
                ),
            ),
        ),

        array(
            'title' => 'Сообщения',
            'cmd'   => '.adm.menu_group.messages',
            'related' => array(
                '.adm.faq' => array(
                    'title' => 'Вопросы',
                ),
                '.adm.feedback' => array(
                    'title' => 'Отзывы',
                ),
                '.adm.request' => array(
                    'title' => 'Заявки',
                ),
                '.adm.companion' => array(
                    'title' => 'Поиск попутчика',
                ),
            ),
        ),

        array(
            'cmd'   => '.adm.product',
            'title' => 'Туры',
        ),

        array(
            'cmd'   => '.adm.order',
            'title' => 'Заказы',
        ),

        array(
            'title' => 'Подразделения',
            'cmd'   => '.adm.menu_group.staff',
            'related' => array(
                '.adm.city' => array(
                    'title' => 'Города',
                ),
                '.adm.subdivision' => array(
                    'title' => 'Подразделения',
                ),
                '.adm.office' => array(
                    'title' => 'Офисы',
                ),
                '.adm.staff' => array(
                    'title' => 'Сотрудники',
                ),
            ),
        ),

        array(
            'title' => 'Справочники',
            'cmd'   => '.adm.menu_group.book',
            'related' => array(
                '.adm.touroperator' => array(
                    'title' => 'Туроператоры',
                ),
                '.adm.country' => array(
                    'title' => 'Страны',
                ),
                '.adm.resort' => array(
                    'title' => 'Курорты',
                ),
                '.adm.hotel' => array(
                    'title' => 'Отели',
                ),
            ),
        ),
    ),
);