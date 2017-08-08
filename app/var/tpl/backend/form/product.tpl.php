{assign var="model" value=$__f->getModel()}

{$__ctx->addJs('/backend/tiny_mce/tiny_mce.js')}
{$__ctx->addJs('/backend/tiny_mce.js')}

{$__ctx->addCss('/backend/files_popup.css')}
{$__ctx->addJs('/backend/files_popup.js')}

{$__ctx->addCss('../js/backend/datepicker/jquery-ui-1.10.3.custom.css')}
{$__ctx->addJs('/backend/datepicker/jquery-ui-1.10.3.custom.js')}
{$__ctx->addCss('../js/backend/datepicker/jquery-ui-timepicker.css')}
{$__ctx->addJs('/backend/datepicker/jquery-ui-timepicker.js')}
{$__ctx->addJs('/backend/datepicker.js')}
{$__ctx->addJs('/backend/timepicker.js')}

{$__ctx->addCss('/backend/form/product.css')}
{$__ctx->addJs('/backend/form/product.js')}

{$__ctx->addJs('/base.js')}
{$__ctx->addJs('/base.many_rows.js')}

{$__ctx->addJs('/bootstrap-typeahead.js')}

{$__ctx->addJs('/suggest.js')}

{$__ctx->addJs('/autocomplete.js')}

{literal}
<script type="text/javascript">
    var
        TINY_FILEMANAGER_PATH = '{/literal}{$__url->adm('.files-mce')}{literal}',
        PROD_LIGHT_REDACTOR_ID = '{/literal}{$__f->encode('product_brief')}{literal}',
        PROD_REDACTOR_ID = '{/literal}{$__f->encode('product_content')}{literal}';
</script>
{/literal}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Данные о туре изменены
    </div>
{/if}

{assign var="departures" value=$model->getDepartures()}

