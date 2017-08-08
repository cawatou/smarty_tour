{assign var="model" value=$__f->getModel()}

{$__ctx->addJs('/backend/jquery.show-password.js')}
{$__ctx->addJs('/backend/form/user.js')}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

        Данные о пользователе изменены
    </div>
{/if}

{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

        Произошла ошибка, проверьте правильность заполнения полей
    </div>
{/if}

<form role="form" method="post" action="{$__f->getUrl()}" class="module-form">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('user_login'))} has-error{/if}">
                <label for="{$__f->encode('user_login')}">Email <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('user_login')}" value="{$model->getLogin()|escape}" id="{$__f->encode('user_login')}"{if $__f->getId() == 'user_edit'} disabled{/if}>
                <span class="help-block help-block-error">{if $__f->e('user_login') == 'LOGIN_ALREADY_EXISTS'}Такой email уже используется{elseif $__f->e('user_login') == 'INVALID_FORMAT'}Неверный формат, такой адрес не может существовать{else}Обязательное поле{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('user_name'))} has-error{/if}">
                <label for="{$__f->encode('user_name')}">Имя Фамилия <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('user_name')}" value="{$model->getName()|escape}" id="{$__f->encode('user_name')}">
                <span class="help-block help-block-error">Обязательное поле</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-password-container{if $__f->getId() == 'user_add'} form-group-required{/if}{if !is_null($__f->e('user_password'))} has-error{/if}">
                <label for="{$__f->encode('user_password')}">Пароль{if $__f->getId() == 'user_add'} <i class="fa fa-check"></i>{/if}</label>
                <input type="password" class="form-control" name="{$__f->encode('user_password')}" value="" id="{$__f->encode('user_password')}" autocomplete="off">
                <span class="help-block help-block-error">Пароль должен быть не менее 6ти символов</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('user_role'))} has-error{/if}">
                <label for="{$__f->encode('user_role')}">Роль</label>

                <select name="{$__f->encode('user_role')}" id="{$__f->encode('user_role')}" class="form-control form-control-user-role">
                    {foreach DomainObjectModel_User::getRolesList() as $role => $role_title}
                        {if !$__ctx->getCurrentUser()->isDeveloper() && $role == 'DEVELOPER'}{continue}{/if}

                        {if !$__ctx->getCurrentUser()->isUserInRoles(array('DEVELOPER', 'ADMIN')) && ($role == 'ADMIN')}{continue}{/if}

                        <option value="{$role|escape}"{if $role == $model->getRole()} selected{/if}>{$role_title|t|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">Обязательное поле</span>
            </div>
        </div>
    </div>

    <div class="cms-group cms-group-expanded row-wrappers row-wrapper-operator row-wrapper-director">
        <div class="cms-group-label">Доступные города отправления</div>

        {if !empty($departures)}
            {foreach $departures as $departure}
                <div class="row row-attr">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" value="{$departure.departure_title|escape}" placeholder="Город вылета" readonly="readonly" />
                            <input type="hidden" name="{$__f->encode('user_froms')}[{$departure@iteration}][departure_id]" value="{$departure.departure_id|escape}" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="{$__f->encode('user_froms')}[{$departure@iteration}][is_shown]" value="1"{if !empty($departure.is_shown)} checked="checked"{/if} />

                                Разрешить
                            </label>
                        </div>
                    </div>
                </div>
            {/foreach}
        {/if}
    </div>

    {if $__ctx->getCurrentUser()->getRole() == 'DIRECTOR'}
        <input type="hidden" name="{$__f->encode('subdivision_id')}" value="{$__ctx->getCurrentUser()->getSubdivisionId()}">
    {else}
        <div class="row row-wrappers row-wrapper-director">
            <div class="col-md-4">
                <div class="form-group form-group-required{if $__f->e('subdivision_id') !== null} has-error{/if}">
                    <label for="{$__f->encode('subdivision_id')}">Подразделение <i class="fa fa-check"></i></label>

                    <select name="{$__f->encode('subdivision_id')}" id="{$__f->encode('subdivision_id')}" class="form-control">
                        <option value="">Выберите подразделение</option>

                        {foreach $subdivisions as $subdivision}
                            <option value="{$subdivision->getId()|escape}"{if $subdivision->getId() == $model->getSubdivisionId()} selected{/if}{if $subdivision->getStatus() != 'ENABLED'} style="color: #ccc;"{/if}>
                                {$subdivision->getTitle()|escape}
                            </option>
                        {/foreach}
                    </select>

                    <span class="help-block">
                        <a href="{$__url->adm('.subdivision.add')}">Добавить подразделение?</a>
                    </span>

                    <span class="help-block help-block-error">Обязательное поле</span>
                </div>
            </div>
        </div>
    {/if}

    <div class="row row-wrappers row-wrapper-operator row-wrapper-seller">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('office_id') !== null} has-error{/if}">
                <label for="{$__f->encode('office_id')}">Офис <i class="fa fa-check"></i></label>

                <select name="{$__f->encode('office_id')}" id="{$__f->encode('office_id')}" class="form-control">
                    <option value="">Выберите офис</option>

                    {foreach $offices_array as $city => $offices}
                        <optgroup label="{$city|escape}">
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

                <span class="help-block help-block-error">Обязательное поле</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">Статус</div>

                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('user_status')}" value="ENABLED"{if $model->getStatus() == 'ENABLED'} checked{/if}> Разрешен
                    </label>
                </div>

                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('user_status')}" value="DISABLED"{if $model->getStatus() == 'DISABLED'} checked{/if}> Заблокирован
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {if $__f->getId() == 'user_edit'}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">Внести изменения</button>
                {else}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">Добавить пользователя</button>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.user')}">Отмена</button>
            </div>
        </div>
    </div>
</form>