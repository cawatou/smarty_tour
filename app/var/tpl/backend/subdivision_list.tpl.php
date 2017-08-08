<div class="container">
    {include file='backend/submenu/office.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if empty($list) && !$filter->isActive()}
                <div class="alert alert-info">Вы еще не добавили ни одного подразделения. Хотите <a href="{$__url->url('.adm.subdivision.add', true)}">добавить</a>?</div>
            {else}
                {$filter->draw()}

                {if empty($list)}
                    <div class="alert alert-warning">Ничего не найдено. Вы можете <a href="{$__url->adm('.subdivision')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.</div>
                {else}
                    <form action="{$__ctx->getData('uri')}" method="post">
                        <div class="table-responsive">
                            <table class="table table-hover table-responsive table-striped table-condensed">
                                <thead>
                                    <tr class="center">
                                        <th>Название</th>
                                        {**<th>Alias</th>**}
                                        <th>Статус</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach $list as $subdivision}
                                        <tr>
                                            <td class="middle">
                                                {$subdivision->getTitle()|escape}
                                            </td>

                                            {**
                                            <td class="middle">
                                                {if $subdivision->getAlias() !== null}{$subdivision->getAlias()}{else}&mdash;{/if}
                                            </td>
                                            **}

                                            {if $subdivision->getStatus() === 'ENABLED'}
                                                <td class="center middle">
                                                    <a href="{$__url->adm('.subdivision.status')}?subdivision_id={$subdivision->getId()|escape}" title="Сменить статус на «Скрывать»" class="scrollfix">Показывать</a>
                                                </td>
                                            {elseif $subdivision->getStatus() === 'DISABLED'}
                                                <td class="center middle">
                                                    <a href="{$__url->adm('.subdivision.status')}?subdivision_id={$subdivision->getId()|escape}" title="Сменить статус на «Показывать»" class="scrollfix highlight-warning">Скрывать</a>
                                                </td>
                                            {/if}

                                            <td class="right nowrap middle">
                                                <a href="{$__url->adm('.subdivision.edit')}?subdivision_id={$subdivision->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                                <a href="{$__url->adm('.subdivision.delete')}?subdivision_id={$subdivision->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
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