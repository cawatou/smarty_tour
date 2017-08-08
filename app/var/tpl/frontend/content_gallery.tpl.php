{$__ctx->addJs('/frontend/jquery.waterwheel_carousel.min.js')}

{$__ctx->addCss('../js/frontend/fancybox/jquery.fancybox.css')}
{$__ctx->addJs('/frontend/fancybox/jquery.fancybox.pack.js')}

{if $gallery->getCategory() == 'CYCLING' && count($gallery->getImages()) > 0}
    <div class="waterwheel carousel-cycling" data-waterwheel data-waterwheel-fancybox="true">
        {foreach $gallery->getImages() as $img}
            <img src="{$__url->thumb($img->getPath(), 105, 150)}" alt="{$img->getTitle()|escape}" data-waterwheel-source="{$img->getPath()}" />
        {/foreach}
    </div>
{/if}