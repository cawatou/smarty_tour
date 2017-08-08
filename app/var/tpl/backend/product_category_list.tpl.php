<div class="container">
{include file='backend/submenu/product.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
        {if empty($tree) || count($tree) == 1}
            <div class="alert alert-info">Вы еще не добавили ни одной категории. Хотите <a href="{$__url->adm('.product.category.add')}">добавить</a>?</div>
        {else}
        <div class="cms-tree cms-tree-root">
            {assign var='current_level' value=0}
            {assign var='disabled_product_category' value=0}

            {foreach $tree as $k => $pc}
                {if !$pc@first}
                    {if $current_level < $pc.level}<div class="cms-tree">
                        {elseif $current_level == $pc.level}</div><!-- /.cms-node -->
                        {elseif $current_level > $pc.level}{assign var='r' value=$current_level-$pc.level}{"</div><!-- /.cms-node --></div><!-- /.cms-tree -->"|str_repeat:$r}</div><!-- /.cms-node -->{/if}
                {/if}

                {if $pc.product_category_status == 'DISABLED' && (!$disabled_product_category || $pc.level <= $disabled_product_category.level)}
                    {assign var="disabled_product_category" value=$pc}
                {/if}

            <div class="cms-node{if $pc.lft == 1} cms-node-root{/if}">
                <div class="cms-node-content">
                    {if $pc.lft == 1}
                        <strong>{$pc.product_category_title|escape}</strong>
                        {else}
                        <a href="{$__url->adm('.product.category.edit')}?product_category_id={$pc.product_category_id|escape}" {if $disabled_product_category && ($pc.product_category_id == $disabled_product_category.product_category_id || $pc.level > $disabled_product_category.level && $pc.rgt < $disabled_product_category.rgt)}class="cms-node-disabled"{/if}>{$pc.product_category_title|escape}</a>
                        <span class="cms-node-content-buttons">
                            {if $pc.prev_sibling}
                                <a href="{$__url->adm('.product.category.order')}?product_category_id={$pc.product_category_id|escape}&amp;up=1" class="btn btn-xs btn-primary scrollfix" title="{'Переместить выше'|t}"><i class="fa fa-arrow-up"></i></a>
                            {/if}
                            {if $pc.next_sibling}
                                <a href="{$__url->adm('.product.category.order')}?product_category_id={$pc.product_category_id|escape}&amp;down=1" class="btn btn-xs btn-primary scrollfix" title="{'Переместить ниже'|t}"><i class="fa fa-arrow-down"></i></a>
                            {/if}
                            {if $pc.product_category_contains_products}
                                <a href="{$__url->adm('.product.list')}?{Form_Filter::encodeSearchName('fp', 'product_category_id')}={$pc.product_category_id|escape}" class="btn btn-xs btn-primary" title="Просмотреть товары в этой категории"><i class="fa fa-list"></i></a>
                                <a href="{$__url->adm('.product.add')}?product_category_id={$pc.product_category_id|escape}" class="btn btn-xs btn-success" title="Добавить товар"><i class="fa fa-file-o"></i></a>
                            {/if}
                            <a href="{$__url->adm('.product.category.add')}?parent_id={$pc.product_category_id|escape}" class="btn btn-xs btn-success" title="Добавить вложенную категорию"><i class="fa fa-plus"></i></a>
                            <a href="{$__url->adm('.product.category.edit')}?product_category_id={$pc.product_category_id|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                            <a href="{$__url->adm('.product.category.delete')}?product_category_id={$pc.product_category_id|escape}" onclick="return confirm('Вы уверены, что хотите удалить категорию и все товары из неё?')" class="btn btn-xs btn-danger scrollfix" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                        </span>
                        <span class="cms-node-content-title">{$pc.product_category_alias|escape}</span>
                    {/if}
                </div>

                {assign var='current_level' value=$pc.level}
                {if $pc@last}
                    {assign var='r' value=$current_level-1}{"</div><!-- /.cms-node --></div><!-- /.cms-tree -->"|str_repeat:$r}</div><!-- /.cms-node -->
                {/if}
            {/foreach}
        </div></div><!-- /.cms-tree -->            
        {/if}
        </div>
    </div>
</div>
{*
<div class="cmsContainer">
    <div class="cmsBlock span10">
    {include file='backend/submenu/product.tpl.php'}

{if count($product_categories_tree) == 1}
    <div class="cmsAlert">Вы еще не добавили ни одной категории. Хотите <a href="{$__url->adm('.product.category.add')}">добавить</a>?</div>
{else}
<div class="cms-tree cms-tree-root">
    {assign var='current_level' value=0}
    {assign var='disabled_product_category' value=0}

    {foreach $product_categories_tree as $k => $pc}
        {if !$pc@first}
            {if $current_level < $pc.level}<div class="cms-tree">
                {elseif $current_level == $pc.level}</div><!-- /.cms-node -->
                {elseif $current_level > $pc.level}{assign var='r' value=$current_level-$pc.level}{"</div><!-- /.cms-node --></div><!-- /.cms-tree -->"|str_repeat:$r}</div><!-- /.cms-node -->{/if}
        {/if}

        {if $pc.product_category_status == 'DISABLED' && (!$disabled_product_category || $pc.level <= $disabled_product_category.level)}
            {assign var="disabled_product_category" value=$pc}
        {/if}

    <div class="cms-node{if $pc.lft == 1} cms-node-root{/if}">
        <div class="cms-node-content">
            {if $pc.lft == 1}
                <strong>{$pc.product_category_title|escape}</strong>
                {else}
                <a href="{$__url->adm('.product.category.edit')}?product_category_id={$pc.product_category_id|escape}" {if $disabled_product_category && ($pc.product_category_id == $disabled_product_category.product_category_id || $pc.level > $disabled_product_category.level && $pc.rgt < $disabled_product_category.rgt)}class="cms-node-disabled"{/if}>{$pc.product_category_title|escape}</a>
                <span class="cms-node-content-buttons">
                    {if $pc.prev_sibling}
                        <a href="{$__url->adm('.product.category.order')}?product_category_id={$pc.product_category_id|escape}&amp;up=1" class="cmsOp action scrollfix" title="Переместить выше"><i class="icon-arrow-up icon-white"></i></a>
                    {/if}
                    {if $pc.next_sibling}
                        <a href="{$__url->adm('.product.category.order')}?product_category_id={$pc.product_category_id|escape}&amp;down=1" class="cmsOp action scrollfix" title="Переместить ниже"><i class="icon-arrow-down icon-white"></i></a>
                    {/if}
                    {if $pc.product_category_contains_products}
                    <a href="{$__url->adm('.product.list')}?{Form_Filter::encodeSearchName('fp', 'product_category_id')}={$pc.product_category_id|escape}" class="cmsOp action" title="Просмотреть товары в этой категории"><i class="icon-list-alt icon-white"></i></a>
                    <a href="{$__url->adm('.product.add')}?product_category_id={$pc.product_category_id|escape}" class="cmsOp add" title="Добавить товар"><i class="icon-file icon-white"></i></a>
                    {/if}
                    <a href="{$__url->adm('.product.category.add')}?parent_id={$pc.product_category_id|escape}" class="cmsOp add" title="Добавить вложенную категорию"><i class="icon-plus icon-white"></i></a>
                    <a href="{$__url->adm('.product.category.edit')}?product_category_id={$pc.product_category_id|escape}" class="cmsOp edit" title="Редактировать"><i class="icon-pencil icon-white"></i></a>
                    <a href="{$__url->adm('.product.category.delete')}?product_category_id={$pc.product_category_id|escape}" onclick="return confirm('Вы уверены, что хотите удалить категорию и все товары из неё?')" class="cmsOp remove scrollfix" title="Удалить"><i class="icon-remove icon-white"></i></a>
                </span>
                <span class="cms-node-content-title">{$pc.product_category_alias|escape}</span>
            {/if}
        </div>

        {assign var='current_level' value=$pc.level}
        {if $pc@last}
            {assign var='r' value=$current_level-1}{"</div><!-- /.cms-node --></div><!-- /.cms-tree -->"|str_repeat:$r}</div><!-- /.cms-node -->
        {/if}
    {/foreach}
</div></div><!-- /.cms-tree -->
{/if}
</div>
</div>
*}