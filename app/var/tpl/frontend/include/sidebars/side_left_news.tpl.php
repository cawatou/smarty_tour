{if !empty($news)}
    <h3>Новости:</h3>

    <div class="b-left-news b-adres b-adres-nobg">
        <div class="b-left-news-verx"> </div>

        {foreach $news as $new}
            <p>
                <a href="{$new->getUrl()}">
                    <b>{$new->getDate()->setDefaultTimeZone()->format('d.m.Y')}</b>
                    {$new->getTitle()|escape}
                </a>
            </p>
        {/foreach}

        <div class="b-left-news-niz">
            <a href="{$__url->url('/news')}">Все новости</a>
        </div>
    </div>
{/if}