{assign var="model" value=$__f->getModel()}

{$__ctx->addCss('/backend/files_popup.css')}
{$__ctx->addJs('/backend/files_popup.js')}

{$__ctx->addCss('/backend/form/office.css')}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Данные об офисе изменены
    </div>
{/if}

{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Произошла ошибка, проверьте правильность заполнения полей
    </div>
{/if}

<form role="form" method="POST" action="{$__f->getURL()}">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('office_title') !== null} has-error{/if}">
                <label for="{$__f->encode('office_title')}">Название</label>
                <input type="text" class="form-control" name="{$__f->encode('office_title')}" value="{$model->getTitle(true)|escape}" id="{$__f->encode('office_title')}">
                <span class="help-block help-block-error">{if $__f->e('office_title') == 'ALREADY_EXISTS'}Такой офис уже существует{else}Обязательное поле{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('office_display_name') !== null} has-error{/if}">
                <label for="{$__f->encode('office_display_name')}">Подпись</label>
                <input type="text" class="form-control" name="{$__f->encode('office_display_name')}" value="{$model->getDisplayName()|escape}" id="{$__f->encode('office_display_name')}">
                <span class="help-block help-block-error">Неверное значение</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('city_id') !== null} has-error{/if}">
                <label for="{$__f->encode('city_id')}">Город <i class="fa fa-check"></i></label>

                <select name="{$__f->encode('city_id')}" id="{$__f->encode('city_id')}" class="form-control">
                    <option value="">Выберите город</option>

                    {foreach $city_list as $city}
                        <option value="{$city->getId()|escape}"{if $city->getId() == $model->getCityId()} selected="selected"{/if}{if $city->getStatus() !== 'ENABLED'} style="color: #ccc;"{/if}>{$city->getTitle()|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">Обязательное поле</span>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('related_city_id') !== null} has-error{/if}">
                <label for="{$__f->encode('related_city_id')}">Связанный город</label>

                <select name="{$__f->encode('related_city_id')}" id="{$__f->encode('related_city_id')}" class="form-control">
                    <option value="">Выберите связанный город</option>

                    {foreach $city_list as $city}
                        <option value="{$city->getId()|escape}"{if $city->getId() == $model->getRelatedCityId()} selected="selected"{/if}{if $city->getStatus() !== 'ENABLED'} style="color: #ccc;"{/if}>{$city->getTitle()|escape}</option>
                    {/foreach}
                </select>

                <span class="help-block help-block-error">Неверное значение</span>
            </div>
        </div>
    </div>

    {if $__ctx->getCurrentUser()->getRole() != 'ADMIN' && $__ctx->getCurrentUser()->getRole() != 'DEVELOPER'}
        <input type="hidden" name="{$__f->encode('subdivision_id')}" value="{$__ctx->getCurrentUser()->getSubdivisionId()}">
    {else}
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-group-required{if $__f->e('subdivision_id') !== null} has-error{/if}">
                    <label for="{$__f->encode('subdivision_id')}">Подразделение <i class="fa fa-check"></i></label>

                    <select name="{$__f->encode('subdivision_id')}" id="{$__f->encode('subdivision_id')}" class="form-control">
                        <option value="">Выберите подразделение</option>

                        {foreach $subdivision_list as $sub}
                            <option value="{$sub->getId()|escape}"{if $sub->getId() == $model->getSubdivisionId()} selected{/if}{if $sub->getStatus() !== 'ENABLED'} style="color: #ccc;"{/if}>
                                {$sub->getTitle()|escape}
                            </option>
                        {/foreach}
                    </select>

                    <span class="help-block help-block-error">Обязательное поле</span>
                </div>
            </div>
        </div>
    {/if}

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('office_email') !== null} has-error{/if}">
                <label for="{$__f->encode('office_email')}">Email для уведомлений</label>
                <input type="text" class="form-control" name="{$__f->encode('office_email')}" value="{$model->getEmail()|escape}" id="{$__f->encode('office_email')}">
                <span class="help-block help-block-error">{if $__f->e('office_email') == 'INVALID_FORMAT'}Неверный формат{else}Обязательное поле{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('office_phone') !== null} has-error{/if}">
                <label for="{$__f->encode('office_phone')}">Телефон <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('office_phone')}" value="{$model->getPhone()|escape}" id="{$__f->encode('office_phone')}">
                <span class="help-block help-block-error">Обязательное поле</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('office_address') !== null} has-error{/if}">
                <label for="{$__f->encode('office_address')}">Адрес <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('office_address')}" value="{$model->getAddress()|escape}" id="{$__f->encode('office_address')}">
                <span class="help-block help-block-error">Обязательное поле</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('office_metro') !== null} has-error{/if}">
                <label for="{$__f->encode('office_metro')}">Метро</label>
                <input type="text" class="form-control" name="{$__f->encode('office_metro')}" value="{$model->getMetro()|escape}" id="{$__f->encode('office_metro')}">
                <span class="help-block help-block-error">Неверное значение</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="cms-group cms-group-expanded form-horizontal">
                <div class="cms-group-label">Принимаемые виды оплаты</div>

                <input type="hidden" name="{$__f->encode('office_is_pay_cash')}" value="0" />
                <input type="hidden" name="{$__f->encode('office_is_pay_cashless')}" value="0" />
                <input type="hidden" name="{$__f->encode('office_is_pay_installment')}" value="0" />
                <input type="hidden" name="{$__f->encode('office_is_pay_credit')}" value="0" />

                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="{$__f->encode('office_is_pay_cash')}" value="1"{if $model->getIsPayCash()} checked{/if}> {'Наличными'|t}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="{$__f->encode('office_is_pay_cashless')}" value="1"{if $model->getIsPayCashless()} checked{/if}> {'Безналичный'|t}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="{$__f->encode('office_is_pay_installment')}" value="1"{if $model->getIsPayInstallment()} checked{/if}> {'Рассрочка'|t}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="{$__f->encode('office_is_pay_credit')}" value="1"{if $model->getIsPayCredit()} checked{/if}> {'В кредит'|t}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="cms-group cms-group-expanded form-horizontal">
                <div class="cms-group-label">Расписание</div>

                <input type="hidden" name="{$__f->encode('office_schedule')}[1][checked]" value="0" />
                <input type="hidden" name="{$__f->encode('office_schedule')}[2][checked]" value="0" />
                <input type="hidden" name="{$__f->encode('office_schedule')}[3][checked]" value="0" />
                <input type="hidden" name="{$__f->encode('office_schedule')}[4][checked]" value="0" />
                <input type="hidden" name="{$__f->encode('office_schedule')}[5][checked]" value="0" />
                <input type="hidden" name="{$__f->encode('office_schedule')}[6][checked]" value="0" />
                <input type="hidden" name="{$__f->encode('office_schedule')}[7][checked]" value="0" />

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="{$__f->encode('office_schedule_1')}">Пн</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="{$__f->encode('office_schedule')}[1][time]" id="{$__f->encode('office_schedule_1')}" value="{$model->getScheduleDay(1, 'time')|escape}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="{$__f->encode('office_schedule_6')}">Сб</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="{$__f->encode('office_schedule')}[6][time]" id="{$__f->encode('office_schedule_6')}" value="{$model->getScheduleDay(6, 'time')|escape}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="{$__f->encode('office_schedule_2')}">Вт</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="{$__f->encode('office_schedule')}[2][time]" id="{$__f->encode('office_schedule_2')}" value="{$model->getScheduleDay(2, 'time')|escape}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="{$__f->encode('office_schedule_7')}">Вс</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="{$__f->encode('office_schedule')}[7][time]" id="{$__f->encode('office_schedule_7')}" value="{$model->getScheduleDay(7, 'time')|escape}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="{$__f->encode('office_schedule_3')}">Ср</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="{$__f->encode('office_schedule')}[3][time]" id="{$__f->encode('office_schedule_3')}" value="{$model->getScheduleDay(3, 'time')|escape}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="{$__f->encode('office_schedule_4')}">Чт</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="{$__f->encode('office_schedule')}[4][time]" id="{$__f->encode('office_schedule_4')}" value="{$model->getScheduleDay(4, 'time')|escape}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="{$__f->encode('office_schedule_5')}">Пт</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="{$__f->encode('office_schedule')}[5][time]" id="{$__f->encode('office_schedule_5')}" value="{$model->getScheduleDay(5, 'time')|escape}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cms-group cms-group-expanded">
        <div class="cms-group-label">Настройки для заказов sletat.ru</div>

        {if $model->getSletatData()}
            {foreach $model->getSletatData() as $name => $v}
                <div class="row row-attr">
                    <label for="{$__f->encode('office_sletat_data')}_{$name}_value" class="col-sm-2 control-label">{$v.title|escape}</label>

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" id="{$__f->encode('office_sletat_data')}_{$name}_value" name="{$__f->encode('office_sletat_data')}[{$name}][value]" value="{if !empty($v.value)}{$v.value|escape}{/if}"{if !empty($v.help)} aria-describedby="{$__f->encode('office_sletat_data')}_{$name}_help"{/if}>
                        </div>
                    </div>

                    {if !empty($v.help)}
                        <span id="{$__f->encode('office_sletat_data')}_{$name}_help" class="help-block">
                            {$v.help}
                        </span>
                    {/if}
                </div>
            {/foreach}
        {/if}
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">Статус</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('office_status')}" value="ENABLED"{if $model->getStatus() === 'ENABLED'} checked{/if}> Показывать
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('office_status')}" value="DISABLED"{if $model->getStatus() === 'DISABLED'} checked{/if}> Скрывать
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {if $__f->getId() == 'office_edit'}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">Внести изменения</button>
                {else}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">Добавить офис</button>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.office')}">Отмена</button>
            </div>
        </div>
    </div>
</form>