{$__ctx->setPageTitle('Справочники')}

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
            {if $__ctx->getCurrentUser()->canView('.adm.touroperator')}
                <a href="{$__url->adm('.touroperator.list')}" class="{if $group == 'touroperator' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">Туроператор</a>
            {/if}

            {if $__ctx->getCurrentUser()->canCreate('.adm.touroperator')}
                <a href="{$__url->adm('.touroperator.add')}" class="{if $group == 'touroperator' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">Добавить</a>
            {/if}

            {if $__ctx->getCurrentUser()->canEdit('.adm.touroperator')}
                {if $group == 'touroperator' && $op == 'edit'}
                    <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">Редактировать</a>
                {/if}
            {/if}
        </div>

        <div class="btn-group">
            {if $__ctx->getCurrentUser()->canView('.adm.country')}
                <a href="{$__url->adm('.country.list')}" class="{if $group == 'country' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Страны'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canCreate('.adm.country')}
                <a href="{$__url->adm('.country.add')}" class="{if $group == 'country' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canEdit('.adm.country')}
                {if $group == 'country' && $op == 'edit'}
                    <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
                {/if}
            {/if}
        </div>

        <div class="btn-group">
            {if $__ctx->getCurrentUser()->canView('.adm.resort')}
                <a href="{$__url->adm('.resort.list')}" class="{if $group == 'resort' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Курорты'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canCreate('.adm.resort')}
                <a href="{$__url->adm('.resort.add')}" class="{if $group == 'resort' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canEdit('.adm.resort')}
                {if $group == 'resort' && $op == 'edit'}
                    <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
                {/if}
            {/if}
        </div>

        <div class="btn-group">
            {if $__ctx->getCurrentUser()->canView('.adm.hotel')}
                <a href="{$__url->adm('.hotel.list')}" class="{if $group == 'hotel' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Отели'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canCreate('.adm.hotel')}
                <a href="{$__url->adm('.hotel.add')}" class="{if $group == 'hotel' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
            {/if}

            {if $__ctx->getCurrentUser()->canEdit('.adm.hotel')}
                {if $group == 'hotel' && $op == 'edit'}
                    <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
                {/if}
            {/if}
        </div>
    </div>
</div>