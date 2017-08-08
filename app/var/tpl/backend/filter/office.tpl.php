<div class="navbar navbar-default cms-filter">
    <p class="navbar-text">Фильтр</p>

    <form action="{$__f->getUrl()}" method="get" class="navbar-form navbar-left" role="search">
        <div class="form-group cms-filter-item">
            <select name="{$__f->encodeSearch('city_id')}" class="form-control" title="{'Выберите город'|t}" id="filter_search_from_id">
                <option value="">Выберите город</option>

                {foreach $city_list as $city}
                    <option value="{$city.city_id|escape}"{if $city.city_id == $__f->v('city_id')} selected="selected"{/if}>
                        {$city.city_title|escape}
                    </option>
                {/foreach}
            </select>
        </div>

        <button type="submit" class="btn btn-default">{'Применить'|t}</button>

        {if $__f->isActive()}
            <button type="submit" name="{$__f->encode(Form_Filter::FILTER_CLEAR)}" class="btn btn-default">{'Очистить'|t}</button>
        {/if}
    </form>
</div>