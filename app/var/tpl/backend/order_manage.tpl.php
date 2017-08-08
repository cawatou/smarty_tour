<div class="container">
    {include file='backend/submenu/order.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">
            {if !empty($message)}
                <div class="alert{if
                      $message == 'PAYMENT_ALREADY_CANCELLED'
                        ||
                      $message == 'PAYMENT_UNCANCELLABLE'
                        ||
                      $message == 'PAYMENT_UNCOMPLETEABLE'
                        ||
                      $message == 'PAYMENT_ALREADY_COMPLETED'
                    } alert-error{else} alert-success{/if}">
                    {if $message == 'MAIL_SENT'}
                        Письмо успешно отправлено пользователю
                    {elseif $message == 'PAYMENT_UNCANCELLABLE'}
                        Данный платёж отменить невозможно
                    {elseif $message == 'PAYMENT_CANCELLED'}
                        Платёж успешно отменён
                    {elseif $message == 'PAYMENT_ALREADY_CANCELLED'}
                        Платёж уже был отменён
                    {elseif $message == 'PAYMENT_UNCOMPLETEABLE'}
                        Списать средства по данному платежу невозможно
                    {elseif $message == 'PAYMENT_COMPLETED'}
                        Средства списаны, в течение суток они поступят на наш счёт
                    {elseif $message == 'PAYMENT_ALREADY_COMPLETED'}
                        Средства по этому платежу уже были списаны
                    {/if}
                </div>
            {/if}

            {$form_html}
        </div>
    </div>
</div>