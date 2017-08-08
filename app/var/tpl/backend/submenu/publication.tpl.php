{$__ctx->setPageTitle({'Публикации'|t})}
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
            <a href="{$__url->adm('.publication.list')}" class="{if $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Список'|t}</a>
            <div class="btn-group">
                <a href="{$__url->adm('.publication.add')}" class="{if $op == 'add'}btn btn-primary active{else}btn btn-default{/if} dropdown-toggle" data-toggle="dropdown">{'Добавить'|t} <span class="caret"></span></a>
                <ul class="dropdown-menu">
                {foreach DomainObjectModel_Publication::getCategories() as $k => $i}
                <li><a href="{$__url->adm('.publication.add')}?category={$k}">{$i.title|escape}</a></li>
                {/foreach}
                </ul>
            </div>
            {if $op == 'edit'}<a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>{/if}
        </div>
    </div>
</div>

