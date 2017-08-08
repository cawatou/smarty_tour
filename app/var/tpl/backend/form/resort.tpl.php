{assign var="model" value=$__f->m()}

{$__ctx->addJs('/backend/tiny_mce/tiny_mce.js')}
{$__ctx->addJs('/backend/tiny_mce.js')}

{literal}
<script type="text/javascript">
    var TINY_FILEMANAGER_PATH = '{/literal}{$__url->adm('.files-mce')}{literal}';
    initRedactor('{/literal}{$__f->encode('resort_content')}{literal}');
</script>
{/literal}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Данные о курорте изменены'|t}
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
            <div class="form-group form-group-required{if $__f->e('resort_title') !== null} has-error{/if}">
                <label for="{$__f->encode('resort_title')}">{'Название'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('resort_title')}" value="{$model->getTitle()|escape}" id="{$__f->encode('resort_title')}">
                <span class="help-block help-block-error">{if $__f->e('resort_title') == 'ALREADY_EXISTS'}Такой курорт уже существует{else}{'Обязательное поле'|t}{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('resort_alias') !== null} has-error{/if}">
                <label for="{$__f->encode('resort_alias')}">{'Alias'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('resort_alias')}" value="{$model->getAlias()|escape}" id="{$__f->encode('resort_alias')}">
                <span class="help-block help-block-error">{if $__f->e('resort_alias') == 'ALREADY_EXISTS'}Курорт с таким alias уже существует{elseif $__f->e('resort_alias') === 'INVALID_FORMAT'}Неверный формат. Допускаются только a-z, -, _{else}Не может быть пустым{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('country_id') !== null} has-error{/if}">
                <label for="{$__f->encode('country_id')}">{'Страна'|t} <i class="fa fa-check"></i></label>

                <select name="{$__f->encode('country_id')}" id="{$__f->encode('country_id')}" class="form-control">
                    <option value="">Выберите страну</option>

                    {foreach $country_list as $country}
                        <option value="{$country->getId()|escape}"{if $country->getId() == $model->getCountryId()} selected="selected"{/if}{if $country->getStatus() !== 'ENABLED'} style="color: #ccc;"{/if}>{$country->getTitle()|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block"><a href="{$__url->adm('.country.add')}">Добавить страну?</a></span>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
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
            <div class="form-group form-group-required{if $__f->e('resort_keywords') !== null} has-error{/if}">
                <label for="{$__f->encode('resort_keywords')}">{'Ключевые слова (keywords)'|t}</label>
                <textarea name="{$__f->encode('resort_keywords')}" id="{$__f->encode('resort_keywords')}" class="form-control form-textarea-vertical">{$model->getKeywords()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('resort_description') !== null} has-error{/if}">
                <label for="{$__f->encode('resort_description')}">{'Описание (description)'|t}</label>
                <textarea name="{$__f->encode('resort_description')}" id="{$__f->encode('resort_description')}" class="form-control form-textarea-vertical">{$model->getDescription()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('resort_brief') !== null} has-error{/if}">
                <label for="{$__f->encode('resort_brief')}">{'Краткое описание'|t}</label>
                <textarea name="{$__f->encode('resort_brief')}" id="{$__f->encode('resort_brief')}" class="form-control form-textarea-vertical">{$model->getBrief()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('resort_content') !== null} has-error{/if}">
                <label for="{$__f->encode('resort_content')}">{'Полное описание'|t}</label>
                <textarea name="{$__f->encode('resort_content')}" id="{$__f->encode('resort_content')}" class="form-control form-textarea-vertical">{$model->getContent()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">{'Статус'|t}</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('resort_status')}" value="ENABLED"{if $model->getStatus() === 'ENABLED'} checked="checked"{/if}> {'Показывать'|t}
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('resort_status')}" value="DISABLED"{if $model->getStatus() === 'DISABLED'} checked="checked"{/if}> {'Скрывать'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {if $__f->getId() == 'resort_edit'}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
                {else}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить курорт'|t}</button>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.resort')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>