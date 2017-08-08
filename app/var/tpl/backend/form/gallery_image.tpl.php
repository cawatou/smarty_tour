{$__ctx->addCss('/backend/files_popup.css')}
{$__ctx->addJs('/backend/files_popup.js')}

{if $__f->successful}
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {'Данные об изображении изменены'|t}
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
            <div class="form-group form-group-required{if !is_null($__f->e('gallery_image_path'))} has-error{/if}">
                <label for="{$__f->encode('gallery_image_path')}">{'Изображение'|t} <i class="fa fa-check"></i></label>
                <div class="input-group">
                    {if $__f->getId() == 'gallery_image_edit' && null !== $__f->m()->getPath() && null === $__f->e('gallery_image_path')}
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-preview" data-image-path="{$__url->thumb($__f->m()->getPath(), 150, 150)}"><i class="fa fa-eye"></i></button>
                    </span>                
                    {/if}                     
                    <input type="text" class="form-control" name="{$__f->encode('gallery_image_path')}" value="{if is_null($__f->v('gallery_image_path'))}{$__f->m()->getPath()|escape}{else}{$__f->v('gallery_image_path')}{/if}" id="{$__f->encode('gallery_image_path')}">
                    <span class="input-group-btn">
                        {if $__f->getId() == 'gallery_image_add'}
                        <button class="btn btn-default" onclick="return dialog.show('{$__url->adm('.files-multi', '?history')}', '{$__f->encode('gallery_image_path')}');"><i class="fa fa-search"></i> Обзор</button>
                        {else}
                        <button class="btn btn-default" onclick="return dialog.show('{$__url->adm('.files-dialog', '?history')}', '{$__f->encode('gallery_image_path')}');"><i class="fa fa-search"></i> Обзор</button>
                        {/if}
                    </span>
                </div>
                <span class="help-block help-block-error">{if $__f->e('gallery_image_path') == 'IMAGE_UNSUPPORTED'}Этот формат изображений не поддерживается (используйте GIF, PNG, JPG){elseif $__f->e('gallery_image_path') == 'NOT_VALID'}Обязательное поле{else}Файл не существует или не является картинкой ({$__f->e('gallery_image_path')}){/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('gallery_id'))} has-error{/if}">
                <label for="{$__f->encode('gallery_id')}">{'Галерея'|t} <i class="fa fa-check"></i></label>
                <select name="{$__f->encode('gallery_id')}" id="{$__f->encode('gallery_id')}" class="form-control">
                    <option value="">Выберите галерею</option>
                {foreach $gallery_list as $gallery}
                    <option value="{$gallery->getId()|escape}"{if $gallery->getId() == $__f->m()->getGalleryId()} selected{/if}{if $gallery->getStatus() != 'ENABLED'} style="color: #ccc;"{/if}>{$gallery->getTitle()|escape}</option>
                {/foreach}
                </select>
                <span class="help-block"><a href="{$__url->adm('.gallery.category.add')}">Добавить галерею?</a></span>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if !is_null($__f->e('gallery_image_title'))} has-error{/if}">
                <label for="{$__f->encode('gallery_image_title')}">{'Название'|t}</label>
                <input type="text" class="form-control" name="{$__f->encode('gallery_image_title')}" value="{$__f->m()->getTitle()|escape}" id="{$__f->encode('gallery_image_title')}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if !is_null($__f->e('gallery_image_link'))} has-error{/if}">
                <label for="{$__f->encode('gallery_image_link')}">{'Ссылка'|t}</label>
                <input type="text" class="form-control" name="{$__f->encode('gallery_image_link')}" value="{$__f->m()->getLink()|escape}" id="{$__f->encode('gallery_image_link')}">
                <span class="help-block">Используется для добавления в галереи баннеров</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group{if !is_null($__f->e('gallery_image_description'))} has-error{/if}">
                <label for="{$__f->encode('gallery_image_description')}">{'Описание'|t}</label>
                <textarea name="{$__f->encode('gallery_image_description')}" id="{$__f->encode('gallery_image_description')}" class="form-control form-textarea-vertical">{$__f->m()->getDescription()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">{'Статус'|t}</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('gallery_image_status')}" value="ENABLED"{if $__f->m()->getStatus() == 'ENABLED'} checked{/if}> {'Показывать'|t}
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('gallery_image_status')}" value="DISABLED"{if $__f->m()->getStatus() == 'DISABLED'} checked{/if}> {'Скрывать'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
            {if $__f->getId() == 'gallery_image_edit'}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
            {else}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить изображение'|t}</button>
            {/if}
                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.gallery.image')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>