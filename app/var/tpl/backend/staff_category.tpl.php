<div class="container">
{include file='backend/submenu/staff.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
        {if empty($list)}
            <div class="alert alert-info">
                Нет отделов. Хотите <a href="{$__url->adm('.staff.category.add')}">добавить</a>?
            </div>
        {else}
            <div class="table-responsive">
                <form action="{$__ctx->getData('uri')}" method="post">
                    <table class="table table-hover table-responsive table-striped">
                        <thead>
                        <tr class="center">
                            <th>{'Название отдела'|t}</th>
                            <th>{'Статус'|t}</th>
                            <th>{'Сортировка'|t}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            {foreach $list as $sc}
                            <tr>
                                <td class="middle">{$sc->getTitle()|escape}</td>
                                <td class="center middle">
                                    {if $sc->getStatus() == 'ENABLED'}
                                        <a href="{$__url->adm('.staff.category.status')}?staff_category_id={$sc->getId()|escape}" title="Сменить статус на «Скрывать»" class="scrollfix">Показывать</a>
                                    {elseif $sc->getStatus() == 'DISABLED'}
                                        <a href="{$__url->adm('.staff.category.status')}?staff_category_id={$sc->getId()|escape}" title="Сменить статус на «Показывать»" class="scrollfix highlight-warning">Скрывать</a>
                                    {/if}
                                </td>
                                <td class="center" width="100">
                                    <input type="text" name="staff_category_qnt[{$sc->getId()}]" value="{$sc->getQnt()|escape}" class="form-control" />
                                </td>
                                <td class="right nowrap middle">
                                    <a href="{$__url->adm('.staff.category.edit')}?staff_category_id={$sc->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                    <a href="{$__url->adm('.staff.category.delete')}?staff_category_id={$sc->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
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
            </div>
        {/if}
    </div>
</div>