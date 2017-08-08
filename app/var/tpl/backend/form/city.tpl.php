{assign var="model" value=$__f->m()}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Данные о городе изменены
    </div>
{/if}

{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Произошла ошибка, проверьте правильность заполнения полей
    </div>
{/if}

<form method="post" action="{$__f->getUrl()}">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('city_title') !== null} has-error{/if}">
                <label for="{$__f->encode('city_title')}">Название <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('city_title')}" value="{$model->getTitle()|escape}" id="{$__f->encode('city_title')}">
                <span class="help-block help-block-error">{if $__f->e('city_title') === 'ALREADY_EXISTS'}Такой город уже существует{else}Обязательное поле{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('city_alias') !== null} has-error{/if}">
                <label for="{$__f->encode('city_alias')}">Alias</label>
                <input type="text" class="form-control" name="{$__f->encode('city_alias')}" value="{$model->getAlias()|escape}" id="{$__f->encode('city_alias')}">
                <span class="help-block help-block-error">{if $__f->e('city_alias') === 'ALREADY_EXISTS'}Город с таким alias уже существует{elseif $__f->e('city_alias') == 'INVALID_FORMAT'}Неверный формат. Допускаются только a-z, -, _{else}Не может быть пустым{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('city_email') !== null} has-error{/if}">
                <label for="{$__f->encode('city_email')}">Email</label>
                <input type="text" class="form-control" name="{$__f->encode('city_email')}" value="{$model->getEmail()|escape}" id="{$__f->encode('city_email')}">
                <span class="help-block help-block-error">{if $__f->e('city_email') == 'INVALID_FORMAT'}{'Неверный формат'|t}{else}Обязательное поле{/if}</span>
            </div>
        </div>
    </div>

    {if $__ctx->getCurrentUser()->getRole() == 'DIRECTOR'}
        <input type="hidden" name="{$__f->encode('subdivision_id')}" value="{$__ctx->getCurrentUser()->getSubdivisionId()}">
    {else}
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-group-required{if $__f->e('subdivision_id') !== null} has-error{/if}">
                    <label for="{$__f->encode('subdivision_id')}">Подразделение <i class="fa fa-check"></i></label>

                    <select name="{$__f->encode('subdivision_id')}" id="{$__f->encode('subdivision_id')}" class="form-control">
                        <option value="">Выберите подразделение</option>

                        {foreach $subdivision_list as $subdivision}
                            <option value="{$subdivision->getId()|escape}"{if $subdivision->getId() == $model->getSubdivisionId()} selected{/if}>{$subdivision->getTitle()|escape}</option>
                        {/foreach}
                    </select>

                    <span class="help-block help-block-error">Обязательное поле</span>
                </div>
            </div>
        </div>
    {/if}

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('city_sms_group') !== null} has-error{/if}">
                <label for="{$__f->encode('city_sms_group')}">ID группы. SMS рассылка</label>
                <input type="text" class="form-control" name="{$__f->encode('city_sms_group')}" value="{$model->getSmsGroup()|escape}" id="{$__f->encode('city_sms_group')}">
                <span class="help-block help-block-error">Неверное значение</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('city_email_group') !== null} has-error{/if}">
                <label for="{$__f->encode('city_email_group')}">ID города. Email рассылка</label>
                <input type="text" class="form-control" name="{$__f->encode('city_email_group')}" value="{$model->getEmailGroup()|escape}" id="{$__f->encode('city_email_group')}">
                <span class="help-block help-block-error">Неверное значение</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('city_vk_group') !== null} has-error{/if}">
                <label for="{$__f->encode('city_vk_group')}">ID группы. Вконтакте</label>
                <input type="text" class="form-control" name="{$__f->encode('city_vk_group')}" value="{$model->getVkGroup()|escape}" id="{$__f->encode('city_vk_group')}">
                <span class="help-block help-block-error">Неверное значение</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('city_odnkl_group') !== null} has-error{/if}">
                <label for="{$__f->encode('city_odnkl_group')}">ID группы. Однокласники</label>
                <input type="text" class="form-control" name="{$__f->encode('city_odnkl_group')}" value="{$model->getOdnklGroup()|escape}" id="{$__f->encode('city_odnkl_group')}">
                <span class="help-block help-block-error">Неверное значение</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('city_insta_group') !== null} has-error{/if}">
                <label for="{$__f->encode('city_insta_group')}">ID аккаунта. Инстаграм</label>
                <input type="text" class="form-control" name="{$__f->encode('city_insta_group')}" value="{$model->getInstaGroup()|escape}" id="{$__f->encode('city_insta_group')}">
                <span class="help-block">Аккаунт в Инстаграм должен быть публичным</span>
                <span class="help-block help-block-error">Неверное значение</span>
            </div>
        </div>
    </div>
	
	<div class="row">
        <div class="col-md-4">
            <div class="form-group{if $__f->e('city_facebook_group') !== null} has-error{/if}">
                <label for="{$__f->encode('city_facebook_group')}">ID аккаунта. FACEBOOK</label>
                <input type="text" class="form-control" name="{$__f->encode('city_facebook_group')}" value="{$model->getFacebookGroup()|escape}" id="{$__f->encode('city_facebook_group')}">
                
                <span class="help-block help-block-error">Неверное значение</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            {if !empty($city_list)}
                <div class="cms-group cms-group-expanded">
                    <div class="cms-group-label">Показывать офисы из</div>

                    {foreach $city_list as $nearby_city}
                        {if $nearby_city->getId() == $model->getCityId()}{continue}{/if}

                        <div class="row row-attr">
                            <div class="col-md-7">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="{$__f->encode('city_city_ids')}[{$nearby_city->getId()}][is_shown]" value="1"{if $model->isNearbyCityId($nearby_city->getId())} checked="checked"{/if} />
                                        {$nearby_city->getTitle()|escape}
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="{$__f->encode('city_city_ids')}[{$nearby_city->getId()}][qnt]" value="{$model->getNearbyCityQnt($nearby_city->getId())|escape}">
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            {/if}
        </div>

        {if !empty($departures)}
            <div class="col-md-4">
                <div class="cms-group cms-group-expanded">
                    <div class="cms-group-label">Города отправления</div>

                    {foreach $departures as $departure}
                        <div class="row row-attr">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <p class="form-control-static">{$departure.departure_title|escape}</p>
                                    <input type="hidden" name="{$__f->encode('city_departure_list')}[{$departure@iteration}][departure_id]" value="{$departure.departure_id|escape}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="{$__f->encode('city_departure_list')}[{$departure@iteration}][qnt]" value="{$departure.qnt|escape}" placeholder="Приоритет при сортировке" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="{$__f->encode('city_departure_list')}[{$departure@iteration}][is_hidden]" value="1"{if !empty($departure.is_hidden)} checked{/if} />
                                        Скрывать
                                    </label>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        {/if}

        {if !empty($departures)}
            <div class="col-md-4">
                <div class="cms-group cms-group-expanded">
                    <div class="cms-group-label">Соседние города</div>

                    {foreach $departures as $departure}
                        <div class="row row-attr">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="{$__f->encode('city_similar_product_cities')}[{$departure@iteration}][is_shown]" value="1"{if $model->isTourFromCityId($departure.departure_id)} checked{/if} />
                                        <p class="form-control-static">{$departure.departure_title_flat|escape}</p>
                                        <input type="hidden" name="{$__f->encode('city_similar_product_cities')}[{$departure@iteration}][departure_id]" value="{$departure.departure_id|escape}" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        {/if}
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group{if $__f->e('city_top_news') !== null} has-error{/if}">
                <label for="{$__f->encode('city_top_news')}">Новость в шапке сайта</label>
                <textarea name="{$__f->encode('city_top_news')}" id="{$__f->encode('city_top_news')}" class="form-control form-textarea-vertical">{$model->getTopNews()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">Статус</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('city_status')}" value="ENABLED"{if $model->getStatus() === 'ENABLED'} checked="checked"{/if}>
                        Показывать
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('city_status')}" value="DISABLED"{if $model->getStatus() === 'DISABLED'} checked="checked"{/if}>
                        Скрывать
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {if $__f->getId() === 'city_edit'}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">Внести изменения</button>
                {else}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">Добавить город</button>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.city')}">Отмена</button>
            </div>
        </div>
    </div>
</form>