{$__ctx->addJs('/backend/filter/hotel.js')}

{$__ctx->addJs('/suggest.js')}

<div class="navbar navbar-default cms-filter">
    <p class="navbar-text">Фильтр</p>

    <form action="{$__f->getUrl()}" method="GET" class="navbar-form navbar-left" role="search">
        <div class="form-group cms-filter-item">
            <select name="{$__f->encodeSearch('country_id')}" class="form-control" title="{'Укажите страну'|t}" id="filter_search_country">
                <option value="">{'Укажите страну'|t}</option>

                {foreach $country_list as $country}
                    <option value="{$country.country_id|escape}"{if $__f->v('country_id') !== null && $__f->v('country_id') == $country.country_id} selected="selected"{/if}>{$country.country_title|escape}</option>
                {/foreach}
            </select>
        </div>

        <div class="form-group cms-filter-item">
            <select name="{$__f->encodeSearch('resort_id')}" class="form-control{if $__f->v('country_id') === null} hidden{/if}" title="{'Укажите курорт'|t}" id="filter_search_resort">
                <option value="">{'Укажите курорт'|t}</option>

                {foreach $resort_list as $resort}
                    <option value="{$resort.resort_id|escape}"{if $__f->v('resort_id') !== null && $__f->v('resort_id') == $resort.resort_id} selected="selected"{/if}>{$resort.resort_title|escape}</option>
                {/foreach}
            </select>
        </div>

        <div class="form-group cms-filter-item">
            <input type="text" name="{$__f->encodeSearch('hotel_title')}" placeholder="{'Название'|t}" value="{$__f->v('hotel_title')}" class="form-control" />
        </div>

        <input type="hidden" name="{$__f->encodeSearch('hotel_status')}" value="" />

        <div class="checkbox cms-filter-item">
            <label>
                <input type="checkbox" name="{$__f->encodeSearch('hotel_status')}" value="ENABLED"{if $__f->v('hotel_status')} checked="checked"{/if}> Убрать скрытые
            </label>
         </div>

        <button type="submit" class="btn btn-default">{'Применить'|t}</button>

        {if $__f->isActive()}
            <button type="submit" name="{$__f->encode(Form_Filter::FILTER_CLEAR)}" class="btn btn-default" />{'Очистить'|t}</button>
        {/if}
    </form>
</div>