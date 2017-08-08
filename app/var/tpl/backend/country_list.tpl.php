<div class="container">
    {include file='backend/submenu/book.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if empty($list) && !$filter->isActive()}
                <div class="alert alert-info">Вы еще не добавили ни одной страны. Хотите <a href="{$__url->url('.adm.country.add', true)}">добавить</a>?</div>
            {else}
                {$filter->draw()}

                {if empty($list)}
                    <div class="alert alert-warning">Ничего не найдено. Вы можете <a href="{$__url->adm('.country')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.</div>
                {else}
                    <form action="{$__ctx->getData('uri')}" method="post">
                        <div class="table-responsive">
                            <table class="table table-hover table-responsive table-striped table-condensed">
                                <thead>
                                    <tr class="center">
                                        <th>Название</th>
                                        <th>Alias</th>
                                        <th>Галерея</th>
                                        <th>Статус</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach $list as $country}
                                        <tr>
                                            <td class="middle">
                                                {$country->getTitle()|escape}
                                            </td>

                                            <td class="middle">
                                                {if $country->getAlias() !== null}{$country->getAlias()}{else}&mdash;{/if}
                                            </td>

                                            <td class="center middle">
                                                {if $country->getGallery() !== null}
                                                    <a href="{$__url->adm('.gallery.category.edit')}?gallery_id={$country->getGalleryId()|escape}">
                                                        {$country->getGallery()->getTitle()|escape}
                                                    </a>
                                                {else}
                                                    &mdash;
                                                {/if}
                                            </td>

                                            {if $country->getStatus() === 'ENABLED'}
                                                <td class="center middle">
                                                    <a href="{$__url->adm('.country.status')}?country_id={$country->getId()|escape}" title="Сменить статус на «Скрывать»" class="scrollfix">Показывать</a>
                                                </td>
                                            {elseif $country->getStatus() === 'DISABLED'}
                                                <td class="center middle">
                                                    <a href="{$__url->adm('.country.status')}?country_id={$country->getId()|escape}" title="Сменить статус на «Показывать»" class="scrollfix highlight-warning">Скрывать</a>
                                                </td>
                                            {/if}

                                            <td class="right nowrap middle">
                                                <a href="{$__url->adm('.country.edit')}?country_id={$country->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                                <a href="{$__url->adm('.country.delete')}?country_id={$country->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </form>

                    {include file='backend/common_paginator.tpl.php' state=$state}
                {/if}
            {/if}
        </div>
    </div>
</div>