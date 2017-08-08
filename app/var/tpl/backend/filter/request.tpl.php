{if count($request_types) > 1}
    <div class="navbar navbar-default cms-filter">
        <p class="navbar-text">Фильтр</p>

        <form action="{$__f->getUrl()}" method="get" class="navbar-form navbar-left" role="search">
            <div class="form-group cms-filter-item">
                <select name="{$__f->encodeSearch('request_type')}" class="form-control">
                    <option value="">Укажите тип заявки</option>

                    {foreach $request_types as $k => $i}
                        <option value="{$k|escape}"{if $__f->v('request_type') == $k} selected{/if}>{$i|escape}</option>
                    {/foreach}
                </select>
            </div>

            <button type="submit" class="btn btn-default">{'Применить'|t}</button>

            {if $__f->isActive()}
                <button type="submit" name="{$__f->encode(Form_Filter::FILTER_CLEAR)}" class="btn btn-default" />{'Очистить'|t}</button>
            {/if}
        </form>
    </div>
{/if}