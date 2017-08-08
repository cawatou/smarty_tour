<div class="container">
    {include file='backend/submenu/settings.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
        {if empty($list)}
            <div class="alert alert-info">
                Нет страниц для корректировки. Хотите <a href="{$__url->adm('.seo.add')}">добавить</a>?
            </div>
        {else}
            <div class="table-responsive">
                <table class="table table-hover table-responsive table-striped">
                    <thead>
                        <tr class="center">
                            <th>{'Страница'|t}</th>
                            <th>{'Заголовок'|t}</th>
                            <th>{'Статус'|t}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $list as $seo}
                        <tr>
                           <td><a href="{$__url->url($seo->getRequest())}" class="blank">{$seo->getRequest()|escape}</a></td>
                           <td>{if !is_null($seo->getTitle())}{$seo->getTitle()|escape}{else}&mdash;{/if}</td>
                           <td class="center">
                           {if $seo->getStatus() == 'ENABLED'}
                               <a href="{$__url->adm('.seo.status')}?seo_id={$seo->getId()|escape}" title="Сменить статус на «Отложить»" class="scrollfix">Применить</a>
                           {elseif $seo->getStatus() == 'DISABLED'}
                               <a href="{$__url->adm('.seo.status')}?seo_id={$seo->getId()|escape}" title="Сменить статус на «Применить»" class="scrollfix highlight-warning">Отложить</a>
                           {/if}
                           </td>
                           <td class="right nowrap">
                                <a href="{$__url->adm('.seo.edit')}?seo_id={$seo->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                <a href="{$__url->adm('.seo.delete')}?seo_id={$seo->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>                                                                
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