<div class="container">
    {include file='backend/submenu/feedback.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if empty($list) && !$filter->isActive()}
                <div class="alert alert-info">{'Пользователи сайта не оставили ни одного отзыва'|t}</div>
            {else}
                {$filter->draw()}

                {if empty($list)}
                    <div class="alert alert-warning">Ничего не найдено. Вы можете <a href="{$__url->adm('.feedback')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.</div>
                {else}
                    {foreach $list as $fb}
                        <div class="cms-group-header">
                            <div class="cms-group-actions">
                                {if $__ctx->getCurrentUser()->canEdit('.adm.feedback')}
                                    <a href="{$__url->adm('.feedback.edit')}?feedback_id={$fb->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                {/if}

                                {if $__ctx->getCurrentUser()->canDelete('.adm.feedback')}
                                    <a href="{$__url->adm('.feedback.delete')}?feedback_id={$fb->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                                {/if}
                            </div>

                            {if in_array($fb->getType(true), array('QUALITY'))}
                                <div class="cms-group-status">
                                    {if $fb->getStatus() == 'ENABLED'}
                                        <a href="{$__url->adm('.feedback.status')}?feedback_id={$fb->getId()|escape}" title="{'Сменить статус на «Обработано»'|t}" class="highlight-warning scrollfix">{'Новый отзыв'|t}</a>
                                    {elseif $fb->getStatus() == 'DISABLED'}
                                        <a href="{$__url->adm('.feedback.status')}?feedback_id={$fb->getId()|escape}" title="{'Сменить статус на «Новый отзыв»'|t}" class="scrollfix">{'Обработано'|t}</a>
                                    {/if}
                                </div>
                            {else}
                                <div class="cms-group-status">
                                    {if $fb->getStatus() == 'ENABLED'}
                                        <a href="{$__url->adm('.feedback.status')}?feedback_id={$fb->getId()|escape}" title="{'Сменить статус на «Скрывать»'|t}" class="scrollfix">{'Показывать'|t}</a>
                                    {elseif $fb->getStatus() == 'DISABLED'}
                                        <a href="{$__url->adm('.feedback.status')}?feedback_id={$fb->getId()|escape}" title="{'Сменить статус на «Показывать»'|t}" class="highlight-warning scrollfix">{'Скрывать'|t}</a>
                                    {/if}
                                </div>
                            {/if}

                            <div class="cms-group-date">{$fb->getCreated()->setDefaultTimeZone()->format('d.m.Y H:i')}</div>
                        </div>

                        <div class="cms-group cms-group-expanded{if $fb->getUserId() == $__ctx->getCurrentUser()->getId()} cms-group-white{/if}">
                            <div class="cms-group-label">
                                {$fb->getType()|escape}
                            </div>

                            <div class="row">
                                <div class="col col-md-6">
                                    <div class="panel panel-default">
                                        <table class="table table-condensed table-small table-bordered">
                                            <thead></thead>

                                            <tbody>
                                                <tr>
                                                    <th>Имя:</th>
                                                    <td>{$fb->getUserName()|escape}</td>
                                                </tr>

                                                {if $fb->getUserPhone() !== null}
                                                    <tr>
                                                        <th>Телефон:</th>
                                                        <td>{$fb->getUserPhone()|escape}</td>
                                                    </tr>
                                                {/if}

                                                {if $fb->getUserEmail() !== null}
                                                    <tr>
                                                        <th>Email:</th>
                                                        <td>
                                                            <a href="mailto:{$fb->getUserEmail()|escape}">{$fb->getUserEmail()|escape}</a>
                                                        </td>
                                                    </tr>
                                                {/if}

                                                <tr>
                                                    <th>IP:</th>
                                                    <td>{$fb->getUserIp()|escape}</td>
                                                </tr>

                                                {if $fb->getOffice() !== null}
                                                    <tr>
                                                        <th>Офис:</th>
                                                        <td>
                                                            <a href="{$__url->adm('.office.edit')}?office_id={$fb->getOfficeId()|escape}">
                                                                {$fb->getOffice()->getTitle()|escape}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                {/if}

                                                {if $fb->getStaffName() !== null}
                                                    <tr>
                                                        <th>Для менеджера:</th>
                                                        <td>
                                                            {if $fb->getStaffId() !== null && $fb->getStaff() !== null}
                                                                <a href="{$__url->adm('.staff.edit')}?staff_id={$fb->getStaffId()|escape}">{$fb->getStaff()->getName()|escape}</a>
                                                            {else}
                                                                {$fb->getStaffName()|escape}
                                                            {/if}
                                                        </td>
                                                    </tr>
                                                {/if}

                                                {if $fb->getUser() !== null}
                                                    <tr>
                                                        <th>Обрабатывает сообщение:</th>
                                                        <td>{$fb->getUser()->getName()|escape}</td>
                                                    </tr>
                                                {/if}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {if $fb->getType(true) == 'HOTEL'}
                                    <div class="col col-md-6">
                                        <div class="panel panel-default">
                                            <table class="table table-condensed table-small table-bordered">
                                                <thead></thead>

                                                <tbody>
                                                    {if $fb->getHotel() !== null}
                                                        <tr>
                                                            <th>Отель:</th>
                                                            <td>
                                                                <a href="{$fb->getHotel()->getUrl()}">
                                                                    {$fb->getHotel()->getTitle()|escape}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    {/if}

                                                    <tr>
                                                        <th>Оценки:</th>
                                                        <td>
                                                            <table class="table table-condensed table-small">
                                                                <thead>
                                                                    <tr>
                                                                        <td>
                                                                            Состояние<br />номера
                                                                        </td>
                                                                        <td>
                                                                            Территория
                                                                        </td>
                                                                        <td>
                                                                            Пляж
                                                                        </td>
                                                                        <td>
                                                                            Обслуживание
                                                                        </td>
                                                                        <td>
                                                                            Питание
                                                                        </td>
                                                                        <td>
                                                                            Анимация
                                                                        </td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            {(int)$fb->getExtendedData('rating_room')}
                                                                        </td>
                                                                        <td>
                                                                            {(int)$fb->getExtendedData('rating_territory')}
                                                                        </td>
                                                                        <td>
                                                                            {(int)$fb->getExtendedData('rating_beach')}
                                                                        </td>
                                                                        <td>
                                                                            {(int)$fb->getExtendedData('rating_service')}
                                                                        </td>
                                                                        <td>
                                                                            {(int)$fb->getExtendedData('rating_food')}
                                                                        </td>
                                                                        <td>
                                                                            {(int)$fb->getExtendedData('rating_anim')}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Рекомендуется для:</th>
                                                        <td>
                                                            {$fb->getRecommendedFor()|default:"&mdash;"}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Дата отдыха:</th>
                                                        <td>
                                                            {$fb->getExtendedData('date_staying')}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                {/if}
                            </div>

                            <div class="cms-group-content">
                                {if $fb->getType(true) == 'HOTEL'}
                                    {if count($fb->getExtendedData('photos')) > 0}
                                        <div class="cms-group-content-photos">
                                            {foreach $fb->getExtendedData('photos') as $photo}
                                                <a href="{$photo|escape}">
                                                    <img src="{$__url->thumb($photo, 75, 75)}" alt="" width="75" height="75" />
                                                </a>
                                            {/foreach}
                                        </div>
                                    {/if}
                                {/if}

                                <blockquote>
                                    <p>{$fb->getMessage()|escape|nl2br}</p>

                                    {if $fb->getAnswer() !== null}
                                        <small>
                                            <strong>Ответ:</strong> {$fb->getAnswer()|escape|nl2br}
                                        </small>
                                    {/if}
                                </blockquote>
                            </div>
                        </div>
                    {/foreach}

                    {include file='backend/common_paginator.tpl.php' state=$state}
                {/if}
            {/if}
        </div>
    </div>
</div>