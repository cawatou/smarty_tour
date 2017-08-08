<div class="container">
    {include file='backend/submenu/office.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if empty($list) && !$filter->isActive()}
                <div class="alert alert-info">Вы еще не добавили ни одного офиса. Хотите <a href="{$__url->url('.adm.office.add', true)}">добавить</a>?</div>
            {else}
                {if empty($list)}
                    <div class="alert alert-warning">Ничего не найдено. Вы можете <a href="{$__url->adm('.office')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.</div>
                {else}
                    {$filter->draw()}

                    <form action="{$__ctx->getData('uri')}" method="POST">
                        <table class="table table-hover table-responsive table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Город</th>

                                    {if $__ctx->getCurrentUser()->isUserInRoles(array('ADMIN', 'DEVELOPER'))}
                                        <th>Подразделение</th>
                                    {/if}

                                    <th width="250">Адрес</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Статус</th>
                                    <th>Сортировка</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach $list as $office}
                                    <tr>
                                        <td class="middle nowrap">{$office->getTitle()|escape}</td>
                                        <td class="middle">
                                            {if $office->getCityId() !== null}
                                                <a href="{$__url->adm('.city.edit')}?city_id={$office->getCityId()|escape}">
                                                    {$office->getCityName()|escape}
                                                </a>
                                            {else}
                                                &mdash;
                                            {/if}
                                        </td>

                                        {if $__ctx->getCurrentUser()->isUserInRoles(array('ADMIN', 'DEVELOPER'))}
                                            <td class="middle">
                                                {if $office->getSubdivisionId() !== null}
                                                    <a href="{$__url->adm('.subdivision.edit')}?subdivision_id={$office->getSubdivisionId()|escape}">
                                                        {$office->getSubdivisionName()|escape}
                                                    </a>
                                                {else}
                                                    &mdash;
                                                {/if}
                                            </td>
                                        {/if}

                                        <td class="middle">{$office->getAddress()|escape}</td>
                                        <td class="middle">
                                            {if $office->getEmail() !== null}
                                                <a href="mailto:{$office->getEmail()|escape}">{$office->getEmail()|escape}</a>
                                            {else}
                                                &mdash;
                                            {/if}
                                        </td>
                                        <td class="middle">{$office->getPhone()|escape|default:"&mdash;"}</td>

                                        {if $office->getStatus() === 'ENABLED'}
                                            <td class="center middle">
                                                <a href="{$__url->adm('.office.status')}?office_id={$office->getId()|escape}" title="Сменить статус на «Скрывать»" class="scrollfix">Показывать</a>
                                            </td>
                                        {elseif $office->getStatus() === 'DISABLED'}
                                            <td class="center middle">
                                                <a href="{$__url->adm('.office.status')}?office_id={$office->getId()|escape}" title="Сменить статус на «Показывать»" class="scrollfix highlight-red">Скрывать</a>
                                            </td>
                                        {/if}

                                        <td class="middle" width="100">
                                            <input type="text" name="office_qnt[{$office->getId()}]" value="{$office->getQnt()|escape}" class="form-control" />
                                        </td>

                                        <td class="right middle nowrap">
                                            <a href="{$__url->adm('.office.edit')}?office_id={$office->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <a href="{$__url->adm('.office.delete')}?office_id={$office->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" name="__change" class="btn btn-primary">{'Внести изменения'|t}</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    {include file='backend/common_paginator.tpl.php' state=$state}
                {/if}
            {/if}
        </div>
    </div>
</div>