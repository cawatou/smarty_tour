<div class="container">
    {include file='backend/submenu/companion.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if empty($list)}
                <div class="alert alert-info">{'Пользователи сайта пока не ищут попутчиков'|t}</div>
            {else}
                {foreach $list as $companion}
                    <div class="cms-group-header">
                        <div class="cms-group-actions">
                            {if $__ctx->getCurrentUser()->canEdit('.adm.companion')}
                                <a href="{$__url->adm('.companion.edit')}?companion_id={$companion->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                            {/if}

                            {if $__ctx->getCurrentUser()->canDelete('.adm.companion')}
                                <a href="{$__url->adm('.companion.delete')}?companion_id={$companion->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                            {/if}
                        </div>

                        <div class="cms-group-status">
                            {if $__ctx->getCurrentUser()->canEdit('.adm.companion')}
                                {if $companion->getStatus() == 'DISABLED'}
                                    <a href="{$__url->adm('.companion.status')}?companion_id={$companion->getId()|escape}" title="{'Сменить статус на «Показать»'|t}" class="highlight-warning scrollfix">{'Скрыть'|t}</a>
                                {elseif $companion->getStatus() == 'ENABLED'}
                                    <a href="{$__url->adm('.companion.status')}?companion_id={$companion->getId()|escape}" title="{'Сменить статус на «Скрыть»'|t}" class="scrollfix">{'Показать'|t}</a>
                                {/if}
                            {else}
                                {if $companion->getStatus() == 'DISABLED'}
                                    {'Показан'|t}
                                {else}
                                    {'Скрыт'|t}
                                {/if}
                            {/if}
                        </div>

                        <div class="cms-group-date">{$companion->getCreated()->setDefaultTimeZone()->format('d.m.Y H:i')}</div>
                    </div>

                    <div class="cms-group cms-group-white">
                        {if $companion->getUserPhoto()}
                            <img src="{$__url->thumb($companion->getUserPhoto(), 75, 75)}" alt="" width="75" height="75" class="pull-right" />
                        {/if}

                        <div class="row">
                            <div class="col col-md-4">
                                <div class="panel panel-default">
                                    <table class="table table-condensed table-small table-bordered">
                                        <thead></thead>

                                        <tbody>
                                            <tr>
                                                <th>{'Имя'}:</th>
                                                <td>
                                                    {$companion->getUserName()|escape}
                                                    {if $companion->getUserAge()}
                                                        ({(int)$companion->getUserAge()})
                                                    {/if}
                                                </td>
                                            </tr>

                                            {if $companion->getUserPhone() !== null}
                                                <tr>
                                                    <th>Телефон:</th>
                                                    <td>{$companion->getUserPhone()|escape}</td>
                                                </tr>
                                            {/if}

                                            {if $companion->getUserEmail() !== null}
                                                <tr>
                                                    <th>Email:</th>
                                                    <td>
                                                        <a href="mailto:{$companion->getUserEmail()|escape}">{$companion->getUserEmail()|escape}</a>
                                                    </td>
                                                </tr>
                                            {/if}

                                            <tr>
                                                <th>IP:</th>
                                                <td>{$companion->getUserIp()|escape}</td>
                                            </tr>

                                            <tr>
                                                <th>Город:</th>
                                                <td>{$companion->getUserCity()|escape}</td>
                                            </tr>

                                            <tr>
                                                <th>Страна:</th>
                                                <td>{$companion->getLocation()|escape}</td>
                                            </tr>

                                            <tr>
                                                <th>Бюджет:</th>
                                                <td>{$companion->getPrice()|escape|default:"&mdash;"}</td>
                                            </tr>

                                            <tr>
                                                <th>Кол-во дней:</th>
                                                <td>от {(int)$companion->getDaynumFrom()} до {(int)$companion->getDaynumTo()}</td>
                                            </tr>

                                            <tr>
                                                <th>Дата:</th>
                                                <td>с {$companion->getDateFrom()->setDefaultTimeZone()->format('d.m.Y')} по {$companion->getDateTo()->setDefaultTimeZone()->format('d.m.Y')}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="cms-group-content">
                            <blockquote>
                                <p>{$companion->getNotes()|escape|nl2br}</p>

                                {if $companion->getAgencyNotes() !== null}
                                    <small>
                                        <strong>Заметки:</strong> {$companion->getAgencyNotes()|escape|nl2br}
                                    </small>
                                {/if}
                            </blockquote>
                        </div>
                    </div>
                {/foreach}

                {include file='backend/common_paginator.tpl.php' state=$state}
            {/if}
        </div>
    </div>
</div>