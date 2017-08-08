<div class="row row-attr{if !empty($class)} {$class}{/if} row-component-hotel-wrapper">
    <div class="row-component-discount" data-counter="{$item_key}">
        <div class="col-md-2">
            <div class="form-group">
                <label>Страна</label>

                <select name="{$field}[{$item_key}][country_id]" class="form-control">
                    <option value=""></option>

                    {foreach $country_list as $country}
                        {if $country->getStatus() != 'ENABLED'}{continue}{/if}

                        <option value="{$country->getId()}"{if !empty($model) && $country->getId() == $model->getCountryId()} selected{/if}>
                            {$country->getTitle()|escape}
                        </option>
                    {/foreach}
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label>Туроператор</label>

                <select name="{$field}[{$item_key}][touroperator_id]" class="form-control">
                    <option value=""></option>

                    {foreach $touroperator_list as $touroperator}
                        {if $touroperator->getStatus() != 'ENABLED'}{continue}{/if}

                        <option value="{$touroperator->getId()}"{if !empty($model) && $touroperator->getId() == $model->getTouroperatorId()} selected{/if}>
                            {$touroperator->getTitle()|escape}
                        </option>
                    {/foreach}
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label>Город вылета</label>

                <select name="{$field}[{$item_key}][departure_city_id]" class="form-control">
                    <option value=""></option>

                    {foreach $froms_list as $from_id => $from}
                        <option value="{$from_id}"{if !empty($model) && $from_id == $model->getDepartureCityId()} selected{/if}>
                            {$from.title_from|escape}
                        </option>
                    {/foreach}
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label>Цена, мин</label>

                <div class="input-group">
                    <input type="text" name="{$field}[{$item_key}][price_min]" value="{if !empty($model)}{$model->getPriceMin()|escape}{/if}" class="form-control text-right" />
                    <span class="input-group-addon"><i class="fa fa-rub"></i></span>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label>Цена, макс</label>

                <div class="input-group">
                    <input type="text" name="{$field}[{$item_key}][price_max]" value="{if !empty($model)}{$model->getPriceMax()|escape}{/if}" class="form-control text-right" />
                    <span class="input-group-addon"><i class="fa fa-rub"></i></span>
                </div>
            </div>
        </div>

        <div class="col-md-2" style="width: 120px;">
            <div class="form-group">
                <label>Скидка</label>

                <div class="input-group">
                    <input type="text" name="{$field}[{$item_key}][discount_percent]" value="{if !empty($model)}{$model->getDiscountPercent()|escape}{/if}" class="form-control" />
                    <span class="input-group-addon">%</span>
                </div>
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