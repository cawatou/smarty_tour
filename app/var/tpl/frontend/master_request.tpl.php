{if $__ctx->getCurrentCommand()->getCmd() == '.order'}
    {assign var="title" value="Купить тур on-line"}
{else}
    {assign var="title" value="Заявка на тур"}
{/if}

{$__ctx->setPageTitle($title)}

<div class="content-block">
    <header class="content-header" id="form">
        <h2>{$title|escape}</h2>
    </header>

    {if $__f->successful}
        <div class="form-successful">
            <strong>Спасибо,</strong> ваша заявка на покупку тура отправлена!
        </div>
    {/if}

    {$form_html}
</div>