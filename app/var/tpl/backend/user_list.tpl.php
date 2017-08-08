<div class="container">
    {include file='backend/submenu/user.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-responsive table-striped">
                    <thead>
                        <tr class="center">
                            <th>Email</th>
                            <th>{'Имя Фамилия'|t}</th>
                            <th>{'Роль'|t}</th>
                            <th>{'Доступ'|t}</th>
                            <th>{'Создан'|t}</th>
                            <th>{'Посещение'|t}</th>
                            <th>IP</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $list as $user}
                        {if !$__ctx->getCurrentUser()->isDeveloper() && $user->getRole() == 'DEVELOPER'}{continue}{/if}
                        <tr{if $__ctx->getCurrentUser()->getId() == $user->getId()} class="success"{/if}>
                            <td class="center">{$user->getLogin()|escape}</td>
                            <td class="center">{$user->getName()|escape}</td>
                            <td class="center">{$user->getRoleTitle()|t|escape}</td>
                        {if $user->getId() == $__ctx->getCurrentUser()->getId()}
                            <td class="center">
                                {if $user->getStatus() == 'ENABLED'}{'Разрешен'|t}{else}<span class="highlight-red">{'Заблокирован'|t}</span>{/if}
                            </td>
                        {else}
                            <td class="center">
                            {if $user->getStatus() == 'ENABLED'}
                                <a href="{$__url->adm('.user.status')}?user_id={$user->getId()|escape}" title="{'Сменить статус на «Заблокирован»'|t}" class="scrollfix">{'Разрешен'|t}</a>
                            {elseif $user->getStatus() == 'DISABLED'}
                                <a href="{$__url->adm('.user.status')}?user_id={$user->getId()|escape}" title="{'Сменить статус на «Разрешён»'|t}" class="scrollfix highlight-warning">{'Заблокирован'|t}</a>
                            {/if}
                            </td>
                        {/if}
                            <td class="center">{$user->getCreated()->format('d.m.Y')}</td>
                            <td class="center">{$user->getVisited()->setDefaultTimeZone()->format('d.m.Y H:i')}</td>
                            <td class="center">{$user->getIp()|default:"0.0.0.0"|escape}</td>
                            <td class="right nowrap">
                                {if $__ctx->getCurrentUser()->isDeveloper() && $user->getId() != $__ctx->getCurrentUser()->getUser()->getId() && $user->getStatus() == 'ENABLED'}
                                    <a href="{$__url->adm('.signInAs')}?user_id={$user->getId()|escape}" onclick="return confirm('{'Вы уверены?'|t}');" class="btn btn-xs btn-primary" title="{'Войти под другим пользователем'|t}"><i class="fa fa-sign-in"></i></a>
                                {/if}

                                <a href="{$__url->adm('.user.edit')}?user_id={$user->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>

                                {if $user->getId() != $__ctx->getCurrentUser()->getUser()->getId()}
                                    <a href="{$__url->adm('.user.delete')}?user_id={$user->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                                {/if}
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
            {include file='backend/common_paginator.tpl.php' state=$state}
        </div>
    </div>
</div>