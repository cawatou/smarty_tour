<div class="container">
    {include file='backend/submenu/office.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if empty($list)}
                <div class="alert alert-info">
                    Нет сотрудников. Хотите <a href="{$__url->adm('.staff.add')}">добавить</a>?
                </div>
            {else}
                <div class="table-responsive">
                    <form action="{$__ctx->getData('uri')}" method="post">
                        <table class="table table-hover table-responsive table-striped table-condensed">
                            <thead>
                                <tr class="center">
                                    <th title="{'Фото'|t}">{'Ф.'|t}</th>
                                    <th>{'Имя/Должность'|t}</th>
                                    <th>{'Офис'|t}</th>
                                    <th>{'Email'|t}</th>
                                    <th>{'Телефон'|t}</th>
                                    <th>{'Статус'|t}</th>
                                    <th>{'Сортировка'|t}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach $list as $staff}
                                    <tr{if $staff->getIsHighlight()} class="success"{/if}>
                                        <td class="center middle">
                                            {if $staff->getPhoto() === null}
                                                &mdash;
                                            {else}
                                                <img src="{$__url->thumb($staff->getPhoto(), 150 , 150)}" width="20" height="20">
                                            {/if}
                                        </td>
                                        <td>{$staff->getName()|escape}{if $staff->getPosition() !== null}<br /><small>{$staff->getPosition()|escape}</small>{/if}</td>
                                        <td class="center middle">
                                            {if null == $staff->getOffice()}
                                                &mdash;
                                            {else}
                                                <a href="{$__url->adm('.office.edit')}?office_id={$staff->getOfficeId()|escape}">
                                                    {$staff->getOffice()->getTitle()|escape}
                                                </a>
                                            {/if}
                                        </td>
                                        <td class="center middle nowrap">
                                            {if $staff->getEmail() !== null}
                                                <a href="mailto:{$staff->getEmail()|escape}">{$staff->getEmail()|escape}</a>
                                            {else}
                                                &mdash;
                                            {/if}
                                        </td>
                                        <td class="center middle nowrap">{$staff->getPhone()|escape|default:"&mdash;"}</td>
                                        <td class="center middle">
                                            {if $staff->getStatus() == 'ENABLED'}
                                                <a href="{$__url->adm('.staff.status')}?staff_id={$staff->getId()|escape}" title="Сменить статус на «Скрывать»" class="scrollfix">Показывать</a>
                                            {elseif $staff->getStatus() == 'DISABLED'}
                                                <a href="{$__url->adm('.staff.status')}?staff_id={$staff->getId()|escape}" title="Сменить статус на «Показывать»" class="scrollfix highlight-warning">Скрывать</a>
                                            {/if}
                                        </td>
                                        <td class="center middle" width="100">
                                            <input type="text" name="staff_qnt[{$staff->getId()}]" value="{$staff->getQnt()|escape}" class="form-control" />
                                        </td>
                                        <td class="right nowrap middle">
                                            <a href="{$__url->adm('.staff.edit')}?staff_id={$staff->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                            <a href="{$__url->adm('.staff.delete')}?staff_id={$staff->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
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
</div>