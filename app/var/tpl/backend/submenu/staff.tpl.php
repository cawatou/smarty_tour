{$__ctx->setPageTitle({'Сотрудники'|t})}
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
            {if $__ctx->getCurrentUser()->canView('.adm.staff')}
                <a href="{$__url->adm('.staff.list')}" class="{if $group == 'staff' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Список'|t}</a>
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
        <div class="btn-group">
            {if $__ctx->getCurrentUser()->canView('.adm.staff.category')}
                <a href="{$__url->adm('.staff.category.list')}" class="{if $group == 'staff_category' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Отделы'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canCreate('.adm.staff.category')}
                <a href="{$__url->adm('.staff.category.add')}" class="{if $group == 'staff_category' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canEdit('.adm.staff.category')}
                {if $group == 'staff_category' && $op == 'edit'}
                    <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
                {/if}
            {/if}
        </div>
    </div>
</div>