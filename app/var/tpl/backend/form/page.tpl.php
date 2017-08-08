{$__ctx->addJs('/backend/form/page.js')}

{$__ctx->addCss('/backend/files_popup.css')}
{$__ctx->addJs('/backend/files_popup.js')}

{if $__f->m()->getType() == 'WYSIWYG'}
    {$__ctx->addJs('/backend/tiny_mce/tiny_mce.js')}
    {$__ctx->addJs('/backend/tiny_mce.js')}

    {literal}
    <script type="text/javascript">
        var
            TINY_FILEMANAGER_PATH = '{/literal}{$__url->adm('.files-mce')}{literal}',
            TEXTAREA_WYSIWYG_ID = '{/literal}{$__f->encode('page_content')}{literal}';
    </script>
    {/literal}
{elseif $__f->m()->getType() == 'CODE'}
    {$__ctx->addCss('../js/backend/codemirror/codemirror.css')}
    {$__ctx->addJs('/backend/codemirror/codemirror.js')}
    {$__ctx->addJs('/backend/codemirror/mode/xml.js')}
    {$__ctx->addJs('/backend/codemirror/mode/javascript.js')}
    {$__ctx->addJs('/backend/codemirror/mode/vbscript.js')}
    {$__ctx->addJs('/backend/codemirror/mode/htmlmixed.js')}
    {$__ctx->addJs('/backend/codemirror.js')}
    {literal}
    <script type="text/javascript">
        var TEXTAREA_CODE_ID = '{/literal}{$__f->encode('page_content')}{literal}';
    </script>
    {/literal}
{/if}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Данные о странице изменены'|t}
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
            <div class="form-group{if !is_null($__f->e('page_name'))} has-error{/if}">
                <label for="{$__f->encode('page_name')}">{'Название'|t}</label>
                <input type="text" class="form-control" name="{$__f->encode('page_name')}" value="{$__f->m()->getName()|escape}" id="{$__f->encode('page_name')}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('page_title'))} has-error{/if}">
                <label for="{$__f->encode('page_title')}">{'Название (Заголовок страницы)'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('page_title')}" value="{$__f->m()->getTitle()|escape}" id="{$__f->encode('page_title')}">
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('page_alias'))} has-error{/if}">
                <label for="{$__f->encode('page_alias')}">Alias <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('page_alias')}" value="{$__f->m()->getAlias()|escape}" id="{$__f->encode('page_alias')}">
                <span class="help-block help-block-error">{if $__f->e('page_alias') == 'INVALID_FORMAT'}Неверный формат. Допускаются только a-z, -, _{elseif $__f->e('page_alias') == 'ALIAS_ALREADY_EXISTS'}Такой alias уже используется{else}{'Обязательное поле'|t}{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('parent_id'))} has-error{/if}">
                <label for="{$__f->encode('parent_id')}">{'Путь'|t}</label>
                <select name="{$__f->encode('parent_id')}" id="{$__f->encode('parent_id')}" class="form-control">
                {assign var="parent_id" value=$__f->m()->getParent()->getId()}
                {foreach $pages_tree as $p}
                    <option value="{$p.page_id|escape}"{if $p.page_id == $parent_id} selected{/if}{if $__f->m()->getId() == $p.page_id || $__f->m()->getLevel() < $p.level && $__f->m()->getRgt() > $p.rgt && $__f->m()->getLft() < $p.lft} disabled{/if}>{'&bull;'|str_repeat:$p.level} {if empty($p.page_name)}{$p.page_title|escape}{else}{$p.page_name|escape}{/if}</option>
                {/foreach}
                </select>
            {if $__f->getId() == 'page_edit'}
                <span class="help-block">Ссылка: <a href="{$__url->url($__f->m()->getPath())}" class="blank">{$__url->url($__f->m()->getPath())}</a></span>
            {/if}
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('page_cover') !== null} has-error{/if}">
                <label for="{$__f->encode('page_cover')}">{'Обложка'|t}</label>
                <div class="input-group">
                    {if $__f->getModel()->getCover() !== null}
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-preview" data-image-path="{$__url->thumb($__f->getModel()->getCover(), 150, 150)}"><i class="fa fa-eye"></i></button>
                        </span>
                    {/if}

                    <input type="text" class="form-control" name="{$__f->encode('page_cover')}" value="{$__f->getModel()->getCover()|escape}" id="{$__f->encode('page_cover')}">

                    <span class="input-group-btn">
                        <button class="btn btn-default" onclick="return dialog.show('{$__url->adm('.files-dialog', '?history')}', '{$__f->encode('page_cover')}');">
                            <i class="fa fa-search"></i> Обзор
                        </button>
                    </span>
                </div>

                <span class="help-block help-block-error">{if $__f->e('page_cover') === 'IMAGE_UNSUPPORTED'}Этот формат изображений не поддерживается (используйте GIF, PNG, JPG){else}Файл не существует или не является картинкой ({$__f->e('page_cover')}){/if}</span>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-8">
            <div class="form-group{if !is_null($__f->e('page_keywords'))} has-error{/if}">
                <label for="{$__f->encode('page_keywords')}">{'Ключевые слова (keywords)'|t}</label>
                <textarea name="{$__f->encode('page_keywords')}" id="{$__f->encode('page_keywords')}" class="form-control form-textarea-vertical">{$__f->m()->getKeywords()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-8">
            <div class="form-group{if !is_null($__f->e('page_description'))} has-error{/if}">
                <label for="{$__f->encode('page_description')}">{'Описание (description)'|t}</label>
                <textarea name="{$__f->encode('page_description')}" id="{$__f->encode('page_description')}" class="form-control form-textarea-vertical">{$__f->m()->getDescription()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('page_cmd'))} has-error{/if}">
                <label for="{$__f->encode('page_cmd')}">{'Декоратор'|t}</label>
                <select name="{$__f->encode('page_cmd')}" id="{$__f->encode('page_cmd')}" class="form-control">
                {foreach $__f->m()->getDecorators() as $decorator}
                    <option value="{$decorator.cmd|escape}"{if $decorator.cmd == $__f->m()->getCmd()} selected{/if}>{$decorator.title|escape}</option>
                {/foreach}
                </select>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group{if !is_null($__f->e('page_content'))} has-error{/if}">
                <label for="{$__f->encode('page_content')}">{if $__f->m()->getType() == 'WYSIWYG'}{'Текст страницы'|t}{elseif $__f->m()->getType() == 'CODE'}{'Код страницы'|t}{/if}</label>
                <textarea name="{$__f->encode('page_content')}" id="{$__f->encode('page_content')}" class="form-control form-textarea-vertical">{$__f->m()->getContent()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">{'Статус'|t}</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('page_status')}" value="ENABLED"{if $__f->m()->getStatus() == 'ENABLED'} checked{/if}> {'Показывать'|t}
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('page_status')}" value="DISABLED"{if $__f->m()->getStatus() == 'DISABLED'} checked{/if}> {'Скрывать'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
            {if $__f->getId() == 'page_edit'}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
            {else}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить страницу'|t}</button>
            {/if}
                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.page')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>