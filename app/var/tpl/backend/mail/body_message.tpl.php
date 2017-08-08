Здравствуйте,{assign var="site_name" value="Мой горящий тур (http://moihottur.ru)"}

{if $data.for == 'FAQ'}
Недавно, вы задали вопрос на сайте {$site_name}{if $data.model->getAnswer()}, а мы на него только что ответили{/if}!
{elseif $data.for == 'FEEDBACK'}
Недавно, вы оставили свой отзыв на сайте {$site_name}{if $data.model->getAnswer()}, а мы на него только что ответили{/if}!
{elseif $data.for == 'REQUEST'}
Недавно, вы оставили заявку на сайте {$site_name}{if $data.model->getAnswer()}, а мы на неё только что ответили{/if}!
{/if}

{if $data.model->getMessage()}
Ваше сообщение:
{$data.model->getMessage()}

{/if}
{if $data.model->getAnswer()}
Наш ответ:
{$data.model->getAnswer()}

{/if}

Данный адрес электронной почты был указан в качестве контактного.

--
С уважением, служба почтовых сообщений {$site_name}