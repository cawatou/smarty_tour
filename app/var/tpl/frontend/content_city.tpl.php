<div class="content content-common">
    <h1>{$parent_page->getTitle()|escape}</h1>

    <div class="city">
        {foreach $page_list as $page_group}
            <div class="col">
                {foreach $page_group as $page}
                    <a href="{$page->getPath()|escape}">
                        {if $active_page->getAlias() == $page->getAlias()}
                            <b>{$page->getName()|escape}</b>
                        {else}
                            {$page->getName()|escape}
                        {/if}
                    </a>
                {/foreach}
            </div>
        {/foreach}
    </div>

    <div class="content-body">
        <h3>{$active_page->getTitle()|escape}</h3>

        {$active_page->getContent()}
    </div>
</div>