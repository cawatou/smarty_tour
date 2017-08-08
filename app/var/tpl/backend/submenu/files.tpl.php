{$__ctx->setPageTitle({'Файлы'|t})}
{$__ctx->addCss('/backend/module/files.css')}
{$__ctx->addJs('/backend/stupidtable.js')}

{if !in_array($cmd, array('.adm.files-mce', '.adm.files-dialog', '.adm.files-multi'))}
<div class="row">
    <div class="col col-sm-12">
        <div class="page-header">
            <h2>{$__ctx->getPageTitle()|escape}</h2>
        </div>
    </div>
</div>
{/if}
{if !$restrict}
<div class="row">
    <div class="col col-sm-12">
        <div class="btn-group">
            <a href="{$__url->cmd($cmd)}" class="{if $op == 'list'}btn btn-primary active{else}btn btn-default{/if}">{'Файлы'|t}</a>
            {if $__ctx->userCanCreate()}
            <a href="{$__url->cmd("`$cmd`.addFile", "?path=`$path`")}" class="{if $op == 'addFile'}btn btn-primary active{else}btn btn-default{/if}">{'Добавить файлы'|t}</a>
            <a href="{$__url->cmd("`$cmd`.addFolder", "?path=`$path`")}" class="{if $op == 'addFolder'}btn btn-primary active{else}btn btn-default{/if}">{'Создать папку'|t}</a>
            {/if}
            {if $op == 'rename'}<a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Переименовать'|t}</a>{/if}
            {if $op == 'preview'}<a href="{$__ctx->getData('uri')}" class="btn btn-primary active">{'Превью'|t}</a>{/if}
        </div>
    </div>
</div>
{/if}