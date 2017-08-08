<div class="navbar navbar-default cms-filter">
    <p class="navbar-text">Фильтр</p>
    <form action="{$__f->getUrl()}" method="get" class="navbar-form navbar-left" role="search">
        <div class="form-group cms-filter-item">
            <select name="{$__f->encodeSearch('gallery_id')}" class="form-control">
                <option value="">Укажите галерею</option>
            {foreach $gallery_list as $gallery}
                <option value="{$gallery->getId()|escape}"{if $__f->v('gallery_id') == $gallery->getId()} selected{/if}>{$gallery->getTitle()|escape}</option>
            {/foreach}
            </select>
        </div>
        <button type="submit" class="btn btn-default">{'Применить'|t}</button>
    {if $__f->isActive()}
        <button type="submit" name="{$__f->encode(Form_Filter::FILTER_CLEAR)}" class="btn btn-default" />{'Очистить'|t}</button>
    {/if}
    </form>
</div>