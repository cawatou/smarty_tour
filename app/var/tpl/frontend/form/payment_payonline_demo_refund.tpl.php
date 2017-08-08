<form action="{$cfg.url_refund}" method="post">
    <input type="hidden" name="MerchantId" value="{$cfg.merchant_id}" />
    <input type="hidden" name="TransactionId" value="{$data.transaction_id|escape}" />
    <input type="hidden" name="Amount" value="{$data.amount|escape}" />

    <input type="hidden" name="SecurityKey" value="{$crc}" />

    <input type="submit" value="Вернуть" />
</form>