{$__ctx->addJs('/backend/tiny_mce/tiny_mce.js')}
{$__ctx->addJs('/backend/tiny_mce.js')}

{$__ctx->addCss('../js/backend/datepicker/jquery-ui-1.10.3.custom.css')}
{$__ctx->addJs('/backend/datepicker/jquery-ui-1.10.3.custom.js')}
{$__ctx->addJs('/backend/datepicker.js')}

{$__ctx->addCss('../js/backend/datepicker/jquery-ui-timepicker.css')}
{$__ctx->addJs('/backend/datepicker/jquery-ui-timepicker.js')}
{$__ctx->addJs('/backend/timepicker.js')}

{$__ctx->addCss('/backend/files_popup.css')}
{$__ctx->addJs('/backend/files_popup.js')}

{$__ctx->addCss('/backend/form/publication.css')}
{$__ctx->addJs('/backend/form/publication.js')}

{literal}
<script type="text/javascript">
    var
        TINY_FILEMANAGER_PATH = '{/literal}{$__url->adm('.files-mce')}{literal}',
        TINY_LIGHT_REDACTOR_ID = '{/literal}{$__f->encode('publication_brief')}{literal}',
        TINY_REDACTOR_ID = '{/literal}{$__f->encode('publication_content')}{literal}';
