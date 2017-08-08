{$__ctx->addJs('/frontend/form_order_customer_data.js')}

{assign var="model" value=$__f->getModel()}

<div class="form-intro">
    Чтобы мы могли подготовить договор и начать бронирование этого тура для вас, заполните форму ниже
</div>

<form class="form form-order form-order-customer-data" action="{$__f->getUrl()}" method="post">
    <div{if $model->isCustomerDataFilled()} class="hidden"{/if}>
        {foreach $model->getCustomerData() as $type => $customers}
            {if count($customers) == 0}{continue}{/if}

            <h4>{if $type == 'ADULTS'}Взрослые{else}Дети{/if}</h4>

            {foreach $customers as $k => $customer}
                <div class="customer-data-element customer-data-{$type|lower|escape}">
                    {if $type == 'ADULTS'}
                        {if !empty($errors.ADULTS[$k])}
                            {include file="frontend/form/order/include/part_adults.tpl.php" data=$customer id=$k type=ADULTS fieldname=$__f->encode('customer_data') errors=$errors.ADULTS[$k]}
                        {else}
                            {include file="frontend/form/order/include/part_adults.tpl.php" data=$customer id=$k type=ADULTS fieldname=$__f->encode('customer_data') errors=array()}
                        {/if}
                    {else}
                        {if !empty($errors.CHILDREN[$k])}
                            {include file="frontend/form/order/include/part_adults.tpl.php" data=$customer id=$k type=CHILDREN fieldname=$__f->encode('customer_data') errors=$errors.CHILDREN[$k]}
                        {else}
                            {include file="frontend/form/order/include/part_adults.tpl.php" data=$customer id=$k type=CHILDREN fieldname=$__f->encode('customer_data') errors=array()}
                        {/if}
                    {/if}
                </div>
            {/foreach}
        {/foreach}
    </div>

    {if $model->getContract() === null}
        {*<h2>Необходимо добавить договор</h2>*}
    {else}
        <div class="control-group{if !empty($errors['order_is_contract_agree'])} has-error{/if}">
            <label for="{$__f->encode('order_is_contract_agree')}" class="controls">
                <input type="checkbox" name="{$__f->encode('order_is_contract_agree')}" id="{$__f->encode('order_is_contract_agree')}" value="1"{if $model->getIsContractAgree()} checked="checked"{/if} />

                Я согласен/согласна с <a href="{$model->getContract()}">условиями договора</a>

                <div class="help-block error">
                    Обязательно согласитесь с договором перед началом оплаты
                </div>
            </label>
        </div>
    {/if}

    <footer class="form-footer">
        <div class="form-field">
            <div class="form-button-container">
                <div class="pull-right">
                    <div class="form-remark">
                        <span class="form-asterisk">*</span> &mdash; обязательные для заполнения поля
                    </div>
                </div>

                <input type="submit" name="{$__f->encode('__send')}" class="site-btn" value="Отправить данные{if $model->getContract()} и перейти к оплате{/if}" />
            </div>
        </div>

        <div class="footer-notice">
            Персональные данные обрабатываются в соответствии с действующим законодательством РФ (Федеральный закон РФ от 27 июля 2006 года № 152-ФЗ «О персональных данных»).
        </div>
    </footer>
</form>