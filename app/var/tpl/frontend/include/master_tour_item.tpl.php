
{if $__ctx->getCurrentUser()->getName()}
	 <div class="service-info">
        <p>
            <b>Туроператор:</b>

            {if $tour->getTouroperatorId()}
                {$tour->getTouroperator()->getTitle()|escape}
            {else}
                {$tour->getOperator()|escape|default:"&mdash;"}
            {/if}
        </p>
    </div>
{/if}
<h2>
    {$tour->getCountryName()|escape}

    {if $tour->getResortName()}
        {$tour->getResortName()|escape}
    {/if}

    {$tour->getFrom('title_from')|escape}
</h2>

<table width="100%" border="0">
    <tbody>
        <tr>
            <td width="210" style="vertical-align: top;">
                <div class="tour-covers">
                    {if $tour->getCover() === null}
                        <div class="tour-cover-placeholder">&nbsp;</div>
                    {else}
                        <img src="{$__url->thumb($tour->getCover()|escape, 200, 160)}" width="200" height="160" class="c-tour-photo" alt="{$tour->getCountryName()|escape} {$tour->getFrom('title_from')|escape}" />
                    {/if}

                    <div id="c-tour-photo-skidka"{if $tour->getDiscountPercent() === null} class="invisible"{/if}>{$tour->getDiscountPercent()}%</div>
                </div>

                {if $tour->getBrief()|strip_tags}
                    <a href="#modal-visas-info-{$tour->getId()}" data-toggle="modal" class="tour-image-linky">Сведения о визе</a>
                {/if}

                <a href="#modal-passport" data-toggle="modal" class="tour-image-linky">Проверка срока загранпаспорта</a>

                <a href="{$__url->url('/search')}" class="tour-image-linky">Подобрать другие туры</a>
            </td>

            <td style="vertical-align: top;" class="departure-dates-text">
                {foreach $tour->getDepartures() as $departure}
                    <div class="departure-item departure-item-{if $departure@first}left{else}right{/if}">
                        {if $departure->getIsDatetime()}
                            <div class="departure-item-part departure-item-part-send">
                                <div class="departure-item-part-title">
                                    Туда
                                </div>

                                <div class="departure-item-part-content">
                                    {$departure->getDate()->toUTC()->localeFormat('d.m.Y H:i', 'RU2')|lower}
                                </div>
                            </div>

                            {if $departure->getDateBack()}
                                <div class="departure-item-part departure-item-part-middle" title="{$departure->getDaynum()|escape} {$departure->getDaynum()|plural_form:'день':'дня':'дней'}">
                                    <div class="departure-item-part-arrow">
                                        {$departure->getDaynum()|escape} {$departure->getDaynum()|plural_form:"день":"дня":"дней"}
                                    </div>
                                </div>

                                <div class="departure-item-part departure-item-part-back">
                                    <div class="departure-item-part-title">
                                        Обратно
                                    </div>

                                    <div class="departure-item-part-content">
                                        {$departure->getDateBack()->toUTC()->localeFormat('d.m.Y H:i', 'RU2')|lower}
                                    </div>
                                </div>
                            {/if}
                        {else}
                            <div class="departure-item-part departure-item-part-send">
                                <div class="departure-item-part-title">
                                    Туда
                                </div>

                                <div class="departure-item-part-content">
                                    {$departure->getDate()->toUTC()->localeFormat('d.m.Y', 'RU2')|lower}
                                </div>
                            </div>

                            {if $departure->getDateBack()}
                                <div class="departure-item-part departure-item-part-middle" title="{$departure->getDaynum()|escape} {$departure->getDaynum()|plural_form:'день':'дня':'дней'}">
                                    <div class="departure-item-part-arrow">
                                        {$departure->getDaynum()|escape} {$departure->getDaynum()|plural_form:"день":"дня":"дней"}
                                    </div>
                                </div>

                                <div class="departure-item-part departure-item-part-back">
                                    <div class="departure-item-part-title">
                                        Обратно
                                    </div>

                                    <div class="departure-item-part-content">
                                        {$departure->getDateBack()->toUTC()->localeFormat('d.m.Y', 'RU2')|lower}
                                    </div>
                                </div>
                            {/if}
                        {/if}
                    </div>
                {/foreach}

                <div class="clearfix"></div>

                <hr class="tour-line-separator">

                <div id="c-tour-price-fon" style="display: none;">
                    {if $tour->getDiscountPercent() !== null}
                        <div id="c-tour-price">от {$tour->getSalePrice()|escape|price_format} руб.</div>
                        <div id="c-tour-price-old">{$tour->getPrice()|escape|price_format} руб.</div>
                        <div id="c-tour-price-line"></div>
                    {else}
                        <div class="c-tour-price-old print-unstrike">от {$tour->getSalePrice()|escape|price_format} руб.</div>
                    {/if}
                </div>

                {if $tour->getPayableIncludes() !== null && count($tour->getPayableIncludes())}
                    <div class="tour-payable-part">
                        <b>В стоимость входит:</b>

                        <ul>
                            {foreach $tour->getPayableIncludes() as $include}
                                <li>
                                    <span class="check-mark check-mark-red">&check;</span>
                                    {$include|escape}
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}

                {if $tour->getPayableExcludes() !== null && count($tour->getPayableExcludes()|array_filter)}
                    <div class="tour-payable-part">
                        <b>Оплачивается дополнительно:</b>

                        <ul>
                            {foreach $tour->getPayableExcludes()|array_filter as $exclude}
                                <li>
                                    <span class="check-mark check-mark-gray">&check;</span>
                                    {$exclude|escape}
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}

                <div class="clearfix"></div>
            </td>
        </tr>
    </tbody>
