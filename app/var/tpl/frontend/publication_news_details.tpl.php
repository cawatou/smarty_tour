{$__ctx->setPageTitle("`$pub->getTitle()` — Новости")}
<div class="body-content common-content">
    <div class="common-content-header">
        <h2>{$pub->getTitle()|escape}</h2>
    </div>

    <div class="common-content-text">
        <div class="news-body">
            <i style="float: right;">{$pub->getDate()->setDefaultTimeZone()->format('d.m.Y')}</i>

            <div class="news-body-descr">
                {if $pub->getYoutube() !== null}
                    <div class="news-body-descr-video">
                        {$pub->getYoutubeData(true)->iframe}
                    </div>
                {/if}

                {if count($pub->getImages()) > 0}
                    <div class="gallery-list">
                        {foreach $pub->getImages() as $img}
                            <div class="gallery-item">
                                <a href="{$__url->thumb($img->getPath(), 800)}" class="fancy" rel="pub"><img src="{$__url->thumb($img->getPath(), 170, 170)}" alt="{$pub->getTitle()|escape}" /></a>
                            </div>
                        {/foreach}
                    </div>
                {/if}

                {$pub->getContent()}
            </div>

            <div class="news-body-hrf">
                <a href="{$__url->url('/news')}">Читать остальные новости</a>
            </div>
        </div>
    </div>
</div>