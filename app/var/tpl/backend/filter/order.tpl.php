<div class="navbar navbar-default cms-filter">
    <p class="navbar-text">Фильтр</p>

    <form action="{$__f->getUrl()}" method="get" class="navbar-form navbar-left" role="search">
        <div class="form-group cms-filter-item">
            <select name="{$__f->encodeSearch('order_status')}" class="form-control">
                <option value="">Укажите статус</option>

                {foreach $order_statuses as $os => $os_title}
                    <option value="{$os|escape}"{if !is_null($__f->v('order_status')) && $__f->v('order_status') == $os} selected{/if}>{$os_title|escape}</option>
                {/foreach}
            </select>
        </div>

        <button type="submit" class="btn btn-default">{'Применить'|t}</button>

        {if $__f->isActive()}
            <button type="submit" name="{$__f->encode(Form_Filter::FILTER_CLEAR)}" class="btn btn-default" />{'Очистить'|t}</button>
        {/if}
    </form>
</div>