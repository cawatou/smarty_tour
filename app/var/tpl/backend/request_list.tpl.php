<div class="container">
    {include file='backend/submenu/request.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if empty($list) && !$filter->isActive()}
                <div class="alert alert-info">{'Пользователи не оставили ни одной заявки.'|t}</div>
            {else}
                {$filter->draw()}

                {if empty($list)}
                    <div class="alert alert-warning">Ничего не найдено. Вы можете <a href="{$__url->adm('.request')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.</div>
                {else}
					
                    {foreach $list as $req}
					
				
						
                        <div class="cms-group-header">
                            <div class="cms-group-actions">
                                {if $__ctx->getCurrentUser()->canEdit('.adm.request')}
                                    <a href="{$__url->adm('.request.edit')}?request_id={$req->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                {/if}

                                {if $__ctx->getCurrentUser()->canDelete('.adm.request')}
                                    <a href="{$__url->adm('.request.delete')}?request_id={$req->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                                {/if}
                            </div>

                            <div class="cms-group-status">
                                {if $req->getStatus() == 'ENABLED'}
                                    <a href="{$__url->adm('.request.status')}?request_id={$req->getId()|escape}" title="{'Сменить статус на «Обработано»'|t}" class="highlight-warning scrollfix">{'Новая заявка'|t}</a>
                                {elseif $req->getStatus() == 'DISABLED'}
                                    <a href="{$__url->adm('.request.status')}?request_id={$req->getId()|escape}" title="{'Сменить статус на «Новая заявка»'|t}" class="scrollfix">{'Обработано'|t}</a>
                                {/if}
                            </div>

                            <div class="cms-group-date">{$req->getCreated()->setDefaultTimeZone()->format('d.m.Y H:i')}</div>
                        </div>
                        <div class="cms-group{if $req->getUserId() == $__ctx->getCurrentUser()->getId()} cms-group-white{/if}">
                            <div class="row">
                                <div class="col col-md-6">
                                    <div class="panel panel-default">
                                        <table class="table table-condensed table-small table-bordered">
                                            <thead></thead>
                                            <tbody>
                                                {if $req->getType() == 'SLETAT_ORDER'}
                                                    <tr>
                                                        <td>Заголовок письма:</td>
                                                        <td>{$req->getUserName()|escape}</td>
                                                    </tr>

                                                    {if $req->getUserEmail()}
                                                        <tr>
                                                            <td>От:</td>
                                                            <td>{$req->getUserEmail()|escape}</td>
                                                        </tr>
                                                    {/if}

                                                    {if $req->getExtendedData('city') !== null}
                                                        <tr>
                                                            <td>Город:</td>
                                                            <td>{$req->getExtendedData('city')|escape}</td>
                                                        </tr>
                                                    {/if}
                                                {else}
                                                    <tr>
                                                        <td>Имя:</td>
                                                        <td>{$req->getUserName()|escape}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>IP:</td>
                                                        <td>{$req->getUserIp()|escape}</td>
                                                    </tr>

                                                    {if $req->getUserEmail() !== null}
                                                        <tr>
                                                            <td>Email:</td>
                                                            <td>
                                                                <a href="mailto:{$req->getUserEmail()|escape}">{$req->getUserEmail()|escape}</a>
                                                            </td>
                                                        </tr>
                                                    {/if}

                                                    {if $req->getUserPhone() !== null}
                                                        <tr>
                                                            <td>Тел.:</td>
                                                            <td>{$req->getUserPhone()|escape}</td>
                                                        </tr>
                                                    {/if}

                                                    {if $req->getExtendedData('city') !== null}
                                                        <tr>
                                                            <td>Город:</td>
                                                            <td>{$req->getExtendedData('city')|escape}</td>
                                                        </tr>
                                                    {/if}

                                                    {if $req->getOfficeId() !== null || $req->getExtendedData('office_other') !== null}
                                                        <tr>
                                                            <td>Офис:</td>
                                                            <td>
                                                                {if $req->getExtendedData('office_other')}
                                                                    ВНИМАНИЕ! При заказе, выбран другой город ({$req->getExtendedData('office_other')})
                                                                {elseif $req->getOffice() !== null}
                                                                    Офис: {$req->getOffice()->getTitle()}{if $req->getOffice()->getCity() !== null && $req->getOffice()->getCity()->getTitle() != $req->getOffice()->getTitle()} ({$req->getOffice()->getCity()->getTitle()}){/if}
                                                                {/if}
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                {/if}

                                                {if $req->getUser() !== null}
                                                    <tr>
                                                        <td>Обрабатывает сообщение:</td>
                                                        <td>{$req->getUser()->getName()|escape}</td>
                                                    </tr>
                                                {/if}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {if $req->getType() == 'REQUEST' || $req->getType() == 'ORDER' || $req->getType() == 'ORDER_SHORT'}
                                    <div class="col col-md-6">
                                        <div class="panel panel-default">
                                            <table class="table table-condensed table-small table-bordered">
                                                <thead></thead>
                                                <tbody>
                                                {if $req->getType() == 'REQUEST' || $req->getType() == 'ORDER' || $req->getType() == 'ORDER_SHORT'}
                                                    {if $req->getExtendedData('country')}<tr><td>Страна/курорт:</td><td>{$req->getExtendedData('country')|escape}</td></tr>{/if}
                                                    {if $req->getExtendedData('price')}
                                                        <tr>
                                                            <td>Цена:</td>
                                                            <td>
                                                                {if is_numeric($req->getExtendedData('price'))}
                                                                    {$req->getExtendedData('price')|price_format:true} р.
                                                                {else}
                                                                    {$req->getExtendedData('price')|escape}
                                                                {/if}

                                                                {if $req->getExtendedData('get_via_price') > 0}
                                                                    + {$req->getExtendedData('get_via_price')|price_format:false} р. (способ доставки «{$req->getExtendedData('get_via_title')|escape}»)
                                                                {/if}
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                    {if $req->getExtendedData('adults')}<tr><td>Взрослых:</td><td>{$req->getExtendedData('adults')|escape}</td></tr>{/if}

                                                    {if $req->getExtendedData('children')}
                                                        <tr>
                                                            <td>Детей:</td>
                                                            <td>
                                                                {$req->getExtendedData('children')|escape}

                                                                {if $req->getExtendedData('children_age_from') || $req->getExtendedData('children_age_to')}
                                                                    ({if $req->getExtendedData('children_age_from')}с {$req->getExtendedData('children_age_from')|escape}{/if}

                                                                    {if $req->getExtendedData('children_age_to')}до {$req->getExtendedData('children_age_to')|escape}{/if})
                                                                {/if}
                                                            </td>
                                                        </tr>
                                                    {/if}

                                                    {if $req->getExtendedData('date_begin')}<tr><td>Вылет с:</td><td>{$req->getExtendedData('date_begin')|escape}</td></tr>{/if}
                                                    {if $req->getExtendedData('date_end')}<tr><td>Вылет по:</td><td>{$req->getExtendedData('date_end')|escape}</td></tr>{/if}
                                                    {if $req->getExtendedData('daynum')}<tr><td>Дней/ночей:</td><td>{$req->getExtendedData('daynum')|escape}</td></tr>{/if}
                                                    {if $req->getExtendedData('flyaway')}<tr><td>Вылет из:</td><td>{$req->getExtendedData('flyaway')|escape}</td></tr>{/if}
                                                    {if $req->getExtendedData('product')}<tr><td>Тур:</td><td><a href="{$__url->adm('.product.edit')}?product_id={(int)$req->getExtendedData('product')}">{$req->getExtendedData('country')|escape} {$req->getExtendedData('flyaway')|escape}</a></td></tr>{/if}
                                                    {if $req->getExtendedData('hotel_name')}<tr><td>Название отеля:</td><td>{$req->getExtendedData('hotel_name')|escape}</td></tr>{/if}
                                                    {if $req->getExtendedData('hotel_stars')}<tr><td>Тип отеля:</td><td>{$req->getExtendedData('hotel_stars')|escape}</td></tr>{/if}
                                                {/if}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                {/if}
                            </div>

                            <div class="cms-group-content">
                                <h3><span class="label label-default">{$types[$req->getType()]|default:"???"}</span></h3>

                                {if $req->getMessage() !== null}
                                    <blockquote>
                                        {if $req->getType() == 'SLETAT_ORDER'}
                                            <p>
											{assign var="result" value="---"|explode:$req->getMessage()} {* Разбор строки на элементы по разделителю. *}
												{if $result.0 != ""}
													{$result.0|escape|nl2br} {* Первый элемент. *}
												{else}
													Error parse!
												{/if}
											</p>
                                        {else}
                                            <p>{$req->getMessage()|escape|nl2br}</p>
                                        {/if}
                                    </blockquote>
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