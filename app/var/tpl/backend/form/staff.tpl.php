{assign var="model" value=$__f->getModel()}

{$__ctx->addJs('/backend/tiny_mce/tiny_mce.js')}
{$__ctx->addJs('/backend/tiny_mce.js')}

{$__ctx->addCss('/backend/files_popup.css')}
{$__ctx->addJs('/backend/files_popup.js')}

{$__ctx->addJs('/backend/form/staff.js')}

{literal}
<script type="text/javascript">
    var
        TINY_FILEMANAGER_PATH = '{/literal}{$__url->adm('.files-mce')}{literal}',
        TINY_REDACTOR_ID = '{/literal}{$__f->encode('staff_description')}{literal}';
</script>
{/literal}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Данные о сотруднике изменены'|t}
    </div>
{/if}

{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Произошла ошибка, проверьте правильность заполнения полей'|t}
    </div>
{/if}

<form role="form" method="post" action="{$__f->getUrl()}">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('staff_name') !== null} has-error{/if}">
                <label for="{$__f->encode('staff_name')}">{'Имя'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('staff_name')}" value="{$model->getName()|escape}" id="{$__f->encode('staff_name')}">
                <span class="help-block help-block-error">{if $__f->e('staff_name') == 'ALREADY_EXISTS'}Такой сотрудник уже существует{else}{'Обязательное поле'|t}{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('office_id') !== null} has-error{/if}">
                <label for="{$__f->encode('office_id')}">{'Офис'|t} <i class="fa fa-check"></i></label>

                <select name="{$__f->encode('office_id')}" id="{$__f->encode('office_id')}" class="form-control">
                    <option value="">Выберите офис</option>

                    {foreach $offices_array as $city => $offices}
                        <optgroup label="{$city}">
                            {foreach $offices as $office}
                                <option value="{$office->getId()|escape}"{if $office->getId() == $model->getOfficeId()} selected="selected"{/if}{if $office->getStatus() != 'ENABLED'} style="color: #ccc;"{/if}>
                                    {$office->getTitle()|escape}
                                </option>
                            {/foreach}
                        </optgroup>
                    {/foreach}
                </select>

                <span class="help-block">
                    <a href="{$__url->adm('.office.add')}">Добавить офис?</a>
                </span>

                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('staff_position') !== null} has-error{/if}">
                <label for="{$__f->encode('staff_position')}">{'Должность'|t}</label>
                <input type="text" class="form-control" name="{$__f->encode('staff_position')}" value="{$model->getPosition()|escape}" id="{$__f->encode('staff_position')}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('staff_email') !== null} has-error{/if}">
                <label for="{$__f->encode('staff_email')}">Email</label>
                <input type="text" class="form-control" name="{$__f->encode('staff_email')}" value="{$model->getEmail()|escape}" id="{$__f->encode('staff_email')}">
                <span class="help-block help-block-error">{if $__f->e('staff_email') == 'INVALID_FORMAT'}Не верный формат{else}{'Обязательное поле'|t}{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('staff_phone') !== null} has-error{/if}">
                <label for="{$__f->encode('staff_phone')}">{'Телефон'|t}</label>
                <input type="text" class="form-control" name="{$__f->encode('staff_phone')}" value="{$model->getPhone()|escape}" id="{$__f->encode('staff_phone')}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('staff_skype') !== null} has-error{/if}">
                <label for="{$__f->encode('staff_skype')}">Skype</label>
                <input type="text" class="form-control" name="{$__f->encode('staff_skype')}" value="{$model->getSkype()|escape}" id="{$__f->encode('staff_skype')}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('staff_icq') !== null} has-error{/if}">
                <label for="{$__f->encode('staff_skype')}">ICQ</label>
                <input type="text" class="form-control" name="{$__f->encode('staff_icq')}" value="{$model->getIcq()|escape}" id="{$__f->encode('staff_icq')}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group {if $__f->e('staff_photo') !== null} has-error{/if}">
                <label for="{$__f->encode('staff_photo')}">{'Фото'|t}</label>
                <div class="input-group">
                {if null !== $model->getPhoto() && null === $__f->e('staff_photo')}
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-preview" data-image-path="{$__url->thumb($model->getPhoto(), 150, 150)}"><i class="fa fa-eye"></i></button>
                    </span>
                {/if}
                    <input type="text" class="form-control" name="{$__f->encode('staff_photo')}" value="{$model->getPhoto()|escape}" id="{$__f->encode('staff_photo')}">
                        <span class="input-group-btn">
                            <button class="btn btn-default" onclick="return dialog.show('{$__url->adm('.files-dialog', '?history')}', '{$__f->encode('staff_photo')}');"><i class="fa fa-search"></i> {'Обзор'|t}</button>
                        </span>
                </div>
                <span class="help-block help-block-error">{if $__f->e('staff_photo') == 'IMAGE_UNSUPPORTED'}Этот формат изображений не поддерживается (используйте GIF, PNG, JPG){else}Файл не существует или не является картинкой ({$__f->e('staff_photo')}){/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group{if $__f->e('staff_description') !== null} has-error{/if}">
                <label for="{$__f->encode('staff_description')}">{'Описание'|t}</label>
                <textarea name="{$__f->encode('staff_description')}" id="{$__f->encode('staff_description')}" class="form-control form-textarea-vertical">{$model->getDescription()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="{$__f->encode('staff_is_highlight')}" value="1"{if $model->getIsHighlight()} checked{/if}> Выделить / подсветить
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">{'Статус'|t}</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('staff_status')}" value="ENABLED"{if $model->getStatus() == 'ENABLED'} checked{/if}> {'Показывать'|t}
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('staff_status')}" value="DISABLED"{if $model->getStatus() == 'DISABLED'} checked{/if}> {'Скрывать'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {if $__f->getId() === 'staff_edit'}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
                {else}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить сотрудника'|t}</button>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.staff')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>