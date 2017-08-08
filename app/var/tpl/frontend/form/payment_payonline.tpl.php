<form action="{$conf.url_select}" method="post">
    <input type="hidden" name="MerchantId" value="{$conf.merchant_id}" />
    <input type="hidden" name="OrderId" value="{$order->getId()|escape}" />
    <input type="hidden" name="Amount" value="{$order->getPrice()|escape}" />
    <input type="hidden" name="Currency" value="{$conf.currency|default:'RUB'}" />

    <input type="hidden" name="SecurityKey" value="{$crc}" />
    <input type="hidden" name="ReturnUrl" value="{$__url->url('/payonline/success')|escape:'url'}" />
    <input type="hidden" name="FailUrl" value="{$__url->url("/payonline/fail/`$order->getId()`")|escape:'url'}" />

    <input type="submit" value="Оплатить" />
</form>