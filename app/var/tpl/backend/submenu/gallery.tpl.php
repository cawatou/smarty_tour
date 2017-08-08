{$__ctx->setPageTitle({'Галереи'|t})}
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
            <a href="{$__url->adm('.gallery.image.list')}" class="{if $group == 'gallery_image' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Картинки'|t}</a>
            <a href="{$__url->adm('.gallery.image.add')}" class="{if $group == 'gallery_image' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
            {if $group == 'gallery_image' && $op == 'edit'}
                <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
            {/if}
        </div>
        <div class="btn-group">
            <a href="{$__url->adm('.gallery.category.list')}" class="{if $group == 'gallery_category' && $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Галереи'|t}</a>
            <a href="{$__url->adm('.gallery.category.add')}" class="{if $group == 'gallery_category' && $op == 'add'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить'|t}</a>
            {if $group == 'gallery_category' && $op == 'edit'}
                <a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Редактировать'|t}</a>
            {/if}
        </div>
    </div>
</div>