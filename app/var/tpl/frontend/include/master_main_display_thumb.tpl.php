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

<ul class="b-list-tours">
    
    
     {addnewtours departure={getcityId} mazafaka=$mazafaka}

    

    {foreach $tours.tours as $tour}
		
        <li>
            <a href="{$tour->getUrl()|escape}" class="tour" onmouseout="this.style.background = '#FFF'" onMouseover="this.style.background = '#FF9'">
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
                        {$departure->getDate()->toUTC()->format('d.m')}
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
                                {$departure->getDate()->toUTC()->format('d.m')}
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