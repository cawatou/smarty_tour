<form action="{$cfg.url_select}" method="post">
    <input type="hidden" name="MerchantId" value="{$cfg.merchant_id}" />
    <input type="hidden" name="OrderId" value="{$payment->getId()|escape}" />
    <input type="hidden" name="Amount" value="{$payment->getPayonlineAmount()|escape}" />
    <input type="hidden" name="Currency" value="{$cfg.currency|default:'RUB'}" />

    <input type="hidden" name="SecurityKey" value="{$payment->calculateCrcSend()}" />
    <input type="hidden" name="ReturnUrl" value="{$urls.success}" />
    <input type="hidden" name="FailUrl" value="{$urls.fail|escape:'url'}" />

    <input type="submit" value="Оплатить картой через PayOnline" />
</form>