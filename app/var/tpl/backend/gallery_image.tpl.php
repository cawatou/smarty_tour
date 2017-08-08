<div class="container">
    {include file='backend/submenu/gallery.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
        {if empty($list) && !$filter->isActive()}
            <div class="alert alert-info">
                Вы еще не добавили ни одной галереи. Хотите <a href="{$__url->adm('.gallery.category.add')}">добавить</a>?
            </div>
        {else}
            {$filter->draw()}
            {if empty($list)}
                <div class="alert alert-warning">
                    Ничего не найдено. Вы можете <a href="{$__url->adm('.gallery.image')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.
                </div>
            {else}
            <div class="table-responsive">
                <table class="table table-hover table-responsive table-striped">
                    <thead>
                    <tr class="center">
                        <th width="60">{'Картинка'|t}</th>
                        <th>{'Название'|t}</th>
                        <th>{'Галерея'|t}</th>
                        <th>{'Добавлено'|t}</th>
                        <th>{'Статус'|t}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        {foreach $list as $image}
                        <tr>
                            <td class="center middle">
                                <a href="{$__url->adm('.gallery.image.edit')}?gallery_image_id={$image->getId()|escape}"><img src="{$__url->thumb($image->getPath(), 150, 150)}" width="50" height="50" alt="{$image->getTitle()|escape}"></a>
                            </td>
                            <td class="middle">
                                {$image->getTitle()|escape|default:"&mdash;"}
                            </td>
                            <td class="center middle">
                                <a href="{$__url->adm('.gallery.image')}?{Form_Filter::encodeSearchName('fgi', 'gallery_id')}={$image->getGallery()->getId()|escape}">{$image->getGallery()->getTitle()|escape}</a>
                            </td>
                            <td class="center middle">{$image->getCreated()->format('d.m.Y')}</td>
                            <td class="center middle">
                                {if $image->getStatus() == 'ENABLED'}
                                    <a href="{$__url->adm('.gallery.image.status')}?gallery_image_id={$image->getId()|escape}" title="{'Сменить статус на «Скрывать»'|t}" class="scrollfix">{'Показывать'|t}</a>
                                {elseif $image->getStatus() == 'DISABLED'}
                                    <a href="{$__url->adm('.gallery.image.status')}?gallery_image_id={$image->getId()|escape}" title="{'Сменить статус на «Показывать»'|t}" class="scrollfix highlight-warning">{'Скрывать'|t}</a>
                                {/if}
                            </td>
                            <td class="right nowrap middle">
                                {if !is_null($filter->v('gallery_id'))}
                                    {if !$image@first}
                                        <a href="{$__url->adm('.gallery.image.shift')}?gallery_image_id={$image->getId()}&amp;way=LEFT" class="btn btn-xs btn-primary scrollfix" title="{'Сдвинуть вверх'|t}"><i class="fa fa-arrow-up"></i></a>
                                    {/if}
                                    {if !$image@last}
                                        <a href="{$__url->adm('.gallery.image.shift')}?gallery_image_id={$image->getId()}&amp;way=RIGHT" class="btn btn-xs btn-primary scrollfix" title="{'Сдвинуть вниз'|t}"><i class="fa fa-arrow-down"></i></a>
                                    {/if}
                                {/if}
                                <a href="{$__url->adm('.gallery.image.edit')}?gallery_image_id={$image->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                <a href="{$__url->adm('.gallery.image.delete')}?gallery_image_id={$image->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
                {*
                <div class="row">
                    {foreach $list as $image}
                        <div class="col col-md-3">
                            <div class="cms-group-header">
                                <div class="cms-group-actions">
                                    {if $image->getStatus() == 'ENABLED'}
                                        <a href="{$__url->adm('.gallery.image.status')}?gallery_image_id={$image->getId()|escape}" title="{'Сменить статус на «Скрывать»'|t}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>
                                    {elseif $image->getStatus() == 'DISABLED'}
                                        <a href="{$__url->adm('.gallery.image.status')}?gallery_image_id={$image->getId()|escape}" title="{'Сменить статус на «Показывать»'|t}" class="btn btn-xs btn-default"><i class="fa fa-eye-slash"></i></a>
                                    {/if}
                                    <a href="{$__url->adm('.gallery.image.edit')}?gallery_image_id={$image->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                    <a href="{$__url->adm('.gallery.image.delete')}?gallery_image_id={$image->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                                </div>
                            </div>
                            <div class="cms-group cms-group-white">
                            <img src="{$__url->thumb($image->getPath(), 150, 150)}" width="100" height="100" alt="{$image->getTitle()|escape}">
                            </div>
                        </div>
                    {/foreach}
                </div>
                *}
                {include file='backend/common_paginator.tpl.php' state=$state}
            {/if}
        {/if}
        </div>
    </div>
</div>
{*$__ctx->addCss('/backend/gallery.css')
<div class="cmsContainer">
    <div class="cmsBlock span10">
    {include file='backend/submenu/gallery.tpl.php'}

    {if empty($list) && !$filter->isActive()}
        <div class="cmsAlert">Вы еще не добавили ни одной картинки. Хотите <a href="{$__url->adm('.gallery.image.add')}">добавить</a>?</div>
    {else}
        {$filter->draw()}

        {if empty($list)}
            <div class="cmsAlert">Ничего не найдено. Вы можете <a href="{$__url->adm('.gallery.image')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.</div>
        {else}
            <ul class="cmsBoxImageList">
                {foreach $list as $image}
                <li class="cmsBox">
                    {if $image->getStatus() == 'ENABLED'}
                        <div class="cmsBox-status cmsBox-status-enabled">
                            <a href="{$__url->adm('.gallery.image.status')}?gallery_image_id={$image->getId()|escape}" title="Сменить статус на «Скрывать»" class="scrollfix">Показывать</a>
                        </div>
                        {elseif $image->getStatus() == 'DISABLED'}
                        <div class="cmsBox-status cmsBox-status-disabled">
                            <a href="{$__url->adm('.gallery.image.status')}?gallery_image_id={$image->getId()|escape}" title="Сменить статус на «Показывать»" class="scrollfix">Скрывать</a>
                        </div>
                    {/if}

                    <div class="cmsBox-actions">
                        {if !is_null($filter->v('gallery_id'))}
                            {if !$image@first}
                                <a href="{$__url->adm('.gallery.image.shift')}?gallery_image_id={$image->getId()}&amp;way=LEFT" class="cmsOp action scrollfix" title="Сдвинуть влево"><i class="icon-white icon-arrow-left"></i></a>
                            {/if}
                            {if !$image@last}
                                <a href="{$__url->adm('.gallery.image.shift')}?gallery_image_id={$image->getId()}&amp;way=RIGHT" class="cmsOp action scrollfix" title="Сдвинуть вправо"><i class="icon-white icon-arrow-right"></i></a>
                            {/if}
                        {/if}
                        <a href="{$__url->adm('.gallery.image.edit')}?gallery_image_id={$image->getId()|escape}" class="cmsOp edit"><i class="icon-pencil icon-white" title="Редактировать"></i></a>
                        <a href="{$__url->adm('.gallery.image.delete')}?gallery_image_id={$image->getId()|escape}" onclick="return confirm('Вы уверены?');" class="cmsOp remove scrollfix" title="Удалить"><i class="icon-remove icon-white"></i></a>
                    </div>
                    <img src="{$__url->thumb($image->getPath(), 100, 100)}" alt="{$image->getTitle()|escape}" title="{$image->getTitle()|escape}" />
                    <p>
                        <a href="{$__url->adm('.gallery.image')}?{Form_Filter::encodeSearchName('fgi', 'gallery_id')}={$image->getGallery()->getId()|escape}" class="truncate" title="{$image->getGallery()->getTitle()|escape}">{$image->getGallery()->getTitle()|escape}</a>
                        <span class="truncate" title="{$image->getTitle()|escape}">{$image->getTitle()|escape|truncate:13:"…":true|default:"&nbsp;"}</span>
                    </p>
                </li>
                {/foreach}
            </ul>
            {include file='backend/common_paginator.tpl.php' state=$state}
        {/if}
    {/if}
    </div>
</div>
*}