{$__ctx->addJs('frontend/form-companion.js')}

{assign var="model" value=$__f->m()}

<form class="form form-companion" action="{$__f->getUrl()}" method="post" enctype="multipart/form-data">
    <div class="control-group{if $__f->e('companion_user_name') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_user_name')}">
            Ваше имя:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('companion_user_name')}" value="{$model->getUserName()|escape}" id="{$__f->encode('companion_user_name')}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_user_email') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_user_email')}">
            Ваш e-mail:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('companion_user_email')}" value="{$model->getUserEmail()|escape}" id="{$__f->encode('companion_user_email')}" />

            <div class="help-block error">
                {if $__f->e('feedback_user_email') === 'NOT_VALID'}Обязательное поле{else}Неверный адрес{/if}
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_user_phone') !== null} has-error{/if}">
        <label for="form_companion_user_phone">
            Ваш телефон:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('companion_user_phone')}" value="{$model->getUserPhone()|escape}" id="form_companion_user_phone" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_user_city') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_user_city')}">
            Ваш город:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('companion_user_city')}" value="{$model->getUserCity()|escape}" id="{$__f->encode('companion_user_city')}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_user_age') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_user_age')}">
            Ваш возраст:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('companion_user_age')}" value="{$model->getUserAge()|escape}" id="{$__f->encode('companion_user_age')}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_location') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_location')}">
            Страна:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('companion_location')}" value="{$model->getLocation()|escape}" id="{$__f->encode('companion_location')}" />

            <div class="help-block help">
                Место планируемого отдыха
            </div>

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_date_from') !== null || $__f->e('companion_date_to') !== null} has-error{/if}">
        <label for="form_companion_date_from">
            Дата вылета:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <span class="prefix">с</span>
            <input type="text" class="input-text has-datepicker" id="form_companion_date_from" name="{$__f->encode('companion_date_from')}" value="{if $model->getDateFrom() !== null}{$model->getDateFrom()->setDefaultTimeZone()->format('d.m.Y')|escape}{/if}" />
            <span>по</span>
            <input type="text" class="input-text has-datepicker" id="form_companion_date_to" name="{$__f->encode('companion_date_to')}" value="{if $model->getDateTo() !== null}{$model->getDateTo()->setDefaultTimeZone()->format('d.m.Y')|escape}{/if}" />

            <div class="help-block error">
                {if $__f->e('companion_date_from') === 'NOT_VALID'}Обязательное поле{else}Должно быть меньше значения "Вылет по"{/if}
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_user_gender') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_user_gender')}">
            Ваш пол:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <select name="{$__f->encode('companion_user_gender')}" class="ik-select" id="{$__f->encode('companion_user_gender')}">
                {foreach $user_genders as $gender => $gender_name}
                    <option value="{$gender|escape}"{if $model->getUserGender() === $gender} selected="selected"{/if}>{$gender_name|escape}</option>
                {/foreach}
            </select>

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_target_gender') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_user_gender')}">
            Ищу:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <select name="{$__f->encode('companion_target_gender')}" class="ik-select" id="{$__f->encode('companion_target_gender')}">
                {foreach $target_genders as $gender => $gender_name}
                    <option value="{$gender|escape}"{if $model->getTargetGender() === $gender} selected="selected"{/if}>{$gender_name|escape}</option>
                {/foreach}
            </select>

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_price') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_price')}">
            Макс. бюджет:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('companion_price')}" value="{$model->getPrice()|escape}" id="{$__f->encode('companion_price')}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_daynum_from') !== null || $__f->e('companion_daynum_to') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_daynum_from')}">
            Кол-во дней:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <span class="prefix">от</span>
            <input type="text" class="input-text input-text-shorty" id="{$__f->encode('companion_daynum_from')}" name="{$__f->encode('companion_daynum_from')}" value="{$model->getDaynumFrom()|escape}" />
            <span class="between">до</span>
            <input type="text" class="input-text input-text-shorty" id="{$__f->encode('companion_daynum_to')}" name="{$__f->encode('companion_daynum_to')}" value="{$model->getDaynumTo()|escape}" />

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_notes') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_notes')}">
            Доп. информация:
        </label>

        <div class="controls">
            <textarea name="{$__f->encode('companion_notes')}" id="{$__f->encode('companion_notes')}" class="textarea-vertical">{$model->getNotes()|escape}</textarea>

            <div class="help-block error">
                Неверное значение
            </div>
        </div>
    </div>

    <div class="control-group control-group-file{if $__f->e('companion_user_photo') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_user_photo')}">
            Фотография:
        </label>

        <div class="controls">
            {if $model->getUserPhoto()}
                <div class="control-photo">
                    <img src="{$__url->thumb($model->getUserPhoto(), 100, 100)}" alt="Загруженная фотография" />

                    <input type="hidden" name="{$__f->encode('companion_user_photo_uploaded')|escape}" value="{$model->getUserPhoto()|escape}" />
                </div>
            {/if}

            <input type="file" class="input-file" name="companion_user_photo" value="" id="{$__f->encode('companion_user_photo')}" />

            <div class="help-block error">
                {if $__f->e('companion_user_photo') == 'INVALID_FORMAT'}Файл не является изображением{else}Неверное значение{/if}
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_extended_is_signup_email') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_extended_is_signup_email')}" class="controls">
            <input type="checkbox" name="{$__f->encode('companion_extended_is_signup_email')}" id="{$__f->encode('companion_extended_is_signup_email')}" value="1"{if $model->getExtendedData('is_signup_email')} checked="checked"{/if} />
            Я согласен получать рассылку горящих туров на свой e-mail
        </label>

        <div class="help-block error">
            Неверное значение
        </div>
    </div>

    <div class="control-group{if $__f->e('companion_extended_is_signup_sms') !== null} has-error{/if}">
        <label for="{$__f->encode('companion_extended_is_signup_sms')}" class="controls">
            <input type="checkbox" name="{$__f->encode('companion_extended_is_signup_sms')}" id="{$__f->encode('companion_extended_is_signup_sms')}" value="1"{if $model->getExtendedData('is_signup_sms')} checked="checked"{/if} />
            Я согласен получать смс-рассылку горящих туров
        </label>

        <div class="help-block error">
            Неверное значение
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