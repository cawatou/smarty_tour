<div class="container">
    {include file='backend/submenu/gallery.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
        {if empty($list) && !$filter->isActive()}
            <div class="alert alert-info">Вы еще не добавили ни одной галереи. Хотите <a href="{$__url->adm('.gallery.category.add')}">добавить</a>?</div>
        {else}
            {$filter->draw()}
            {if empty($list)}
                <div class="alert alert-warning">Ничего не найдено. Вы можете <a href="{$__url->adm('.gallery.category')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.</div>
            {else}
                <div class="table-responsive">
                <table class="table table-hover table-responsive table-striped">
                    <thead>
                    <tr class="center">
                        <th>{'Дата'|t}</th>
                        <th>{'Название'|t}</th>
                        <th>Alias</th>
                        <th>{'Группа'|t}</th>
                        <th>{'Статус'|t}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        {foreach $list as $gallery}
                        <tr{if $gallery->getIsHighlight()} class="success"{/if}>
                            <td class="center">{$gallery->getDate()->setDefaultTimeZone()->format('d.m.Y')}</td>
                            <td>{$gallery->getTitle()|escape}</td>
                            <td class="center">{$gallery->getAlias()|escape}</td>
                            <td class="center">{$gallery->getCategoryName()|escape|default:"???"}</td>
                            <td class="center">
                            {if $gallery->getStatus() == 'ENABLED'}
                                <a href="{$__url->adm('.gallery.category.status')}?gallery_id={$gallery->getId()|escape}" title="{'Сменить статус на «Скрывать»'|t}" class="scrollfix">{'Показывать'|t}</a>
                            {elseif $gallery->getStatus() == 'DISABLED'}
                                <a href="{$__url->adm('.gallery.category.status')}?gallery_id={$gallery->getId()|escape}" title="{'Сменить статус на «Показывать»'|t}" class="scrollfix highlight-warning">{'Скрывать'|t}</a>
                            {/if}
                            </td>
                            <td class="right nowrap">
                                <a href="{$__url->adm('.gallery.image')}?{Form_Filter::encodeSearchName('fgi', 'gallery_id')}={$gallery->getId()|escape}" class="btn btn-xs btn-primary" title="{'Просмотреть все картинки в этой галерее'|t}"><i class="fa fa-list"></i></a>
                                <a href="{$__url->adm('.gallery.category.edit')}?gallery_id={$gallery->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                <a href="{$__url->adm('.gallery.category.delete')}?gallery_id={$gallery->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
                </div>
                {include file='backend/common_paginator.tpl.php' state=$state}
            {/if}
        {/if}
        </div>
    </div>
</div>