{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

        <p>
            Произошла ошибка, проверьте правильность заполнения полей
        </p>
    </div>

    {$updateLimit = 0}

    {if !empty($departures)}
        {$departure = current(current($departures))}

        {if !empty($departure) && count($departure->getHotels())}
            {$updateLimit = ceil(count($departure->getHotels()) / 3)}
        {/if}
    {/if}

    {if !empty($__f->errors.special_hotel_rotten)}
        <div class="alert alert-danger alert-dismissable">
            <p>
                Слишком много "старых" отелей{if $updateLimit},
                необходимо заменить {$updateLimit} {$updateLimit|plural_form:'отель':'отеля':'отелей'}{/if}
            </p>
        </div>
    {/if}

    {if !empty($__f->errors.special_hotel_empty)}
        <div class="alert alert-danger alert-dismissable">
            <p>
                Нужно добавить хотя бы один отель
            </p>
        </div>
    {/if}
{/if}

<form role="form" method="POST" action="{$__f->getUrl()}" id="form-product">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('product_title') !== null} has-error{/if}">
                <label for="{$__f->encode('product_title')}">Название <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('product_title')}" value="{$model->getTitle()|escape}" id="{$__f->encode('product_title')}">
                <span class="help-block help-block-error">{if $__f->e('product_title') == 'ALREADY_EXISTS'}Товар с таким названием уже существует{else}Обязательное поле{/if}</span>
            </div>
        </div>
    </div>

    {if count($__f->m()->getLinkedProducts()) == 0}
        <div class="row hidden" id="linked_products">
            <div class="col-md-4">
                <div class="form-group{if $__f->e('product_linked_id') !== null} has-error{/if}">
                    <label for="{$__f->encode('product_linked_id')}">Связать с другим туром</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="{$__f->encode('product_linked_id')}" value="{$model->getLinkedId()|escape}" id="{$__f->encode('product_linked_id')}">
                        <span class="input-group-addon"><i class="fa fa-link"></i></span>
                    </div>
                    <span class="help-block">Укажите ID тура с которым хотите сделать связь</span>
                    <span class="help-block help-block-error">{if $__f->e('product_linked_id') == 'NOT_EXIST_ID'}Тур не найден{elseif $__f->e('product_linked_id') == 'EQUIAL_ID'}Номера туров совпадают{elseif $__f->e('product_linked_id') == 'CAN_NOT'}Вы не можете указать этот тур{elseif $__f->e('product_linked_id') == 'TWO_DEPARTURES'} У этого тура 2 даты вылета{else}Не верный формат{/if}</span>
                </div>
            </div>
        </div>
    {/if}

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('product_cover') !== null} has-error{/if}">
                <label for="{$__f->encode('product_cover')}">Обложка</label>

                <div class="input-group">
                    {if $model->getCover() !== null}
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-preview" data-image-path="{$__url->thumb($model->getCover(), 150, 150)}"><i class="fa fa-eye"></i></button>
                        </span>
                    {/if}

                    <input type="text" class="form-control" name="{$__f->encode('product_cover')}" value="{$model->getCover()|escape}" id="{$__f->encode('product_cover')}">

                    <span class="input-group-btn">
                        {if $__ctx->userCanEdit('.adm.files-dialog')}
                            <button class="btn btn-default" onclick="return dialog.show('{$__url->cmd('.adm.files-dialog', '?history')}', '{$__f->encode('product_cover')}');">
                                <i class="fa fa-search"></i> Обзор
                            </button>
                        {else}
                            <button class="btn btn-default" onclick="return dialog.show('{$__url->cmd('.adm.files-dialog', '?path=/tours&restrict=1')}', '{$__f->encode('product_cover')}');">
                                <i class="fa fa-search"></i> Обзор
                            </button>
                        {/if}
                    </span>
                </div>

                <span class="help-block help-block-error">{if $__f->e('product_cover') === 'IMAGE_UNSUPPORTED'}Этот формат изображений не поддерживается (используйте GIF, PNG, JPG){else}Файл не существует или не является картинкой ({$__f->e('product_cover')}){/if}</span>
            </div>
        </div>
    </div>

    <div class="hidden row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('product_alias') !== null} has-error{/if}">
                <label for="{$__f->encode('product_alias')}">Alias</label>
                <input type="text" class="form-control" name="{$__f->encode('product_alias')}" value="{$model->getAlias()|escape}" id="{$__f->encode('product_alias')}">
                <span class="help-block help-block-error">{if $__f->e('product_alias') == 'ALREADY_EXISTS'}Тур с таким alias уже существует{elseif $__f->e('product_alias') == 'INVALID_FORMAT'}Неверный формат. Допускаются только a-z, -, _{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group form-group-required{if $__f->e('product_price') !== null} has-error{/if}">
                <label for="{$__f->encode('product_price')}">Цена <i class="fa fa-check"></i></label>

                <div class="input-group">
                    <input type="text" class="form-control text-right" name="{$__f->encode('product_price')}" value="{$model->getPrice()|escape}" id="{$__f->encode('product_price')}"{if $__f->getId() == 'product_edit' || $__f->getId() == 'product_copy'} readonly{/if} data-price-base="{$model->getPrice()|escape}">
                    <span class="input-group-addon"><i class="fa fa-rub"></i></span>
                </div>

                {if $__f->getId() == 'product_edit' || $__f->getId() == 'product_copy'}
                    <span class="help-block hidden">
                        <label>
                            <input type="checkbox" onchange="{literal}if ($(this).is(':checked')) { $('#{/literal}{$__f->encode('product_price')}{literal}').removeAttr('readonly'); } else { $('#{/literal}{$__f->encode('product_price')}{literal}').attr('readonly', 'readonly').val($('#{/literal}{$__f->encode('product_price')}{literal}').attr('data-price-base')); }{/literal}"/>
                            Изменить цену
                        </label>
                    </span>
                {/if}

                <span class="help-block help-block-error">{if $__f->e('product_price') == 'INVALID_FORMAT'}Неверный формат. Допускаются только цифры{elseif $__f->e('product_price') == 'INCORRECT'}Цена не должна быть меньше или равна цене со скидкой{else}Обязательное поле{/if}</span>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group{if $__f->e('product_discount_price') !== null} has-error{/if}">
                <label for="{$__f->encode('product_discount_price')}">Цена со скидкой</label>

                <div class="input-group">
                    <input type="text" class="form-control text-right" name="{$__f->encode('product_discount_price')}" value="{$model->getDiscountPrice()|escape}" id="{$__f->encode('product_discount_price')}" readonly>
                    <span class="input-group-addon"><i class="fa fa-rub"></i></span>
                </div>

                <span class="help-block help-block-error">{if $__f->e('product_discount_price') == 'INVALID_FORMAT'}Неверный формат. Допускаются только цифры{else}Обязательное поле. Должно быть больше нуля{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('touroperator_id') !== null} has-error{/if}">
                <label for="{$__f->encode('touroperator_id')}">Туроператор</label>

                <select name="{$__f->encode('touroperator_id')}" id="{$__f->encode('touroperator_id')}" class="form-control">
                    <option value="">Выберите туроператора</option>

                    {foreach $touroperator_list as $touroperator}
                        {if $touroperator->getStatus() != 'ENABLED'}{continue}{/if}

                        <option value="{$touroperator->getId()}"{if $touroperator->getId() == $model->getTouroperatorId()} selected{/if}>{$touroperator->getTitle()|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">Неверное значение</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('product_from_id') !== null} has-error{/if}">
                <label for="{$__f->encode('product_from_id')}">Отправление <i class="fa fa-check"></i></label>

                <select name="{$__f->encode('product_from_id')}" id="{$__f->encode('product_from_id')}" class="form-control">
                    <option value="">Выберите город отправления</option>

                    {foreach $from_all_list as $from}
                        {if empty($from.is_shown)}{continue}{/if}

                        <option value="{$from.departure_id}"{if $from.departure_id == $model->getFromId()} selected="selected"{/if}>{$from.departure_title}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">Обязательное поле</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('country_id') !== null} has-error{/if}">
                <label for="filter_search_country">Страна <i class="fa fa-check"></i></label>

                <select name="{$__f->encode('country_id')}" id="filter_search_country" class="form-control" data-russia-id="{DomainObjectQuery_Product::COUNTRY_ID_RUSSIA}">
                    <option value="">Выберите страну</option>

                    {foreach $country_list as $country}
                        <option value="{$country.country_id|escape}"{if $country.country_id == $model->getCountryId()} selected="selected"{/if}{if $country.country_status !== 'ENABLED'} style="color: #ccc;"{/if}>{$country.country_title|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">Обязательное поле</span>
            </div>
        </div>
    </div>

    <div class="row{if $model->getCountryId() === null} hidden{/if}" id="filter_search_resort_wrapper">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('resort_id') !== null} has-error{/if}">
                <label for="filter_search_resort">Курорт</label>

                <select name="{$__f->encode('resort_id')}" id="filter_search_resort" class="form-control" data-wrapper="#filter_search_resort_wrapper">
                    <option value="">Выберите курорт</option>

                    {foreach $resort_list as $resort}
                        <option value="{$resort.resort_id|escape}"{if $resort.resort_id == $model->getResortId()} selected="selected"{/if}{if $resort.resort_status !== 'ENABLED'} style="color: #ccc;"{/if}>{$resort.resort_title|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">Обязательное поле</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group{if $__f->e('resort_name') !== null} has-error{/if}" id="product_resort_name_wrapper">
                <label for="{$__f->encode('resort_name')}">Направление/область</label>

                <input type="text" class="form-control" name="{$__f->encode('resort_name')}" value="{$model->getResortName()|escape}" id="{$__f->encode('resort_name')}">

                <span class="help-block help-block-error">Неверное значение</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group{if $__f->e('product_brief') !== null} has-error{/if}">
                <label for="{$__f->encode('product_brief')}">Документы, визы</label>
                <textarea name="{$__f->encode('product_brief')}" id="{$__f->encode('product_brief')}" class="form-control form-textarea-vertical">{$model->getBrief()|escape}</textarea>
                <span class="help-block help-block-error">Обязательное поле</span>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-12">
            <div class="form-group form-group-required{if $__f->e('product_content') !== null} has-error{/if}">
                <label for="{$__f->encode('product_content')}">Полное описание</label>
                <textarea name="{$__f->encode('product_content')}" id="{$__f->encode('product_content')}" class="form-control form-textarea-vertical">{$model->getContent()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-12">
            <div class="form-group{if $__f->e('product_notes') !== null} has-error{/if}">
                <label for="{$__f->encode('product_notes')}">Примечание</label>
                <textarea name="{$__f->encode('product_notes')}" id="{$__f->encode('product_notes')}" class="form-control form-textarea-vertical">{$model->getNotes()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="cms-group cms-group-expanded hidden">
        <div class="cms-group-label">Атрибуты</div>

        {if $model->getAttributes()}
            {foreach $model->getAttributes() as $field => $value}
                <div class="row row-attr">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" name="{$__f->encode('product_attributes')}[fields][]" value="{$field|escape}" placeholder="Название атрибута">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" name="{$__f->encode('product_attributes')}[values][]" value="{$value|escape}" placeholder="Значение атрибута">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="btn btn-default remove-attr"><i class="fa fa-times"></i> </button>
                        </div>
                    </div>
                </div>
            {/foreach}
        {/if}

        <div class="row row-attr">
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="form-control" name="{$__f->encode('product_attributes')}[fields][]" value="" placeholder="Название атрибута">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="form-control" name="{$__f->encode('product_attributes')}[values][]" value="" placeholder="Значение атрибута">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <button class="btn btn-default add-attr"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-default remove-attr hidden"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="cms-group cms-group-expanded dependable-country-russia">
        <div class="cms-group-label">Трансферы</div>

        {if $model->getGetVia()}
            {foreach $model->getGetVia() as $via_id => $v}
                <div class="row row-attr">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" name="{$__f->encode('product_get_via')}[{$via_id}][title]" value="{if !empty($v.title)}{$v.title|escape}{/if}" placeholder="Название">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" name="{$__f->encode('product_get_via')}[{$via_id}][price]" value="{if !empty($v.price)}{$v.price|escape}{/if}" placeholder="Цена">
                        </div>
                    </div>

                    <div class="col-md-2 hidden">
                        <div class="form-group">
                            <button class="btn btn-default remove-attr"><i class="fa fa-times"></i> </button>
                        </div>
                    </div>
                </div>
            {/foreach}
        {/if}

        <div class="row row-attr hidden">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control" name="{$__f->encode('product_get_via')}[_1][title]" value="" placeholder="Название" data-name="{$__f->encode('product_get_via')}[_#COUNTER_HOTELS#][title]">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="form-control" name="{$__f->encode('product_get_via')}[_1][price]" value="" placeholder="Цена" data-name="{$__f->encode('product_get_via')}[_#COUNTER_HOTELS#][price]">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <button class="btn btn-default add-attr"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-default remove-attr hidden"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden cms-group cms-group-expanded">
        <div class="cms-group-label">Изображения</div>

        {if count($model->getImages()) > 0}
            <div class="thumbnail-list">
                {foreach $model->getImages() as $image}
                    <div class="thumbnail thumbnail-active pull-left">
                        <img src="{$__url->thumb($image->getPath(), 150, 150)}" alt="" width="120" height="120" />

                        {if $image->getIsCover()}
                            <a href="#" class="btn btn-xs btn-default" onclick="return false;" title="Это изображение является титульным"><i class="fa fa-eye"></i></a>
                        {else}
                            <a href="{$__url->adm('.product.cover_image')}?product_image_id={$image->getId()}" class="btn btn-xs btn-success scrollfix" title="Назначить изображение титульным"><i class="fa fa-eye"></i></a>
                        {/if}

                        <a href="{$__url->adm('.product.delete_image')}?product_image_id={$image->getId()}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('Вы уверены?');" title="Удалить изображение"><i class="fa fa-times"></i></a>
                    </div>
                {/foreach}
            </div>
        {/if}

        <div class="row">
            <div class="col-md-4">
                <div class="form-group{if $__f->e('product_image') !== null} has-error{/if}">
                    <label for="{$__f->encode('product_image')}">Добавить изображение</label>

                    <div class="input-group">
                        <input type="text" class="form-control" name="{$__f->encode('product_image')}" value="{$__f->v('product_image')|escape}" id="{$__f->encode('product_image')}">

                        <span class="input-group-btn">
                            <button class="btn btn-default" onclick="return dialog.show('{$__url->adm('.files-dialog', '?history')}', '{$__f->encode('product_image')}');"><i class="fa fa-search"></i> Обзор</button>
                        </span>
                    </div>

                    <span class="help-block help-block-error">{if $__f->e('product_image') === 'IMAGE_UNSUPPORTED'}Этот формат изображений не поддерживается (используйте GIF, PNG, JPG){else}Файл не существует или не является картинкой ({$__f->e('product_image')}){/if}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="cms-group cms-group-expanded">
                <div class="cms-group-label">В стоимость входит</div>

                {if $model->getPayableIncludes()}
                    {foreach $model->getPayableIncludes() as $value}
                        <div class="row row-attr">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <input type="text" name="{$__f->encode('product_payable_includes')}[]" value="{$value|escape}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-default remove-attr">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                {/if}

                <div class="row row-attr">
                    <div class="col-md-10">
                        <div class="form-group">
                            <input type="text" name="{$__f->encode('product_payable_includes')}[]" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="btn btn-default add-attr">
                                <i class="fa fa-plus"></i>
                            </button>

                            <button class="btn btn-default remove-attr hidden">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="cms-group cms-group-expanded">
                <div class="cms-group-label">Оплачивается отдельно</div>

                {if $model->getPayableExcludes()}
                    {foreach $model->getPayableExcludes() as $value}
                        <div class="row row-attr">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <input type="text" name="{$__f->encode('product_payable_excludes')}[]" value="{$value|escape}" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-default remove-attr">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                {/if}

                <div class="row row-attr">
                    <div class="col-md-10">
                        <div class="form-group">
                            <input type="text" name="{$__f->encode('product_payable_excludes')}[]" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="btn btn-default add-attr">
                                <i class="fa fa-plus"></i>
                            </button>

                            <button class="btn btn-default remove-attr hidden">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="row-departure-components" class="cms-group cms-group-expanded">
        <div class="cms-group-label">Даты вылета</div>

        {foreach $departures as $k_pd => $departure}
            <div class="row-component-departure-date" id="{$__f->encode("row_departure_date_`$departure@iteration`")}" data-counter="{$departure@iteration}" data-id="{$departure@iteration}">
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="{$__f->encode("product_departure[`$departure@iteration`][product_departure_is_datetime]")}" value="1"{if $departure->getIsDatetime()} checked{/if}>
                                Добавить время
                            </label>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group{if $__f->e("product_departure_`$departure@iteration`_product_departure_date") !== null} has-error{/if}">
                            <label for="{$__f->encode("product_departure_date_`$departure@iteration`_date")}">Дата вылета</label>
                            <input type="text" class="form-control input-special-datepicker input-special-departure-date" name="{$__f->encode("product_departure[`$departure@iteration`][product_departure_date]")}" value="{$departure->getDate()->format('d.m.Y H:i')}" id="{$__f->encode("product_departure_date_`$departure@iteration`_date")}">

                            <span class="help-block help-block-error">
                                {if $__f->e("product_departure_`$departure@iteration`_product_departure_date") == 'INVALID_FORMAT'}
                                    Обязательное поле
                                {else}
                                    Неверное значение
                                {/if}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group{if $__f->e("product_departure_`$departure@iteration`_product_departure_date_back") !== null} has-error{/if}">
                            <label for="{$__f->encode("product_departure_date_`$departure@iteration`_date_back")}">Дата прилета</label>
                            <input type="text" class="form-control input-special-datepicker input-special-departure-date" name="{$__f->encode("product_departure[`$departure@iteration`][product_departure_date_back]")}" value="{if $departure->getDateBack() !== null}{$departure->getDateBack()->format('d.m.Y H:i')}{/if}" id="{$__f->encode("product_departure_date_`$departure@iteration`_date_back")}">

                            <span class="help-block help-block-error">
                                {if $__f->e("product_departure_`$departure@iteration`_product_departure_date_back") == 'INVALID_FORMAT'}
                                    Обязательное поле
                                {else}
                                    Неверное значение
                                {/if}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group{if $__f->e("product_departure_`$departure@iteration`_product_departure_daynum") !== null} has-error{/if}">
                            <label for="{$__f->encode("product_departure_daynum_`$departure@iteration`")}">Кол-во дней</label>
                            <input type="text" class="form-control" name="{$__f->encode("product_departure[`$departure@iteration`][product_departure_daynum]")}" value="{$departure->getDaynum()|escape}" id="{$__f->encode("product_departure_daynum_`$departure@iteration`")}">

                            <span class="help-block help-block-error">Обязательное поле</span>
                        </div>
                    </div>

                    <div class="col-md-2 hidden{if $__f->e("product_departure_`$departure@iteration`_product_departure_nightnum") !== null} has-error{/if}">
                        <div class="form-group">
                            <label for="{$__f->encode("product_departure_nightnum_`$departure@iteration`")}">Кол-во ночей</label>
                            <input type="text" class="form-control" name="{$__f->encode("product_departure[`$departure@iteration`][product_departure_nightnum]")}" value="{$departure->getNightnum()}" id="{$__f->encode("product_departure_nightnum_`$departure@iteration`")}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group{if $__f->e("product_departure_`$departure@iteration`_product_departure_seats") !== null} has-error{/if}">
                            <label for="{$__f->encode("product_departure_seats_`$departure@iteration`")}">Кол-во мест</label>
                            <input type="text" class="form-control" name="{$__f->encode("product_departure[`$departure@iteration`][product_departure_seats]")}" value="{$departure->getSeats()|escape}" id="{$__f->encode("product_departure_seats_`$departure@iteration`")}">

                            <span class="help-block help-block-error">Неверное значение</span>
                        </div>
                    </div>

                    <div class="col-md-2" style="margin-top: 25px;">
                        <button class="btn btn-default btn-departure-remove" title="Удалить эту дату вылета">
                            <i class="fa fa-times"></i>
                        </button>

                        <button class="btn btn-default btn-departure-add" title="Добавить новую дату вылета">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        {/foreach}
    </div>

    <div id="{$__f->encode('row-hotel-components')}" class="cms-group cms-group-expanded">
        <div class="cms-group-label">Отельная база</div>

        <div class="row">
            <div class="col-md-12">
                <p class="help-block text-muted">
                    Отельная база автоматически сортируется по стоимости первой даты вылета, за исключением первых двух строк.
                </p>
            </div>
        </div>

        {assign var="departure" value=current(current($departures))}

        {include file="backend/include/form-product-hotel.tpl.php" item=null field=$__f->encode('_product_departure_hotels') class="row-template hidden" item_key="#ID#" departures=$departures product=$model}

        {if !empty($departure) && count($departure->getHotels()) > 0}
            {foreach $departure->getHotels() as $k_h => $hotel}
                {$classname = ''}

                {if !empty($hotel.name)}
                    {if $__f->m()->isHotelExpired($hotel.name)}
                        {$classname = 'hotel-expired'}
                    {/if}
                {/if}

                {include file="backend/include/form-product-hotel.tpl.php" item=$hotel field=$__f->encode('_product_departure_hotels') item_key=$k_h departures=$departures class=$classname product=$model}
            {/foreach}
			
        {else}
		
            {include file="backend/include/form-product-hotel.tpl.php" item=null field=$__f->encode('_product_departure_hotels') item_key="_0" departures=$departures product=$model}
        {/if}

        <div class="row hidden">
            <div class="col-md-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="{$__f->encode('product_attributes_is_sort_hotels')}" value="1"{if true || $model->getAttributes('is_sort_hotels')} checked{/if}>
                        Автоматически отсортировать отельную базу по цене
                    </label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="{$__f->encode('product_is_discount_applied')}" value="1"{if $model->getIsDiscountApplied()} checked{/if} class="checkbox-change-is-discount-applied">
                        Автоматический расчёт скидки

                        <abbr title="Если галочка стоит:&#13;при выводе на сайте к указанной цене применяется скидка, а полученный результат делится на два.&#13;&#13;Если галочка не стоит:&#13;на сайт выводится значение как есть.">
                            <strong>?</strong>
                        </abbr>
                    </label>
                </div>
            </div>
        </div>

        <div class="row dependable-is-discount-applied">
            <div class="col-md-4">
                <div class="checkbox">
                    <h4>
                        <label class="label label-{if $model->getIsHighlight()}danger{else}default{/if}">
                            <input type="checkbox" name="{$__f->encode('product_is_highlight')}" value="1"{if $model->getIsHighlight()} checked{/if} class="checkbox-change-is-highlight">
                            Промо цена
                        </label>
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {if $__f->e('special_hotel_rotten')}
        <div class="has-error">
            <div class="help-block help-block-error">
                Слишком много "старых" отелей{if $updateLimit},
                необходимо заменить {$updateLimit} {$updateLimit|plural_form:'отель':'отеля':'отелей'}{/if}
            </div>
        </div>
    {/if}

    {literal}
    <script type="text/javascript">
        var PRODUCT_HOTEL_STORAGE = {
            container: '#{/literal}{$__f->encode('row-hotel-components')}{literal}',
            tpl:       '.row-template',
            row:       '.row',
            add:       '.add-row',
            del:       '.del-row',
            up:        '.up-row',
            down:      '.down-row'
        };
    </script>
    {/literal}

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">Статус</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('product_status')}" value="ENABLED"{if $model->getStatus() == 'ENABLED'} checked{/if}> Показывать
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('product_status')}" value="DISABLED"{if $model->getStatus() == 'DISABLED'} checked{/if}> Скрывать
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <input type="hidden" name="{$__f->encode('source_data')}" value="{if $__f->v('source_data')}{$__f->v('source_data')|escape}{else}{$__f->m()->encodeSourceData()|escape}{/if}">

                {if $__f->getId() == 'product_edit'}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary" onclick="return confirm('Я подтверждаю, что обратил(а) внимание на СПО, а так же на необходимость отображения цен по системе ПРОМОПРАЙС');">Внести изменения</button>
                {else}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">Добавить тур</button>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.product')}">Отмена</button>
            </div>
        </div>
    </div>
</form>

{$__ctx->addJs('/backend/classes/product.js')}

<template class="template-departure-date-line" data-name="departure_date_line">
    <div class="row-component-departure-date" id="{$__f->encode('row_departure_date_#COUNTER#')}" data-counter="#COUNTER#" data-id="#COUNTER#">
        <div class="row">
            <div class="col-md-2">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="{$__f->encode("product_departure[#COUNTER#][product_departure_is_datetime]")}" value="1">
                        Добавить время
                    </label>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label for="{$__f->encode('product_departure_date_#COUNTER#')}">Дата вылета</label>
                    <input type="text" class="form-control input-special-datepicker input-special-departure-date" name="{$__f->encode("product_departure[#COUNTER#][product_departure_date]")}" value="" id="{$__f->encode('product_departure_date_#COUNTER#')}" data-datepicker="">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label for="{$__f->encode('product_departure_date_back_#COUNTER#')}">Дата прилета</label>
                    <input type="text" class="form-control input-special-datepicker input-special-departure-date" name="{$__f->encode("product_departure[#COUNTER#][product_departure_date_back]")}" value="" id="{$__f->encode('product_departure_date_back_#COUNTER#')}" data-datepicker="">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label for="{$__f->encode('product_departure_daynum_#COUNTER#')}">Кол-во дней</label>
                    <input type="text" class="form-control" name="{$__f->encode('product_departure[#COUNTER#][product_departure_daynum]')}" value="" id="{$__f->encode('product_departure_daynum_#COUNTER#')}">
                </div>
            </div>

            <div class="col-md-2 hidden">
                <div class="form-group">
                    <label for="{$__f->encode('product_departure_nightnum_#COUNTER#')}">Кол-во ночей</label>
                    <input type="text" class="form-control" name="{$__f->encode('product_departure[#COUNTER#][product_departure_nightnum]')}" value="" id="{$__f->encode('product_departure_nightnum_#COUNTER#')}">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label for="{$__f->encode('product_departure_seats_#COUNTER#')}">Кол-во мест</label>
                    <input type="text" class="form-control" name="{$__f->encode('product_departure[#COUNTER#][product_departure_seats]')}" value="" id="{$__f->encode('product_departure_seats_#COUNTER#')}">
                </div>
            </div>

            <div class="col-md-2" style="margin-top: 25px;">
                <button class="btn btn-default btn-departure-remove" title="Удалить эту дату вылета">
                    <i class="fa fa-times"></i>
                </button>

                <button class="btn btn-default btn-departure-add" title="Добавить новую дату вылета">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<template class="template-hotel-line" data-name="hotel_line">
    <div class="row-component-hotel" data-counter="#COUNTER_HOTELS#">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" name="{$__f->encode('_product_departure_hotels[name][#COUNTER_HOTELS#]')}" value="" class="form-control input-special-hotel-name" placeholder="Название отеля" autocomplete="off" data-url-hotel-edit="{$__url->adm('.hotel.edit')}?hotel_id=" />
                    <input type="hidden" name="{$__f->encode('_product_departure_hotels[id][#COUNTER_HOTELS#]')}" value="" />

                    <span class="notice"></span>
                </div>
            </div>

            <div class="col-md-2 col-special-hotel-stars">
                <div class="form-group">
                    <select name="{$__f->encode('_product_departure_hotels[stars][#COUNTER_HOTELS#]')}" class="form-control input-special-hotel-stars">
                        <option value=""></option>

                        {foreach DomainObjectModel_Hotel::getHotelStars() as $star_id => $star_data}
                            <option value="{$star_id|escape}">{$star_data.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="col-md-1 col-special-hotel-nutrition">
                <div class="form-group">
                    <select name="{$__f->encode('_product_departure_hotels[nutrition_type][#COUNTER_HOTELS#]')}" class="form-control input-special-hotel-nutrition">
                        <option value=""></option>

                        {foreach DomainObjectModel_Hotel::getNutritionTypes() as $nutr_type => $nutr_data}
                            <option value="{$nutr_type|escape}">{$nutr_data.title|escape}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="col-md-2 column-special-hotel-url">
                <div class="form-group input-special-hotel-url-wrapper">
                    <input type="text" name="{$__f->encode('_product_departure_hotels[url][#COUNTER_HOTELS#]')}" value="" class="form-control input-special-hotel-url" placeholder="URL с описанием" />
                </div>
            </div>

            <div class="col-md-2 column-special-hotel-addremove">
                <button class="btn btn-default btn-hotel-remove">
                    <i class="fa fa-times"></i>
                </button>

                <button class="btn btn-default btn-hotel-add">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<template class="template-hotel-price-dot" data-name="price_dot">
    <div class="col-md-1 column-special-hotel-price #DEPARTURE_ID#">
        <div class="form-group input-special-hotel-price-wrapper">
            <label></label>

            <div class="input-group">
                <span class="input-group-addon input-special-hotel-is-promoprice dependable-is-discount-applied" title="Использовать промо цену для данной даты вылета">
                    <input type="checkbox" name="{$__f->encode('_product_departure_hotels')}[#COUNTER_HOTELS#][is_promoprice][#COUNTER#]" value="1" />
                </span>

                <input type="text" name="{$__f->encode('_product_departure_hotels')}[#COUNTER_HOTELS#][price][#COUNTER#]" value="" class="form-control input-special-hotel-price" />
                <span class="input-group-addon"><i class="fa fa-rub"></i></span>
            </div>
        </div>
    </div>
</template>