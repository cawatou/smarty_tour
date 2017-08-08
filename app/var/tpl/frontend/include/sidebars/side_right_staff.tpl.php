
<style>
.b2 {
	background:    #c00;
	background:    -webkit-linear-gradient(#c00, #900);
	background:    linear-gradient(#c00, #900);
	border-radius: 5px;
	color:         #ff0;
	display:       inline-block;
	padding:       8px 20px;
	font:          normal 700 24px/1 "Calibri", sans-serif;
	text-align:    center;
	text-shadow:   1px 1px 0 #000;
	margin-top: 125px;
	margin-left: 35px;
	cursor:pointer;
}
.d2 {
	width:227px;
	height:177px;
	background-size: 100% 100%;
	background-image: url('http://www.moihottur.ru/static/img/frontend/frans/banner.png');
}
</style>

<br />
<div class="d2">
	<button type="button" class="b2" onClick="location.href='http://www.moihottur.ru/franchise/'">Подробнее</button>
</div>
<br />

{if $is_not_empty}
    <h3>Сотрудники:</h3>

    <div class="b-adres b-adres-staffs">
        {foreach $offices as $_offices}
            {foreach $_offices as $office}
            {foreach $office->getStaffs() as $staff}
                <div class="b-people">
                    <div class="b-people-manager">
                        {if $staff->getPhoto() !== null}
                            <img src="{$__url->thumb($staff->getPhoto(), 82, 86)}" alt="Фотография сотрудника" />
                        {else}
                            <div class="empty-photo">&nbsp;</div>
                        {/if}

                        <div class="b-people-name">
                            {$staff->getName()|escape}
                        </div>

                        {if $staff->getPosition()}
                            <div class="b-people-position">
                                {$staff->getPosition()|escape}
                            </div>
                        {/if}

                        <div class="b-people-adress">
                            {$office->getTitle()|escape}
                        </div>

                        {if $staff->getIcq()}
                            <div class="b-people-icq" title="{$staff->getIcq()|escape}">
                                {$staff->getIcq()|escape}
                            </div>
                        {/if}

                        {if $staff->getSkype()}
                            <div class="b-people-skype" title="{$staff->getSkype()|escape}">
                                {$staff->getSkype()|escape}
                            </div>
                        {/if}
                    </div>
                </div>
            {/foreach}
            {/foreach}
        {/foreach}
    </div>
{/if}