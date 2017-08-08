<!DOCTYPE html>
<html lang="en">
<head>
    <title>{if !is_null($__ctx->getData('page_title'))}{$__ctx->getData('page_title')|escape} &mdash; {/if}{'Панель управления'|t}</title>
    <meta charset="utf-8" />
    {assign var='v' value='1.3'}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="dxcms v4.5" />
    <link rel="shortcut icon" href="{$__url->img('/favicon.ico')}?{$v}" />

    <link href="{$__url->bootstrap('/css/bootstrap.min.css')}?{$v}" rel="stylesheet" />
    <link href="{$__url->css('/backend/font-awesome.css')}?{$v}" rel="stylesheet" />
    <link href="{$__url->css('/backend/layout.css')}?{$v}" rel="stylesheet" />
    {if $type == 'SIGNIN'}<link href="{$__url->css('/backend/module/signin.css')}?{$v}" rel="stylesheet" />{/if}
    {foreach from=$__ctx->getCss() item='css'}
    <link href="{$css}?{$v}" rel="stylesheet" />
    {/foreach}

    <!--[if lt IE 9]>
        <script src="{$__url->js('/backend/html5shiv.js')}"></script>
    <![endif]-->
</head>
{if $type == 'SIGNIN'}
<body class="body-signin">
{elseif $type == 'COMMON' || $type == 'DIALOG'}
<body>
{/if}

{if $type == 'SIGNIN'}
{$html}
{elseif $type == 'DIALOG'}
<section class="cms-body-dialog">
    {$html}
</section>
{elseif $type == 'COMMON'}
<header class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">{'Показать меню'|t}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse navbar-left">
            <ul class="nav navbar-nav navbar-right">
            {foreach $config.sections as $section}
                {if empty($section.related)}
                    {if $__ctx->getCurrentUser()->canView($section.cmd)}<li{if $__ctx->getCurrentCommand()->getCmd() == $section.cmd} class="active"{/if}><a href="{$__url->url($section.cmd, true)}">{$section.title|escape}</a></li>{/if}
                {else}
                    {if $__ctx->getCurrentUser()->canView($section.cmd)}
                    <li class="dropdown{if array_key_exists($__ctx->getCurrentCommand()->getCmd(), $section.related)} active{/if}">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{$section.title|escape} <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                        {foreach $section.related as $cmd => $related}
                            {if $__ctx->getCurrentUser()->canView($cmd)}<li><a href="{$__url->url($cmd, true)}">{$related.title|escape}</a></li>{/if}
                        {/foreach}
                        </ul>
                    </li>
                    {/if}
                {/if}
            {/foreach}
            </ul>
        </div>
    </div>
</header>
<section class="cms-header">
    <div class="container">
        <div class="row">
            <div class="col col-md-8">
                {if !empty($config.title)}
                <h1>{$config.title|escape} <a href="{$__url->main()}" class="cms-header-tomain" title="{'Перейти на главную страницу сайта'|t}"><i class="fa fa-home"></i> {'Перейти на сайт'|t}</a></h1>
                {/if}
                <p>{'Вы вошли как'|t} <span class="label label-primary"><i class="fa fa-user"></i> {$__ctx->getCurrentUser()->getName()|escape}</span> &mdash; {$__ctx->getCurrentUser()->getRoleTitle()|@mb_strtolower}</p>
            </div>
            <div class="col col-md-4 text-right cms-header-buttons">

            {if !empty($config.langs)}
                <div class="btn-group">
                {foreach $config.langs as $locale => $title}
                    <a href="{$__url->adm('.i18n.set')}?locale={$locale}" class="btn {if $__ctx->getCurrentLocale() == $locale}btn-primary active{else}btn btn-default{/if}">{$title}</a>
                {/foreach}
                </div>
            {/if}

            {if !empty($config.settings) && $__ctx->getCurrentUser()->canView('.adm.settings')}
                <a href="{$__url->adm('.settings')}" class="btn btn-default" title="{'Настройки'|t}"><i class="fa fa-cog"></i></a>
            {/if}
                <a href="{$__url->adm('.signOut')}" class="btn btn-warning cms-header-signout" title="{'Завершить сеанс работы'|t}"><i class="fa fa-sign-out"></i> {'Выход'|t}</a>
            </div>
        </div>
    </div>
</section>
<section class="cms-body">
    {$html}
</section>
<footer class="cms-footer">
    <div class="container">
        <div class="row">
            <div class="col col-md-12 text-right">
                Dynamic Executor {include file="backend/v.tpl.php"}
                {*
                Комментарии о работе с системой и по работе с заказчиком можно получить по yaa@rosapp.ru
                <br />{'Разработано ООО «Росапп»'|t}
                <br /><a href="http://www.rosapp.ru" class="blank">{'Техническая поддержка'|t}</a>
                *}
            </div>
        </div>
    </div>
</footer>
{/if}
    <script src="{$__url->js('/backend/jquery-1.10.2.min.js')}?{$v}"></script>
    <script src="{$__url->bootstrap('/js/bootstrap.min.js')}?{$v}"></script>
    <script src="{$__url->js('/backend/jquery.cookie.js')}?{$v}"></script>
    {foreach from=$__ctx->getJs() item='js'}
    <script src="{$js}?{$v}"></script>
    {/foreach}
    <script src="{$__url->js('/backend/app.js')}?{$v}"></script>
</body>
</html>