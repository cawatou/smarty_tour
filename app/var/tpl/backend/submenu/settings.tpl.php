{$__ctx->setPageTitle({'Настройки'|t})}

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
            <a href="{$__url->adm('.settings')}" class="{if $group == 'settings' &&  $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Настройки'|t}</a>
        </div>

        <div class="btn-group">
            {if $__ctx->getCurrentUser()->canView('.adm.seo')}
                <a href="{$__url->adm('.seo.list')}" class="{if $group == 'seo' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'SEO корректировки'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canCreate('.adm.seo')}
                <a href="{$__url->adm('.seo.add')}" class="{if $group == 'seo' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canEdit('.adm.seo')}
                {if $group == 'seo' && $op == 'edit'}
                    <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
                {/if}
            {/if}
        </div>
    </div>
</div>