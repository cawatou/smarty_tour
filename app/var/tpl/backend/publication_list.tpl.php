{$__ctx->addCss('/backend/module/publication.css')}
<div class="container">
    {include file='backend/submenu/publication.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
        {if empty($list) && !$filter->isActive()}
        <div class="alert alert-info">Вы еще не добавили ни одной публикации. Хотите <a href="{$__url->adm('.publication.add')}">добавить</a>?</div>
        {else}
            {*$filter->draw()*}
            {if empty($list)}
            <div class="alert alert-warning">Ничего не найдено. Вы можете <a href="{$__url->adm('.publication')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.</div>
            {else}
            {foreach $list as $pub}
            <div class="cms-group-header">
                <div class="cms-group-actions">
                    <a href="{$__url->adm('.publication.edit')}?publication_id={$pub->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                    <a href="{$__url->adm('.publication.delete')}?publication_id={$pub->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                </div>
                {if $pub->getStatus() == 'ENABLED'}
                    <div class="cms-group-status">
                        <a href="{$__url->adm('.publication.status')}?publication_id={$pub->getId()|escape}" title="{'Сменить статус на «Скрывать»'|t}" class="scrollfix">{'Показывать'|t}</a>
                    </div>
                    {elseif $pub->getStatus() == 'DISABLED'}
                    <div class="cms-group-status">
                        <a href="{$__url->adm('.publication.status')}?publication_id={$pub->getId()|escape}" title="{'Сменить статус на «Показывать»'|t}" class="highlight-warning scrollfix">{'Скрывать'|t}</a>
                    </div>
                {/if}
                <div class="cms-group-date">{$pub->getDate()->setDefaultTimeZone()->format('d.m.Y H:i')}</div>
            </div>
            <div class="cms-group cms-group-white">
                <div class="cms-group-content">
                <h3><span class="label label-default">{$pub->getCategoryName()|default:"???"}</span> {$pub->getTitle()|escape|truncate:70:'…':false}</h3>
                {if $pub->getBrief()}
                {$pub->getBrief()}
                {else}
                {$pub->getContent()}
                {/if}      

               {if !is_null($pub->getCover())}
                <div class="thumbnail-list">
                   <div class="thumbnail pull-left"><img src="{$__url->thumb($pub->getCover(), 150, 150)}" width="50" width="50" alt="" /></div>
                </div>
                {elseif count($pub->getImages()) > 0}
                    <div class="thumbnail-list">
                        {foreach $pub->getImages() as $img}
                            {if $img@iteration > 5}{break}{/if}
                            <div class="thumbnail pull-left"><img src="{$__url->thumb($img->getPath(), 150, 150)}" width="50" width="50" alt="" /></div>
                        {/foreach}
                    </div>
                {/if}
                </div>
            </div>
            {/foreach}
            {include file='backend/common_paginator.tpl.php' state=$state}
            {/if}
        {/if}
        </div>
    </div>
</div>