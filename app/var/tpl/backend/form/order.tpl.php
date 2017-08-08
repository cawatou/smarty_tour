{assign var="model" value=$__f->m()}

{$__ctx->addCss('/backend/form/order.css')}
{$__ctx->addJs('/backend/form/order.js')}

{$__ctx->addCss('../js/backend/datepicker/jquery-ui-1.10.3.custom.css')}
{$__ctx->addJs('/backend/datepicker/jquery-ui-1.10.3.custom.js')}
{$__ctx->addJs('/backend/datepicker.js')}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Данные о заказе изменены'|t}
    </div>
{/if}

{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Произошла ошибка, проверьте правильность заполнения полей'|t}
    </div>
{/if}

<form role="form" method="post" action="{$__f->getUrl()}" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('order_price') !== null} has-error{/if}">
                <label for="order_price">{'Полная стоимость'|t}</label>

                <div class="input-group">
                    <input type="text" class="form-control" name="{$__f->encode('order_price')}" value="{$model->getPrice()|escape}" id="order_price">
                    <span class="input-group-addon"><i class="fa fa-rub"></i></span>
                </div>

                <span class="help-block help-block-error">
                    {if $__f->e('order_price') == 'LESS_THAN_PAYMENTS'}
                        Итоговая сумма платежей выше, чем полная стоимость заказа
                    {else}
                        {'Неверное значение'|t}
                    {/if}
                </span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label>Стоимость предложения</label>

                <div class="input-group">
                    <input type="text" class="form-control" value="{$model->getPriceOpening()|price_format:true}" readonly="readonly">
                    <span class="input-group-addon"><i class="fa fa-rub"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('order_customer_total_adults') !== null} has-error{/if}">
                <label for="{$__f->encode('order_customer_total_adults')}">{'Кол-во взрослых'|t} <i class="fa fa-check"></i></label>

                <select name="{$__f->encode('order_customer_total_adults')}" id="{$__f->encode('order_customer_total_adults')}" class="form-control"{if $model->isCustomerDataFilled()} readonly="readonly"{/if}>
                    {foreach $adults_vals as $adult}
                        <option value="{(int)$adult}"{if $adult == $model->getCustomerTotalAdults()} selected="selected"{/if}>{(int)$adult}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">{'Неверное значение'|t}</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('order_customer_total_children') !== null} has-error{/if}">
                <label for="{$__f->encode('order_customer_total_children')}">{'Кол-во детей'|t} <i class="fa fa-check"></i></label>

                <select name="{$__f->encode('order_customer_total_children')}" id="{$__f->encode('order_customer_total_children')}" class="form-control"{if $model->isCustomerDataFilled()} readonly="readonly"{/if}>
                    {foreach $childs_vals as $child}
                        <option value="{(int)$child}"{if $child == $model->getCustomerTotalChildren()} selected="selected"{/if}>{(int)$child}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">{'Неверное значение'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="cms-group cms-group-expanded">
                <div class="cms-group-label">Файл договора</div>

                {if $model->getContract() !== null}
                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <a href="{$model->getContract()|escape}" class="btn btn-default">
                                    <i class="fa fa-download"></i>
                                    Скачать договор
                                </a>
                            </div>
                        </div>
                    </div>
                {/if}

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {if $model->getContract() !== null}
                                <label>Заменить файл договора</label>
                            {/if}

                            <input type="file" name="order_contract" value="Обзор" />
                            <span class="help-block help-block-error">{if $__f->e('order_contract') === 'TYPE_UNSUPPORTED'}Этот формат не поддерживается (используйте RTF, DOC, PDF){else}Файл не существует ({$__f->e('order_contract')}){/if}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group{if $__f->e('order_comment') !== null} has-error{/if}">
                <label for="{$__f->encode('order_comment')}">{'Комментарий к заказу'|t}</label>
                <textarea name="{$__f->encode('order_comment')}" id="{$__f->encode('order_comment')}" class="form-control form-textarea-vertical">{$model->getComment()|escape}</textarea>
            </div>
        </div>
    </div>

    {if $model->isCustomerDataFilled()}
        <div class="cms-group cms-group-expanded">
            <div class="cms-group-label">Данные о покупателях</div>

            {foreach $model->getCustomerData() as $type => $customers}
                {if empty($customers)}{continue}{/if}

                <h4>{if $type == 'ADULTS'}Взрослые{else}Дети{/if}</h4>

                {foreach $customers as $k => $customer}
                    <div class="cms-group cms-group-white">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group form-group-required{if !empty($errors_cust[{$type}][{$k}].name_latin)} has-error{/if}">
                                    <label for="{$__f->encode('order_customer_data')}_{$type}_{$k}_name_latin">{'Имя (латиницей)'|t} <i class="fa fa-check"></i></label>

                                    <input name="{$__f->encode('order_customer_data')}[{$type}][{$k}][name_latin]" id="{$__f->encode('order_customer_data')}_{$type}_{$k}_name_latin" class="form-control" value="{if !empty($customer.name_latin)}{$customer.name_latin|escape}{/if}" />

                                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group form-group-required{if !empty($errors_cust[{$type}][{$k}].surname_latin)} has-error{/if}">
                                    <label for="{$__f->encode('order_customer_data')}_{$type}_{$k}_surname_latin">{'Фамилия (латиницей)'|t} <i class="fa fa-check"></i></label>

                                    <input name="{$__f->encode('order_customer_data')}[{$type}][{$k}][surname_latin]" id="{$__f->encode('order_customer_data')}_{$type}_{$k}_surname_latin" class="form-control" value="{if !empty($customer.surname_latin)}{$customer.surname_latin|escape}{/if}" />

                                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group form-group-required{if !empty($errors_cust[{$type}][{$k}].birthday)} has-error{/if}">
                                    <label for="{$__f->encode('order_customer_data')}_{$type}_{$k}_birthday">{'Дата рождения'|t} <i class="fa fa-check"></i></label>

                                    <div class="input-group">
                                        <input name="{$__f->encode('order_customer_data')}[{$type}][{$k}][birthday]" id="{$__f->encode('order_customer_data')}_{$type}_{$k}_birthday" class="form-control datepicker" value="{if !empty($customer.birthday)}{$customer.birthday->format('d.m.Y')}{/if}" />
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>

                                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group form-group-required{if !empty($errors_cust[{$type}][{$k}].gender)} has-error{/if}">
                                    <label for="{$__f->encode('order_customer_data')}_{$type}_{$k}_gender">{'Пол'|t} <i class="fa fa-check"></i></label>

                                    <select name="{$__f->encode('order_customer_data')}[{$type}][{$k}][gender]" id="{$__f->encode('order_customer_data')}_{$type}_{$k}_gender" class="form-control">
                                        <option value="MALE"{if empty($customer.gender) || $customer.gender == 'MALE'} selected="selected"{/if}>М</option>
                                        <option value="FEMALE"{if !empty($customer.gender) && $customer.gender == 'FEMALE'} selected="selected"{/if}>Ж</option>
                                    </select>

                                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group form-group-required{if !empty($errors_cust[{$type}][{$k}].citizenship)} has-error{/if}">
                                    <label for="{$__f->encode('order_customer_data')}_{$type}_{$k}_citizenship">{'Гражданство'|t} <i class="fa fa-check"></i></label>

                                    <input name="{$__f->encode('order_customer_data')}[{$type}][{$k}][citizenship]" id="{$__f->encode('order_customer_data')}_{$type}_{$k}_citizenship" class="form-control" value="{if !empty($customer.citizenship)}{$customer.citizenship|escape}{/if}" />

                                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group form-group-required{if !empty($errors_cust[{$type}][{$k}].passport_series)} has-error{/if}">
                                    <label for="{$__f->encode('order_customer_data')}_{$type}_{$k}_passport_series">{'Серия паспорта'|t} <i class="fa fa-check"></i></label>

                                    <input name="{$__f->encode('order_customer_data')}[{$type}][{$k}][passport_series]" id="{$__f->encode('order_customer_data')}_{$type}_{$k}_passport_series" class="form-control" value="{if !empty($customer.passport_series)}{$customer.passport_series|escape}{/if}" />

                                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group form-group-required{if !empty($errors_cust[{$type}][{$k}].passport_number)} has-error{/if}">
                                    <label for="{$__f->encode('order_customer_data')}_{$type}_{$k}_passport_number">{'Номер паспорта'|t} <i class="fa fa-check"></i></label>

                                    <input name="{$__f->encode('order_customer_data')}[{$type}][{$k}][passport_number]" id="{$__f->encode('order_customer_data')}_{$type}_{$k}_passport_number" class="form-control" value="{if !empty($customer.passport_number)}{$customer.passport_number|escape}{/if}" />

                                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group form-group-required{if !empty($errors_cust[{$type}][{$k}].passport_issue_date)} has-error{/if}">
                                    <label for="{$__f->encode('order_customer_data')}_{$type}_{$k}_passport_issue_date">{'Дата выдачи'|t} <i class="fa fa-check"></i></label>

                                    <div class="input-group">
                                        <input name="{$__f->encode('order_customer_data')}[{$type}][{$k}][passport_issue_date]" id="{$__f->encode('order_customer_data')}_{$type}_{$k}_passport_issue_date" class="form-control datepicker" value="{if !empty($customer.passport_issue_date)}{$customer.passport_issue_date->format('d.m.Y')|escape}{/if}" />
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>

                                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group form-group-required{if !empty($errors_cust[$type].passport_expiration_date)} has-error{/if}">
                                    <label for="{$__f->encode('order_customer_data')}_{$type}_{$k}_passport_expiration_date">{'Годен до'|t} <i class="fa fa-check"></i></label>

                                    <div class="input-group">
                                        <input name="{$__f->encode('order_customer_data')}[{$type}][{$k}][passport_expiration_date]" id="{$__f->encode('order_customer_data')}_{$type}_{$k}_passport_expiration_date" class="form-control datepicker" value="{if !empty($customer.passport_expiration_date)}{$customer.passport_expiration_date->format('d.m.Y')|escape}{/if}" />
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>

                                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group form-group-required{if !empty($errors_cust[{$type}][{$k}].passport_issuer)} has-error{/if}">
                                    <label for="{$__f->encode('order_customer_data')}_{$type}_{$k}_passport_issuer">{'Кем выдан'|t} <i class="fa fa-check"></i></label>

                                    <input name="{$__f->encode('order_customer_data')}[{$type}][{$k}][passport_issuer]" id="{$__f->encode('order_customer_data')}_{$type}_{$k}_passport_issuer" class="form-control" value="{if !empty($customer.passport_issuer)}{$customer.passport_issuer|escape}{/if}" />

                                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                {/foreach}
            {/foreach}
        </div>
    {/if}

    <div class="cms-group cms-group-expanded">
        <div class="cms-group-label">Платежи</div>

        {foreach $model->getPayments() as $k => $payment}
            <div class="row">
                <div class="col-md-2">
                    {if $payment->getStatus() == 'NEW'}
                        {capture assign="error_pa_key"}order_payment_{$k}_amount{/capture}
                        <div class="form-group form-group-required{if $__f->e($error_pa_key) !== null || $__f->e('order_price') !== null} has-error{/if}">
                            <div class="input-group">
                                <input name="{$__f->encode('order_payment')}[{$k}][order_payment_amount]" id="{$__f->encode('order_payment')}_{$k}_order_payment_amount" class="form-control" value="{$payment->getAmount()}" />
                                <span class="input-group-addon"><i class="fa fa-rub"></i></span>
                            </div>

                            <span class="help-block help-block-error">
                                {if $__f->e('order_price') === 'LESS_THAN_PAYMENTS'}
                                    Проверьте итоговую сумму всех не отменённых платежей
                                {else}
                                    {'Неверное значение'|t}
                                {/if}
                            </span>
                        </div>
                    {else}
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control" value="{$payment->getAmount()}" disabled="disabled" />
                                <span class="input-group-addon"><i class="fa fa-rub"></i></span>
                            </div>
                        </div>
                    {/if}
                </div>

                <div class="col-md-4">
                    <h4>
                        <span class="label label-{$payment->getStatusClass()}">
                            {$payment->getStatusTitle()|escape}
                        </span>
                    </h4>
                </div>

                <div class="col-md-3">
                    {if $payment->getStatus() == 'PREAUTH'}
                        <a href="{$__url->adm('.order.cancel_payment')}?order_id={$payment->getOrderId()}&order_payment_id={$payment->getId()}" class="btn btn-default" onclick="return confirm('Вы уверены? Эту операцию нельзя отменить.');">
                            Вернуть
                        </a>

                        <a href="{$__url->adm('.order.complete_payment')}?order_id={$payment->getOrderId()}&order_payment_id={$payment->getId()}" class="btn btn-default" onclick="return confirm('Вы уверены? Эту операцию нельзя отменить. Средства будут физически списаны со счёта пользователя.');">
                            Списать
                        </a>
                    {/if}
                </div>
            </div>
        {/foreach}

        {if $model->getStatus() != 'COMPLETED' && $model->getStatus() != 'CANCELLED'}
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group form-group-required{if $__f->e('order_payment_0_amount') !== null} has-error{/if}">
                        <label for="{$__f->encode('order_payment')}_0_order_payment_amount">{'Новый платеж'|t} <i class="fa fa-check"></i></label>

                        <div class="input-group">
                            <input name="{$__f->encode('order_payment')}[0][order_payment_amount]" id="{$__f->encode('order_payment')}_0_order_payment_amount" class="form-control" value="" />
                            <span class="input-group-addon"><i class="fa fa-rub"></i></span>
                        </div>

                        <span class="help-block help-block-error">
                            {'Неверное значение'|t}
                        </span>
                    </div>
                </div>
            </div>
        {/if}
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('order_status') !== null} has-error{/if}">
                <label for="{$__f->encode('order_status')}">{'Статус заказа'|t} <i class="fa fa-check"></i></label>

                <select name="{$__f->encode('order_status')}" id="{$__f->encode('order_status')}" class="form-control">
                    {foreach $order_statuses as $status_id => $status_name}
                        {if $status_id == 'NEW'}{continue}{/if}

                        <option value="{$status_id|escape}"{if $status_id == $model->getStatus()} selected="selected"{/if}>{$status_name|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">{'Неверное значение'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>

                {if $model->getPrice() !== null && $model->getContract() !== null && count($model->getPayments()) > 0}
                    <a href="{$__url->adm('.order.send')}?order_id={$model->getId()}" class="btn btn-default" title="Отправить клиенту письмо с информацией о счёте, итоговой суммой, договором и возможностью оплаты счёта">{'Отправить письмо клиенту'|t}</a>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.order')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>