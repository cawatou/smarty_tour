<div class="navbar navbar-default cms-filter">
    <p class="navbar-text">Фильтр</p>
    <form action="{$__f->getUrl()}" method="get" class="navbar-form navbar-left" role="search">
        <div class="form-group cms-filter-item">
            <select name="{$__f->encodeSearch('gallery_category')}" class="form-control">
                <option value="">Укажите группу</option>
            {foreach $categories as $k => $i}
                <option value="{$k|escape}"{if $__f->v('gallery_category') == $k} selected{/if}>{$i.title|escape}</option>
            {/foreach}
            </select>
        </div>
        <button type="submit" class="btn btn-default">{'Применить'|t}</button>
    {if $__f->isActive()}
        <button type="submit" name="{$__f->encode(Form_Filter::FILTER_CLEAR)}" class="btn btn-default" />{'Очистить'|t}</button>
    {/if}
    </form>
</div>