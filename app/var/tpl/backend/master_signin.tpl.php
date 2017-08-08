{$__ctx->addJs('/backend/jquery.show-password.js')}
<div class="cms-signin">
    <div class="cms-signin-container">
        <form method="post" action="{$__url->adm()}">
            <div class="form-group">
                <label for="login">Email</label>
                <input type="email" id="login" name="__login" value="" class="form-control">
            </div>
            <div class="form-group form-password-container">
                <label for="pass">{'Пароль'|t}</label>            
                <input type="password" id="pass" name="__password" value="" class="form-control">
            </div>
            <button type="submit" name="__auth" class="btn btn-primary">
                <i class="fa fa-sign-in"></i> {'Войти'|t}
            </button>
        </form>
        <div class="text">
            <p>{'Заполните форму, чтобы получить доступ к панели управления.'|t}</p>
            <p><a href="{$__url->adm('.restore')}">{'Забыли пароль?'|t}</a></p>

            {if $error_code == 'USER_LOGIN_EMPTY'}<p class="error">{'Вы не указали email'|t}</p>
            {elseif $error_code == 'USER_NOT_FOUND'}<p class="error">{'Пользователь не найден'|t}</p>
            {elseif $error_code == 'USER_DISABLED'}<p class="error">{'Пользователь заблокирован'|t}</p>
            {elseif $error_code == 'USER_PASSWORD_EMPTY'}<p class="error">{'Вы не указали пароль'|t}</p>
            {elseif $error_code == 'USER_PASSWORD_INVALID'}<p class="error">{'Указанный пароль не подходит'|t}</p>
            {elseif $error_code == 'USER_ACCESS_DENIED'}<p class="error">{'Доступ запрещён'|t}</p>
            {/if}
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById('login').focus();
</script>