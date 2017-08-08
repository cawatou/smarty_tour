<div class="container">
    {include file='backend/submenu/faq.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
        {if empty($list)}
            <div class="alert alert-info">Пользователи сайта ещё не задали ни одного вопроса</div>
        {else}
            {foreach $list as $faq}
                <div class="cms-group-header">
                    <div class="cms-group-actions">
                        {if $__ctx->getCurrentUser()->canEdit('.adm.faq')}
                            <a href="{$__url->adm('.faq.edit')}?faq_id={$faq->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                        {/if}

                        {if $__ctx->getCurrentUser()->canDelete('.adm.faq')}
                            <a href="{$__url->adm('.faq.delete')}?faq_id={$faq->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                        {/if}
                    </div>

                    <div class="cms-group-status">
                        {if $faq->getStatus() == 'ENABLED'}
                            <a href="{$__url->adm('.faq.status')}?faq_id={$faq->getId()|escape}" title="{'Сменить статус на «Скрывать»'|t}" class="scrollfix">{'Показывать'|t}</a>
                        {elseif $faq->getStatus() == 'DISABLED'}
                            <a href="{$__url->adm('.faq.status')}?faq_id={$faq->getId()|escape}" title="{'Сменить статус на «Показывать»'|t}" class="highlight-warning scrollfix">{'Скрывать'|t}</a>
                        {/if}
                    </div>

                    <div class="cms-group-date">{$faq->getCreated()->setDefaultTimeZone()->format('d.m.Y H:i')}</div>
                </div>

                <div class="cms-group{if $faq->getUserId() == $__ctx->getCurrentUser()->getId()} cms-group-white{/if}">
                    <div class="row">
                        <div class="col col-md-4">
                            <div class="panel panel-default">
                                <table class="table table-condensed table-small table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr><td width="10%">Имя:</td><td>{$faq->getUserName()|escape}</td></tr>
                                    <tr><td>IP:</td><td>{$faq->getUserIp()|escape}</td></tr>
                                        {if !is_null($faq->getUserOrg())}<tr><td>Организация:</td><td>{$faq->getUserOrg()|escape}</td></tr>{/if}
                                        {if !is_null($faq->getUserPhone())}<tr><td>Телефон:</td><td>{$faq->getUserPhone()|escape}</a></td></tr>{/if}
                                        {if !is_null($faq->getUserEmail())}<tr><td>Email:</td><td><a href="mailto:{$faq->getUserEmail()|escape}">{$faq->getUserEmail()|escape}</a></td></tr>{/if}

                                        {if $faq->getOffice() !== null}<tr><td>Офис:</td><td>{$faq->getOffice()->getTitle()|escape}</td></tr>{/if}

                                        {if $faq->getUser() !== null}<tr><td>Обрабатывает сообщение:</td><td>{$faq->getUser()->getName()|escape}</td></tr>{/if}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="cms-group-content">
                        <blockquote>
                        <p>{$faq->getMessage()|escape|nl2br}</p>
                        {if !is_null($faq->getAnswer())}<small><strong>Ответ:</strong> {$faq->getAnswer()|escape|nl2br}</small>{/if}
                        </blockquote>
                    </div>
                </div>
            {/foreach}
            {include file='backend/common_paginator.tpl.php' state=$state}
        {/if}
        </div>
    </div>
</div>