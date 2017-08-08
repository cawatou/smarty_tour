<div class="container">
    {include file='backend/submenu/book.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if empty($list) && !$filter->isActive()}
                <div class="alert alert-info">Вы еще не добавили ни одного отеля. Хотите <a href="{$__url->url('.adm.hotel.add', true)}">добавить</a>?</div>
            {else}
                {$filter->draw()}

                {if empty($list)}
                    <div class="cmsAlert">Ничего не найдено. Вы можете <a href="{$__url->adm('.hotel')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.</div>
                {else}
                    <form action="{$__ctx->getData('uri')}" method="post">
                        <div class="table-responsive">
                            <table class="table table-hover table-responsive table-striped table-condensed">
                                <thead>
                                    <tr class="center">
                                        <th>Название</th>
                                        <th>Классификация</th>
                                        <th>Описание на Sletat.ru</th>
                                        <th>Страна</th>
                                        <th>Курорт</th>
                                        <th>Статус</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach $list as $hotel}
                                        <tr>
                                            <td>
                                                {$hotel->getTitle()|escape}
                                            </td>

                                            <td>
                                                {if $hotel->getStars() === null}
                                                    &mdash;
                                                {else}
                                                    {$hotel->getStars()|escape}
                                                {/if}
                                            </td>

                                            <td class="center small middle">
                                                {if $hotel->getExternalId() !== null}
                                                    Есть
                                                {else}
                                                    Нет
                                                {/if}
                                            </td>

                                            <td class="center middle">
                                                {if $hotel->getCountryTitle() !== null}
                                                    <a href="{$__url->adm('.country.edit')}?country_id={$hotel->getCountryId()|escape}">
                                                        {$hotel->getCountryTitle()|escape}
                                                    </a>
                                                {else}
                                                    &mdash;
                                                {/if}
                                            </td>

                                            <td class="center middle">
                                                {if $hotel->getResortTitle() !== null}
                                                    <a href="{$__url->adm('.resort.edit')}?resort_id={$hotel->getResortId()|escape}">
                                                        {$hotel->getResortTitle()|escape}
                                                    </a>
                                                {else}
                                                    &mdash;
                                                {/if}
                                            </td>

                                            <td class="center middle">
                                                {if $hotel->getStatus() === 'ENABLED'}
                                                    <a href="{$__url->adm('.hotel.status')}?hotel_id={$hotel->getId()|escape}" title="Сменить статус на «Скрывать»" class="scrollfix">Показывать</a>
                                                {elseif $hotel->getStatus() === 'DISABLED'}
                                                    <a href="{$__url->adm('.hotel.status')}?hotel_id={$hotel->getId()|escape}" title="Сменить статус на «Показывать»" class="scrollfix highlight-warning">Скрывать</a>
                                                {/if}
                                            </td>

                                            <td class="right nowrap middle">
                                                <a href="{$__url->adm('.hotel.edit')}?hotel_id={$hotel->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                                <a href="{$__url->adm('.hotel.delete')}?hotel_id={$hotel->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
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