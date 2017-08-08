<div class="navbar navbar-default cms-filter">
    <p class="navbar-text">Фильтр</p>
    <form action="{$__f->getUrl()}" method="get" class="navbar-form navbar-left" role="search">
        <div class="form-group cms-filter-item">
            <input type="text" name="{$__f->encodeSearch('i18n_source_string')}" placeholder="{"Оригинал"|t}" value="{$__f->v('i18n_source_string')}" class="form-control" />
        </div>

        <div class="form-group cms-filter-item">
            <select name="{$__f->encodeSearch('i18n_source_locale')}" class="form-control" title="{"Локаль оригинала"|t}">
                <option value="">{"Все"|t}</option>
            {foreach $q_i18n->getSourceLocales() as $sl}
                <option value="{$sl.source_locale|escape}"{if !is_null($__f->v('i18n_source_locale')) && $__f->v('i18n_source_locale') == $sl.source_locale} selected{/if} >{$sl.source_locale|escape}</option>
            {/foreach}
            </select>
        </div>

        <div class="form-group cms-filter-item">&mdash;</div>

        <div class="form-group cms-filter-item">
            <input type="text" name="{$__f->encodeSearch('i18n_target_string')}" placeholder="{"Перевод"|t}" value="{$__f->v('i18n_target_string')}" class="form-control" />
        </div>

        <div class="form-group cms-filter-item">
            <select name="{$__f->encodeSearch('i18n_target_locale')}" class="form-control" title="{"Локаль перевода"|t}">
                <option value="">{"Все"|t}</option>
            {foreach $q_i18n->getTargetLocales() as $tl}
                <option value="{$tl.target_locale|escape}" {if !is_null($__f->v('i18n_target_locale')) && $__f->v('i18n_target_locale') == $tl.target_locale}selected="selected"{/if} >{$tl.target_locale|escape}</option>
            {/foreach}
            </select>
        </div>

        <div class="checkbox cms-filter-item">
            <label>
                <input type="checkbox" name="{$__f->encodeSearch('only_not_translated')}" value="1"{if $__f->v('only_not_translated')} checked{/if}> {"Только без перевода"|t}
            </label>
        </div>

        <button type="submit" class="btn btn-default">{'Применить'|t}</button>
    {if $__f->isActive()}
        <button type="submit" name="{$__f->encode(Form_Filter::FILTER_CLEAR)}" class="btn btn-default">{'Очистить'|t}</button>
    {/if}
    </form>
</div>