{if !empty($offices_list)}
    <h3>Наши офисы:</h3>

    <div class="b-adres">
        {foreach $offices_list as $offices}
            {foreach $offices as $office}
                <div class="b-adres-tab{if $office@first} b-adres-tab-first{/if}{if $office@last} b-adres-tab-last{/if}">
                    {if $office@first}
                        <div class="b-city-title">
                            <strong>{$office->getCityName()|escape}</strong>
                        </div>
                    {/if}

                    <div class="b-city-adress">
                       {$office->getAddress(false)|escape}
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
            {/foreach}
        {/foreach}
    </div>
{/if}