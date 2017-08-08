{$__ctx->setPageTitle('Payonline - произошла ошибка оплаты')}

<div class="payonline payonline-error">
    <h3>Упс!</h3>
    <h2>При попытке оплатить, произошла следующая ошибка:</h2>

    <div class="error-message" style="margin-top: 15px;margin-left:15px;">
        {$error_msg|escape}
    </div>
</div>