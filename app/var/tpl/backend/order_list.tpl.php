<div class="container">
    {include file='backend/submenu/order.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">

            {if empty($list) && !$filter->isActive()}
                <div class="alert alert-info">Пользователи сайта ещё не сделали ни одного заказа.</div>
            {else}
                {$filter->draw()}

                {if empty($list)}
                    <div class="alert alert-warning">Ничего не найдено. Вы можете <a href="{$__url->adm('.order')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.</div>
                {else}
                    {foreach $list as $order}
                        <div class="cms-group-header">
                            <div class="cms-group-actions">
                                <a href="{$__url->adm('.order.edit')}?order_id={$order->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>

                                {if $order->getStatus() == 'NEW' && $__ctx->getCurrentUser()->canDelete('.adm.order')}
                                    <a href="{$__url->adm('.order.delete')}?order_id={$order->getId()|escape}" class="btn btn-xs btn-danger" title="{'Удалить'|t}" onclick="return confirm('Вы уверены?');"><i class="fa fa-trash-o"></i></a>
                                {/if}
                            </div>

                            <div class="cms-group-status{if $order->getStatus() == 'NEW'} highlight-warning{/if}">
                                {$order->getStatusName()|escape}
                            </div>

                            <div class="cms-group-date">
                                {$order->getCreated()->setDefaultTimeZone()->format('d.m.Y H:i')}
                            </div>
                        </div>

                        <div class="cms-group cms-group-white">
                            {if !$order->isCustomerDataFilled()}
                                <div class="alert alert-danger">
                                    Клиент оставил заявку, но ещё не заполнил анкету.
                                </div>
                            {/if}

                            <div class="row">
                                <div class="col col-md-4">
                                    <div class="panel panel-default">
                                        <table class="table table-bordered table-small">
                                            <thead></thead>
                                            <tbody>
                                                <tr>
                                                    <td width="30%">
                                                        Заказ:
                                                    </td>
                                                    <td>
                                                        {if $order->isAvailable()}
                                                            <a href="{$order->getUrl()}">
                                                                #{$order->getId()|escape}
                                                            </a>
                                                        {else}
                                                            <abbr title="Данный заказ недоступен для просмотра (статус 'Новый', не указаны платежи или не добавлен договор)">
                                                                #{$order->getId()|escape}
                                                            </abbr>
                                                        {/if}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30%">
                                                        Тур:
                                                    </td>
                                                    <td>
                                                        {if $order->getProductData('product_id')}
                                                            <a href="{$__url->url("/tours/`$order->getProductData('product_id')`")}">
                                                                {$order->getProductData('country_name')|escape}

                                                                {if $order->getProductData('resort_name')}
                                                                    {$order->getProductData('resort_name')|escape}
                                                                {/if}

                                                                {$order->getProductFrom('title_from')|escape}
                                                            </a>
                                                        {else}
                                                            {$order->getProductData('country_name')|escape}

                                                            {if $order->getProductData('resort_name')}
                                                                {$order->getProductData('resort_name')|escape}
                                                            {/if}

                                                            {$order->getProductFrom('title_from')|escape}

                                                        {/if}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30%">
                                                        Отель:
                                                    </td>
                                                    <td>
                                                        {$order->getHotelData('name')|escape}
                                                    </td>
                                                </tr>
                                                {if $order->getHotelData('departure_date') !== null}
                                                <tr>
                                                    <td width="30%">
                                                        Дата:
                                                    </td>
                                                    <td>
                                                        {$order->getHotelData('departure_date')->format('d.m.Y')}
                                                    </td>
                                                </tr>
                                                {/if}
                                                <tr>
                                                    <td width="30%">
                                                        Ночей:
                                                    </td>
                                                    <td>
                                                        {$order->getHotelData('departure_daynum')|escape}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30%">
                                                        Цена:
                                                    </td>
                                                    <td>
                                                        {if $order->getPrice() !== null}
                                                            <s>{$order->getPriceOpening()|price_format:true} р.</s> {$order->getPrice()|price_format:true} р.
                                                        {else}
                                                            {$order->getPriceOpening()|price_format:true} р.
                                                        {/if}

                                                        {if $order->getCustomerData('get_via_price') > 0}
                                                            + {$order->getCustomerData('get_via_price')|price_format:false} р. (способ доставки «{$order->getCustomerData('get_via_title')|escape}»)
                                                        {/if}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col col-md-4">
                                    <div class="panel panel-default">
                                        <table class="table table-bordered table-small">
                                            <thead></thead>
                                            <tbody>
                                                <tr>
                                                    <td width="30%">
                                                        Имя:
                                                    </td>
                                                    <td>
                                                        {$order->getCustomerName()|escape}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        IP:
                                                    </td>
                                                    <td>
                                                        {$order->getCustomerIp()|escape}
                                                    </td>
                                                </tr>

                                                {if $order->getCustomerEmail() !== null}
                                                    <tr>
                                                        <td>
                                                            Email:
                                                        </td>
                                                        <td>
                                                            <a href="mailto:{$order->getCustomerEmail()|escape}">{$order->getCustomerEmail()|escape}</a>
                                                        </td>
                                                    </tr>
                                                {/if}

                                                {if $order->getCustomerPhone() !== null}
                                                    <tr>
                                                        <td>
                                                            Телефон:
                                                        </td>
                                                        <td>
                                                            {$order->getCustomerPhone()|escape}
                                                        </td>
                                                    </tr>
                                                {/if}

                                                {if $order->getCustomerTotalAdults() !== null}
                                                    <tr>
                                                        <td>
                                                            Взрослых:
                                                        </td>
                                                        <td>
                                                            {$order->getCustomerTotalAdults()|escape}
                                                        </td>
                                                    </tr>
                                                {/if}

                                                {if $order->getCustomerTotalChildren() !== null}
                                                    <tr>
                                                        <td>
                                                            Детей:
                                                        </td>
                                                        <td>
                                                            {$order->getCustomerTotalChildren()|escape}
                                                        </td>
                                                    </tr>
                                                {/if}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {if $order->getComment() !== null}
                                    <div class="col col-md-4">
                                        <div class="panel panel-default">
                                            <table class="table table-bordered table-small">
                                                <tr>
                                                    <td>
                                                        Комментарий:
                                                    </td>
                                                    <td>
                                                        {$order->getComment()|escape|nl2br}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                {/if}
                            </div>

                            {**
                            {if count($order->getPayments()) > 0}
                                <div class="table-responsive">
                                    <table class="table table-responsive table-striped">
                                        <thead>
                                            <tr class="center">
                                                <th class="left">Дата</th>
                                                <th>Сумма</th>
                                                <th>Тип</th>
                                                <th>Статус</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach $order->getPayments() as $payment}
                                                <tr>
                                                    <td>
                                                        {$payment->getDate()->setDefaultTimeZone()->format('d.m.Y H:i:s')}
                                                    </td>
                                                    <td class="center">
                                                        {$payment->getAmount()|price_format:true}
                                                    </td>
                                                    <td class="center">
                                                        {$payment->getTypeName()|escape}
                                                    </td>
                                                    <td class="center">
                                                        {$payment->getStatusName()|escape}
                                                    </td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                            {/if}
                            **}
                        </div>
                    {/foreach}

                    {include file='backend/common_paginator.tpl.php' state=$state}
                {/if}
            {/if}
        </div>
    </div>
</div>