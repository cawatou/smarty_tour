{$__ctx->setPageTitle('Просмотр заказа')}

<div class="content-block">
    <header class="content-header">
        <h2>
            Заказ на покупку тура{if $tour !== null} &mdash;
                {$tour->getCountryName()|escape}

                {if $tour->getResortName()}
                    {$tour->getResortName()|escape}
                {/if}

                {$tour->getFrom('title_from')|escape}
            {else} &mdash;
                {$order->getProductData('country_name')|escape}

                {if $order->getProductData('resort_name')}
                    {$order->getProductData('resort_name')|escape}
                {/if}

                {$order->getProductFrom('title_from')|escape}
            {/if}
        </h2>

        <div class="form-intro">
            <p>
                <b>
                    По всем возникающим вопросам, вы можете связаться с вашим персональным менеджером.
                </b>
            </p>

            <p>
                {$settings.MANAGER_NAME|escape}{if $settings.MANAGER_EMAIL || $settings.MANAGER_PHONE},
                    {if $settings.MANAGER_PHONE}тел.: {$settings.MANAGER_PHONE|escape}{if $settings.MANAGER_EMAIL},{/if}{/if}
                    {if $settings.MANAGER_EMAIL}email: {$settings.MANAGER_EMAIL}{/if}
                {/if}
            </p>
        </div>

        {if $order->getContractUrl()}
            <p style="padding: 5px 0 15px">
                Для ознакомления с договором, <a href="{$order->getContractUrl()}">перейдите по ссылке</a>
            </p>
        {/if}
    </header>

    <div id="c-tour">
        <table width="100%" border="1" class="c-tour-tabl-wapk" id="table-departure-hotels">
            <tbody>
                <tr>
                    <th scope="col">
                        Название отеля
                    </th>

                    <th scope="col" class="nutrition">
                        Питание
                    </th>

                    <th scope="col" class="price">
                        {$order->getHotelData('departure_date')->format('d.m.Y')}
                        на {$order->getHotelData('departure_daynum')|escape} {$order->getHotelData('departure_daynum')|plural_form:"день":"дня":"дней"}
                    </th>
                </tr>

                <tr class="tabl_grey_style">
                    <td class="hotels">
                        {if $order->getHotelData('id')}
                            <a href="{$__url->url("/hotel/{$order->getHotelData('id')|escape}")}" class="hotels blank">
                                {$order->getHotelData('name')|escape} {$order->getHotelData('stars')|escape}
                            </a>
                        {else}
                            {if $order->getHotelData('url')}
                                <a href="{$order->getHotelData('url')|escape}" class="blank" rel="nofollow">
                                    {$order->getHotelData('name')|escape} {$order->getHotelData('stars')|escape}
                                </a>
                            {else}
                                {$order->getHotelData('name')|escape} {$order->getHotelData('stars')|escape}
                            {/if}
                        {/if}
                    </td>
                    <td class="nutrition">
                        {if $order->getHotelData('nutrition_type')}
                            {DomainObjectModel_Hotel::obtainNutritionType($order->getHotelData('nutrition_type'), 'title')}
                        {else}
                            &mdash;
                        {/if}
                    </td>

                    <td class="price">
                        {if $order->getPrice() !== null}
                            {$order->getPrice()|price_format} руб.
                        {else}
                            <span title="Цена пока что не указана">
                                &mdash;
                            </span>
                        {/if}
                    </td>
                </tr>

                <tr>
                    <td class="c-tour-tabl-wapk-low" colspan="4">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="customer-data">
        {if $form->successful}
            <div class="form-successful">
                {if $order->getContractUrl()}
                    <strong>Спасибо,</strong> вы можете внести предоплату!
                {else}
                    <strong>Спасибо,</strong> в ближайшее время менеджер проверит введённые данные, подготовит договор и выставит счёт на оплату!
                {/if}
            </div>
        {else}
            {if !empty($callback)}
                <div class="form-successful">
                    {if $callback.message == 'SUCCESS'}
                        Спасибо за вашу оплату!
                        Как только платежная система пришлет нам подтверждение, мы забронируем для вас тур.
                    {elseif $callback.message == 'FAIL'}
                        Упс!
                        Оплата не прошла. Но не переживайте, вы можете попробовать оплатить позже или использовать другую карту.
                    {/if}
                </div>
            {/if}
        {/if}

        {if !$order->isCustomerDataFilled() || !empty($errors)}
            {$form->draw()}
        {else}
            {foreach $order->getCustomerData() as $type => $customers}
                {if count($customers) == 0}{continue}{/if}

                <h4>{if $type == 'ADULTS'}Взрослые{else}Дети{/if}</h4>

                <ol>
                    {foreach $customers as $customer}
                        <li>
                            <p>
                                <b>{$customer.name_latin|escape} {$customer.surname_latin|escape}</b>,
                                {$customer.birthday->localeFormat('\x\x F Y')|lower}
                            </p>

                            <p>
                                Паспорт:
                                {'x'|str_repeat:strlen($customer.passport_series)}
                                {'x'|str_repeat:strlen($customer.passport_number)},

                                выдан
                                {$customer.passport_issue_date->localeFormat('j F Y')|lower},
                                {$customer.passport_issuer|escape},

                                годен до
                                {$customer.passport_expiration_date->localeFormat('j F Y')|lower}
                            </p>

                            <p>
                                Гражданство: {$customer.citizenship|escape}
                            </p>
                        </li>
                    {/foreach}
                </ol>
            {/foreach}

            {if !$order->getIsContractAgree() && $order->getContract()}
                {$form->draw()}
            {/if}
        {/if}
    </div>

    {if count($order->getPayments()) > 0 && $order->isCustomerDataFilled() && !$form->hasErrors() && $order->getIsContractAgree()}
        <table width="100%" border="1" class="c-tour-tabl-wapk c-tour-tabl-wapk-clear">
            <tbody>
                <tr>
                    <th>
                        Выставлен
                    </th>

                    <th>
                        Оплачен
                    </th>

                    <th>
                        Сумма платежа
                    </th>

                    <th>
                        Статус
                    </th>

                    <th>&nbsp;</th>
                </tr>

                {foreach $order->getPayments() as $payment}
                    <tr>
                        <td>
                            {$payment->getCreated()->format('d.m.Y')}
                        </td>
                        <td>
                            {if $payment->getCompleted() !== null}
                                {$payment->getCompleted()->format('d.m.Y')}
                            {else}
                                &mdash;
                            {/if}
                        </td>
                        <td>
                            {$payment->getAmount()|price_format:true}
                        </td>
                        <td>
                            {$payment->getStatusTitle()|escape}
                        </td>
                        <td>
                            {if $payment->getStatus() == 'NEW'}
                                {include file="frontend/form/include/order_payonline.tpl.php" cfg=$payonline_cfg payment=$payment urls=$urls}
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {/if}
</div>