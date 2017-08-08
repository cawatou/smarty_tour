{assign var="model" value=$__f->m()}

{$__ctx->addCss('/backend/form/product.css')}
{$__ctx->addCss('/backend/form/hotel.css')}

{$__ctx->addJs('/backend/tiny_mce/tiny_mce.js')}
{$__ctx->addJs('/backend/tiny_mce.js')}

{$__ctx->addJs('/suggest.js')}
{$__ctx->addJs('/base.js')}
{$__ctx->addJs('/base.many_rows.js')}
{$__ctx->addJs('/backend/form/hotel.js')}

{literal}
<script type="text/javascript">
var HOTEL_TITLE = '{/literal}{$__f->encode('hotel_title')}{literal}',
    HOTEL_DESCRIPTION_DATA = '{/literal}{$__f->encode('hotel_description_data')}{literal}',
    HOTEL_REDACTOR_ID = '{/literal}{$__f->encode('hotel_description')}{literal}';
</script>
{/literal}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Данные об отеле изменены'|t}
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
            <div class="form-group form-group-required{if $__f->e('hotel_title') !== null} has-error{/if} has-feedback">
                <label for="{$__f->encode('hotel_title')}">{'Название'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('hotel_title')}" value="{$model->getTitle()|escape}" id="{$__f->encode('hotel_title')}" data-check-url="{$__url->url('/adm/hotel/check_title')}">

                <span class="help-block help-block-error">{if $__f->e('hotel_title') === 'ALREADY_EXISTS'}Такой отель уже существует{else}{'Обязательное поле'|t}{/if}</span>
                <span class="help-block text-success title-state-success">Выбранное название является уникальным</span>
                <span class="help-block text-danger title-state-error">Отель с таким названием уже существует</span>
            </div>
        </div>
    </div>

    {if $__f->getId() === 'hotel_edit'}
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-group-required">
                    <input type="checkbox"{if $model->getExternalId() !== null} checked="checked"{/if} disabled="disabled" />
                    {if $model->getExternalId() !== null}Есть описание{else}Нет описания{/if} на Sletat.ru
                </div>
            </div>
        </div>
    {/if}

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('hotel_stars') !== null} has-error{/if}">
                <label for="{$__f->encode('hotel_stars')}">{'Классификация'|t}</label>

                <select name="{$__f->encode('hotel_stars')}" id="{$__f->encode('hotel_stars')}" class="form-control">
                    <option value="">Выберите классификацию</option>

                    {foreach DomainObjectModel_Hotel::getHotelStars() as $star_key => $star}
                        <option value="{$star_key|escape}"{if $star_key == $model->getStars('id')} selected="selected"{/if}>{$star.title}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('country_id') !== null} has-error{/if}">
                <label for="country_id">{'Страна'|t} <i class="fa fa-check"></i></label>

                <select name="{$__f->encode('country_id')}" id="country_id" class="form-control">
                    <option value="">Выберите страну</option>

                    {foreach $country_list as $country}
                        <option value="{$country.country_id|escape}"{if $country.country_id == $model->getCountryId()} selected="selected"{/if}{if $country.country_status !== 'ENABLED'} style="color: #ccc;"{/if}>{$country.country_title|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('resort_id') !== null} has-error{/if}">
                <label for="resort_id">{'Курорт'|t} <i class="fa fa-check"></i></label>

                <select name="{$__f->encode('resort_id')}" id="resort_id" class="form-control">
                    <option value="">Выберите курорт</option>

                    {foreach $resort_list as $resort}
                        <option value="{$resort.resort_id|escape}"{if $resort.resort_id == $model->getResortId()} selected="selected"{/if}{if $resort.resort_status !== 'ENABLED'} style="color: #ccc;"{/if}>{$resort.resort_title|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('hotel_message') !== null} has-error{/if}">
                <label for="{$__f->encode('hotel_message')}">{'Комментарий агенства'|t}</label>
                <textarea name="{$__f->encode('hotel_message')}" id="{$__f->encode('hotel_message')}" class="form-control form-textarea-vertical">{$model->getMessage()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('gallery_agency_id') !== null} has-error{/if}">
                <label for="{$__f->encode('gallery_agency_id')}">{'Галерея (фото агентства)'|t}</label>

                <select name="{$__f->encode('gallery_agency_id')}" id="{$__f->encode('gallery_agency_id')}" class="form-control">
                    <option value="">Выберите галерею</option>

                    {foreach $gallery_agency_list as $gallery}
                        <option value="{$gallery.gallery_id|escape}"{if $gallery.gallery_id == $model->getGalleryAgencyId()} selected="selected"{/if}{if $gallery.gallery_status !== 'ENABLED'} style="color: #ccc;"{/if}>{$gallery.gallery_title|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('gallery_operator_id') !== null} has-error{/if}">
                <label for="{$__f->encode('gallery_operator_id')}">{'Галерея (фото туроператора)'|t}</label>

                <select name="{$__f->encode('gallery_operator_id')}" id="{$__f->encode('gallery_operator_id')}" class="form-control">
                    <option value="">Выберите галерею</option>

                    {foreach $gallery_operator_list as $gallery}
                        <option value="{$gallery.gallery_id|escape}"{if $gallery.gallery_id == $model->getGalleryOperatorId()} selected="selected"{/if}{if $gallery.gallery_status !== 'ENABLED'} style="color: #ccc;"{/if}>{$gallery.gallery_title|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('gallery_tourists_id') !== null} has-error{/if}">
                <label for="{$__f->encode('gallery_tourists_id')}">{'Галерея (фото туристов)'|t}</label>

                <select name="{$__f->encode('gallery_tourists_id')}" id="{$__f->encode('gallery_tourists_id')}" class="form-control">
                    <option value="">Выберите галерею</option>

                    {foreach $gallery_tourists_list as $gallery}
                        <option value="{$gallery.gallery_id|escape}"{if $gallery.gallery_id == $model->getGalleryTouristsId()} selected="selected"{/if}{if $gallery.gallery_status !== 'ENABLED'} style="color: #ccc;"{/if}>{$gallery.gallery_title|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    {if $model->getExternalId() === null}
        <div class="row">
            <div class="col-md-4">
                <div class="form-group{if $__f->e('hotel_website') !== null} has-error{/if}">
                    <label for="{$__f->encode('hotel_website')}">{'Сайт отеля'|t}</label>
                    <input type="text" class="form-control" name="{$__f->encode('hotel_website')}" value="{$model->getWebsite()|escape}" id="{$__f->encode('hotel_website')}">

                    <span class="help-block help-block-error">{'Неверное значение'|t}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group form-group-required{if $__f->e('hotel_description') !== null} has-error{/if}">
                    <label for="{$__f->encode('hotel_description')}">{'Описание'|t}</label>
                    <textarea name="{$__f->encode('hotel_description')}" id="{$__f->encode('hotel_description')}" class="form-control form-textarea-vertical">{$model->getDescription()|escape}</textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group{if $__f->e('hotel_description_url') !== null} has-error{/if}">
                    <label for="{$__f->encode('hotel_description_url')}">{'Ссылка на полное описание'|t}</label>
                    <input type="text" class="form-control" name="{$__f->encode('hotel_description_url')}" value="{$model->getDescriptionUrl()|escape}" id="{$__f->encode('hotel_description_url')}">

                    <span class="help-block help-block-error">{'Неверное значение'|t}</span>
                </div>
            </div>
        </div>

        <div class="cms-group cms-group-white" id="{$__f->encode('hotel_description_data')}">
            {include file="backend/include/form-hotel-description-data.tpl.php" item=null class="row-template hidden" field=$__f->encode('hotel_description_data') item_key="#ID#"}

            {foreach $description_datas as $id => $datas}
                <h3>{$datas.title|escape}</h3>

                <section class="options-wrapper">
                    {if !empty($datas.options)}
                        {foreach $datas.options as $option}
                            {include file="backend/include/form-hotel-description-data.tpl.php" item=$option field=$__f->encode('hotel_description_data') item_key=$id}
                        {/foreach}
                    {else}
                        {include file="backend/include/form-hotel-description-data.tpl.php" item=null field=$__f->encode('hotel_description_data') item_key="0"}
                    {/if}
                </section>
            {/foreach}
        </div>

        {literal}
        <script type="text/javascript">
            var HOTEL_DATA_STORAGE = {
                container: '#{/literal}{$__f->encode('hotel_description_data')}{literal}',
                tpl:       '.row-template',
                row:       '.row',
                add:       '.add-row',
                del:       '.del-row',
                up:        '.up-row',
                down:      '.down-row'
            }
        </script>
        {/literal}
    {/if}

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">{'Статус'|t}</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('hotel_status')}" value="ENABLED"{if $model->getStatus() === 'ENABLED'} checked="checked"{/if}> {'Показывать'|t}
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('hotel_status')}" value="DISABLED"{if $model->getStatus() === 'DISABLED'} checked="checked"{/if}> {'Скрывать'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {if $__f->getId() == 'hotel_edit'}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
                {else}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить отель'|t}</button>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.hotel')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>