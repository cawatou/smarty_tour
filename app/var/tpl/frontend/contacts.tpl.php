{$__ctx->setPageTitle('Где купить')}

<div class="contact-body">
    <h3>Где купить путевку:</h3>

    <div class="city">
        {foreach $city_list as $city_group}
            <div class="col">
                {foreach $city_group as $city}
                    <a href="{$city->getContactsUrl()|escape}">
                        {if $city->getId() == $active_city->getId()}
                            <b>{$city->getTitle()|escape}</b>
                        {else}
                            {$city->getTitle()|escape}
                        {/if}
                    </a>
                {/foreach}
            </div>
        {/foreach}
    </div>

    {foreach $office_list as $offices}
        <h3 style="color:#000;padding-top:20px;">
            Офисы в {$offices[0]->getCity()->getTitleIn()|escape}:
        </h3>

        {foreach $offices as $office}
            <div class="contact-adr">
                <div class="b-city-adress">
                    {$office->getAddress(true)|escape|nl2br}
                </div>

                <div class="b-city-tel">
                    т.: {$office->getPhone()|escape}
                </div>

                {if $office->getMetro()}
                    <div class="b-city-metro">
                        {$office->getMetro()|escape}
                    </div>
                {/if}

                {if $office->getScheduleAsString()}
                    <div class="b-city-time">
                        {$office->getScheduleAsString()|escape|nl2br}
                    </div>
                {/if}
            </div>

            <div class="form-opl">
                <h4>Форма оплаты:</h4>

                <br />

                <table width="100%" border="0" class="middle">
                    <tbody>
                        {if $office->getIsPayCashless()}
                            <tr>
                                <td width="130px">Безналичный расчет</td>
                                <td>
                                    <img src="{$__url->img('/frontend/logo_visa_mastercard.gif')}" width="55" height="18" />
                                </td>
                            </tr>
                        {/if}

                        {if $office->getIsPayCash()}
                            <tr>
                                <td>Наличный расчет</td>
                                <td>
                                    <img src="{$__url->img('/frontend/logo_cash.gif')}" width="22" height="22" />
                                </td>
                            </tr>
                        {/if}

                        {if $office->getIsPayInstallment()}
                            <tr>
                                <td colspan="2">
                                    <div class="rassr">Рассрочка 0%</div>
                                </td>
                            </tr>
                        {/if}

                        {if $office->getIsPayCredit()}
                            <tr>
                                <td>Кредит</td>
                                <td>
                                    <img src="{$__url->img('/frontend/logo_credit_alfabank.gif')}" width="74" height="22">
                                </td>
                            </tr>
                        {/if}
                    </tbody>
                </table>
            </div>

            {foreach $office->getStaffs() as $staff}
                <div class="staff-photo">
                    <div class="staff-name">
                        {$staff->getName()|escape}
                    </div>

                    {if $staff->getPosition()}
                        <div class="staff-position">
                            {$staff->getPosition()|escape}
                        </div>
                    {/if}

                    {if $staff->getSkype()}
                        <div class="staff-contact-skype">
                            <img src="{$__url->img('/frontend/icon-skype.gif')}" width="15" height="13" />

                            {$staff->getSkype()|escape}
                        </div>
                    {/if}

                    {if $staff->getIcq()}
                        <div class="staff-contact-icq">
                            <img src="{$__url->img('/frontend/icon-icq.gif')}" width="15" height="13" />

                            {$staff->getIcq()|escape}
                        </div>
                    {/if}

                    <img src="{$__url->thumb($staff->getPhoto(), 82, 86)}" width="82" height="86" alt="Фотография сотрудника" />
                </div>
            {/foreach}

            <div class="line"></div>
        {/foreach}
    {/foreach}

    {**<div id="hotel-photos-turist-body">
        <a onclick="$('#carusel-137').jcarousel('scroll', '-=1');" id="hotel-img-but-l" href="javascript://"></a>
        <div id="hotel-img-body">
            <div id="carusel-137" data-wrap="0" class="carousel" data-jcarousel="true">
                <ul style="left: 0; top: 0;"></ul>
            </div>
        </div>
        <a onclick="$('#carusel-137').jcarousel('scroll', '+=1');" id="hotel-img-but-r" href="javascript://"></a>
    </div>
    <script type="text/javascript">
        start_carusel('carusel-137');
    </script>**}
</div>