{$__ctx->addJs('/backend/filter/product.js')}

{$__ctx->addJs('/suggest.js')}

<div class="navbar navbar-default cms-filter">
    <p class="navbar-text">Фильтр</p>
    <form action="{$__f->getUrl()}" method="get" class="navbar-form navbar-left" role="search">
        <div class="form-group cms-filter-item">
            <select name="{$__f->encodeSearch('product_from_id')}" class="form-control" title="{'Выберите город отправления'|t}" id="filter_search_from_id">
                <option value="">Выберите город отправления</option>

                {foreach $from_all as $from}
                    {if empty($from.is_shown)}{continue}{/if}

                    <option value="{$from.departure_id|escape}"{if $from.departure_id == $__f->v('product_from_id')} selected="selected"{/if}>
                        {$from.departure_title|escape}
                    </option>
                {/foreach}
            </select>
        </div>

        <div class="form-group cms-filter-item">
            <select name="{$__f->encodeSearch('country_id')}" class="form-control" title="{'Укажите страну'|t}" id="filter_search_country">
                <option value="">{'Укажите страну'|t}</option>

                {foreach $country_list as $country}
                    <option value="{$country.country_id|escape}"{if $__f->v('country_id') !== null && $__f->v('country_id') == $country.country_id} selected="selected"{/if}>{$country.country_title|escape}</option>
                {/foreach}
            </select>
        </div>

        <div class="form-group cms-filter-item">
            <select name="{$__f->encodeSearch('resort_id')}" class="form-control{if !$__f->v('country_id')} hidden{/if}" title="{'Укажите курорт'|t}" id="filter_search_resort">
                <option value="">{'Укажите курорт'|t}</option>

                {foreach $resort_list as $resort}
                    <option value="{$resort.resort_id|escape}"{if $__f->v('resort_id') !== null && $__f->v('resort_id') == $resort.resort_id} selected="selected"{/if}>{$resort.resort_title|escape}</option>
                {/foreach}
            </select>
        </div>

        <div class="checkbox cms-filter-item hidden">
            <label>
                <input type="checkbox" name="{$__f->encodeSearch('product_is_highlight')}" value="1"{if $__f->v('product_is_highlight')} checked{/if}> С подсветкой
            </label>
        </div>

        <button type="submit" class="btn btn-default">{'Применить'|t}</button>

        {if $__f->isActive()}
            <button type="submit" name="{$__f->encode(Form_Filter::FILTER_CLEAR)}" class="btn btn-default">{'Очистить'|t}</button>
        {/if}
    </form>
</div>