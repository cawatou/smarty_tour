{$__ctx->setPageTitle('Поиск отеля')}

{$filter->draw()}

{if !empty($list)}
    <div class="hotel-seach-form-result">
        <p>Результат поиска отелей:</p>

        <table width="100%" border="0">
            <tbody>
                {foreach $list as $hotel}
                    <tr>
                        <td width="40" align="center" valign="middle" style="font-size:26px;line-height:30px;">{$hotel->getId()|escape}</td>

                        <td width="150" align="left" valign="middle">
                            <a href="{$hotel->getUrl()}">{$hotel->getTitle()|upper|escape} {$hotel->getStars()|escape}</a>
                        </td>

                        <td width="100" align="center" valign="middle">{$hotel->getCountryTitle()|escape}, {$hotel->getResortTitle()|escape}</td>

                        <td width="87" align="center" valign="middle">
                            <a href="{$hotel->getUrl()}">Фотографий: {count($hotel->getImages())}</a>
                            <br />
                            <a href="{$hotel->getUrl()}">Отзывов: {count($hotel->getFeedbacks())}</a>
                        </td>

                        <td width="50" align="center" valign="middle" class="rating rating-{if $hotel->getExtendedData('total_rating') > 4.49}5{elseif $hotel->getExtendedData('total_rating') > 3.49}4{elseif $hotel->getExtendedData('total_rating') > 2.49}3{elseif $hotel->getExtendedData('total_rating') > 1.49}2{else}1{/if}">{(float)$hotel->getExtendedData('total_rating')|round:1}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>

        <br />

        {include file='frontend/include/paginator.tpl.php' state=$state}
    </div>
{else}
    {if $filter->isActive()}
        <h4>Отелей по указанным критериям не найдено</h4>
    {/if}
{/if}