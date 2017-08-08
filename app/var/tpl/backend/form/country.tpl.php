{assign var="model" value=$__f->m()}

{$__ctx->addJs('/backend/tiny_mce/tiny_mce.js')}
{$__ctx->addJs('/backend/tiny_mce.js')}

{$__ctx->addJs('/backend/form/country.js')}

{literal}
<script type="text/javascript">
    var
        TINY_FILEMANAGER_PATH = '{/literal}{$__url->adm('.files-mce')}{literal}',
        COUNTRY_CONTENT_ID    = '{/literal}{$__f->encode('country_content')}{literal}';
</script>
{/literal}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Данные о стране изменены'|t}
    </div>
{/if}

{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Произошла ошибка, проверьте правильность заполнения полей'|t}
    </div>
{/if}

<form method="POST" action="{$__f->getUrl()}">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('country_title') !== null} has-error{/if}">
                <label for="{$__f->encode('country_title')}">{'Название'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('country_title')}" value="{$model->getTitle()|escape}" id="{$__f->encode('country_title')}">
                <span class="help-block help-block-error">{if $__f->e('country_title') === 'ALREADY_EXISTS'}Такая страна уже существует{else}{'Обязательное поле'|t}{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('country_alias') !== null} has-error{/if}">
                <label for="{$__f->encode('country_alias')}">{'Alias'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('country_alias')}" value="{$model->getAlias()|escape}" id="{$__f->encode('country_alias')}">
                <span class="help-block help-block-error">{if $__f->e('country_alias') === 'ALREADY_EXISTS'}Страна с таким alias уже существует{elseif $__f->e('country_alias') == 'INVALID_FORMAT'}Неверный формат. Допускаются только a-z, -, _{else}Не может быть пустым{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('gallery_id') !== null} has-error{/if}">
                <label for="{$__f->encode('gallery_id')}">{'Галерея'|t}</label>

                <select name="{$__f->encode('gallery_id')}" id="{$__f->encode('gallery_id')}" class="form-control">
                    <option value="">Выберите галерею</option>

                    {foreach $gallery_list as $gallery}
                        <option value="{$gallery->getId()|escape}"{if $gallery->getId() == $model->getGalleryId()} selected="selected"{/if}{if $gallery->getStatus() !== 'ENABLED'} style="color: #ccc;"{/if}>{$gallery->getTitle()|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block"><a href="{$__url->adm('.gallery.category.add')}">Добавить галерею?</a></span>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('country_keywords') !== null} has-error{/if}">
                <label for="{$__f->encode('country_keywords')}">{'Ключевые слова (keywords)'|t}</label>
                <textarea name="{$__f->encode('country_keywords')}" id="{$__f->encode('country_keywords')}" class="form-control form-textarea-vertical">{$model->getKeywords()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('country_description') !== null} has-error{/if}">
                <label for="{$__f->encode('country_description')}">{'Описание (description)'|t}</label>
                <textarea name="{$__f->encode('country_description')}" id="{$__f->encode('country_description')}" class="form-control form-textarea-vertical">{$model->getDescription()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('country_brief') !== null} has-error{/if}">
                <label for="{$__f->encode('country_brief')}">{'Краткое описание'|t}</label>
                <textarea name="{$__f->encode('country_brief')}" id="{$__f->encode('country_brief')}" class="form-control form-textarea-vertical">{$model->getBrief()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group{if $__f->e('country_content') !== null} has-error{/if}">
                <label for="{$__f->encode('country_content')}">{'Полное описание'|t}</label>
                <textarea name="{$__f->encode('country_content')}" id="{$__f->encode('country_content')}" class="form-control form-textarea-vertical">{$model->getContent()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group{if $__f->e('country_visa_days') !== null} has-error{/if}">
                <label for="{$__f->encode('country_visa_days')}">{'На сколько дней выдаётся виза?'|t}</label>
                <input type="text" class="form-control" name="{$__f->encode('country_visa_days')}" value="{$model->getVisaDays()|escape}" id="{$__f->encode('country_visa_days')}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">{'Статус'|t}</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('country_status')}" value="ENABLED"{if $model->getStatus() === 'ENABLED'} checked="checked"{/if}> {'Показывать'|t}
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('country_status')}" value="DISABLED"{if $model->getStatus() === 'DISABLED'} checked="checked"{/if}> {'Скрывать'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {if $__f->getId() === 'country_edit'}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
                {else}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить страну'|t}</button>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.country')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>