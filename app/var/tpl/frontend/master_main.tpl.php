<!--div class="display-styles clearfix">
    <ul>
        <li{if empty($display_style) || $display_style == 'THUMB'} class="active"{/if}>
            <a href="?display_style=THUMB">
                <i class="icons-display-styles icon-display-style-thumb"></i>
                Плитками
            </a>
        </li><li{if !empty($display_style) && $display_style == 'ROWS'} class="active"{/if}>
            <a href="?display_style=ROWS">
                <i class="icons-display-styles icon-display-style-rows"></i>
                Списком
            </a>
        </li>
    </ul>
</div-->


{foreach $list as $fromId => $tours}

    {if empty($display_style) || $display_style == 'THUMB'}


        {if $smart == 0}
            {include file="frontend/include/master_main_display_thumb.tpl.php" mazafaka=0  tours=$tours fromId=$fromId listId=$tours@iteration isRussia=false}
        {else}
            {include file="frontend/include/master_main_display_thumb.tpl.php" mazafaka=1  tours=$tours fromId=$fromId listId=$tours@iteration isRussia=false}
        {/if}
    {else}

         

        {include file="frontend/include/master_main_display_rows.tpl.php" tours=$tours fromId=$fromId listId=$tours@iteration isRussia=false}
         


        
    {/if}

    {assign var="smart" value={bl}}

{/foreach}