{if !empty($state.found_pages) && $state.found_pages > 1}
<ul class="pagination">
    {if $state.prev_page}
        <li><a href="{$state.prev_page_url|escape:"html"}">&larr;</a></li>
    {else}
        <li class="disabled"><a href="#">&larr;</a></li>
    {/if}
    {foreach $state.pages_index as $page}
        {if !empty($page.separator)}
        <li class="disabled"><a href="#">&hellip;</a></li>
        {else}
        <li{if $page.number == $state.current_page} class="active"{/if}><a href="{$page.url|escape:"html"}">{$page.number|escape}</a></li>
        {/if}
    {/foreach}    
    {if $state.next_page}
        <li><a href="{$state.next_page_url|escape:"html"}">&rarr;</a></li>
    {else}
        <li class="disabled"><a href="#">&rarr;</a></li>
    {/if}
</ul>
{/if}