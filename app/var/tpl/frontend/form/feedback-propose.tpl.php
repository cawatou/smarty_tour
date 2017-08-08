{$__ctx->addJs('/frontend/form-feedback-propose.js')}

{assign var="model" value=$__f->getModel()}

<form class="form form-feedback form-feedback-propose" action="{$__f->getUrl()}" method="post">
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

    <div class="control-group{if $__f->e('feedback_user_email') !== null} has-error{/if}">
        <label for="{$__f->encode('feedback_user_email')}">
            Ваш email:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('feedback_user_email')}" value="{$model->getUserEmail()|escape}" id="{$__f->encode('feedback_user_email')}" />

            <div class="help-block error">
                {if $__f->e('feedback_user_email') == 'INVALID_FORMAT'}Некорректный адрес{else}Обязательное поле{/if}
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('feedback_user_phone') !== null} has-error{/if}">
        <label for="{$__f->encode('feedback_user_phone')}">
            Ваш телефон:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('feedback_user_phone')}" value="{$model->getUserPhone()|escape}" id="{$__f->encode('feedback_user_phone')}" />

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

    <div class="control-group{if $__f->e('feedback_extended_staff_id') !== null} has-error{/if}">
        <label for="{$__f->encode('feedback_extended_staff_id')}">
            Выберите менеджера:
        </label>

        <div class="controls">
            <select name="{$__f->encode('feedback_extended_staff_id')}" class="ik-select ik-select-staff-id" data-value="{$model->getExtendedData('staff_id')|default:''}">
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