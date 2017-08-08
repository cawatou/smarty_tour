<div class="container">
    {include file='backend/submenu/block.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
        {if empty($list)}
            <div class="alert alert-info">
                На сайте нет блоков.{if $__ctx->getCurrentUser()->isDeveloper()} Хотите <a href="{$__url->adm('.block.add')}">добавить</a>?{/if}
            </div>
        {else}
            {foreach $list as $category => $_blocks}
                <h3>{$_blocks[0]->getCategoryName()|escape}</h3>
                <div class="row">
                    {foreach $_blocks as $block}
                        <div class="col col-md-3">
                            <div class="cms-group-header">
                                <div class="cms-group-actions">
                                    <a href="{$__url->adm('.block.edit')}?block_id={$block->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
                                    {if $__ctx->getCurrentUser()->isDeveloper()}
                                        <a href="{$__url->adm('.block.delete')}?block_id={$block->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                                    {/if}
                                </div>
                            </div>
                            <div class="cms-group cms-group-white">
                                <div class="cms-group-content">
                                {if $__ctx->getCurrentUser()->isDeveloper()}
                                    <p><span class="label label-default">{$block->getAlias()|escape}</span></p>
                                {/if}
                                <p>{$block->getName()|escape}</p>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            {/foreach}
        {/if}
        </div>
    </div>
</div>