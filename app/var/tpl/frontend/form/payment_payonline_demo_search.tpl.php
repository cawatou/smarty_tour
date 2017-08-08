<form action="{$cfg.url_search}" method="post">
    <input type="hidden" name="MerchantId" value="{$cfg.merchant_id}" />
    <input type="hidden" name="OrderId" value="{$data.order_id|escape}" />

    <input type="hidden" name="SecurityKey" value="{$crc}" />

    <input type="submit" value="Найти" />
</form>