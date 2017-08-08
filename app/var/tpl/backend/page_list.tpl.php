<div class="container">
{include file='backend/submenu/page.tpl.php'}
    <div class="row cms-body-content">
    <div class="col col-md-12">
    {if empty($tree)}
        <div class="alert alert-info">Вы еще не добавили ни одной страницы. Хотите <a href="{$__url->adm('.page.add')}">добавить</a>?</div>
    {else}
    <div class="cms-tree cms-tree-root">
        {assign var='current_level' value=0}
        {assign var='disabled_page' value=0}

        {foreach $tree as $k => $p}
            {if !$p@first}
                {if $current_level < $p.level}<div class="cms-tree">
                    {elseif $current_level == $p.level}</div><!-- /.cms-node -->
                    {elseif $current_level > $p.level}{assign var='r' value=$current_level-$p.level}{"</div><!-- /.cms-node --></div><!-- /.cms-tree -->"|str_repeat:$r}</div><!-- /.cms-node -->{/if}
            {/if}

            {if $p.page_status == 'DISABLED' && (!$disabled_page || $p.level <= $disabled_page.level)}
                {assign var="disabled_page" value=$p}
            {/if}

        <div class="cms-node{if $p.lft == 1} cms-node-root{/if}">
            <div class="cms-node-content">
                {if $p.lft == 1}
                    <strong>{$p.page_title|escape}</strong>
                    {else}
                    <a href="{$__url->adm('.page.edit')}?page_id={$p.page_id|escape}" title="Путь: {$p.page_path|escape}" {if $disabled_page && ($p.page_id == $disabled_page.page_id || $p.level > $disabled_page.level && $p.rgt < $disabled_page.rgt)}class="cms-node-disabled"{/if}>{if empty($p.page_name)}{$p.page_title|truncate:"65":"…"|escape}{else}{$p.page_name|truncate:"65":"…"|escape}{/if}</a>
                <span class="cms-node-content-buttons">
                    {if $p.prev_sibling}
                        <a href="{$__url->adm('.page.order')}?page_id={$p.page_id|escape}&amp;up=1" class="cmsOp action scrollfix" title="Переместить выше"><i class="icon-arrow-up icon-white"></i></a>
                    {/if}
                    {if $p.next_sibling}
                        <a href="{$__url->adm('.page.order')}?page_id={$p.page_id|escape}&amp;down=1" class="cmsOp action scrollfix" title="Переместить ниже"><i class="icon-arrow-down icon-white"></i></a>
                    {/if}
                    <a href="{$__url->adm('.page.add')}?parent_id={$p.page_id|escape}" class="btn btn-xs btn-success" title="Добавить вложенную страницу"><i class="fa fa-plus"></i></a>
                    <a href="{$__url->adm('.page.edit')}?page_id={$p.page_id|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                    <a href="{$__url->adm('.page.delete')}?page_id={$p.page_id|escape}" onclick="return confirm('Вы уверены, что хотите удалить?')" class="btn btn-xs btn-danger scrollfix" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                </span>
                    <span class="cms-node-content-title">{$p.page_alias|truncate:"50":"…"|escape}</span>
                {/if}
            </div>

            {assign var='current_level' value=$p.level}
            {if $p@last}
                {assign var='r' value=$current_level-1}
                {if $r > 0}{"</div><!-- /.cms-node --></div><!-- /.cms-tree -->"|str_repeat:$r}</div><!-- /.cms-node -->{/if}
            {/if}
        {/foreach}
    </div></div><!-- /.cms-tree -->
    {/if}
    </div>
</div>
</div>