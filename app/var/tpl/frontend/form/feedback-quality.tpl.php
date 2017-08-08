{if $__f->successful}
    <div class="form-successful"><strong>Спасибо,</strong> ваше обращение будет рассмотренно в самое ближайшее время!</div>
{else}
    {assign var="model" value=$__f->getModel()}

    <form class="form form-feedback modal-feedback-quality-form" action="{$__f->getUrl()}" method="post">
        <div class="control-group{if $__f->e('feedback_user_name') !== null} has-error{/if}">
            <label for="{$__f->encode('feedback_user_name')}">
                Ваше имя:
                <span class="form-asterisk">*</span>
            </label>

            <div class="controls">
                <input type="text" class="input-text" name="{$__f->encode('feedback_user_name')}" value="{$model->getUserName()|escape}" id="{$__f->encode('feedback_user_name')}" />

                <div class="help-block error">
                    Обязательное поле
                </div>
            </div>
        </div>

        <div class="control-group{if $__f->e('feedback_user_phone') !== null} has-error{/if}">
            <label for="{$__f->encode('feedback_user_phone')}">
                Ваш телефон:
                <span class="form-asterisk">*</span>
            </label>

            <div class="controls">
                <input type="text" class="input-text input-text-phone-number" name="{$__f->encode('feedback_user_phone')}" value="{$model->getUserPhone()|escape}" id="{$__f->encode('feedback_user_phone')}" />

                <div class="help-block error">
                    Обязательное поле
                </div>
            </div>
        </div>

        <div class="control-group{if $__f->e('feedback_extended_complain_type') !== null} has-error{/if}">
            <label for="{$__f->encode('feedback_extended_complain_type')}">
                Цель:
                <span class="form-asterisk">*</span>
            </label>

            <div class="controls">
                <select name="{$__f->encode('feedback_extended_complain_type')}" class="ik-select">
                    <option value="">Выберите цель</option>

                    {foreach $complain_types as $id => $name}
                        <option value="{$id|escape}"{if $model->getExtendedData('complain_type') == $id} selected="selected"{/if}>{$name|escape}</option>
                    {/foreach}
                </select>

                <div class="help-block error">
                    Неверное значение
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

        <div class="control-group{if $__f->e('feedback_extended_staff_id') !== null} has-error{/if}">
            <label for="{$__f->encode('feedback_extended_staff_id')}">
                Выберите менеджера:
            </label>

            <div class="controls">
                <select name="{$__f->encode('feedback_extended_staff_id')}" class="ik-select ik-select-staff-id">
                    <option value="">Выберите менеджера</option>

                    {foreach $office_staffs as $city_id => $office_staff}
                        {foreach $office_staff as $office_name => $staffs}
                            {if $model->getOffice() === null || $model->getOffice()->getTitle() != $office_name}{continue}{/if}

                            <optgroup label="{$office_name|escape}">
                                {foreach $staffs as $staff_id => $staff_name}
                                    <option value="{$staff_id|escape}"{if $model->getExtendedData('staff_id') == $staff_id} selected="selected"{/if}>{$staff_name|escape}</option>
                                {/foreach}
                            </optgroup>
                        {/foreach}
                    {/foreach}
                </select>

                <div class="help-block error">
                    Неверное значение
                </div>
            </div>
        </div>

        <div class="control-group{if $__f->e('feedback_message') !== null} has-error{/if}">
            <label for="{$__f->encode('feedback_message')}">
                Ваш отзыв/предложение:
                <span class="form-asterisk">*</span>
            </label>

            <div class="controls">
                <textarea name="{$__f->encode('feedback_message')}" id="{$__f->encode('feedback_message')}" class="textarea-vertical">{$model->getMessage()|escape}</textarea>

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
                    <img src="{$__url->url('/feedback/captcha')}" alt="Код" title="Кликните, чтобы обновить картинку" />
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

                    <input type="submit" class="site-btn" value="Отправить отзыв" />
                </div>
            </div>
        </footer>
    </form>
{/if}