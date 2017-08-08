<form action="{$cfg.url_complete}" method="post">
    <input type="hidden" name="MerchantId" value="{$cfg.merchant_id}" />
    <input type="hidden" name="TransactionId" value="{$data.transaction_id|escape}" />

    {if !empty($data.amount)}
        <input type="hidden" name="Amount" value="{$data.amount|escape}" />
    {/if}

    <input type="hidden" name="SecurityKey" value="{$crc}" />

    <input type="submit" value="Отдай мои деньги" />
</form>