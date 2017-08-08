<div class="container">
    {include file='backend/submenu/office.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if empty($list) && !$filter->isActive()}
                <div class="alert alert-info">Вы еще не добавили ни одного города. Хотите <a href="{$__url->url('.adm.city.add', true)}">добавить</a>?</div>
            {else}
                {$filter->draw()}

                {if empty($list)}
                    <div class="alert alert-warning">Ничего не найдено. Вы можете <a href="{$__url->adm('.city')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.</div>
                {else}
                    <form action="{$__ctx->getData('uri')}" method="post">
                        <div class="table-responsive">
                            <table class="table table-hover table-responsive table-striped table-condensed">
                                <thead>
                                    <tr class="center">
                                        <th>Название</th>
                                        <th>Alias</th>
                                        <th>ID группы SMS рассылки</th>
                                        <th>ID города для Email рассылки</th>
                                        <th>Статус</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach $list as $city}
                                        <tr>
                                            <td class="middle">
                                                {$city->getTitle()|escape}
                                            </td>

                                            <td class="middle">
                                                {if $city->getAlias() !== null}{$city->getAlias()}{else}&mdash;{/if}
                                            </td>

                                            <td class="middle center">
                                                {if $city->getSmsGroup() !== null}{$city->getSmsGroup()}{else}&mdash;{/if}
                                            </td>

                                            <td class="middle center">
                                            {*    {if $city->getEmailSubscriptionId() !== null}{$city->getEmailSubscriptionId()}{else}&mdash;{/if}  *}
                                                {if $city->getEmailGroup() !== null}{$city->getEmailGroup()}{else}&mdash;{/if}
                                            </td>

                                            {if $city->getStatus() === 'ENABLED'}
                                                <td class="center middle">
                                                    <a href="{$__url->adm('.city.status')}?city_id={$city->getId()|escape}" title="Сменить статус на «Скрывать»" class="scrollfix">Показывать</a>
                                                </td>
                                            {elseif $city->getStatus() === 'DISABLED'}
                                                <td class="center middle">
                                                    <a href="{$__url->adm('.city.status')}?city_id={$city->getId()|escape}" title="Сменить статус на «Показывать»" class="scrollfix highlight-warning">Скрывать</a>
                                                </td>
                                            {/if}

                                            <td class="right nowrap middle">
                                                <a href="{$__url->adm('.city.edit')}?city_id={$city->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                                <a href="{$__url->adm('.city.delete')}?city_id={$city->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </form>

                    {include file='backend/common_paginator.tpl.php' state=$state}
                {/if}
            {/if}
        </div>
    </div>
</div>