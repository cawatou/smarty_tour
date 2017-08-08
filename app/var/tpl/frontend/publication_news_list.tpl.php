{$__ctx->setPageTitle('Новости')}

<div class="body-content common-content">
    <div class="common-content-header">
        <h2>Новости</h2>
    </div>

    <div class="common-content-text">
        {foreach $list as $pub}
            <div class="news-body">
                <a href="{$pub->getUrl()}">
                    <b>{$pub->getTitle()|escape}</b>
                    <i>{$pub->getDate()->setDefaultTimeZone()->format('d.m.Y')}</i>
                    <div class="news-body-descr"></div>
                    <div class="news-body-hrf">прочитать новость</div>
                </a>
            </div>
        {/foreach}

        {include file='frontend/include/paginator.tpl.php' state=$state}
    </div>
</div>