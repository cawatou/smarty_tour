{$__ctx->setPageTitle({'Заявки'|t})}
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
            {if $__ctx->getCurrentUser()->canView('.adm.request')}
                <a href="{$__url->adm('.request.list')}" class="{if $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Список'|t}</a>
            {/if}

            {*
                {if $__ctx->getCurrentUser()->canCreate('.adm.request')}
                    <a href="{$__url->adm('.request.add')}" class="{if $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
                {/if}
            *}

            {if $__ctx->getCurrentUser()->canEdit('.adm.request')}
                {if $op == 'edit'}
                    <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
                {/if}
            {/if}
        </div>
    </div>
</div>