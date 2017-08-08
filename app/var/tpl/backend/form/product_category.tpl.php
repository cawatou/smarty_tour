{$__ctx->addCss('/backend/files_popup.css')}
{$__ctx->addJs('/backend/files_popup.js')}

{if $__f->successful}
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {'Данные о категории изменены'|t}
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
            <div class="form-group form-group-required{if !is_null($__f->e('product_category_title'))} has-error{/if}">
                <label for="{$__f->encode('product_title')}">{'Название'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('product_category_title')}" value="{$__f->m()->getTitle()|escape}" id="{$__f->encode('product_category_title')}">
                <span class="help-block help-block-error">{if $__f->e('product_category_title') == 'ALREADY_EXISTS'}Категория с таким названием уже существует{else}{'Обязательное поле'|t}{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('product_category_alias'))} has-error{/if}">
                <label for="{$__f->encode('product_category_alias')}">Alias <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('product_category_alias')}" value="{$__f->m()->getAlias()|escape}" id="{$__f->encode('product_category_alias')}">
                <span class="help-block help-block-error">{if $__f->e('product_category_alias') == 'ALREADY_EXISTS'}Категория с таким alias уже существует{elseif $__f->e('product_category_alias') == 'INVALID_FORMAT'}Неверный формат. Допускаются только a-z, -, _{else}Не может быть пустым{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if !is_null($__f->e('parent_id'))} has-error{/if}">
                <label for="{$__f->encode('parent_id')}">{'Подкатегория'|t}</label>
                <select name="{$__f->encode('parent_id')}" id="{$__f->encode('parent_id')}" class="form-control">>
                {assign var="parent_id" value=$__f->m()->getParent()->getId()}
                {foreach $product_categories_tree as $pc}
                    <option value="{$pc.product_category_id|escape}"{if $pc.product_category_id == $parent_id} selected{/if}{if $__f->m()->getId() == $pc.product_category_id || $__f->m()->getLevel() < $pc.level && $__f->m()->getRgt() > $pc.rgt && $__f->m()->getLft() < $pc.lft} disabled{/if}>{'&bull;'|str_repeat:$pc.level} {$pc.product_category_title|escape}</option>
                {/foreach}
                </select>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if !is_null($__f->e('product_category_cover'))} has-error{/if}">
                <label for="{$__f->encode('product_category_cover')}">{'Обложка для категории'|t}</label>
                <div class="input-group">
                    {if null !== $__f->m()->getCover() && null === $__f->e('publication_cover')}
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-preview" data-image-path="{$__url->thumb($__f->m()->getCover(), 150, 150)}"><i class="fa fa-eye"></i></button>
                    </span>                
                    {/if}                     
                    <input type="text" class="form-control" name="{$__f->encode('product_category_cover')}" value="{$__f->m()->getCover()|escape}" id="{$__f->encode('product_category_cover')}">
                    <span class="input-group-btn">
                        <button class="btn btn-default" onclick="return dialog.show('{$__url->adm('.files-dialog', '?history')}', '{$__f->encode('product_category_cover')}');"><i class="fa fa-search"></i> {'Обзор'|t}</button>
                    </span>
                </div>
                <span class="help-block help-block-error">{if $__f->e('product_category_cover') == 'IMAGE_UNSUPPORTED'}Этот формат изображений не поддерживается (используйте GIF, PNG, JPG){else}Файл не существует или не является картинкой ({$__f->e('product_category_cover')}){/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group{if !is_null($__f->e('product_category_keywords'))} has-error{/if}">
                <label for="{$__f->encode('product_category_keywords')}">Ключевые слова (keywords)</label>
                <textarea name="{$__f->encode('product_category_keywords')}" id="{$__f->encode('product_category_keywords')}" class="form-control form-textarea-vertical">{$__f->m()->getKeywords()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group{if !is_null($__f->e('product_category_description'))} has-error{/if}">
                <label for="{$__f->encode('product_category_description')}">Описание (description)</label>
                <textarea name="{$__f->encode('product_category_description')}" id="{$__f->encode('product_category_description')}" class="form-control form-textarea-vertical">{$__f->m()->getDescription()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="{$__f->encode('product_category_contains_products')}" value="1"{if $__f->m()->getContainsProducts()} checked{/if}> Категория содержит товары
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
                        <input type="radio" name="{$__f->encode('product_category_status')}" value="ENABLED"{if $__f->m()->getStatus() == 'ENABLED'} checked{/if}> {'Показывать'|t}
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('product_category_status')}" value="DISABLED"{if $__f->m()->getStatus() == 'DISABLED'} checked{/if}> {'Скрывать'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
            {if $__f->getId() == 'product_edit'}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
                {else}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить категорию'|t}</button>
            {/if}
                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.product.category')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>