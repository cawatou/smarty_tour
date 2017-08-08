{$__ctx->setPageTitle({'Страницы'|t})}
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
            <a href="{$__url->adm('.page.list')}" class="{if $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Структура'|t}</a>
            <a href="{$__url->adm('.page.add')}" class="{if $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
        {if $op == 'edit'}
            <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
        {/if}
        </div>
    </div>
</div>