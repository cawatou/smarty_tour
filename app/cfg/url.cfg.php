<?php defined('DX_CFG_DIR') or die('No direct access allowed');

/**
 * :any - [^,^.^/^?]
 * :num - [0-9]
 */

 /**
  * Example:
  * 'uri' => array(
  *    'base' => null,
  *    'host' => ya.ru,
  *    'auto_detect' => true,
  * ),
  */

return array(
    'uri' => array(
        'base' => null,
        'host' => null,
    ),

    'static' => array(
        'css'       => '/static/css',
        'js'        => '/static/js',
        'img'       => '/static/img',
        'files'     => '/static/files',
        'thumbs'    => '/static/thumbs',
        'bootstrap' => '/static/bootstrap',
    ),

    'routes' => array(
        array(
            'rule'            => 'order/create/(:num+)/(:any+)/(:any+)/(:num+)',
            'cmd'             => '.order.create',
            'product_id'      => '$1',
            'departure_date'  => '$2',
            'hotel_signature' => '$3',
            'hotel_price'     => '$4',
        ),

        array(
            'rule'          => 'ads/(:any+)/(:any+)',
            'cmd'           => '.ads',
            'from_alias'    => '$1',
            'country_alias' => '$2',
        ),

        array(
            'rule'          => 'russia/ads/(:any+)',
            'cmd'           => '.russia.ads',
            'from_alias'    => '$1',
        ),

        array(
            'rule'      => 'order/(:any+)/(:any+)',
            'cmd'       => '.order.view',
            'signature' => '$1',
            'callback'  => '$2',
        ),

        array(
            'rule'      => 'order/(:any+)',
            'cmd'       => '.order.view',
            'signature' => '$1',
        ),

        array(
            'rule'     => 'payonline/success/(:num+)',
            'cmd'      => '.payonline.success',
            'order_id' => '$1',
        ),

        array(
            'rule'     => 'payonline/fail/(:num+)',
            'cmd'      => '.payonline.fail',
            'order_id' => '$1',
        ),

        array(
            'rule'      => 'order/(:any+)',
            'cmd'       => '.order',
            'signature' => '$1',
        ),

        array(
            'rule' => 'upload/(:any+)',
            'cmd'  => '.uploader',
            'op'   => '$1',
        ),

        array(
            'rule'  => 'city/(:num+)',
            'cmd'   => '.city',
            'id'    => '$1',
        ),

        array(
            'rule'  => 'news/(:num+)/(:any+)',
            'cmd'   => '.news.details',
            'id'    => '$1',
            'alias' => '$2',
        ),

        array(
            'rule' => 'tours/(:any+)/(:num+)',
            'cmd'  => '.tours.details',
            'city' => '$1',
            'id'   => '$2',
        ),

        array(
            'rule' => '(hotel|tours)/(:num+)',
            'cmd'  => '.$1.details',
            'id'   => '$2',
        ),

        array(
            'rule' => 'russia/(:num+)',
            'cmd'  => '.russia.details',
            'id'   => '$1',
        ),

        array(
            'rule' => '(tours|russia)/(:any+)',
            'cmd'  => '.$1',
            'city' => '$2',
        ),

        array(
            'rule' => '(:any+)/tours',
            'cmd'  => '.tours',
            'city' => '$1',
        ),

        array(
            'rule' => 'contact/(:any+)',
            'cmd'  => '.contacts',
            'city' => '$1',
        ),

        array(
            'rule' => 'turistam/oformlenie_zagranpasporta/(:any+)',
            'cmd'  => '.turistam.oformlenie_zagranpasporta',
            'city' => '$1',
        ),

        array(
            'rule' => 'adm/quiz/option(?:/(:any+))?',
            'cmd'  => '.adm.quiz.option',
            'op'   => '$1',
        ),

        array(
            'rule' => 'adm/(:any+)/(category|image|group)(?:/(:any+))?',
            'cmd'  => '.adm.$1.$2',
            'op'   => '$3',
        ),

        array(
            'rule' => 'adm/(:any+)(?:/(:any+))?',
            'cmd'  => '.adm.$1',
            'op'   => '$2',
        ),

        array(
            'rule' => '(:any+)/(:any+)/(:any+)(?:,(:num+))?',
            'cmd'  => '.$1.$2.$3',
            'page' => '$4',
        ),

        array(
            'rule' => '(:any+)/(:any+)(?:,(:num+))?',
            'cmd'  => '.$1.$2',
            'page' => '$3',
        ),

        array(
            'rule' => '(:any+)(?:,(:num+))?',
            'cmd'  => '.$1',
            'page' => '$2',
        ),
    ),
);