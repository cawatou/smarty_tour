{$isShowImages = false}

<script type="text/javascript">
    var URL_ORDER_CREATE = "{$__url->url('/order/create')}";
</script>

{nocache}
    {$__ctx->addJs('/frontend/order.js')}
{/nocache}

<div id="c-tour">
    {include file='frontend/include/master_tour_item.tpl.php' tour=$tour}

    {if count($tour->getLinkedProducts()) > 0}
        {foreach $tour->getLinkedProducts() as $_tour}
            {include file='frontend/include/master_tour_item.tpl.php' tour=$_tour}
        {/foreach}
    {/if}

    {nocache}
        {if !empty($similar_products)}
            <br>
            <h3>Туры в соседних городах</h3>
            <br>

            <ul class="b-list-tours">
                {foreach $similar_products as $product}

                    <li>
                        <a href="{$product->getUrl()|escape}" class="tour" onmouseout="this.style.background = '#FFF'" onMouseover="this.style.background = '#FF9'">
                            <span class="tour-country">
                                {$product->getCountryName()|escape}
                            </span>

                            <span class="tour-resort">
                                {if $product->getResortName() !== null}
                                    {$product->getResortName()|escape}
                                {/if}

                                {$product->getFrom('title_from')|replace:' ':"&nbsp;"}
                            </span>

                            <span class="tour-dates">
                                {foreach $product->getDepartures() as $departure}
                                    {$departure->getDate()->setDefaultTimeZone()->format('d.m')}
                                    на
                                    {$departure->getDaynum()|escape} {$departure->getDaynum()|plural_form:"день":"дня":"дней"}

                                    {if $departure->getSeats()}
                                        ({$departure->getSeats()|escape})
                                    {/if}

                                    <br />
                                {/foreach}
                            </span>

                            <span class="tour-image"{if $product->getCover() !== null} style="background-image: url({$__url->thumb($product->getCover()|escape, 150, 94)})"{/if}>
                                {if $product->getDiscountPercent() !== null}
                                    <span class="tour-discount">{$product->getDiscountPercent()|escape}%</span>

                                    <span class="tour-price-old">
                                        {$product->getCrossedPrice()|escape|price_format} р.
                                        <span class="cross-out"></span>
                                    </span>
                                {/if}

                                <span class="tour-price">от {$product->getSalePrice()|escape|price_format} р.</span>
                            </span>
                        </a>
                    </li>
                {/foreach}
            </ul>

            <p></p>
        {/if}
    {/nocache}


    <a href="{$__url->url('/search')}" style="float:left" class="c-tour-button">Подобрать другой тур</a>
    <a href="{$__ctx->getData('uri')|regex_replace:'~\?(.+)?~':''}?print" style="float:right;" class="c-tour-button">Распечатать</a>

    <div class="c-tour-snoska">
        Стоимость действительна только в день обновления (на 10:00) и только для новых бронирований с 10:00.
        Стоимость указана на 1 человека при двухместном размещении в стандартном номере.
        Обращаем внимание, что все рейсы - чартерные, время вылета туда и обратно может измениться.
        Тур начинается с момента регистрации билетов на авиарейс.
    </div>
</div>

<div id="modal-passport" class="modal hidden fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="modal-body-inner">
                    <table border="0" width="100%">
                        <tr>
                            <td style="padding-bottom: 15px;">
                                Направление:
                            </td>
                            <td>
                                <select id="passport-country">
                                    <option value="">Выберите направление</option>

                                    {foreach $country_visa_list as $country}
                                        <option value="{$country.country_id|escape}" id="passport-country-option-{$country.country_id|escape}" data-days="{$country.country_visa_days|escape}"{if $country.country_id == $tour->getCountryId()} selected="selected"{/if}>{$country.country_title|escape}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 15px;">
                                Дата начала тура:
                            </td>
                            <td>
                                <input type="text" value="" data-datepicker="" id="passport-date-start" class="input-text" />
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 15px;">
                                Дата окончания тура:
                            </td>
                            <td>
                                <input type="text" value="" data-datepicker="" id="passport-date-end" class="input-text" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div id="passport-result" class="passport-result" style="display: none;">
                                    Крайний срок действия загранпаспорта:

                                    <div id="passport-result-date"></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-buy-tour" class="modal modal-buy-tour hidden fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="modal-body-inner">
                </div>
            </div>
        </div>
    </div>
</div>