{if $__f->successful}
    <div class="form-successful"><strong>Спасибо,</strong> мы перезвоним вам в самое ближайшее время!</div>
{else}
    {assign var="model" value=$__f->getModel()}

    <form class="form form-request modal-request-callback-form" action="{$__f->getUrl()}" method="post">
        <div class="control-group{if $__f->e('request_user_name') !== null} has-error{/if}">
            <label for="{$__f->encode('request_user_name')}">
                Ваше имя:
                <span class="form-asterisk">*</span>
            </label>

            <div class="controls">
                <input type="text" class="input-text" name="{$__f->encode('request_user_name')}" value="{$model->getUserName()|escape}" id="{$__f->encode('request_user_name')}" />

                <div class="help-block error">
                    Обязательное поле
                </div>
            </div>
        </div>

        <div class="control-group{if $__f->e('request_user_phone') !== null} has-error{/if}">
            <label for="{$__f->encode('request_user_phone')}">
                Ваш телефон:
                <span class="form-asterisk">*</span>
            </label>

            <div class="controls">
                <input type="text" class="input-text input-text-phone-number" name="{$__f->encode('request_user_phone')}" value="{$model->getUserPhone()|escape}" id="{$__f->encode('request_user_phone')}" />

                <div class="help-block error">
                    Обязательное поле
                </div>
            </div>
        </div>

        <div class="control-group{if $__f->e('office_id') !== null} has-error{/if}">
            <label for="request_extended_office">
                Офис обслуживания: <span class="form-asterisk">*</span>
            </label>

            <div class="controls relative-controls">
                <select name="{$__f->encode('office_id')}" id="request_extended_office" data-ddcustomclass="selectbox-dropdown-form" data-autoWidth="false">
                    {foreach $office_list as $city_name => $offices}
                        {if count($offices) === 1}
                            {assign var="office" value=current($offices)}
                            <option value="{$office.office_id|escape}"{if $office.office_id == $model->getExtendedData('office')} selected="selected"{/if}>{$office.city_name|escape}</option>
                        {else}
                            <optgroup label="{$city_name|escape}">
                                {foreach $offices as $office}
                                    <option value="{$office.office_id|escape}"{if $office.office_id == $model->getExtendedData('office')} selected="selected"{/if}>{if empty($office.office_title)}{$office.city_name|escape}{else}{$office.office_title|escape}{/if}</option>
                                {/foreach}
                            </optgroup>
                        {/if}
                    {/foreach}

                    <option value="other"{if $model->getExtendedData('office_other')} selected="selected"{/if}>Другой город</option>
                </select>

                <input type="text" class="input-text request-other-city" id="request_extended_office_other" placeholder="Название города" name="{$__f->encode('request_extended_office_other')}" value="{$model->getExtendedData('office_other')|escape}" style="width: 45%;{if !$model->getExtendedData('office_other')} display: none;{/if}" />

                <div class="help-block error">
                    Обязательное поле
                </div>
            </div>
        </div>

        <div class="control-group control-group-captcha{if $__f->e('captcha') !== null} has-error{/if}">
            <label for="{$__f->encode('captcha')}">
                Введите символы с картинки:
                <span class="form-asterisk">*</span>
            </label>

            <div class="controls">
                <a href="#" class="captcha-reload">
                    <img src="{$__url->url('/callback/captcha')}" alt="Код" title="Кликните, чтобы обновить картинку" />
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

                    <input type="hidden" name="{$__f->encode('__send')}" value="1" />

                    <input type="submit" class="site-btn" value="Оставить заявку" />
                </div>
            </div>
        </footer>
    </form>
{/if}