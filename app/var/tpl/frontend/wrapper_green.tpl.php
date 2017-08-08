<!DOCTYPE HTML>
<html lang="RU">
    <head>
        <title>{if null !== $__ctx->getPageTitle()}{$__ctx->getPageTitle()|escape} &mdash; {else}Мой Горящий тур{/if}</title>
        <meta charset="UTF-8" />
        <meta name="Googlebot" content="index, follow, snippet" />
        <meta name="Robots" content="index, follow" />
        {assign var='v' value='5.5'}
        <meta name="keywords" content="{$__ctx->getPageKeywords()|escape}" />
        <meta name="cmsmagazine" content="e6f64cc57dd94a6fb868abad52e3860b" />
        <meta name="description" content="{$__ctx->getPageDescription()|escape}" />
        <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="{$__url->img('/favicon.ico')}?{$v}" type="image/x-icon" />
        <link href="{$__url->css('/frontend/base_style_green.css')}?{$v}" rel="stylesheet" media="all" />
        <link href="{$__url->css('/frontend/modals.css')}?{$v}" rel="stylesheet" media="all" />
        <link href="{$__url->css('/frontend/jquery-ui.min.css')}?{$v}" rel="stylesheet" media="all" />
        <script src="http://userapi.com/js/api/openapi.js" type="text/javascript"></script>

        {foreach from=$__ctx->getCss() item='css'}
            <link href="{$css}?{$v}" rel="stylesheet" type="text/css" media="all" />
        {/foreach}

        <!--[if lte IE 8]>
        <link rel="stylesheet" href="{$__url->css('/frontend/ie.css')}" />
        <![endif]-->

        <script src="{$__url->js('/frontend/modernizr.js')}?{$v}"></script>
        <!--[if IE 7]>
        <script src="{$__url->js('/frontend/fixie.js')}?{$v}"></script>
        <![endif]-->
        {if null !== $__ctx->getPageCanonical()}<link rel="canonical" href="{$__ctx->getPageCanonical()}" />{/if}

        {if isset($smarty.request.print)}
            <link href="{$__url->css('/frontend/print.css')}" rel="stylesheet" type="text/css" media="all" />
            {literal}
            <script>
                window.onload = function(){ window.print(); }
            </script>
            {/literal}
        {else}
            <link href="{$__url->css('/frontend/print.css')}" rel="stylesheet" type="text/css" media="print" />
        {/if}
    </head>
    <body>
        <div id="wrap">
            <div id="header">
                <div class="header-logo-l">
                    <a href="{$__url->main()}" title="На главную" style="display: block; position: relative;">
                        <img src="{$__url->img('/frontend/logo_green.gif')}" alt="Логотип" />
                        <img src="{$__url->img('/frontend/logo_green_suffix.gif')}" alt="Логотип" style="position: absolute; right: 15px;" />
                    </a>
                </div>

                <div class="header-logo-r">
                    {foreach $offices_current as $office}
                        <p>
                            т.: {$office->getPhone()|escape}
                            {if $office->getDisplayName()}<span style="font-weight: 400;">({$office->getDisplayName()|escape})</span>{/if}
                        </p>
                    {/foreach}
                </div>

                <div class="header-logo-c">
                    <div class="header-city-selector">
                        <a href="#" style="text-decoration: none;" class="header-city-current">
                            <span style="color: #000;">Ваш город &mdash;</span>
                            <span style="border-bottom: 1px dashed;">{if $user_city}{$user_city->getTitle()|escape}{/if}</span>
                        </a>

                        <div class="cities-list-wrapper">
                            <div class="cities-list clearfix">
                                {foreach $city_alphas as $city_alpha}
                                    <div class="cities-list-part">
                                        {foreach $city_alpha as $city_letter => $cities}
                                            {if empty($cities)}{continue}{/if}

                                            <div class="cities-list-group">
                                                <strong class="cities-list-letter">{$city_letter|escape}</strong>

                                                <ul>
                                                    {foreach $cities as $city}
                                                        <li>
                                                            {if in_array($__ctx->getCurrentCommand()->getCmd(), array('.russia'))}
                                                                <a href="{$city->getToggleUrl()}?to={$city->getUrl('russia', true)}">{$city->getTitle()|escape}</a>
                                                            {else}
                                                                <a href="{$city->getToggleUrl()}">{$city->getTitle()|escape}</a>
                                                            {/if}
                                                        </li>
                                                    {/foreach}
                                                </ul>
                                            </div>
                                        {/foreach}
                                    </div>
                                {/foreach}
                            </div>
                        </div>

                        <div class="city-tooltip-helper-wrapper" style="display: none;">
                            <div class="city-tooltip-helper">
                                <div class="city-tooltip-helper-title">Мы угадали ваш город?</div>

                                <div class="action-buttons">
                                    <button class="city-tooltip-helper-ok">
                                        Да, {$user_city->getTitle()|escape}
                                    </button>

                                    <button class="city-tooltip-helper-cancel">
                                        Нет, выбрать другой
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="header-logo-c-hotphone">
                        Бесплатная горячая линия: <b>8 (800) 500-23-54</b>

                        <a href="#modal-callback" data-source="{$__url->url('/modal/callback/')}" id="modal-callback-activator" class="btn-spawn-callback">
                            Заказать обратный звонок
                        </a>
                    </div>
                </div>

                <div id="line-grey"></div>

                <div class="header-menu">
                    <ul class="header-menu-ssil">
                        {foreach DomainObjectModel_Menu::getNestedMenu($__ctx->getCurrentCommand(), 'main_menu', $__ctx->getMenuAlias(), $__ctx->getPageId()) as $item}
                            <li{if $item@first || $item@last || $item.active} class="{if $item@first} first{/if}{if $item@last} last{/if}{if $item.active} active-wrap{/if}"{/if}>
                                <a href="{$__url->urlByType($item.menu_value, $item.menu_type)}" class="first-level{if $item.menu_is_jump} blank{/if}{if !empty($item.menu_decor)} {$item.menu_decor}{/if}{if $item.active} active{/if}" title="{$item.menu_title|escape}">{$item.menu_title|escape}</a>

                                {if !empty($item.submenu)}
                                    <ul>
                                        {foreach $item.submenu as $subitem}
                                            <li{if $subitem@first || $subitem@last} class="{if $subitem@first}first{else}last{/if}"{/if}>
                                                <a href="{$__url->urlByType($subitem.menu_value, $subitem.menu_type)}" class="second-level{if $subitem.menu_is_jump} blank{/if}">{$subitem.menu_title|escape}</a>
                                            </li>
                                        {/foreach}
                                    </ul>
                                {/if}
                            </li>
                        {/foreach}
                    </ul>
                </div>

                <div class="header-knopki">
                    <ul class="header-knopki-ssil">
                        {foreach DomainObjectModel_Menu::getNestedMenu($__ctx->getCurrentCommand(), 'main_submenu', $__ctx->getMenuAlias(), $__ctx->getPageId()) as $item}
                            <li{if $item@first || $item@last || $item.active} class="{if $item@first} first{/if}{if $item@last} last{/if}{if $item.active} active-wrap{/if}"{/if}>
                                <i class="icon-left"></i>

                                <a href="{$__url->urlByType($item.menu_value, $item.menu_type)}" class="first-level{if $item.menu_is_jump} blank{/if}{if !empty($item.menu_decor)} {$item.menu_decor}{/if}{if $item.active} active{/if}" title="{$item.menu_title|escape}">{$item.menu_title|escape}</a>

                                <i class="icon-right"></i>
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </div>

            <div class="container">
                <div class="b-left-side">
                    {$side_left_html}
                </div>

                <div class="b-center-side{if $__ctx->getWrapperAddClass()} {$__ctx->getWrapperAddClass()|escape}{/if}">
                    {if $user_city->getTopNews()}
                        <div class="top-news">
                            {$user_city->getTopNews()|escape|nl2br}
                        </div>
                    {/if}

                    {$side_center_html}

                    <div id="mod-turi">
                        {$body_html}
                    </div>
                </div>

                <div class="b-right-side">
                    {$side_right_html}
                </div>
            </div>

            <div class="clearfix"></div>

            <footer class="site-footer">
                <img src="{$__url->img('/frontend/logo_visa_mastercard.gif')}" width="88" height="29" alt="Логотип Visa и MasterCard" />

                <a href="{$__url->main()}" style="padding-left: 30px;">Мой горящий тур</a>

                &copy; {$smarty.now|date_format:"%Y"}
                Информация о ценах, указанная на сайте, является ориентировочной, не является ни рекламой, ни офертой.

                {if empty($smarty.session.access_code_available)}
                    <div class="secure-activator">
                        <i class="icon-secure-login"></i>

                        <form action="" method="post" class="secure-login-form">
                            <input type="password" name="secure_code" value="">

                            <button type="submit" name="__submit_seccode" value="1">&rarr;</button>
                        </form>
                    </div>
                {else}
                    <div class="secure-activator">
                        <form action="" method="post">
                            <button type="submit" name="__submit_seccode_exit" value="1" onclick="return confirm('Вы уверены? Вы больше не сможете видеть служебную информацию по турам.');">Выход</button>
                        </form>
                    </div>
                {/if}

                {if DxApp::getEnv() == DxApp::ENV_PRODUCTION}
                    <div class="counters">
                        {include file='frontend/include/counters.tpl.php'}
                    </div>
                {/if}
            </footer>
        </div>

        <div id="modal-rules-feedback-photos" class="modal modal-rules-feedback-photos hidden fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        {$blocks['COMMON']['rules_feedback_photos']}
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-personal-data-processing" class="modal modal-personal-data-processing hidden fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        {$blocks['COMMON']['personal_data_processing']}
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-callback" class="modal modal-request-callback-form hidden fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <div class="modal-body-inner">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{$__url->js('/frontend/jquery-1.9.1.min.js')}?{$v}"></script>
        <script src="{$__url->js('/frontend/jquery-ui.min.js')}?{$v}"></script>
        <script src="{$__url->js('/frontend/jquery.selectbox.js')}?{$v}"></script>
        <script src="{$__url->js('/frontend/modals.js')}?{$v}"></script>
        <script src="{$__url->js('/frontend/app.js')}?{$v}"></script>
        <script src="{$__url->js('/frontend/client.js')}?{$v}"></script>
        <script src="{$__url->js('/frontend/jquery.mask.min.js')}?{$v}"></script>
        <script src="{$__url->js('/frontend/jquery.cookie.js')}?{$v}"></script>
        <script src="{$__url->js('/frontend/totop.js')}?{$v}"></script>

        {foreach from=$__ctx->getJs() item='js'}
            <script src="{$js}?{$v}"></script>
        {/foreach}

        <div class="toTop toTop-up"><a href="#">&uarr; Наверх</a></div>

        <script type="text/javascript">
            var _json_office_staffs = {$offices_staffs};
        </script>
    </body>
</html>