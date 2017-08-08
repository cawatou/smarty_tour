{$__ctx->addJs('frontend/form-request.js')}

{assign var="model" value=$__f->getModel()}

<form action="{$__f->getUrl()}" method="POST" class="form" id="form-request">
    <div class="control-group{if $__f->e('office_id') !== null} has-error{/if}">
        <label for="request_extended_office">Офис обслуживания: <span class="form-asterisk">*</span></label>

        <div class="controls relative-controls">
            <select name="{$__f->encode('office_id')}" id="request_extended_office" data-ddcustomclass="selectbox-dropdown-form" data-autoWidth="false">
                {foreach $office_list as $city_name => $offices}
                    {if count($offices) === 1}
                        {assign var="office" value=current($offices)}
                        <option value="{$office.office_id|escape}"{if $office.office_id == $model->getOfficeId()} selected="selected"{/if}>{$office.city_name|escape}</option>
                    {else}
                        <optgroup label="{$city_name|escape}">
                            {foreach $offices as $office}
                                <option value="{$office.office_id|escape}"{if $office.office_id == $model->getOfficeId()} selected="selected"{/if}>{if empty($office.office_title)}{$office.city_name|escape}{else}{$office.office_title|escape}{/if}</option>
                            {/foreach}
                        </optgroup>
                    {/if}
                {/foreach}

                <option value="other"{if $model->getExtendedData('office_other')} selected="selected"{/if}>Другой город</option>
            </select>

            <input type="text" class="input-text request-other-city" id="request_extended_office_other" placeholder="Название города" name="{$__f->encode('request_extended_office_other')}" value="{$model->getExtendedData('office_other')|escape}"{if !$model->getExtendedData('office_other')} style="display: none;"{/if} />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('request_user_name') !== null} has-error{/if}">
        <label for="{$__f->encode('request_user_name')}">Ваше имя <span class="form-asterisk">*</span></label>

        <div class="controls">
            <input type="text" class="input-text" id="{$__f->encode('request_user_name')}" name="{$__f->encode('request_user_name')}" value="{$model->getUserName()|escape}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('request_user_email') !== null} has-error{/if}">
        <label for="{$__f->encode('request_user_email')}">Ваш email <span class="form-asterisk">*</span></label>

        <div class="controls">
            <input type="text" class="input-text" id="{$__f->encode('request_user_email')}" name="{$__f->encode('request_user_email')}" value="{$model->getUserEmail()|escape}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('request_user_phone') !== null} has-error{/if}">
        <label for="request_form_user_phone">Ваш телефон <span class="form-asterisk">*</span></label>

        <div class="controls">
            <input type="text" class="input-text" id="request_form_user_phone" name="{$__f->encode('request_user_phone')}" value="{if !$model->getUserPhone()}7{/if}{$model->getUserPhone()|escape}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('request_extended_country') !== null} has-error{/if}">
        <label for="{$__f->encode('request_extended_country')}">Страна, курорт <span class="form-asterisk">*</span></label>

        <div class="controls">
            <input type="text" class="input-text" id="{$__f->encode('request_extended_country')}" name="{$__f->encode('request_extended_country')}" value="{$model->getExtendedData('country')|escape}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('request_extended_flyaway') !== null} has-error{/if}">
        <label for="{$__f->encode('request_extended_flyaway')}">Вылет из</label>

        <div class="controls">
            <input type="text" class="input-text" id="{$__f->encode('request_extended_flyaway')}" name="{$__f->encode('request_extended_flyaway')}" value="{$model->getExtendedData('flyaway')|escape}" />

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('request_extended_date_begin') !== null} has-error{/if}">
        <label for="request_form_extended_date_begin">Дата вылета <span class="form-asterisk">*</span></label>

        <div class="controls">
            <span class="prefix">с</span>
            <input type="text" class="input-text has-datepicker" id="request_form_extended_date_start" name="{$__f->encode('request_extended_date_begin')}" value="{$model->getExtendedData('date_begin')|escape}" />
            <span>по</span>
            <input type="text" class="input-text has-datepicker" id="request_form_extended_date_end" name="{$__f->encode('request_extended_date_end')}" value="{$model->getExtendedData('date_end')|escape}" />

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('request_extended_daynum') !== null} has-error{/if}">
        <label for="{$__f->encode('request_extended_daynum')}">Срок поездки, в днях</label>

        <div class="controls">
            <input type="text" class="input-text" id="{$__f->encode('request_extended_daynum')}" name="{$__f->encode('request_extended_daynum')}" value="{$model->getExtendedData('daynum')|escape}" />

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('request_extended_adults') !== null} has-error{/if}">
        <label for="{$__f->encode('request_extended_adults')}">Кол-во взрослых <span class="form-asterisk">*</span></label>

        <div class="controls">
            <input type="text" class="input-text" id="{$__f->encode('request_extended_adults')}" name="{$__f->encode('request_extended_adults')}" value="{$model->getExtendedData('adults')|escape}" />

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('request_extended_children') !== null} has-error{/if}">
        <label for="{$__f->encode('request_extended_children')}">Кол-во детей</label>

        <div class="controls">
            <input type="text" class="input-text input-text-short" id="{$__f->encode('request_extended_children')}" name="{$__f->encode('request_extended_children')}" value="{$model->getExtendedData('children')|escape}" />

            <span>Возраст от</span>
            <input type="text" class="input-text input-text-short" id="{$__f->encode('request_extended_children_age_from')}" name="{$__f->encode('request_extended_children_age_from')}" value="{$model->getExtendedData('children_age_from')|escape}" />
            <span>до</span>
            <input type="text" class="input-text input-text-short" id="{$__f->encode('request_extended_children_age_to')}" name="{$__f->encode('request_extended_children_age_to')}" value="{$model->getExtendedData('children_age_to')|escape}" />

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('request_extended_hotel_stars') !== null} has-error{/if}">
        <label for="{$__f->encode('request_extended_hotel_stars')}">Тип отеля</label>

        <div class="controls">
            <select class="ik-select" id="{$__f->encode('request_extended_hotel_stars')}" name="{$__f->encode('request_extended_hotel_stars')}">
                <option>Не важно</option>

                {foreach $hotel_stars as $star_id => $star}
                    <option value="{$star|escape}"{if $model->getExtendedData('hotel_stars') == $star} selected="selected"{/if}>{$star|escape}</option>
                {/foreach}
            </select>

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('request_extended_price') !== null} has-error{/if}">
        <label for="{$__f->encode('request_extended_price')}">Максимальный бюджет <span class="form-asterisk">*</span></label>

        <div class="controls">
            <input type="text" class="input-text" id="{$__f->encode('request_extended_price')}" name="{$__f->encode('request_extended_price')}" value="{$model->getExtendedData('price')|escape}" />

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('request_message') !== null} has-error{/if}">
        <label for="{$__f->encode('request_message')}">Дополнительная информация</label>

        <div class="controls">
            <textarea name="{$__f->encode('request_message')}" id="{$__f->encode('request_message')}">{$model->getMessage()|escape}</textarea>

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    {if $model->getType() === 'REQUEST'}
        <div class="control-group{if $__f->e('request_extended_spam_email') !== null} has-error{/if}">
            <label for="{$__f->encode('request_extended_spam_email')}" class="controls">
                <input type="checkbox" name="{$__f->encode('request_extended_spam_email')}" id="{$__f->encode('request_extended_spam_email')}" value="1"{if $model->getExtendedData('spam_email')} checked="checked"{/if} />
                Я согласен получать рассылку горящих туров на свой e-mail
            </label>

            <div class="help-block error">
                Неверное значение
            </div>
        </div>

        <div class="control-group{if $__f->e('request_extended_spam_sms') !== null} has-error{/if}">
            <label for="{$__f->encode('request_extended_spam_sms')}" class="controls">
                <input type="checkbox" name="{$__f->encode('request_extended_spam_sms')}" id="{$__f->encode('request_extended_spam_sms')}" value="1"{if $model->getExtendedData('spam_sms')} checked="checked"{/if} />
                Я согласен получать смс-рассылку горящих туров
            </label>

            <div class="help-block error">
                Неверное значение
            </div>
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

                <input type="submit" name="{$__f->encode('__send')}" class="site-btn" value="Отправить" />
            </div>
        </div>
    </footer>
</form>