</table>

{assign var="departures" value=$tour->getDepartures()}

{**
<h4>
    {foreach $departures as $departure}
        {if $departure@last} и {/if}{$departure->getDate()->setDefaultTimeZone()->format('d.m')} на {$departure->getDaynum()|escape} {$departure->getDaynum()|plural_form:"день":"дня":"дней"}{if $departure->getSeats()} ({$departure->getSeats()|escape}){/if}{if $departure@iteration > 1}{if !$departure@last}, {/if}{/if}
    {/foreach}
</h4>
**}

{if !empty($departures[0])}
    <table width="100%" border="1" class="c-tour-tabl-wapk" id="table-departure-hotels">
        <tbody>
            <tr>
                <th scope="col" class="table-header-first">
                    Название отеля
                </th>

                <th scope="col" class="nutrition">
                    Питание
                </th>

                {foreach $departures as $departure}
                    <th scope="col" class="price{if $departure@last} table-header-last{/if}">
                        {if $departure->getIsDatetime()}
                            <div>{$departure->getDate()->toUTC()->localeFormat('d.m.Y, H:i', 'RU2')|lower}</div>
                            на {$departure->getDaynum()|escape} {$departure->getDaynum()|plural_form:"день":"дня":"дней"}
                        {else}
                            <div>{$departure->getDate()->toUTC()->localeFormat('d.m.Y', 'RU2')|lower}</div>
                            на {$departure->getDaynum()|escape} {$departure->getDaynum()|plural_form:"день":"дня":"дней"}
                        {/if}
                    </th>
                {/foreach}
            </tr>

            {foreach $departures[0]->getOrderedHotels($tour, $discounts, $default_discount, $default_promo) as $k_h => $hotel}
                <tr class="tabl_grey_style">
                    <td class="hotels">
                        {if !empty($hotel.id)}
                            <a href="{$__url->url("/hotel/{$hotel.id|escape}")}" class="hotels blank">
                                {if $isShowImages}
                                    <img src="{$__url->url('/imagi')}?text={$hotel.name|escape|upper} {$hotel.stars|escape}&width=350">
                                {else}
                                    {$hotel.name|escape|upper} {$hotel.stars|escape}
                                {/if}
                            </a>
                        {else}
                            {if !empty($hotel.url)}
                                <a href="{$hotel.url|escape}" class="blank" rel="nofollow">
                                    {if $isShowImages}
                                        <img src="{$__url->url('/imagi')}?text={$hotel.name|escape|upper} {$hotel.stars|escape}&width=350">
                                    {else}
                                        {$hotel.name|escape|upper} {$hotel.stars|escape}
                                    {/if}
                                </a>
                            {else}
                                {if $isShowImages}
                                    <img src="{$__url->url('/imagi')}?text={$hotel.name|escape|upper} {$hotel.stars|escape}&width=350">
                                {else}
                                    {$hotel.name|escape|upper} {$hotel.stars|escape}
                                {/if}
                            {/if}
                        {/if}
                    </td>

                    <td class="nutrition">
                        {if !empty($hotel.nutrition_type)}
                            {if $isShowImages}
                                <img src="{$__url->url('/imagi')}?text={DomainObjectModel_Hotel::obtainNutritionType($hotel.nutrition_type, 'title')}&width=125">
                            {else}
                                {DomainObjectModel_Hotel::obtainNutritionType($hotel.nutrition_type, 'title')}
                            {/if}
                        {else}
                            &mdash;
                        {/if}
                    </td>

                    {foreach $departures as $departure}
                        {assign var="departure_hotels" value=$departure->getHotels()}

                        {$hotel_price = $departure_hotels[$k_h].sale_price}

                        {if $tour->getIsDiscountApplied() && !empty($departure_hotels[$k_h].is_discountable)}
                            {$isAnyFitting = 0}

                            {$type = 'DISCOUNT'}

                            {if $tour->getIsHighlight()}
                                {$type = 'PROMO'}
                            {elseif !empty($departure_hotels[$k_h].is_promoprice)}
                                {$type = 'PROMO'}
                            {/if}

                            {$usableDiscount = null}

                            {if !empty($discounts[$type])}
                                {foreach $discounts[$type] as $discount}
                                    {$isFitting = $tour->isDiscountFitting($discount, $hotel_price)}

                                    {if !$isFitting}
                                        {continue}
                                    {/if}

                                    {$isAnyFitting = true}

                                    {$usableDiscount = $discount}

                                    {break}
                                {/foreach}
                            {/if}

                            {if !$isAnyFitting}
                                {if $type == 'DISCOUNT'}
                                    {if $default_discount !== null && $default_discount->getPercent() > 0}
                                        {$usableDiscount = $default_discount}
                                    {/if}
                                {else}
                                    {if $default_promo !== null && $default_promo->getPercent() > 0}
                                        {$usableDiscount = $default_promo}
                                    {/if}
                                {/if}
                            {/if}

                            {$hotel_price = $tour->calculatePriceWithDiscount($hotel_price, $usableDiscount)}
                        {/if}

                        <td class="price">
                            {if !empty($hotel_price)}
                                {if $isShowImages}
                                    <img src="{$__url->url('/imagi')}?text={$hotel_price|escape|price_format}&width=100">
                                {else}
                                    {$hotel_price|escape|price_format}
                                {/if}

                                <a href="#" data-source="{$__url->url("/order/create/`$tour->getId()`/`$departure->getDate()->format('d-m-Y')`/`$departure_hotels[$k_h].signature`/`$hotel_price`/")}" class="order-button fake-link">
                                    Купить
                                </a>
                            {else}
                                &mdash;
                            {/if}
                        </td>
                    {/foreach}
                </tr>
            {/foreach}

            <tr>
                <td class="c-tour-tabl-wapk-low" colspan="4">&nbsp;</td>
            </tr>
        </tbody>
    </table>
{/if}

<p></p>

{if $tour->getBrief()|strip_tags}
    <div id="modal-visas-info-{$tour->getId()}" class="modal hidden fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <div class="modal-body-inner content-common" style="padding-top: 0; padding-bottom: 0; padding-left: 0;">
                        <h3 style="margin-top: 0;">Сведения о визе</h3>

                        {$tour->getBrief()}
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}