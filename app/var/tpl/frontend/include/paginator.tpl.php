{if !empty($state.found_pages) && $state.found_pages > 1}
    <nav class="paginator">
        <p class="paginator-arrows paginator-arrows">
            {if $state.next_page}
                <a href="{$state.next_page_url|escape:'html'}" class="paginator-next bordered-button">следующая &nbsp;→</a>
            {/if}

            {if $state.prev_page}
                <a href="{$state.prev_page_url|escape:'html'}" class="paginator-prew bordered-button">←&nbsp; предыдущая</a>
            {/if}
        </p>
    </nav>
{/if}