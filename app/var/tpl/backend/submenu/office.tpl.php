{if $group === 'city'}
    {$__ctx->setPageTitle({'Города'|t})}
{elseif $group === 'subdivision'}
    {$__ctx->setPageTitle({'Подразделения'|t})}
{elseif $group === 'office'}
    {$__ctx->setPageTitle({'Офисы'|t})}
{else}
    {$__ctx->setPageTitle({'Сотрудники'|t})}
{/if}

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
            {if $__ctx->getCurrentUser()->canView('.adm.city')}
                <a href="{$__url->adm('.city.list')}" class="{if $group == 'city' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Города'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canCreate('.adm.city')}
                <a href="{$__url->adm('.city.add')}" class="{if $group == 'city' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canEdit('.adm.city')}
                {if $group == 'city' && $op == 'edit'}
                    <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
                {/if}
            {/if}
        </div>

        <div class="btn-group">
            {if $__ctx->getCurrentUser()->canView('.adm.subdivision')}
                <a href="{$__url->adm('.subdivision.list')}" class="{if $group == 'subdivision' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Подразделения'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canCreate('.adm.subdivision')}
                <a href="{$__url->adm('.subdivision.add')}" class="{if $group == 'subdivision' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canEdit('.adm.subdivision')}
                {if $group == 'subdivision' && $op == 'edit'}
                    <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
                {/if}
            {/if}
        </div>

        <div class="btn-group">
            {if $__ctx->getCurrentUser()->canView('.adm.office')}
                <a href="{$__url->adm('.office.list')}" class="{if $group == 'office' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Офисы'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canCreate('.adm.office')}
                <a href="{$__url->adm('.office.add')}" class="{if $group == 'office' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canEdit('.adm.office')}
                {if $group == 'office' && $op == 'edit'}
                    <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
                {/if}
            {/if}
        </div>

        <div class="btn-group">
            {if $__ctx->getCurrentUser()->canView('.adm.staff')}
                <a href="{$__url->adm('.staff.list')}" class="{if $group == 'staff' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Сотрудники'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canCreate('.adm.staff')}
                <a href="{$__url->adm('.staff.add')}" class="{if $group == 'staff' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canEdit('.adm.staff')}
                {if $group == 'staff' && $op == 'edit'}
                    <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
                {/if}
            {/if}
        </div>
    </div>
</div>