Здравствуйте,
{if $data.for == 'RESTORE'}
Запрошен сброс пороля для входа в панель управления.
Если вы не запрашивали сброс пароля или вспомнили свой пароль, просто проигнорируйте это сообщение.

Для сброса пароля перейдите по ссылке:
{$__url->adm('.restore')}?code={$data.code}
{elseif $data.for == 'RESTORE_OKAY'}
По вашему запросу пароль для входа в панель управления изменён.

Ваш новый пароль: {$data.password|escape}

Для входа в панель управления перейдите по ссылке:
{$__url->adm()}
{/if}

--
С уважением, служба почтовых сообщений DxCMS
Профессиональная разработка сайтов: http://www.rosapp.ru