{$__ctx->addCss('/backend/product.css')}

<div class="container">
    {include file='backend/submenu/product.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if empty($ads)}
                <div class="alert alert-info">Рекламных ссылок ещё нет</div>
            {else}
                <form action="{$__ctx->getData('uri')}" method="post">
                    <div class="table-responsive">
                        <table class="table table-hover table-responsive table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th class="left">Название</th>
                                    <th>Отправление</th>
                                    <th>Страна</th>

                                    {if $__ctx->getCurrentUser()->canEdit('.adm.product.ads') || $__ctx->getCurrentUser()->canDelete('.adm.product.ads')}
                                        <th></th>
                                    {/if}
                                </tr>
                            </thead>

                            <tbody>
                                {foreach $ads as $product}
                                    <tr>
                                        <td class="middle">
                                            <a href="{$product->getAdsUrl()}">
                                                {$product->getAdsUrl()}
                                            </a>
                                        </td>

                                        <td class="middle">
                                            {if $product->getFromId()}
                                                {$product->getFrom('title_from')}
                                            {else}
                                                &mdash;
                                            {/if}
                                        </td>

                                        <td class="middle">
                                            {$product->getCountry()->getTitle()|escape}
                                        </td>

                                        <td class="right nowrap middle">
                                            {if $__ctx->getCurrentUser()->canEdit('.adm.product')}
                                                <a href="{$__url->adm('.product.edit')}?product_id={$product->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                            {/if}

                                            {if false && $__ctx->getCurrentUser()->canDelete('.adm.product.ads')}
                                                <a href="{$__url->adm('.product.ads')}?type=remove&product_id={$product->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('Вы уверены?');" title="Удалить"><i class="fa fa-trash-o"></i></a>
                                            {/if}
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" name="__change" class="btn btn-primary">Внести изменения</button>
                            </div>
                        </div>
                    </div>
                </form>
            {/if}
        </div>
    </div>
</div>