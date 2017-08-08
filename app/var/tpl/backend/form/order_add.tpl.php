{assign var="model" value=$__f->m()}

{$__ctx->addCss('/backend/form/order.css')}
{$__ctx->addJs('/backend/form/order.js')}

{$__ctx->addCss('../js/backend/datepicker/jquery-ui-1.10.3.custom.css')}
{$__ctx->addJs('/backend/datepicker/jquery-ui-1.10.3.custom.js')}
{$__ctx->addJs('/backend/datepicker.js')}

{$__ctx->addJs('/base.js')}

{$__ctx->addJs('/bootstrap-typeahead.js')}

{$__ctx->addJs('/suggest.js')}

{$__ctx->addJs('/autocomplete.js')}

{$__ctx->addJs('/backend/form/order_add.js')}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Заказ был создан'|t}
    </div>
{/if}

{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Произошла ошибка, проверьте правильность заполнения полей'|t}
    </div>
{/if}

<form role="form" method="post" action="{$__f->getUrl()}">
    <div class="cms-group cms-group-expanded">
        <div class="cms-group-label">Тур</div>

        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-required{if $__f->e('country_id') !== null} has-error{/if}">
                            <label for="filter_search_country">{'Страна'|t} <i class="fa fa-check"></i></label>

                            <select name="{$__f->encode('country_id')}" id="filter_search_country" class="form-control">
                                <option value="">Выберите страну</option>

                                {foreach $country_list as $country}
                                    <option value="{$country.country_id|escape}"{if $country.country_id == $model->getProductData('country_id')} selected="selected"{/if}{if $country.country_status !== 'ENABLED'} style="color: #ccc;"{/if}>{$country.country_title|escape}</option>
                                {/foreach}
                            </select>

                            <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row{if $model->getProductData('country_id') === null} hidden{/if}" id="filter_search_resort_wrapper">
                    <div class="col-md-6">
                        <div class="form-group{if $__f->e('resort_id') !== null} has-error{/if}">
                            <label for="filter_search_resort">{'Курорт'|t}</label>

                            <select name="{$__f->encode('resort_id')}" id="filter_search_resort" class="form-control" data-wrapper="#filter_search_resort_wrapper">
                                <option value="">Выберите курорт</option>

                                {foreach $resort_list as $resort}
                                    <option value="{$resort.resort_id|escape}"{if $resort.resort_id == $model->getProductData('resort_id')} selected="selected"{/if}{if $resort.resort_status !== 'ENABLED'} style="color: #ccc;"{/if}>{$resort.resort_title|escape}</option>
                                {/foreach}
                            </select>

                            <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{if $__f->e('resort_name') !== null} has-error{/if}" id="resort_name_wrapper">
                            <label for="{$__f->encode('resort_name')}">{'Направление/область'|t}</label>

                            <input type="text" class="form-control" name="{$__f->encode('resort_name')}" value="{$model->getProductData('resort_name')|escape}" id="{$__f->encode('resort_name')}">

                            <span class="help-block help-block-error">{'Неверное значение'|t}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="row_hotel_data">
            <div class="col-md-4">
                <div class="form-group form-group-required{if $__f->e('order_hotel_name') !== null} has-error{/if}">
                    <label for="order_hotel_name">Название отеля <i class="fa fa-check"></i></label>

                    <input type="text" name="{$__f->encode('order_hotel_name')}" value="{$model->getHotelData('name')|escape}" class="form-control" placeholder="Название отеля" autocomplete="off" id="order_hotel_name" data-url-hotel-edit="{$__url->adm('.hotel.edit')}?hotel_id=" />
                    <input type="hidden" name="{$__f->encode('order_hotel_id')}" value="{$model->getHotelData('id')|escape}" />

                    <span class="notice">
                        {if $model->getHotelData('id') && $model->getHotelData('name')}
                            <a href="{$__url->adm('.hotel.edit')}?hotel_id={$model->getHotelData('id')|escape}">{$model->getHotelData('name')|escape}</a>
                        {/if}
                    </span>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-group-required{if $__f->e('order_hotel_stars') !== null} has-error{/if}">
                    <label for="{$__f->encode('order_hotel_stars')}">Классификация <i class="fa fa-check"></i></label>

                    <select name="{$__f->encode('order_hotel_stars')}" class="form-control input-special-hotel-stars" id="{$__f->encode('order_hotel_stars')}">
                        <option value=""></option>

                        {foreach DomainObjectModel_Hotel::getHotelStars() as $star_id => $star_data}
                            <option value="{$star_id|escape}"{if $model->getHotelData('stars') == $star_id} selected="selected"{/if}>{$star_data.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-group-required{if $__f->e('order_hotel_nutrition') !== null} has-error{/if}">
                    <label for="{$__f->encode('order_hotel_nutrition')}">Питание <i class="fa fa-check"></i></label>

                    <select name="{$__f->encode('order_hotel_nutrition')}" class="form-control input-special-hotel-nutrition" id="{$__f->encode('order_hotel_nutrition')}">
                        <option value=""></option>

                        {foreach DomainObjectModel_Hotel::getNutritionTypes() as $nutr_type => $nutr_data}
                            <option value="{$nutr_type|escape}"{if $model->getHotelData('nutrition_type') == $nutr_type} selected="selected"{/if}>{$nutr_data.title|escape}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group input-special-hotel-url-wrapper form-group-required{if $__f->e('order_hotel_url') !== null} has-error{/if}{if $model->getHotelData('id')} invisible{/if}">
                    <label for="{$__f->encode('order_hotel_url')}">URL с описанием <i class="fa fa-check"></i></label>

                    <input type="text" name="{$__f->encode('order_hotel_url')}" value="{$model->getHotelData('url')|escape}" class="form-control input-special-hotel-url" id="{$__f->encode('order_hotel_url')}" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-group-required{if $__f->e('order_hotel_departure_date') !== null} has-error{/if}">
                    <label for="{$__f->encode('order_hotel_departure_date')}">Дата вылета <i class="fa fa-check"></i></label>
                    <input type="text" class="form-control datepicker" name="{$__f->encode('order_hotel_departure_date')}" value="{if $model->getHotelData('departure_date')}{$model->getHotelData('departure_date')->format('d.m.Y')}{/if}" id="{$__f->encode('order_hotel_departure_date')}" data-datepicker="">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group form-group-required{if $__f->e('order_hotel_departure_daynum') !== null} has-error{/if}">
                    <label for="{$__f->encode('order_hotel_departure_daynum')}">Кол-во дней <i class="fa fa-check"></i></label>
                    <input type="text" class="form-control" name="{$__f->encode('order_hotel_departure_daynum')}" value="{$model->getHotelData('departure_daynum')}" id="{$__f->encode('order_hotel_departure_daynum')}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-group-required{if $__f->e('order_price') !== null} has-error{/if}">
                    <label for="{$__f->encode('order_price')}">Цена <i class="fa fa-check"></i></label>

                    <div class="input-group">
                        <input type="text" class="form-control" name="{$__f->encode('order_price')}" value="{$model->getPrice()|escape}" id="{$__f->encode('order_price')}">
                        <span class="input-group-addon"><i class="fa fa-rub"></i></span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group form-group-required{if $__f->e('order_product_from_id') !== null} has-error{/if}">
                    <label for="{$__f->encode('order_product_from_id')}">{'Отправление'|t} <i class="fa fa-check"></i></label>

                    <select name="{$__f->encode('order_product_from_id')}" id="{$__f->encode('order_product_from_id')}" class="form-control">
                        <option value="">Выберите город отправления</option>

                        {foreach $froms_list as $dep_id => $from}
                            <option value="{$dep_id|escape}"{if $dep_id == $model->getProductData('product_from_id')} selected="selected"{/if}>{$from.title_from}</option>
                        {/foreach}
                    </select>

                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="cms-group cms-group-expanded">
        <div class="cms-group-label">Клиент</div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-group-required{if $__f->e('order_customer_name') !== null} has-error{/if}">
                    <label for="{$__f->encode('order_customer_name')}">{'Имя'|t} <i class="fa fa-check"></i></label>

                    <input type="text" class="form-control" name="{$__f->encode('order_customer_name')}" value="{$model->getCustomerName()|escape}" id="{$__f->encode('order_customer_name')}">

                    <span class="help-block help-block-error">
                        {'Обязательное значение'|t}
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-group-required{if $__f->e('order_customer_email') !== null} has-error{/if}">
                    <label for="{$__f->encode('order_customer_email')}">{'Email'|t} <i class="fa fa-check"></i></label>

                    <input type="text" class="form-control" name="{$__f->encode('order_customer_email')}" value="{$model->getCustomerEmail()|escape}" id="{$__f->encode('order_customer_email')}">

                    <span class="help-block help-block-error">
                        {'Обязательное значение'|t}
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-group-required{if $__f->e('order_customer_phone') !== null} has-error{/if}">
                    <label for="{$__f->encode('order_customer_phone')}">{'Телефон'|t} <i class="fa fa-check"></i></label>

                    <input type="text" class="form-control" name="{$__f->encode('order_customer_phone')}" value="{$model->getCustomerPhone()|escape}" id="{$__f->encode('order_customer_phone')}">

                    <span class="help-block help-block-error">
                        {'Обязательное значение'|t}
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-group-required{if $__f->e('order_customer_total_adults') !== null} has-error{/if}">
                    <label for="{$__f->encode('order_customer_total_adults')}">{'Кол-во взрослых'|t} <i class="fa fa-check"></i></label>

                    <select name="{$__f->encode('order_customer_total_adults')}" id="{$__f->encode('order_customer_total_adults')}" class="form-control"{if $model->isCustomerDataFilled()} readonly="readonly"{/if}>
                        {foreach $adults_vals as $adult}
                            <option value="{(int)$adult}"{if $adult == $model->getCustomerTotalAdults()} selected="selected"{/if}>{(int)$adult}</option>
                        {/foreach}
                    </select>

                    <span class="help-block help-block-error">{'Неверное значение'|t}</span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-group-required{if $__f->e('order_customer_total_children') !== null} has-error{/if}">
                    <label for="{$__f->encode('order_customer_total_children')}">{'Кол-во детей'|t} <i class="fa fa-check"></i></label>

                    <select name="{$__f->encode('order_customer_total_children')}" id="{$__f->encode('order_customer_total_children')}" class="form-control"{if $model->isCustomerDataFilled()} readonly="readonly"{/if}>
                        {foreach $childs_vals as $child}
                            <option value="{(int)$child}"{if $child == $model->getCustomerTotalChildren()} selected="selected"{/if}>{(int)$child}</option>
                        {/foreach}
                    </select>

                    <span class="help-block help-block-error">{'Неверное значение'|t}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Создать заказ'|t}</button>

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.order')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>