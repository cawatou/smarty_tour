{$__ctx->addJs('/backend/tiny_mce/tiny_mce.js')}
{$__ctx->addJs('/backend/tiny_mce.js')}
{$__ctx->addJs('/backend/form/block.js')}

{$__ctx->addCss('../js/backend/codemirror/codemirror.css')}
{$__ctx->addJs('/backend/codemirror/codemirror.js')}
{$__ctx->addJs('/backend/codemirror/mode/xml.js')}
{$__ctx->addJs('/backend/codemirror/mode/javascript.js')}
{$__ctx->addJs('/backend/codemirror/mode/vbscript.js')}
{$__ctx->addJs('/backend/codemirror/mode/htmlmixed.js')}
{$__ctx->addJs('/backend/codemirror.js')}

{literal}
<script type="text/javascript">
    var TINY_FILEMANAGER_PATH = '{/literal}{$__url->adm('.files-mce')}{literal}';
    var
        toggle_id        = '{/literal}{$__f->encode('block_type')}{literal}',
        box_pain_id    = '{/literal}{$__f->encode('box_plain')}{literal}',
        box_wysiwyg_id = '{/literal}{$__f->encode('box_wysiwyg')}{literal}',
        box_code_id    = '{/literal}{$__f->encode('box_code')}{literal}',
        textarea_wysiwyg_id = '{/literal}{$__f->encode('block_content')}{literal}',
        textarea_code_id    = '{/literal}{$__f->encode('block_content_code')}{literal}';

</script>
{/literal}

{if $__f->successful}
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {'Данные о блоке изменены'|t}
</div>
{/if}
{if !empty($__f->errors)}
<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {'Произошла ошибка, проверьте правильность заполнения полей'|t}
</div>
{/if}
<form role="form" method="post" action="{$__f->getUrl()}">
    <div class="{if !$__ctx->getCurrentUser()->isDeveloper()}hidden {/if}row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('block_name'))} has-error{/if}">
                <label for="{$__f->encode('block_name')}">{'Название'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('block_name')}" value="{$__f->m()->getName()|escape}" id="{$__f->encode('block_name')}">
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="{if !$__ctx->getCurrentUser()->isDeveloper()}hidden {/if}row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('block_category'))} has-error{/if}">
                <label for="{$__f->encode('block_category')}">{'Группа'|t}</label>
                <select name="{$__f->encode('block_category')}" id="{$__f->encode('block_category')}" class="form-control">
                {foreach $__f->m()->getCategories() as $k => $v}
                    <option value="{$k}"{if $__f->m()->getCategory() == $k} selected="selected"{/if}>{$v.title|escape}</option>
                {/foreach}
                </select>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="{if !$__ctx->getCurrentUser()->isDeveloper()}hidden {/if}row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('block_alias'))} has-error{/if}">
                <label for="{$__f->encode('block_alias')}">Alias <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('block_alias')}" value="{$__f->m()->getAlias()|escape}" id="{$__f->encode('block_alias')}">
                <span class="help-block help-block-error">{if $__f->e('block_alias') == 'ALREADY_EXISTS'}Блок с таким alias уже существует{elseif $__f->e('block_alias') == 'INVALID_FORMAT'}Обязательное поле. Допускаются только a-z, -, _{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if !is_null($__f->e('block_title'))} has-error{/if}">
                <label for="{$__f->encode('block_title')}">{'Заголовок'|t}</label>
                <input type="text" class="form-control" name="{$__f->encode('block_title')}" value="{$__f->m()->getTitle()|escape}" id="{$__f->encode('block_title')}">
            </div>
        </div>
    </div>

    <div class="row" id="{$__f->encode('box_wysiwyg')}">
        <div class="col-md-8">
            <div class="form-group form-group-required{if !is_null($__f->e('block_content'))} has-error{/if}">
                <label for="{$__f->encode('block_content')}">{'Текст'|t} <i class="fa fa-check"></i></label>
                <textarea name="{$__f->encode('block_content')}" id="{$__f->encode('block_content')}" class="form-control form-textarea-vertical">{$__f->m()->getContent()|escape}</textarea>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row" id="{$__f->encode('box_plain')}">
        <div class="col-md-8">
            <div class="form-group form-group-required{if !is_null($__f->e('block_content'))} has-error{/if}">
                <label for="{$__f->encode('block_content_plain')}">{'Текст'|t} <i class="fa fa-check"></i></label>
                <textarea name="{$__f->encode('block_content')}" id="{$__f->encode('block_content_plain')}" class="form-control form-textarea-vertical">{$__f->m()->getContent()|escape}</textarea>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row" id="{$__f->encode('box_code')}">
        <div class="col-md-8">
            <div class="form-group form-group-required{if !is_null($__f->e('block_content'))} has-error{/if}">
                <label for="{$__f->encode('block_content_code')}">{'Текст'|t} <i class="fa fa-check"></i></label>
                <textarea name="{$__f->encode('block_content')}" id="{$__f->encode('block_content_code')}" class="form-control form-textarea-vertical">{$__f->m()->getContent()|escape}</textarea>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="{if !$__ctx->getCurrentUser()->isDeveloper()}hidden {/if}row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('block_type'))} has-error{/if}">
                <label for="{$__f->encode('block_type')}">{'Тип блока'|t}</label>
                <select name="{$__f->encode('block_type')}" id="{$__f->encode('block_type')}" class="form-control">
                    <option value="TEXT"{if $__f->m()->getType() == 'TEXT'} selected{/if}>Текст</option>
                    <option value="WYSIWYG"{if $__f->m()->getType() == 'WYSIWYG'} selected{/if}>Редактор</option>
                    <option value="CODE"{if $__f->m()->getType() == 'CODE'} selected{/if}>Код</option>
                </select>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
            {if $__f->getId() == 'block_edit'}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
                {else}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить блок'|t}</button>
            {/if}
                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.block')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>