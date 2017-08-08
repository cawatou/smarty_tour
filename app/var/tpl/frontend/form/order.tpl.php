{assign var="model" value=$__f->getModel()}

<div class="form-intro">
    Чтобы купить этот тур, заполните форму ниже и наш менеджер свяжется с вами.
</div>

<form class="form form-order modal-buy-tour-form" action="{$__f->getUrl()}" method="post">
    <input type="hidden" name="{$__f->encode('order_extended_get_via_value')}" class="tour-get-via-input-value" value="">
    <input type="hidden" name="{$__f->encode('order_extended_get_via_title')}" class="tour-get-via-input-title" value="">

    <div class="control-group{if $__f->e('order_type') !== null} has-error{/if} order-type-switcher">
        <label for="{$__f->encode('order_type_office')}" class="controls">
            <input type="radio" name="{$__f->encode('order_type')}" id="{$__f->encode('order_type_office')}" value="OFFICE"{if $__f->v('order_type', 'OFFICE') == 'OFFICE'} checked="checked"{/if} />
            Оплатить в офисе
        </label>

        <label for="{$__f->encode('order_type_online')}" class="controls">
            <input type="radio" name="{$__f->encode('order_type')}" id="{$__f->encode('order_type_online')}" value="ONLINE"{if $__f->v('order_type') == 'ONLINE'} checked="checked"{/if} />
            Оплатить картой on-line (+2% к стоимости тура)
        </label>

        <div class="help-block error">
            Неверное значение
        </div>
    </div>

    <div class="control-group{if $__f->e('order_customer_name') !== null} has-error{/if}">
        <label for="{$__f->encode('order_customer_name')}">
            Ваше ФИО:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('order_customer_name')}" value="{$model->getCustomerName()|escape}" id="{$__f->encode('order_customer_name')}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('order_customer_email') !== null} has-error{/if}">
        <label for="{$__f->encode('order_customer_email')}">
            Ваше E-mail:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('order_customer_email')}" value="{$model->getCustomerEmail()|escape}" id="{$__f->encode('order_customer_email')}" />

            <div class="help-block error">
                {if $__f->e('order_customer_email') == 'NOT_VALID'}Обязательное поле{else}Неверный адрес{/if}
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('order_customer_phone') !== null} has-error{/if}">
        <label for="{$__f->encode('order_customer_phone')}">
            Ваш телефон:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text input-masked-phone" name="{$__f->encode('order_customer_phone')}" value="{$model->getCustomerPhone()|escape}" id="{$__f->encode('order_customer_phone')}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('order_customer_total_adults') !== null} has-error{/if}">
        <label for="{$__f->encode('order_customer_total_adults')}">
            Кол-во взрослых:
        </label>

        <div class="controls">
            <select name="{$__f->encode('order_customer_total_adults')}" id="{$__f->encode('order_customer_total_adults')}" class="ik-select">
                {foreach $adults_vals as $adult}
                    <option value="{(int)$adult}"{if $adult == $model->getCustomerTotalAdults()} selected="selected"{/if}>{(int)$adult}</option>
                {/foreach}
            </select>

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('order_customer_total_children') !== null} has-error{/if}">
        <label for="{$__f->encode('order_customer_total_children')}">
            Кол-во детей:
        </label>

        <div class="controls">
            <select name="{$__f->encode('order_customer_total_children')}" id="{$__f->encode('order_customer_total_children')}" class="ik-select">
                {foreach $childs_vals as $child}
                    <option value="{(int)$child}"{if $child == $model->getCustomerTotalChildren()} selected="selected"{/if}>{(int)$child}</option>
                {/foreach}
            </select>

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('office_id') !== null} has-error{/if} show-only-on-office">
        <label for="{$__f->encode('office_id')}">
            Офис обслуживания:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <select name="{$__f->encode('office_id')}" id="{$__f->encode('office_id')}" class="ik-select">
                {foreach $office_list as $city_name => $offices}
                    {if count($offices) === 1}
                        {assign var="office" value=current($offices)}
                        <option value="{$office.office_id|escape}"{if $office.office_id == $model->getCustomerData('office')} selected="selected"{/if}>{$office.city_name|escape}</option>
                    {else}
                        <optgroup label="{$city_name|escape}">
                            {foreach $offices as $office}
                                <option value="{$office.office_id|escape}"{if $office.office_id == $model->getCustomerData('office')} selected="selected"{/if}>{if empty($office.office_title)}{$office.city_name|escape}{else}{$office.office_title|escape}{/if}</option>
                            {/foreach}
                        </optgroup>
                    {/if}
                {/foreach}
            </select>

            <div class="help-block error">
                Обязательное значение
            </div>
        </div>
    </div>

    <footer class="form-footer">
        <div class="form-field">
            <div class="form-button-container">
                <div class="pull-right">
                    <div class="form-remark">
                        <span class="form-asterisk">*</span> &mdash; обязательные для заполнения поля
                    </div>
                </div>

                <input type="submit" name="{$__f->encode('__send')}" class="site-btn" value="Отправить" />
            </div>
        </div>
    </footer>
</form>