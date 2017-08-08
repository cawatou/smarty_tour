<div class="container">
    {include file='backend/submenu/book.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if empty($list)}
                <div class="alert alert-info">Операторы отсутствуют</div>
            {else}
                <div class="table-responsive">
                    <table class="table table-hover table-responsive table-striped table-condensed">
                        <thead>
                            <tr class="center">
                                <th>Название</th>
                                <th>Статус</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach $list as $touroperator}
                                <tr>
                                    <td class="middle">
                                        {$touroperator->getTitle()|escape}
                                    </td>

                                    {if $touroperator->getStatus() === 'ENABLED'}
                                        <td class="center middle">
                                            <a href="{$__url->adm('.touroperator.status')}?touroperator_id={$touroperator->getId()|escape}" title="Сменить статус на «Скрывать»" class="scrollfix">Показывать</a>
                                        </td>
                                    {elseif $touroperator->getStatus() === 'DISABLED'}
                                        <td class="center middle">
                                            <a href="{$__url->adm('.touroperator.status')}?touroperator_id={$touroperator->getId()|escape}" title="Сменить статус на «Показывать»" class="scrollfix highlight-warning">Скрывать</a>
                                        </td>
                                    {/if}

                                    <td class="right nowrap middle">
                                        <a href="{$__url->adm('.touroperator.edit')}?touroperator_id={$touroperator->getId()|escape}" class="btn btn-xs btn-warning" title="Редактировать"><i class="fa fa-pencil"></i></a>
                                        <a href="{$__url->adm('.touroperator.delete')}?touroperator_id={$touroperator->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('Вы уверены?');" title="Удалить"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>

                {include file='backend/common_paginator.tpl.php' state=$state}
            {/if}
        </div>
    </div>
</div>