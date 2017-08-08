<div class="content content-common">
    <h1>Туристам</h1>

    <div class="content-body">
        <ul class="for-tourists">
            {foreach $pages as $page}
                <li>
                    <a href="{$page->getPath()}">
                        {if $page->getCover() !== null}
                            <img src="{$__url->thumb($page->getCover(), 96, 73)}" />
                        {/if}

                        <h4>{$page->getTitle()|escape}</h4>
                    </a>
                </li>
            {/foreach}
        </ul>
    </div>
</div>