{$__ctx->addCss('/backend/product.css')}

<div class="container">
    {include file='backend/submenu/product.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if empty($list) && !$filter->isActive()}
                <div class="alert alert-info">Вы еще не добавили ни одного тура. Хотите <a href="{$__url->url('.adm.product.add', true)}">добавить</a>?</div>
            {else}
                {$filter->draw()}

                {if empty($list)}
                    <div class="alert alert-warning">Ничего не найдено. Вы можете <a href="{$__url->adm('.product')}?{$filter->encode(Form_Filter::FILTER_CLEAR)}=1">сбросить</a> фильтр поиска.</div>
                {else}
                    <form action="{$__ctx->getData('uri')}" method="post">
                        <div class="table-responsive">
                            <table class="table table-hover table-responsive table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th class="left">Название</th>
                                        <th>Отправление</th>
                                        <th>Страна/Курорт</th>
                                        <th>Даты</th>
                                        <th class="right">Цена</th>
                                        <th>Туроператор</th>
                                        <th class="center">Статус</th>

                                        {if $__ctx->getCurrentUser()->canEdit('.adm.product')}
                                            <th class="center">Сортировка</th>
                                        {/if}

                                        {if $__ctx->getCurrentUser()->canEdit('.adm.product') || $__ctx->getCurrentUser()->canDelete('.adm.product')}
                                            <th></th>
                                        {/if}
                                    </tr>
                                </thead>

                                <tbody>
                                    {foreach $list as $m}
                                        {include file='backend/include/product_list_item.tpl.php' product=$m}
                                        {if count($m->getLinkedProducts()) > 0}
                                            {foreach $m->getLinkedProducts() as $_m}
                                                {include file='backend/include/product_list_item.tpl.php' product=$_m}
                                            {/foreach}
                                        {/if}
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" name="__change" class="btn btn-primary">{'Внести изменения'|t}</button>
                                </div>
                            </div>
                        </div>
                    </form>

                {include file='backend/common_paginator.tpl.php' state=$state}
                {/if}
            {/if}
        </div>
    </div>
</div>