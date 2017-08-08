Здравствуйте,{assign var="site_name" value="Мой горящий тур (http://moihottur.ru)"}

На сайте Мой горящий тур была заполнена заявка на покупку тура онлайн и был указан этот email.
Если вы не заполняли форму заявки, просто проигнорируйте это сообщение.

{$data.model->getProductData('country_name')|escape}{if $data.model->getProductData('resort_name')} {$data.model->getProductData('resort_name')|escape}{/if} {$data.model->getProductFrom('title_from')|escape}
Отель: {$data.model->getHotelData('name')|escape}
Питание: {if $data.model->getHotelData('nutrition_type')}{DomainObjectModel_Hotel::obtainNutritionType($data.model->getHotelData('nutrition_type'), 'title')}{else}-{/if}

Дата вылета: {$data.model->getHotelData('departure_date')->format('d.m.Y')} на {$data.model->getHotelData('departure_daynum')|escape} {$data.model->getHotelData('departure_daynum')|plural_form:"день":"дня":"дней"}

Количество туристов: {if $data.model->getCustomerTotalAdults() > 0}взрослых - {$data.model->getCustomerTotalAdults()}{if $data.model->getCustomerTotalChildren() > 0}, {/if}{/if}{if $data.model->getCustomerTotalChildren() > 0}детей - {$data.model->getCustomerTotalChildren()}{/if}


По всем возникающим вопросам, вы можете связаться с вашим персональным менеджером:
{$data.settings.MANAGER_NAME|escape}{if $data.settings.MANAGER_EMAIL || $data.settings.MANAGER_PHONE},{if $data.settings.MANAGER_PHONE} тел.: {$data.settings.MANAGER_PHONE|escape}{if $data.settings.MANAGER_EMAIL}, {/if}{/if}{if $data.settings.MANAGER_EMAIL}email: {$data.settings.MANAGER_EMAIL}{/if}{/if}


Для продолжения, необходимо перейти по ссылке ниже и заполнить анкету:
{$data.model->getUrl()}

--
С уважением, {$site_name}