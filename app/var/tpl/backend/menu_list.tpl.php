<div class="container">
    {include file='backend/submenu/menu.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
        {if empty($trees)}
            <div class="alert alert-info">Ещё не создано ни одного меню. Хотите <a href="{$__url->adm('.menu.add')}">создать</a>?</div>
        {else}
            {foreach $trees as $menu_tree}
            <div class="cms-tree cms-tree-root">
                {assign var='current_level' value=0}
                {assign var='disabled_menu' value=0}

                {foreach $menu_tree as $k => $m}
                    {if !$m@first}
                        {if $current_level < $m.level}<div class="cms-tree">
                            {elseif $current_level == $m.level}</div><!-- /.cms-node -->
                            {elseif $current_level > $m.level}{assign var='r' value=$current_level-$m.level}{"</div><!-- /.cms-node --></div><!-- /.cms-tree -->"|str_repeat:$r}</div><!-- /.cms-node -->{/if}
                    {/if}

                    {if $m.menu_status == 'DISABLED' && (!$disabled_menu || $m.level <= $disabled_menu.level)}
                        {assign var="disabled_menu" value=$m}
                    {/if}

                <div class="cms-node{if $m.lft == 1} cms-node-root{/if}">
                    <div class="cms-node-content">
                        {if $m.lft == 1}
                            <strong>{$m.menu_title|escape}</strong>
                        {else}
                            <a href="{$__url->adm('.menu.edit')}?menu_id={$m.menu_id|escape}" {if $disabled_menu && ($m.menu_id == $disabled_menu.menu_id || $m.level > $disabled_menu.level && $m.rgt < $disabled_menu.rgt)}class="cms-node-disabled"{/if}>{$m.menu_title|truncate:"90":"…"|escape}</a>
                        {/if}
                        <span class="cms-node-content-buttons">
                            {if $m.prev_sibling}
                                <a href="{$__url->adm('.menu.order')}?menu_id={$m.menu_id|escape}&amp;up=1" class="btn btn-xs btn-primary scrollfix" title="{'Переместить выше'|t}"><i class="fa fa-arrow-up"></i></a>
                            {/if}
                            {if $m.next_sibling}
                                <a href="{$__url->adm('.menu.order')}?menu_id={$m.menu_id|escape}&amp;down=1" class="btn btn-xs btn-primary scrollfix" title="{'Переместить ниже'|t}"><i class="fa fa-arrow-down"></i></a>
                            {/if}
                            <a href="{$__url->adm('.menu.add')}?parent_id={$m.menu_id|escape}" class="btn btn-xs btn-success" title="Добавить подменю"><i class="fa fa-plus"></i></a>
                            {if $m.lft != 1 || $m.lft == 1 && $__ctx->getCurrentUser()->isDeveloper()}
                                <a href="{$__url->adm('.menu.edit')}?menu_id={$m.menu_id|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                            {/if}
                            {if $m.lft != 1 || $m.lft == 1 && $__ctx->getCurrentUser()->isDeveloper()}
                                <a href="{$__url->adm('.menu.delete')}?menu_id={$m.menu_id|escape}" onclick="return confirm('Вы уверены, что хотите удалить?')" class="btn btn-xs btn-danger scrollfix" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                            {/if}
                        </span>
                        <span class="cms-node-content-title">{$m.menu_alias|escape}</span>
                    </div>

                    {assign var='current_level' value=$m.level}
                    {if $m@last}
                        {assign var='r' value=$current_level}
                        {"</div><!-- /.cms-node --></div><!-- /.cms-tree -->"|str_repeat:$r}</div><!-- /.cms-node -->
                    {/if}
                {/foreach}
            </div><!-- /.cms-tree -->
            {/foreach}
        {/if}
        </div>
    </div>
</div>