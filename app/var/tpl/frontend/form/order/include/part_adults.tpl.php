{assign var="fieldname" value="{$fieldname}[{$type}]"}

<div class="control-group{if !empty($errors.name_latin)} has-error{/if}">
    <label for="{$fieldname}_name_latin_{$id}">
        Имя (латиницей):
        <span class="form-asterisk">*</span>
    </label>

    <div class="controls">
        <input type="text" class="input-text" name="{$fieldname}[{$id}][name_latin]" value="{if !empty($data.name_latin)}{$data.name_latin|escape}{/if}" id="{$fieldname}_name_latin_{$id}" />

        <div class="help-block error">
            Обязательное поле
        </div>
    </div>
</div>

<div class="control-group{if !empty($errors.surname_latin)} has-error{/if}">
    <label for="{$fieldname}_surname_latin_{$id}">
        Фамилия (латиницей):
        <span class="form-asterisk">*</span>
    </label>

    <div class="controls">
        <input type="text" class="input-text" name="{$fieldname}[{$id}][surname_latin]" value="{if !empty($data.surname_latin)}{$data.surname_latin|escape}{/if}" id="{$fieldname}_surname_latin_{$id}" />

        <div class="help-block error">
            Обязательное поле
        </div>
    </div>
</div>

<div class="control-group{if !empty($errors.birthday_day) || !empty($errors.birthday_month) || !empty($errors.birthday_year)} has-error{/if}">
    <label for="{$fieldname}_birthday_day_{$id|escape}">
        Дата рождения:
        <span class="form-asterisk">*</span>
    </label>

    <div class="controls">
        <div class="input-group">
            <input type="text" class="input-text input-text-size-smallest{if !empty($errors.birthday_day)} has-error{/if} jumpy-wompy birthday_day" name="{$fieldname}[{$id}][birthday_day]" value="{if !empty($data.birthday)}{$data.birthday->format('d')}{/if}" id="{$fieldname}_birthday_day_{$id}" placeholder="ДД" data-limit="2" data-next=".birthday_month" />
            <input type="text" class="input-text input-text-size-smallest{if !empty($errors.birthday_month)} has-error{/if} jumpy-wompy birthday_month" name="{$fieldname}[{$id}][birthday_month]" value="{if !empty($data.birthday)}{$data.birthday->format('m')}{/if}" id="{$fieldname}_birthday_month_{$id}" placeholder="ММ" data-limit="2" data-next=".birthday_year" />
            <input type="text" class="input-text input-text-size-small{if !empty($errors.birthday_year)} has-error{/if} jumpy-wompy birthday_year" name="{$fieldname}[{$id}][birthday_year]" value="{if !empty($data.birthday)}{$data.birthday->format('Y')}{/if}" id="{$fieldname}_birthday_year_{$id}" placeholder="ГГГГ" data-limit="4" data-next=".jump-citizenship" />
        </div>

        <div class="help-block error">
            {if !empty($errors.birthday_day) && $errors.birthday_day == 'INVALID_DATE'}Выбрана неверная дата ({(int)$data.birthday_day}.{(int)$data.birthday_month}.{(int)$data.birthday_year}){else}Обязательное поле{/if}
        </div>
    </div>
</div>

<div class="control-group{if !empty($errors.citizenship)} has-error{/if}">
    <label for="{$fieldname}_citizenship_{$id}">
        Гражданство:
        <span class="form-asterisk">*</span>
    </label>

    <div class="controls">
        <input type="text" class="input-text jump-citizenship" name="{$fieldname}[{$id}][citizenship]" value="{if !empty($data.citizenship)}{$data.citizenship|escape|default:'RU'}{/if}" id="{$fieldname}_citizenship_{$id}" />

        <div class="help-block error">
            Обязательное поле
        </div>
    </div>
</div>

<div class="control-group{if !empty($errors.gender)} has-error{/if}">
    <label for="{$fieldname}_gender_{$id}">
        Пол:
        <span class="form-asterisk">*</span>
    </label>

    <div class="controls">
        <select class="ik-select" name="{$fieldname}[{$id}][gender]" id="{$fieldname}_gender_{$id}">
            <option{if empty($data.gender) || $data.gender == 'MALE'} selected="selected"{/if} value="MALE">
                М
            </option>
            <option{if !empty($data.gender) && $data.gender == 'FEMALE'} selected="selected"{/if} value="FEMALE">
                Ж
            </option>
        </select>

        <div class="help-block error">
            Обязательное поле
        </div>
    </div>
</div>

<div class="control-group{if !empty($errors.passport_series)} has-error{/if}">
    <label for="{$fieldname}_passport_series_{$id}">
        Серия загранпаспорта:
        <span class="form-asterisk">*</span>
    </label>

    <div class="controls">
        <input type="text" class="input-text" name="{$fieldname}[{$id}][passport_series]" value="{if !empty($data.passport_series)}{$data.passport_series|escape}{/if}" id="{$fieldname}_passport_series_{$id}" />

        <div class="help-block error">
            Обязательное поле
        </div>
    </div>
</div>

<div class="control-group{if !empty($errors.passport_number)} has-error{/if}">
    <label for="{$fieldname}_passport_number_{$id}">
        Номер загранпаспорта:
        <span class="form-asterisk">*</span>
    </label>

    <div class="controls">
        <input type="text" class="input-text" name="{$fieldname}[{$id}][passport_number]" value="{if !empty($data.passport_number)}{$data.passport_number|escape}{/if}" id="{$fieldname}_passport_number_{$id}" />

        <div class="help-block error">
            Обязательное поле
        </div>
    </div>
