<div class="navbar navbar-default cms-filter">
    <p class="navbar-text">Фильтр</p>

    <form action="{$__f->getUrl()}" method="GET" class="navbar-form navbar-left" role="search">
        <div class="form-group cms-filter-item">
            <input type="text" name="{$__f->encodeSearch('city_title')}" placeholder="{'Название'|t}" value="{$__f->v('city_title')}" class="form-control" />
        </div>

        <input type="hidden" name="{$__f->encodeSearch('city_status')}" value="" />

        <div class="checkbox cms-filter-item">
            <label>
                <input type="checkbox" name="{$__f->encodeSearch('city_status')}" value="ENABLED"{if $__f->v('city_status')} checked="checked"{/if}> Убрать скрытые
            </label>
        </div>

        <button type="submit" class="btn btn-default">{'Применить'|t}</button>

        {if $__f->isActive()}
            <button type="submit" name="{$__f->encode(Form_Filter::FILTER_CLEAR)}" class="btn btn-default" />{'Очистить'|t}</button>
        {/if}
    </form>
</div>