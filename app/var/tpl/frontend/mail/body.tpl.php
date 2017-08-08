Здравствуйте,{assign var="site_name" value="Мой горячий тур"}

{if $data.for == 'FAQ'}
В раздел "Вопрос-ответ" на сайте {$site_name} поступил новый вопрос.
Для ответа на вопрос перейдите по ссылке: {$__url->adm('.faq')}

Вопрос:
{$data.faq_message|escape}
{elseif $data.for == 'FEEDBACK'}
На сайте {$site_name} было получено сообщение через форму "Обратная связь".
Для просмотра сообщения перейдите по ссылке: {$__url->adm('.feedback')}

Сообщение:
{$data.feedback_message|escape}
{elseif $data.for == 'FEEDBACK_HOTEL'}
На сайте {$site_name} был оставлен новый отзыв на отель.
Для просмотра сообщения перейдите по ссылке: {$__url->adm('.feedback')}

Сообщение:
{$data.feedback_message|escape}
{elseif $data.for == 'COMPANION'}
На сайте {$site_name} было получено сообщение через форму "Поиск попутчика".
Для просмотра сообщения, перейдите по ссылке: {$__url->adm('.companion')}

Сообщение:
{$data.feedback_message|escape}
{elseif $data.for == 'CALLBACK'}{assign var="req" value=$data.request}
На сайт {$site_name} поступила новая заявка на обратный звонок.

Имя: {$req->getUserName()}
{if $req->getUserEmail() !== null}Email: {$req->getUserEmail()}
{/if}
{if $req->getUserPhone() !== null}Тел.: {$req->getUserPhone()}
{/if}

{if $req->getExtendedData('office_other')}
ВНИМАНИЕ! При заказе, выбран другой город ({$req->getExtendedData('office_other')})
{elseif $req->getOffice() !== null}
Офис: {$req->getOffice()->getTitle()}{if $req->getOffice()->getCity() !== null && $req->getOffice()->getCity()->getTitle() != $req->getOffice()->getTitle()} ({$req->getOffice()->getCity()->getTitle()}){/if}
{/if}
{elseif $data.for == 'REQUEST'}{assign var="req" value=$data.request}
На сайт {$site_name} поступила новая заявка.

Имя: {$req->getUserName()}
{if $req->getUserEmail() !== null}Email: {$req->getUserEmail()}
{/if}
{if $req->getUserPhone() !== null}Тел.: {$req->getUserPhone()}
{/if}


{if $req->getExtendedData('country')}Страна/курорт: {$req->getExtendedData('country')}{if $req->getExtendedData('resort')}/{$req->getExtendedData('resort')}{/if}

{/if}
{if $req->getExtendedData('price')}Цена: {$req->getExtendedData('price')|price_format:true} р.
{/if}


{if $req->getExtendedData('adults')}Взрослых: {$req->getExtendedData('adults')}
{/if}
{if $req->getExtendedData('children')}Детей: {$req->getExtendedData('children')}{if $req->getExtendedData('children_age_from') || $req->getExtendedData('children_age_to')}({if $req->getExtendedData('children_age_from')}с {$req->getExtendedData('children_age_from')}{/if}{if $req->getExtendedData('children_age_to')} до {$req->getExtendedData('children_age_to')}{/if}){/if}

{/if}

{if $req->getExtendedData('date_begin')}Вылет с: {$req->getExtendedData('date_begin')}
{/if}
{if $req->getExtendedData('date_end')}Вылет по: {$req->getExtendedData('date_end')}
{/if}
{if $req->getExtendedData('daynum')}Дней/ночей: {$req->getExtendedData('daynum')}
{/if}
{if $req->getExtendedData('flyaway')}Вылет из: {$req->getExtendedData('flyaway')}
{/if}
{if $req->getExtendedData('hotel_name')}Название отеля: {$req->getExtendedData('hotel_name')}
{/if}
{if $req->getExtendedData('hotel_stars')}Тип отеля: {$req->getExtendedData('hotel_stars')}
{/if}

{if $req->getExtendedData('office_other')}
ВНИМАНИЕ! При заказе, выбран другой город ({$req->getExtendedData('office_other')})
{elseif $req->getOffice() !== null}
Офис: {$req->getOffice()->getTitle()}{if $req->getOffice()->getCity() !== null && $req->getOffice()->getCity()->getTitle() != $req->getOffice()->getTitle()} ({$req->getOffice()->getCity()->getTitle()}){/if}
{/if}


Для просмотра, перейдите по ссылке: {$__url->adm('.request')}
{elseif $data.for == 'ORDER'}{assign var="req" value=$data.request}
На сайт {$site_name} был добавлен новый заказ на покупку.

{if $req->getExtendedData('city') !== null}Город: {$req->getExtendedData('city')}
{/if}

Имя: {$req->getUserName()}
{if $req->getUserEmail() !== null}Email: {$req->getUserEmail()}
{/if}
{if $req->getUserPhone() !== null}Тел.: {$req->getUserPhone()}
{/if}

{if $req->getExtendedData('country')}Страна/курорт: {$req->getExtendedData('country')}{if $req->getExtendedData('resort')}/{$req->getExtendedData('resort')}{/if}

{/if}
{if $req->getExtendedData('price')}Цена: {$req->getExtendedData('price')|price_format:true} р.
{/if}


{if $req->getExtendedData('adults')}Взрослых: {$req->getExtendedData('adults')}
{/if}
{if $req->getExtendedData('children')}Детей: {$req->getExtendedData('children')}{if $req->getExtendedData('children_age_from') || $req->getExtendedData('children_age_to')}({if $req->getExtendedData('children_age_from')}с {$req->getExtendedData('children_age_from')}{/if}{if $req->getExtendedData('children_age_to')} до {$req->getExtendedData('children_age_to')}{/if}){/if}

{/if}

{if $req->getExtendedData('date_begin')}Вылет с: {$req->getExtendedData('date_begin')}
{/if}
{if $req->getExtendedData('date_end')}Вылет по: {$req->getExtendedData('date_end')}
{/if}
{if $req->getExtendedData('daynum')}Дней/ночей: {$req->getExtendedData('daynum')}
{/if}
{if $req->getExtendedData('flyaway')}Вылет из: {$req->getExtendedData('flyaway')}
{/if}
{if $req->getExtendedData('hotel_name')}Название отеля: {$req->getExtendedData('hotel_name')}
{/if}
{if $req->getExtendedData('hotel_stars')}Тип отеля: {$req->getExtendedData('hotel_stars')}
{/if}

{if $req->getExtendedData('office_other')}


>>> ВНИМАНИЕ! При заказе, выбран другой город ({$req->getExtendedData('office_other')})
{/if}

Для просмотра, перейдите по ссылке: {$__url->adm('.request')}
{elseif $data.for == 'ORDER_PAYABLE'}
На сайт {$site_name} был добавлен новый заказ на покупку.

Для просмотра, перейдите по ссылке: {$__url->adm('.order')}
{/if}

--
С уважением, {$site_name}