</script>
{/literal}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Данные о публикации изменены'|t}
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
            <div class="form-group form-group-required{if !is_null($__f->e('publication_date'))} has-error{/if}">
                <label for="{$__f->encode('publication_date')}">{'Дата размещения'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control datetimepicker" name="{$__f->encode('publication_date')}" value="{$__f->m()->getDate()->setDefaultTimeZone()->format('d.m.Y H:i')}" id="{$__f->encode('publication_date')}">
                <span class="help-block help-block-error">{'Не верный формат'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if !is_null($__f->e('publication_category'))} has-error{/if}">
                <label for="{$__f->encode('publication_category')}">{'Вид публикации'|t}</label>
                <select name="{$__f->encode('publication_category')}" id="{$__f->encode('publication_category')}" class="form-control" disabled>
                {foreach $__f->m()->getCategories() as $k => $v}
                    <option value="{$k}"{if $__f->m()->getCategory() == $k} selected="selected"{/if}>{$v.title|escape}</option>
                {/foreach}
                </select>
                <input type="hidden" name="{$__f->encode('publication_category')}" value="{$__f->m()->getCategory()|escape}" />
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('publication_title'))} has-error{/if}">
                <label for="{$__f->encode('publication_title')}">{'Заголовок'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('publication_title')}" value="{$__f->m()->getTitle()|escape}" id="{$__f->encode('publication_title')}">
                <span class="help-block help-block-error">{if $__f->e('publication_title') == 'ALREADY_EXISTS'}{'Публикация с таким заголовком уже существует'|t}{else}{'Обязательное поле'|t}{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group form-group-required{if !is_null($__f->e('publication_brief'))} has-error{/if}">
                <label for="{$__f->encode('publication_brief')}">Краткий текст <i class="fa fa-check"></i></label>
                <textarea name="{$__f->encode('publication_brief')}" id="{$__f->encode('publication_brief')}" class="form-control form-textarea-vertical">{$__f->m()->getBrief()|escape}</textarea>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group form-group-required{if !is_null($__f->e('publication_content'))} has-error{/if}">
                <label for="{$__f->encode('publication_content')}">Полный текст <i class="fa fa-check"></i></label>
                <textarea name="{$__f->encode('publication_content')}" id="{$__f->encode('publication_content')}" class="form-control form-textarea-vertical">{$__f->m()->getContent()|escape}</textarea>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="hidden cms-group">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group {if $__f->e('publication_cover') !== null} has-error{/if}">
                    <label for="{$__f->encode('publication_cover')}">{'Изображение (обложка)'|t}</label>
                    <div class="input-group">
                        {if null !== $__f->m()->getCover() && null === $__f->e('publication_cover')}
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-preview" data-image-path="{$__url->thumb($__f->m()->getCover(), 150, 150)}"><i class="fa fa-eye"></i></button>
                        </span>
                        {/if}
                        <input type="text" class="form-control" name="{$__f->encode('publication_cover')}" value="{$__f->m()->getCover()|escape}" id="{$__f->encode('publication_cover')}">
                        <span class="input-group-btn">
                            <button class="btn btn-default" onclick="return dialog.show('{$__url->adm('.files-dialog', '?history')}', '{$__f->encode('publication_cover')}');"><i class="fa fa-search"></i> {'Обзор'|t}</button>
                        </span>
                    </div>
                    <span class="help-block help-block-error">{if $__f->e('publication_cover') == 'IMAGE_UNSUPPORTED'}Этот формат изображений не поддерживается (используйте GIF, PNG, JPG){else}Файл не существует или не является картинкой ({$__f->e('publication_cover')}){/if}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group{if !is_null($__f->e('publication_file'))} has-error{/if}">
                    <label for="{$__f->encode('publication_file')}">{'Файл'|t}</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="{$__f->encode('publication_file')}" value="{$__f->m()->getFile()|escape}" id="{$__f->encode('publication_file')}">
                        <span class="input-group-btn">
                            <button class="btn btn-default" onclick="return dialog.show('{$__url->adm('.files-dialog', '?history')}', '{$__f->encode('publication_file')}');"><i class="fa fa-search"></i> {'Обзор'|t}</button>
                        </span>
                    </div>
                    <span class="help-block help-block-error">Файл не существует</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group{if !is_null($__f->e('publication_youtube'))} has-error{/if}">
                    <label for="{$__f->encode('publication_youtube')}">{'Youtube-код'|t}</label>
                    <input type="text" class="form-control" name="{$__f->encode('publication_youtube')}" value="{$__f->m()->getYoutube()|escape}" id="{$__f->encode('publication_youtube')}">
                    <span class="help-block">Пример: http://www.youtube.com/watch?v=<strong>m5EW5Fyc77M</strong></span>
                    <span class="help-block help-block-error">Не верный формат</span>
                </div>
            </div>
        </div>

        <div class="row hidden">
            <div class="col-md-4">
                <div class="form-group{if !is_null($__f->e('publication_tags'))} has-error{/if}">
                    <label for="{$__f->encode('publication_tags')}">{'Теги'|t}</label>
                    <input type="text" class="form-control" name="{$__f->encode('publication_tags')}" value="{$__f->m()->getTags()|escape}" id="{$__f->encode('publication_tags')}">
                    <span class="help-block">Пример: <strong>наука, техника, нло</strong></span>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden cms-group cms-group-expanded">
        <div class="cms-group-label">{'Изображения'|t}</div>
    {if count($__f->m()->getImages()) > 0}
        <div class="thumbnail-list">
            {foreach from=$__f->m()->getImages() item='image' name='image'}
                <div class="thumbnail thumbnail-active pull-left">
                    <img src="{$__url->thumb($image->getPath(), 150, 150)}" alt="" width="120" height="120" />
                    {if $image->getIsCover()}
                        <a href="#" class="btn btn-xs btn-default" onclick="return false;" title="Это изображение является титульным"><i class="fa fa-eye"></i></a>
                        {else}
                        <a href="{$__url->adm('.publication.cover_image')}?publication_image_id={$image->getId()}" class="btn btn-xs btn-success scrollfix" title="Назначить изображение титульным"><i class="fa fa-eye"></i></a>
                    {/if}
                    <a href="{$__url->adm('.publication.delete_image')}?publication_image_id={$image->getId()}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('Вы уверены?');" title="Удалить изображение"><i class="fa fa-times"></i></a>
                    {if !$smarty.foreach.image.first}
                        <a href="{$__url->adm('.publication.shift_image')}?publication_image_id={$image->getId()}&amp;way=LEFT" class="btn btn-xs btn-primary scrollfix" title="{'Сдвинуть влево'|t}"><i class="fa fa-arrow-left"></i></a>
                    {/if}
                    {if !$smarty.foreach.image.last}
                        <a href="{$__url->adm('.publication.shift_image')}?publication_image_id={$image->getId()}&amp;way=RIGHT" class="btn btn-xs btn-primary scrollfix" title="{'Сдвинуть вправо'|t}"><i class="fa fa-arrow-right"></i></a>
                    {/if}
                </div>
            {/foreach}
        </div>
    {/if}

        <div class="row">
            <div class="col-md-4">
                <div class="form-group{if !is_null($__f->e('publication_image'))} has-error{/if}">
                    <label for="{$__f->encode('publication_image')}">{'Добавить изображение'|t}</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="{$__f->encode('publication_image')}" value="{$__f->v('publication_image')|escape}" id="{$__f->encode('publication_image')}">
                        <span class="input-group-btn">
                            <button class="btn btn-default" onclick="return dialog.show('{$__url->adm('.files-multi', '?history')}', '{$__f->encode('publication_image')}');"><i class="fa fa-search"></i> Обзор</button>
                        </span>
                    </div>
                    <span class="help-block help-block-error">{if $__f->e('publication_image') == 'IMAGE_UNSUPPORTED'}Этот формат изображений не поддерживается (используйте GIF, PNG, JPG){else}Файл не существует или не является картинкой ({$__f->e('publication_image')}){/if}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden cms-group cms-group-expanded hidden">
        <div class="cms-group-label">Источник публикации</div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group{if $__f->e('publication_source_title') !== null} has-error{/if}">
                    <label for="{$__f->encode('publication_source_title')}">{'Название'|t}</label>
                    <input type="text" class="form-control" name="{$__f->encode('publication_source_title')}" value="{$__f->m()->getSourceTitle()|escape}" id="{$__f->encode('publication_source_title')}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group{if $__f->e('publication_source_link') !== null} has-error{/if}">
                    <label for="{$__f->encode('publication_source_link')}">{'Ссылка'|t}</label>
                    <input type="text" class="form-control" name="{$__f->encode('publication_source_link')}" value="{$__f->m()->getSourceLink()|escape}" id="{$__f->encode('publication_source_link')}">
                    <span class="help-block ">Пример: http://lenta.ru</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="checkbox">
            <label>
                <input type="checkbox" name="{$__f->encode('publication_is_highlight')}" value="1"{if $__f->m()->getIsHighlight()} checked{/if}> Выделить / подсветить
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
                    <input type="radio" name="{$__f->encode('publication_status')}" value="ENABLED"{if $__f->m()->getStatus() == 'ENABLED'} checked{/if}> {'Показывать'|t}
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="{$__f->encode('publication_status')}" value="DISABLED"{if $__f->m()->getStatus() == 'DISABLED'} checked{/if}> {'Скрывать'|t}
                </label>
            </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {if $__f->getId() == 'publication_edit'}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
                {else}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить публикацию'|t}</button>
                {/if}
                {if $__f->m()->getStatus() == 'DISABLED' && null !== $__f->m()->getUrl()}
                    <button class="btn btn-default btn-ref" data-href="{$__f->m()->getUrl()}?preview={$smarty.now|date_format:"%Y.%m.%d"|@md5}" data-target="_blank" />{'Предпросмотр'|t}</button>
                {/if}
                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.publication')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>