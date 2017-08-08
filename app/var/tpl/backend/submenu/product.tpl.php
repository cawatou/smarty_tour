{$__ctx->setPageTitle({'Горящие туры'|t})}

<div class="row">
    <div class="col col-sm-12">
        <div class="page-header">
            <h2>{$__ctx->getPageTitle()|escape}</h2>
        </div>
    </div>
</div>
<div class="row">
    <div class="col col-sm-12">
        <div class="btn-group">
            {if $__ctx->getCurrentUser()->canView('.adm.product')}
                <a href="{$__url->adm('.product.list')}" class="{if $group == 'product' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">Список</a>
            {/if}

            {if $__ctx->getCurrentUser()->canCreate('.adm.product')}
                <a href="{$__url->adm('.product.add')}" class="{if $group == 'product' && ($op == 'add' || $op == 'copy')}btn btn-primary active{else}btn btn-default{/if}">Добавить</a>
            {/if}

            {if $__ctx->getCurrentUser()->canEdit('.adm.product')}
                {if $group == 'product' && $op == 'edit'}
                    <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">Редактировать</a>
                {/if}
            {/if}
        </div>

        {if $__ctx->getCurrentUser()->canEdit('.adm.product.froms')}
            <div class="btn-group">
                <a href="{$__url->adm('.product.froms')}" class="{if $group == 'product' && $op == 'froms'}btn btn-primary active{else}btn btn-default{/if}">Даты обновления</a>
            </div>
        {/if}

        {if $__ctx->getCurrentUser()->canView('.adm.product.discounts') || $__ctx->getCurrentUser()->canView('.adm.product.promoprice')}
            <div class="btn-group">
                {if $__ctx->getCurrentUser()->canView('.adm.product.discounts')}
                    <a href="{$__url->adm('.product.discounts')}" class="{if $group == 'product' && $op == 'discounts'}btn btn-primary active{else}btn btn-default{/if}">Скидки</a>
                {/if}

                {if $__ctx->getCurrentUser()->canView('.adm.product.promoprice')}
                    <a href="{$__url->adm('.product.promoprice')}" class="{if $group == 'product' && $op == 'promoprice'}btn btn-primary active{else}btn btn-default{/if}">Промо цена</a>
                {/if}
            </div>
        {/if}

        {if $__ctx->getCurrentUser()->canEdit('.adm.product.ads')}
            <div class="btn-group">
                <a href="{$__url->adm('.product.ads')}" class="{if $group == 'product' && $op == 'ads'}btn btn-primary active{else}btn btn-default{/if}">Доступные рекламные ссылки</a>
            </div>
        {/if}

        <div class="btn-group hidden">
            <a href="{$__url->adm('.product.category.list')}" class="{if $group == 'product_category' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">Категории</a>
            <a href="{$__url->adm('.product.category.add')}" class="{if $group == 'product_category' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">Добавить</a>

            {if $group == 'product_category' && $op == 'edit'}
                <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">Редактировать</a>
            {/if}
        </div>
    </div>
</div>