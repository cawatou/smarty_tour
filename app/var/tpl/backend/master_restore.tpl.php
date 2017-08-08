<div class="cms-signin">
    <div class="cms-signin-container">
        <form method="post" action="{$__url->adm('.restore')}">
            <div class="form-group">
                <label for="login">Email</label>
                <input type="email" id="login" name="__login" value="" class="form-control">
            </div>
            <input type="submit" name="__restore" value="{'Отправить'|t}" class="btn btn-primary">
        </form>
        <div class="text">
            <p>{'Укажите ваш email, который используется для входа, чтобы получить новый пароль.'|t}</p>
            <p><a href="{$__url->adm()}"><i class="fa fa-long-arrow-left"></i> {'Форма входа'|t}</a><p>
            
            {if $error_code == 'USER_LOGIN_EMPTY'}<p class="error">{'Вы не указали email'|t}</p>
            {elseif $error_code == 'USER_NOT_FOUND'}<p class="error">{'Пользователь не найден'|t}</p>
            {elseif $error_code == 'USER_DISABLED'}<p class="error">{'Пользователь заблокирован'|t}</p>
            {elseif $error_code == 'CODE_NOT_FOUND'}<p class="error">{'Указанный код не верный'|t}</p>
            {/if}
            
            {if $success_code == 'CHECK_EMAIL'}<p class="success">{'Проверьте указанный email и перейдите по ссылке в контрольном письме.'|t}</p>
            {elseif $success_code == 'OKAY'}<p class="success">{'Проверьте email. Вам отправлено письмо с новым паролем.'|t}</p>
            {/if}
        </div>
    </div>
</div>