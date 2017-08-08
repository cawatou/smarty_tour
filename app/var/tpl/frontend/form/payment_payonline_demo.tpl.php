<form action="{$cfg.url_select}" method="post">
    <input type="hidden" name="MerchantId" value="{$cfg.merchant_id}" />
    <input type="hidden" name="OrderId" value="{$data.order_id|escape}" />
    <input type="hidden" name="Amount" value="{$data.amount|escape}" />
    <input type="hidden" name="Currency" value="{$cfg.currency|default:'RUB'}" />

    {if !empty($data.description)}
        <input type="hidden" name="OrderDescription" value="{$data.description|escape:'url'}" />
    {/if}

    <input type="hidden" name="SecurityKey" value="{$crc}" />
    <input type="hidden" name="ReturnUrl" value="{$__url->url("/payonline/success/`$data.order_id`/")}" />
    <input type="hidden" name="FailUrl" value="{$__url->url("/payonline/fail/`$data.order_id`/")|escape:'url'}" />

    <input type="submit" value="Оплатить" />
</form>