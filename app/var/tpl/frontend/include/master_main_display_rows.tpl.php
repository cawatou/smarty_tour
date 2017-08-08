<h2>
	{if $tours.tours|@count!=0}
		Горящие туры и путевки {$tours.departure|escape}

		{if $tours.updated}
			<i>
				обновлено {$tours.updated->localeFormat('j F', 'RU2')}
			</i>
		{/if}
	{/if}
</h2>

<ul class="b-list-tours-rows">


    {foreach $tours.tours as $tour}
		
        <li class="{cycle values='pos-even,pos-odd' name="tours_rows_`$listId`"}">
            <a href="{$tour->getUrl()|escape}" class="tour">
                <span class="tour-country">
                    {$tour->getCountryName()|escape}
                </span>

                <span class="tour-resort">
                    {if $tour->getResortName() !== null}
                        {$tour->getResortName()|escape}
                    {/if}

                    {$tour->getFrom('title_from')|replace:' ':"&nbsp;"}
                </span>

                <span class="tour-dates">
                    {foreach $tour->getDepartures() as $departure}
                        {if $departure->getIsDatetime()}
                            {$departure->getDate()->toUTC()->localeFormat('j F Y H:i', 'RU2')}
                        {else}
                            {$departure->getDate()->toUTC()->localeFormat('j F Y', 'RU2')}
                        {/if}
                        на
                        {$departure->getDaynum()|escape} {$departure->getDaynum()|plural_form:"день":"дня":"дней"}

                        {if $departure->getSeats()}
                            ({$departure->getSeats()|escape})
                        {/if}

                        <br />
                    {/foreach}

                    {if count($tour->getLinkedProducts()) > 0}
                        {foreach $tour->getLinkedProducts() as $_tour}
                            {foreach $_tour->getDepartures() as $departure}
                                {if $departure->getIsDatetime()}
                                   {$departure->getDate()->toUTC()->localeFormat('j F Y H:i', 'RU2')}
                                {else}
                                    {$departure->getDate()->toUTC()->localeFormat('j F Y', 'RU2')}
                                {/if}
                                на
                                {$departure->getDaynum()|escape} {$departure->getDaynum()|plural_form:"день":"дня":"дней"}

                                {if $departure->getSeats()}
                                    ({$departure->getSeats()|escape})
                                {/if}
                                <br />
                            {/foreach}
                        {/foreach}
                    {/if}

                </span>

                <span class="tour-image"{if $tour->getCover() !== null} style="background-image: url({$__url->thumb($tour->getCover()|escape, 150, 94)})"{/if}>
                    {if $tour->getDiscountPercent() !== null}
                        <span class="tour-discount">{$tour->getDiscountPercent()|escape}%</span>
                        <span class="tour-price-old">
                            {$tour->getCrossedPrice()|escape|price_format} р.
                            <span class="cross-out"></span>
                        </span>
                    {/if}

                    {$price = $tour->getSalePrice()}

                    <span class="tour-price">от {$price|escape|price_format} р.</span>
                </span>
            </a>
        </li>
    {/foreach}
</ul>