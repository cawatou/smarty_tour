{$__ctx->setPageTitle('Вопрос-Ответ')}

<div class="content-block">
    {if $__f->successful}
        <div class="form-successful">
            <strong>Спасибо,</strong> ваш вопрос отправлен!
        </div>
    {/if}

    <div id="form-faq-wrapper"{if !$form_submitted} style="display: none;"{/if}>
        <header class="content-header" id="form">
            <h2>Задать вопрос</h2>
        </header>

        <div>
            {$form_html}
        </div>
    </div>

    {if !$form_submitted}
        <a href="#" onclick="{literal}$(this).remove(); $('#form-faq-wrapper').slideDown('fast', function () { $('#form-faq-wrapper .ik-select').ikSelect('redraw'); }); return false;{/literal}" class="show-me-my-form">Форма для ввода сообщения</a>
    {/if}

    {if !empty($list)}
        <div class="faq">
            <div class="faq-separate"></div>

            {foreach $list as $faq}
                <div class="faq-msg-body">
                    <div class="faq-msg-body-left">
                        <img src="{$__url->img('/frontend/vop-otv-turist.gif')}" alt="Изображение туриста" />
                        {$faq->getUserName()|escape}
                    </div>

                    <div class="faq-msg-body-right">
                        {$faq->getMessage()|escape}
                    </div>
                </div>

                {if $faq->getAnswer() !== null}
                    <div class="faq-answer-body">
                        <div class="faq-answer-msg">
                            <div class="faq-answer-msg">
                                <div class="faq-answer-left">
                                    <img src="{$__url->img('/frontend/vop-otv-mht.gif')}" alt="Изображение" />

                                    {if $faq->getStaffAnswer() !== null}
                                        {$faq->getStaffAnswer()->getName()|escape}
                                    {/if}
                                </div>

                                <div class="faq-answer-right">
                                    {$faq->getAnswer()|escape}
                                </div>
                            </div>
                        </div>
                    </div>
                {/if}

                {if !$faq@last}
                    <div class="faq-separate">
                    </div>
                {/if}
            {/foreach}
        </div>

        {include file="frontend/include/paginator.tpl.php" state=$state}
    {/if}
</div>