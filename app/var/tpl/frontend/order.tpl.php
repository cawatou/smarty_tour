<div class="content-block">
    <div>
        {if $is_price_changed}
            <div class="alert">
                <h4>Важно!</h4>

                <p>
                    Цена на данный тур была изменена с
                    <b class="price-original">{$original_price|price_format:true}</b>
                    до
                    <b class="price-current">{$changed_price|price_format:true}</b>
                </p>
            </div>
        {/if}

        <header class="content-header" id="form">
            <h2>
                Покупка тура:

                {$tour->getCountryName()|escape}

                {if $tour->getResortName()}
                    {$tour->getResortName()|escape}
                {/if}

                {$tour->getFrom('title_from')|escape}
            </h2>
        </header>

        <div id="c-tour">
            <table width="100%" border="1" class="c-tour-tabl-wapk" id="table-departure-hotels" style="margin-top: 10px;">
                <tbody>
                    <tr>
                        <th scope="col">
                            Название отеля
                        </th>

                        <th scope="col" class="nutrition">
                            Питание
                        </th>

                        <th scope="col" class="price">
                            {$found_departure->getDate()->setDefaultTimeZone()->format('d.m')}
                            на {$found_departure->getDaynum()|escape} {$found_departure->getDaynum()|plural_form:"день":"дня":"дней"}
                        </th>
                    </tr>

                    <tr class="tabl_grey_style">
                        <td class="hotels">
                            {if !empty($found_hotel.id)}
                                <a href="{$__url->url("/hotel/{$found_hotel.id|escape}")}" class="hotels blank">
                                    {$found_hotel.name|escape} {$found_hotel.stars|escape}
                                </a>
                            {else}
                                {if !empty($found_hotel.url)}
                                    <a href="{$found_hotel.url|escape}" class="blank" rel="nofollow">
                                        {$found_hotel.name|escape} {$found_hotel.stars|escape}
                                    </a>
                                {else}
                                    {$found_hotel.name|escape} {$found_hotel.stars|escape}
                                {/if}
                            {/if}
                        </td>
                        <td class="nutrition">
                            {if !empty($found_hotel.nutrition_type)}
                                {DomainObjectModel_Hotel::obtainNutritionType($found_hotel.nutrition_type, 'title')}
                            {else}
                                &mdash;
                            {/if}
                        </td>

                        <td class="price">
                            {$hotel_price|escape|price_format}
                        </td>
                    </tr>

                    <tr>
                        <td class="c-tour-tabl-wapk-low" colspan="4">&nbsp;</td>
                    </tr>
                </tbody>
            </table>

            <p></p>

            <div class="c-tour-snoska c-tour-snoska-order">
                Стоимость действительна только в день обновления (на 10:00) и только для новых бронирований с 10:00.
                Стоимость указана на 1 человека при двухместном размещении в стандартном номере.
                Обращаем внимание, что все рейсы - чартерные, время вылета туда и обратно может измениться.
                Тур начинается с момента регистрации билетов на авиарейс.
            </div>
        </div>

        {if !$form->successful}
            {$form->draw()}
        {else}
            {$form->setSuccessful(false)}

            <div class="form-successful">
                <strong>Спасибо,</strong> ваш заказ создан. Для продолжения, мы направим вас на страницу вашего заказа для заполнения анкеты.
            </div>

            <div id="modal-buy-form-successful" class="hidden"></div>

            <button onclick="document.location.href = '{$order_url}';">Перейти сейчас</button>

            <script type="text/javascript">
                {literal}setTimeout(function () {document.location.href = '{/literal}{$order_url}{literal}';}, 2500);{/literal}
            </script>
        {/if}
    </div>
</div>