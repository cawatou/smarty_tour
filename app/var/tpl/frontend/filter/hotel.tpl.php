{$__ctx->addJs('/suggest.js')}
{$__ctx->addJs('/frontend/filter/hotel.js')}

<form class="filter filter-hotel" action="{$__f->getUrl()}" method="post">
    <table>
        <tr>
            <td width="35%">
                <div class="filter-item">
                    <select class="ik-select" name="{$__f->encodeSearch('country_id')}" id="filter-country-id" data-ddMaxHeight="250">
                        <option value="">Выберите страну</option>

                        {foreach $country_list as $country}
                            <option value="{$country.country_id|escape}"{if $__f->v('country_id') == $country.country_id} selected="selected"{/if}>{$country.country_title|escape}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="filter-item">
                    <select class="ik-select" name="{$__f->encodeSearch('resort_id')}" id="filter-resort-id" data-ddMaxHeight="250">
                        <option value="">Выберите курорт</option>

                        {foreach $resort_list as $resort}
                            <option value="{$resort.resort_id|escape}"{if $__f->v('resort_id') == $resort.resort_id} selected="selected"{/if}>{$resort.resort_title|escape}</option>
                        {/foreach}
                    </select>
                </div>
            </td>

            <td width="35%">
                <div class="filter-item">
                    <div class="filter-item-title">
                        Категория отеля
                    </div>

                    {foreach $stars as $star_id => $star_title}
                        <label>
                            <input type="checkbox" name="{$__f->encodeSearch('hotel_stars_in')}[]" value="{$star_id|escape}"{if $__f->v('hotel_stars_in') !== null && $star_id|in_array:$__f->v('hotel_stars_in')} checked="checked"{/if} />
                            {$star_id|escape}
                        </label>
                    {/foreach}
                </div>
            </td>

            <td width="30%">
                <button type="submit" class="filter-button">Применить</button>

                {if $__f->isActive()}
                    <button type="submit" name="{$__f->encode(Form_Filter::FILTER_CLEAR)}" class="filter-button filter-button-clear">Очистить</button>
                {/if}
            </td>
        </tr>
    </table>
</form>