</div>

<div class="control-group{if !empty($errors.passport_issue_date_day) || !empty($errors.passport_issue_date_month) || !empty($errors.passport_issue_date_year)} has-error{/if}">
    <label for="{$fieldname}_passport_issue_date_day_{$id}">
        Дата выдачи:
        <span class="form-asterisk">*</span>
    </label>

    <div class="controls">
        <div class="input-group">
            <input type="text" class="input-text input-text-size-smallest{if !empty($errors.passport_issue_date_day)} has-error{/if} jumpy-wompy passport-issue-day" name="{$fieldname}[{$id}][passport_issue_date_day]" value="{if !empty($data.passport_issue_date)}{$data.passport_issue_date->format('d')|escape}{/if}" id="{$fieldname}_passport_issue_date_day_{$id}" placeholder="ДД" data-limit="2" data-next=".passport-issue-month" />
            <input type="text" class="input-text input-text-size-smallest{if !empty($errors.passport_issue_date_month)} has-error{/if} jumpy-wompy passport-issue-month" name="{$fieldname}[{$id}][passport_issue_date_month]" value="{if !empty($data.passport_issue_date)}{$data.passport_issue_date->format('m')|escape}{/if}" id="{$fieldname}_passport_issue_date_month_{$id}" placeholder="ММ" data-limit="2" data-next=".passport-issue-year" />
            <input type="text" class="input-text input-text-size-small{if !empty($errors.passport_issue_date_year)} has-error{/if} jumpy-wompy passport-issue-year" name="{$fieldname}[{$id}][passport_issue_date_year]" value="{if !empty($data.passport_issue_date)}{$data.passport_issue_date->format('Y')|escape}{/if}" id="{$fieldname}_passport_issue_date_year_{$id}" placeholder="ГГГГ" data-limit="4" data-next=".passport-expiration-day" />
        </div>

        <div class="help-block error">
            {if !empty($errors.passport_issue_date_day) && $errors.passport_issue_date_day == 'INVALID_DATE'}Выбрана неверная дата ({(int)$data.passport_issue_date_day}.{(int)$data.passport_issue_date_month}.{(int)$data.passport_issue_date_year}){else}Обязательное поле{/if}
        </div>
    </div>
</div>

<div class="control-group{if !empty($errors.passport_expiration_date_day) || !empty($errors.passport_expiration_date_month) || !empty($errors.passport_expiration_date_year)} has-error{/if}">
    <label for="{$fieldname}_passport_expiration_date_day_{$id}">
        Годен до:
        <span class="form-asterisk">*</span>
    </label>

    <div class="controls">
        <div class="input-group">
            <input type="text" class="input-text input-text-size-smallest{if !empty($errors.passport_expiration_date_day)} has-error{/if} jumpy-wompy passport-expiration-day" name="{$fieldname}[{$id}][passport_expiration_date_day]" value="{if !empty($data.passport_expiration_date)}{$data.passport_expiration_date->format('d')|escape}{/if}" id="{$fieldname}_passport_expiration_date_day_{$id} passport-expiration-day" placeholder="ДД" data-limit="2" data-next=".passport-expiration-month" />
            <input type="text" class="input-text input-text-size-smallest{if !empty($errors.passport_expiration_date_month)} has-error{/if} jumpy-wompy passport-expiration-month" name="{$fieldname}[{$id}][passport_expiration_date_month]" value="{if !empty($data.passport_expiration_date)}{$data.passport_expiration_date->format('m')|escape}{/if}" id="{$fieldname}_passport_expiration_date_month_{$id} passport-expiration-month" placeholder="ММ" data-limit="2" data-next=".passport-expiration-year" />
            <input type="text" class="input-text input-text-size-small{if !empty($errors.passport_expiration_date_year)} has-error{/if} jumpy-wompy passport-expiration-year" name="{$fieldname}[{$id}][passport_expiration_date_year]" value="{if !empty($data.passport_expiration_date)}{$data.passport_expiration_date->format('Y')|escape}{/if}" id="{$fieldname}_passport_expiration_date_year_{$id} passport-expiration-year" placeholder="ГГГГ" data-limit="4" data-next=".jump-passport-issuer" />
        </div>

        <div class="help-block error">
            {if !empty($errors.passport_expiration_date_day) && $errors.passport_expiration_date_day == 'INVALID_DATE'}Выбрана неверная дата ({(int)$data.passport_expiration_date_day}.{(int)$data.passport_expiration_date_month}.{(int)$data.passport_expiration_date_year}){else}Обязательное поле{/if}
        </div>
    </div>
</div>

<div class="control-group{if !empty($errors.passport_issuer)} has-error{/if}">
    <label for="{$fieldname}_passport_issuer_{$id}">
        Кем выдан:
        <span class="form-asterisk">*</span>
    </label>

    <div class="controls">
        <input type="text" class="input-text jump-passport-issuer" name="{$fieldname}[{$id}][passport_issuer]" value="{if !empty($data.passport_issuer)}{$data.passport_issuer|escape}{/if}" id="{$fieldname}_passport_issuer_{$id}" />

        <div class="help-block error">
            Обязательное поле
        </div>
    </div>
</div>