{$__ctx->addJs('/frontend/form-faq.js')}

{assign var="model" value=$__f->getModel()}

<form class="form form-feedback" action="{$__f->getUrl()}" method="post">
    <div class="control-group{if $__f->e('faq_user_name') !== null} has-error{/if}">
        <label for="{$__f->encode('faq_user_name')}">
            Ваше имя:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('faq_user_name')}" value="{$model->getUserName()|escape}" id="{$__f->encode('faq_user_name')}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('faq_user_email') !== null} has-error{/if}">
        <label for="{$__f->encode('faq_user_email')}">
            Ваше E-mail:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('faq_user_email')}" value="{$model->getUserEmail()|escape}" id="{$__f->encode('faq_user_email')}" />

            <div class="help-block error">
                {if $__f->e('faq_user_email') == 'NOT_VALID'}Обязательное поле{else}Неверный адрес{/if}
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('faq_user_phone') !== null} has-error{/if}">
        <label for="{$__f->encode('faq_user_phone')}">
            Ваш телефон:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text input-masked-phone" name="{$__f->encode('faq_user_phone')}" value="{$model->getUserPhone()|escape}" id="{$__f->encode('faq_user_phone')}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('office_id') !== null} has-error{/if}">
        <label for="{$__f->encode('office_id')}">
            Офис обслуживания:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <select name="{$__f->encode('office_id')}" id="{$__f->encode('office_id')}" class="ik-select ik-select-office-id">
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
            </select>

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('faq_message') !== null} has-error{/if}">
        <label for="{$__f->encode('faq_message')}">
            Ваш вопрос:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <textarea name="{$__f->encode('faq_message')}" id="{$__f->encode('faq_message')}" class="textarea-vertical">{$model->getMessage()|escape}</textarea>

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group control-group-captcha{if $__f->e('captcha') !== null} has-error{/if}">
        <label for="{$__f->encode('captcha')}">
            Введите цифры с картинки:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <a href="#" class="captcha-reload">
                <img src="{$__url->url('/faq/captcha')}" alt="Код" title="Кликните, чтобы обновить картинку" />
            </a>

            <input type="text" name="{$__f->encode('captcha')}" value="" id="{$__f->encode('captcha')}" class="input-text" />
            <div class="help-block error">Неверный код</div>
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

                <input type="submit" name="{$__f->encode('__send')}" class="site-btn" value="Задать вопрос" />
            </div>
        </div>
    </footer>
</form>