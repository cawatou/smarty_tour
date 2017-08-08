{$__ctx->addCss('/backend/files_popup.css')}
{$__ctx->addJs('/backend/files_popup.js')}

{$__ctx->addCss('../js/backend/datepicker/jquery-ui-1.10.3.custom.css')}
{$__ctx->addJs('/backend/datepicker/jquery-ui-1.10.3.custom.js')}
{$__ctx->addJs('/backend/datepicker.js')}

{if $__f->successful}
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {'Данные о галерее изменены'|t}
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
            <div class="form-group form-group-required{if !is_null($__f->e('gallery_date'))} has-error{/if}">
                <label for="{$__f->encode('gallery_date')}">{'Дата размещения'|t} <i class="fa fa-check"></i></label>

                <div class="input-group">
                    <input type="text" class="form-control datepicker" name="{$__f->encode('gallery_date')}" value="{$__f->m()->getDate()->setDefaultTimeZone()->format('d.m.Y')}" id="{$__f->encode('gallery_date')}">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>

                <span class="help-block help-block-error">{'Неверный формат'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('gallery_title'))} has-error{/if}">
                <label for="{$__f->encode('gallery_title')}">{'Название'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('gallery_title')}" value="{$__f->m()->getTitle()|escape}" id="{$__f->encode('gallery_title')}">
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('gallery_alias'))} has-error{/if}">
                <label for="{$__f->encode('gallery_alias')}">Alias <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('gallery_alias')}" value="{$__f->m()->getAlias()|escape}" id="{$__f->encode('gallery_alias')}">
                <span class="help-block help-block-error">{if $__f->e('gallery_alias') == 'INVALID_FORMAT'}Должен содержать латинские символы без пробелов{elseif $__f->e('gallery_alias') == 'ALREADY_EXISTS'}Такой alias уже испльзуется{else}{'Обязательное поле'|t}{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('gallery_category'))} has-error{/if}">
                <label for="{$__f->encode('gallery_category')}">{'Группа галерей'|t}</label>
                <select name="{$__f->encode('gallery_category')}" id="{$__f->encode('gallery_category')}" class="form-control">
                {foreach $__f->m()->getCategories() as $k => $v}
                    <option value="{$k}"{if $__f->m()->getCategory() == $k} selected{/if}>{$v.title|escape}</option>
                {/foreach}
                </select>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group{if !is_null($__f->e('gallery_description'))} has-error{/if}">
                <label for="{$__f->encode('gallery_description')}">{'Описание'|t}</label>
                <textarea name="{$__f->encode('gallery_description')}" id="{$__f->encode('gallery_description')}" class="form-control form-textarea-vertical">{$__f->m()->getDescription()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="form-group {if !is_null($__f->e('gallery_cover'))} has-error{/if}">
                <label for="{$__f->encode('gallery_cover')}">{'Обложка'|t}</label>
                <div class="input-group">
                    {if null !== $__f->m()->getCover() && null === $__f->e('publication_cover')}
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-preview" data-image-path="{$__url->thumb($__f->m()->getCover(), 150, 150)}"><i class="fa fa-eye"></i></button>
                    </span>
                    {/if}
                    <input type="text" class="form-control" name="{$__f->encode('gallery_cover')}" value="{$__f->m()->getCover()|escape}" id="{$__f->encode('gallery_cover')}">
                    <span class="input-group-btn">
                        <button class="btn btn-default" onclick="return dialog.show('{$__url->adm('.files-dialog', '?history')}', '{$__f->encode('gallery_cover')}');"><i class="fa fa-search"></i> Обзор</button>
                    </span>
                </div>
                <span class="help-block help-block-error">{if $__f->e('gallery_cover') == 'IMAGE_UNSUPPORTED'}Этот формат изображений не поддерживается (используйте GIF, PNG, JPG){else}Файл не существует или не является картинкой ({$__f->e('gallery_cover')}){/if}</span>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="{$__f->encode('gallery_is_highlight')}" value="1"{if $__f->m()->getIsHighlight()} checked{/if}> Выделить / подсветить
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
                        <input type="radio" name="{$__f->encode('gallery_status')}" value="ENABLED"{if $__f->m()->getStatus() == 'ENABLED'} checked{/if}> {'Показывать'|t}
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('gallery_status')}" value="DISABLED"{if $__f->m()->getStatus() == 'DISABLED'} checked{/if}> {'Скрывать'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
            {if $__f->getId() == 'gallery_category_edit'}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
                {else}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить галерею'|t}</button>
            {/if}
                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.gallery.category')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>