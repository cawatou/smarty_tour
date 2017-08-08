<div class="row row-attr{if !empty($class)} {$class}{/if} row-component-hotel-wrapper">
    <div class="row-component-hotel" data-counter="{$item_key}">
        <div class="col-md-3">
            <div class="form-group form-input-name" style="position: relative;">
                <label class="name-label" style="margin-left: 25px;">Название отеля</label>

                <label class="iteration-label">#</label>
                <strong class="hotel-iteration-id"></strong>

                <input type="text" name="{$field}[{$item_key}][name]" value="{if !empty($item)}{$item.name|escape}{/if}" class="form-control input-special-hotel-name" placeholder="Название отеля" autocomplete="off" data-url-hotel-edit="{$__url->adm('.hotel.edit')}?hotel_id=" style="width: 240px; margin-left: 25px;" />
                <input type="hidden" name="{$field}[{$item_key}][id]" class="input-hidden-hotel-id" value="{if !empty($item)}{$item.id|escape}{/if}" />

                <span class="notice">
                    {if !empty($item.id) && !empty($item.name)}
                        <a href="{$__url->adm('.hotel.edit')}?hotel_id={$item.id|escape}">{$item.name|escape}</a>
                    {/if}
                </span>
            </div>
        </div>

        <input type="hidden" name="{$field}[{$item_key}][added_at]" value="{if !empty($item) && !empty($item.added_at)}{$item.added_at->toUTC()->format('Y-m-d H:i:s')}{/if}" />

        <div class="col-md-2 col-special-hotel-stars" style="width: 100px;">
            <div class="form-group">
                <label>Класс.</label>

                <select name="{$field}[{$item_key}][stars]" class="form-control input-special-hotel-stars">
                    <option value=""></option>

                    {foreach DomainObjectModel_Hotel::getHotelStars() as $star_id => $star_data}
                        <option value="{$star_id|escape}"{if !empty($item) && $item.stars == $star_id} selected="selected"{/if}>{$star_data.title}</option>
                    {/foreach}
                </select>
            </div>
        </div>

        <div class="col-md-1 col-special-hotel-nutrition">
            <div class="form-group">
                <label>Питание</label>

                <select name="{$field}[{$item_key}][nutrition_type]" class="form-control input-special-hotel-nutrition">
                    <option value=""></option>

                    {foreach DomainObjectModel_Hotel::getNutritionTypes() as $nutr_type => $nutr_data}
                        <option value="{$nutr_type|escape}"{if !empty($item) && $item.nutrition_type == $nutr_type} selected="selected"{/if}>{$nutr_data.title|escape}</option>
                    {/foreach}
                </select>
            </div>
        </div>

        {foreach $departures as $dp}
            {assign var="departure_hotels" value=$dp->getHotels()}

            <div class="col-md-1 column-special-hotel-price {$__f->encode("row_departure_date_`$dp@iteration`")}">
                <div class="form-group input-special-hotel-price-wrapper">
                    <label>{$dp->getDate()->format('d.m.Y H:i')}</label>

                    <div class="input-group">
                        <span class="input-group-addon input-special-hotel-is-promoprice dependable-is-discount-applied2" title="Использовать промо цену для данной даты вылета">
						<input type="checkbox" name="{$field}[{$item_key}][is_promoprice][{$dp@iteration}]" value="1"{if $departure_hotels[$item_key].is_promoprice==1} checked{/if}>
                        </span>

                        <input type="text" name="{$field}[{$item_key}][price][{$dp@iteration}]" value="{if !empty($departure_hotels[$item_key].price)}{$departure_hotels[$item_key].price|escape}{/if}" class="form-control input-special-hotel-price" />
                        <span class="input-group-addon"><i class="fa fa-rub"></i></span>
                    </div>
                </div>
            </div>
        {/foreach}

        <div class="col-md-2 column-special-hotel-url">
            <div class="form-group input-special-hotel-url-wrapper{if !empty($item.id)} invisible{/if}">
                <label>URL с описанием</label>

                <input type="text" name="{$field}[{$item_key}][url]" value="{if !empty($item)}{$item.url|escape}{/if}" class="form-control input-special-hotel-url" placeholder="URL с описанием" />
            </div>
        </div>

        <div class="col-md-1" style="width: 68px;">
            <div class="form-group">
                <label>&nbsp;</label>

                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="{$field}_{$item_key}" data-toggle="dropdown">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="dropdown-menu pull-right" aria-labelledby="{$field}_{$item_key}">
                        <li>
                            <a href="#" class="add-row">
                                <i class="fa fa-plus"></i>
                                Добавить ниже
                            </a>
                        </li>

                        <li>
                            <a href="#" class="up-row">
                                <i class="fa fa-arrow-up"></i>
                                Вверх
                            </a>
                        </li>

                        <li>
                            <a href="#" class="down-row">
                                <i class="fa fa-arrow-down"></i>
                                Вниз
                            </a>
                        </li>

                        <li>
                            <a href="#" class="del-row">
                                <i class="fa fa-times"></i>
                                Удалить
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>