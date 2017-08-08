<div class="navbar navbar-default cms-filter">
    <p class="navbar-text">Фильтр</p>

    <form action="{$__f->getUrl()}" method="get" class="navbar-form navbar-left" role="search">
        <div class="form-group cms-filter-item">
            <input name="{$__f->encodeSearch('country_title')}" class="form-control" placeholder="Название" value="{$__f->v('country_title')}" />
        </div>

        <input type="hidden" name="{$__f->encodeSearch('country_status')}" value="" />

        <div class="checkbox cms-filter-item">
            <label>
                <input name="{$__f->encodeSearch('country_status')}" type="checkbox"{if $__f->v('country_status')} checked="checked"{/if} value="ENABLED" />

                Убрать скрытые
            </label>
        </div>

        <button type="submit" class="btn btn-default">{'Применить'|t}</button>

        {if $__f->isActive()}
            <button type="submit" name="{$__f->encode(Form_Filter::FILTER_CLEAR)}" class="btn btn-default">{'Очистить'|t}</button>
        {/if}
    </form>
</div>