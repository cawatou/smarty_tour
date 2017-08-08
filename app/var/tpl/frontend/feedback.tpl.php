{$__ctx->setPageTitle('Ваши отзывы')}

<div class="content-block">
    {if $__f->successful}
        <div class="form-successful">
            <strong>Спасибо,</strong> ваш отзыв отправлен!
        </div>
    {/if}

    <div id="form-faq-wrapper"{if !$__f->isSubmitted()} style="display: none;"{/if}>
        <header class="content-header" id="form">
            <h2>Оставить отзыв</h2>
        </header>

        <div>
            {$form_html}
        </div>
    </div>

    {if !$__f->isSubmitted()}
        <a href="#" onclick="$(this).remove(); $('#form-faq-wrapper').slideDown('fast'); return false;" class="show-me-my-form">Форма для ввода сообщения</a>
    {/if}

    <div class="faq-separate"></div>

    {if !empty($list)}
        <div class="feedback">
            {foreach $list as $feedback}
                <div class="otziv-o-komp">
                    <img src="{$__url->img('/frontend/otziv_noimg.gif')}" alt="" />

                    <div class="otziv-o-komp-r">
                        <div class="otziv-o-komp-r-name">
                            {$feedback->getUserName()|escape}
                            <i>{$feedback->getCreated()->setDefaultTimeZone()->format('d.m.Y')}</i>
                        </div>

                        {$feedback->getMessage()|escape}
                    </div>
                </div>
            {/foreach}
        </div>

        {include file="frontend/include/paginator.tpl.php" state=$state}
    {/if}
</div>