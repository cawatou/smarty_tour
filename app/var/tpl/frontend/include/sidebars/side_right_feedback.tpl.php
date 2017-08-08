<h3>Отзывы клиентов:</h3>

<div class="b-right-otziv b-adres b-adres-feedback">
    {if !empty($feedbacks)}
        {foreach $feedbacks as $feedback}
            <div class="b-right-otziv-name">
                {$feedback->getUserName()|escape}

                <b>{$feedback->getCreated()->setDefaultTimeZone()->format('d.m.Y')}</b>
            </div>

            <p>
                <a href="{$__url->url('/feedback')}">{$feedback->getMessage()|truncate:100:"...":true|escape}</a>
            </p>
        {/foreach}
    {else}
        Будь первым в добавлении отзыва
    {/if}

    <div class="b-adres-feedback-add">
        <a href="{$__url->url('/feedback')}">Оставить свой отзыв</a>
    </div>
</div>