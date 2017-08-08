{$__ctx->addCss('/backend/files_popup.css')}
{$__ctx->addJs('/backend/files_popup.js')}
{$__ctx->addJs('/backend/form/menu.js')}

{if $__f->successful}
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {'Данные о пункте меню изменены'|t}
</div>
{/if}
{if !empty($__f->errors)}
<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {'Произошла ошибка, проверьте правильность заполнения полей'|t}
</div>
{/if}

<form role="form" method="post" action="{$__f->getUrl()}">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('menu_title'))} has-error{/if}">
                <label for="{$__f->encode('menu_title')}">{'Название'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('menu_title')}" value="{$__f->m()->getTitle()|escape}" id="{$__f->encode('menu_title')}">
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="{if !$__ctx->getCurrentUser()->isDeveloper()}hidden {/if}row">
        <div class="col-md-4">
            <div class="form-group{if !is_null($__f->e('menu_alias'))} has-error{/if}">
                <label for="{$__f->encode('menu_alias')}">Alias</label>
                <input type="text" class="form-control" name="{$__f->encode('menu_alias')}" value="{$__f->m()->getAlias()|escape}" id="{$__f->encode('menu_alias')}">
                <span class="help-block help-block-error">{if $__f->e('menu_alias') == 'INVALID_FORMAT'}Неверный формат. Допускаются только a-z, -, _{else}{'Обязательное поле'|t}{/if}</span>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="form-group{if !is_null($__f->e('menu_cover'))} has-error{/if}">
                <label for="{$__f->encode('menu_cover')}">{'Изображение'|t}</label>
                <div class="input-group">
                    {if null !== $__f->m()->getCover() && null === $__f->e('menu_cover')}
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-preview" data-image-path="{$__url->thumb($__f->m()->getCover(), 150, 150)}"><i class="fa fa-eye"></i></button>
                    </span>
                    {/if}
                    <input type="text" class="form-control" name="{$__f->encode('menu_cover')}" value="{$__f->m()->getCover()|escape}" id="{$__f->encode('menu_cover')}">
                    <span class="input-group-btn">
                        <button class="btn btn-default" onclick="return dialog.show('{$__url->adm('.files-dialog', '?history')}', '{$__f->encode('menu_cover')}');"><i class="fa fa-search"></i> {'Обзор'|t}</button>
                    </span>
                </div>
                <span class="help-block help-block-error">{if $__f->e('menu_cover') == 'IMAGE_UNSUPPORTED'}Этот формат изображений не поддерживается (используйте GIF, PNG, JPG){else}Файл не существует или не является картинкой ({$__f->e('product_category_cover')}){/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if !is_null($__f->e('menu_decor'))} has-error{/if}">
                <label for="{$__f->encode('menu_decor')}">{'Декор'|t}</label>
                <input type="text" class="form-control" name="{$__f->encode('menu_decor')}" value="{$__f->m()->getDecor()|escape}" id="{$__f->encode('menu_decor')}">
                <span class="help-block">Например, имя css-класса для оформления пункта меню</span>
            </div>
        </div>
    </div>

    {if !empty($menu_roots_tree)}
        <div class="row js-path-box">
            <div class="col-md-4">
                <div class="form-group form-group-required{if !is_null($__f->e('parent_id'))} has-error{/if}">
                    <label for="{$__f->encode('parent_id')}">{'Путь'|t}</label>
                    <select name="{$__f->encode('parent_id')}" id="{$__f->encode('parent_id')}" class="form-control">
                        {if $__f->m()->getParent()}
                            {assign var="parent_id" value=$__f->m()->getParent()->getId()}
                            {else}
                            {assign var="parent_id" value=0}
                        {/if}
                        {foreach $menu_roots_tree as $menu_tree}
                            {foreach $menu_tree as $p}
                                <option value="{$p.menu_id|escape}"{if $p.menu_id == $parent_id} selected{/if}{if $__f->m()->getId() == $p.menu_id || $__f->m()->getLevel() < $p.level && $__f->m()->getRgt() > $p.rgt && $__f->m()->getLft() < $p.lft} disabled{/if}>{'&bull;'|str_repeat:$p.level} {$p.menu_title|escape}</option>
                            {/foreach}
                        {/foreach}
                    </select>
                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                </div>
            </div>
        </div>
    {/if}

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('menu_type'))} has-error{/if}">
                <label for="{$__f->encode('menu_type')}">{'Тип'|t}</label>
                <select name="{$__f->encode('menu_type')}" id="{$__f->encode('menu_type')}" class="form-control js-menu-type">
                {foreach $menu_types as $type=> $title}
                    <option value="{$type|escape}"{if $type == $__f->m()->getType()} selected{/if}{if in_array($type, array('MENU_ROOT')) && !$__ctx->getCurrentUser()->isDeveloper()} disabled{/if}>{$title|escape}</option>
                {/foreach}
                </select>
                <span class="help-block help-block-error">{'Неверное значение'|t}</span>
            </div>
        </div>
    </div>

    <div class="row js-common-box">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('menu_value'))} has-error{/if}">
                <label for="{$__f->encode('menu_value')}_common">{'Значение'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('menu_value')}[COMMON]" value="{$__f->m()->getValue()|escape}" id="{$__f->encode('menu_value')}_common">
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row js-cmd-box">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('menu_value'))} has-error{/if}">
                <label for="{$__f->encode('menu_value')}_cmd">{'Значение'|t}</label>
                <select name="{$__f->encode('menu_value')}[CMD]" id="{$__f->encode('menu_value')}_cmd" class="form-control">
                {foreach DxApp::config('commands') as $cmd => $cmd_data}
                    {if !empty($cmd_data.use_for_menu)}
                        <option value="{$cmd|escape}"{if $cmd == $__f->m()->getValue()} selected{/if}>{$cmd_data.title|escape}</option>
                    {/if}
                {/foreach}
                </select>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row js-page-box">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('menu_value'))} has-error{/if}">
                <label for="{$__f->encode('menu_value')}_page">{'Значение'|t}</label>
                <select name="{$__f->encode('menu_value')}[PAGE]" id="{$__f->encode('menu_value')}_page" class="form-control">
                {assign var='disabled_page' value=0}
                {foreach $pages_tree as $p}
                    {if $p.page_status == 'DISABLED'}{assign var='disabled_page' value=$p}{/if}
                    <option value="{$p.page_id|escape}"{if $p.page_id == $__f->m()->getValue()} selected{/if}{if !empty($disabled_page) && $disabled_page.lft <= $p.lft && $disabled_page.rgt >= $p.rgt || $p.level == 0} disabled{/if}>{'&bull;'|str_repeat:$p.level} {if empty($p.page_name)}{$p.page_title|escape}{else}{$p.page_name|escape}{/if}</option>
                {/foreach}
                </select>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="{$__f->encode('menu_is_jump')}" value="1"{if $__f->m()->getIsJump()} checked{/if}> {'Открыть в новом окне'|t}
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">{'Статус'|t}</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('menu_status')}" value="ENABLED"{if $__f->m()->getStatus() == 'ENABLED'} checked{/if}> {'Показывать'|t}
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('menu_status')}" value="DISABLED"{if $__f->m()->getStatus() == 'DISABLED'} checked{/if}> {'Скрывать'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
            {if $__f->getId() == 'menu_edit'}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
            {else}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{if $__f->m()->getParent()}{'Добавить пункт меню'|t}{else}{'Добавить меню'|t}{/if}</button>
            {/if}
                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.menu')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>