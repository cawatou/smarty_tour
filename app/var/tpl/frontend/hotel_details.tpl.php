{$__ctx->addCss('/frontend/jquery.fancybox.css')}

{$__ctx->addJs('/frontend/jcarousel.js')}
{$__ctx->addJs('/frontend/jquery.fancybox.js')}

<div class="hotel-body clearfix">
    <h2>{$hotel->getTitle()|escape} {$hotel->getStarsTitle($hotel->getStars(), 'id')}</h2>

    <div class="hotel-city">
        {if $hotel->getCountryId() !== null}
            <p>
                <b>Страна:</b>
                {$hotel->getCountryTitle()|escape}
            </p>
        {/if}

        {if $hotel->getResortId() !== null}
            <p>
                <b>Курорт:</b>
                {$hotel->getResortTitle()|escape}
            </p>
        {/if}
    </div>

    {if $hotel->getCover()}
        <div class="hotel-image">
            <img src="{$__url->thumb($hotel->getCover(), 600)}" />
        </div>
    {/if}

    <div class="description">
        {if $hotel->getMessage()}
            {$hotel->getMessage()}
        {else}
            {if $hotel->getExternalId() !== null}
                {literal}
                    <script src="http://ui.sletat.ru/client/tour_page_linker.js?settings={formViewMode:'block'}&sfx=_NCLVg" type="text/javascript"></script>
                    <iframe onload="sm_sly_NCLVg.init();" id="sm_slyHotel" src="" width="100%" frameborder="0" style="height:100%" scrolling="yes"></iframe>
                    <script type="text/javascript">var m,h,i,o,v=[],s=window.location.href.slice(window.location.href.indexOf("?")+1).split("&"),z="sm_slyHotel";for(i=0;i<s.length;i++){h=s[i].split("=");v.push(h[0]);v[h[0]]=h[1]}v['hid']={/literal}{$hotel->getExternalId()}{literal};m=typeof v["sessionId"]!=="undefined"?"&sessionId="+v["sessionId"]:"";if(typeof v["hid"]!=="undefined"){i=parseInt(v["hid"],10);if(navigator.userAgent.toLowerCase().indexOf("firefox")>-1)o=document.getElementById(z).contentWindow;else{o=window.frames[z];if(typeof o.postMessage==="undefined")o=o.contentWindow};o.location="http://hotels.sletat.ru/?id="+i+m}</script>
                {/literal}
            {else}
                {if $hotel->getWebsite()}
                    <div class="links">
                        <a href="{$hotel->getWebsite()|escape}" class="blank hotel-description-link">
                            {$hotel->getWebsite()|escape}
                        </a>
                    </div>
                {/if}

                {if $hotel->getDescription()}
                    <div class="description-text content-common">
                        {$hotel->getDescription()}
                    </div>
                {/if}

                {if $hotel->getDescriptionUrl()}
                    <div class="links">
                        <a href="{$hotel->getDescriptionUrl()|escape}" class="blank hotel-description-full-link">
                            Подробнее
                        </a>
                    </div>
                {/if}

                {if count($hotel->getDescriptionData()) > 0}
                    <div class="description-data">
                        {$i = 1}

                        {foreach $hotel->getDescriptionData() as $data}
                            {if empty($data.options)}{continue}{/if}

                            <div class="description-data-col{if $i % 2 == 0} description-data-col-odd{/if}">
                                <div class="description-data-col-header">
                                    <strong>
                                        {$data.title|escape}
                                    </strong>
                                </div>

                                {foreach $data.options as $option}
                                    <div class="description-data-col-option">
                                        {$option|escape}
                                    </div>
                                {/foreach}
                            </div>

                            {if $i % 2 == 0}
                                <div class="clearfix"></div>
                            {/if}

                            {$i = $i + 1}
                        {/foreach}
                    </div>

                    <div class="clearfix"></div>
                {/if}
            {/if}
        {/if}
    </div>

    <div class="hotel-info-block hotel-info-block-navigation clearfix">
        <div class="nav-buttons">
            <a href="#" class="bordered-button button-write-feedback" onclick="{literal}$('.show-me-my-form').click(); $('html, body').animate({scrollTop: $('#form').offset().top}, 800); return false;{/literal}">Написать отзыв</a>

            {if $total_feedbacks > 0}
                <a href="#hotel-feedbacks-wrapper" class="bordered-button">Читать отзывы</a>
            {/if}
        </div>

        <div class="info">
            <div class="info-part">
                <span class="label">Фотографий:</span>
                <span class="value">{(int)$total_images}</span>
            </div>

            <div class="info-part">
                <span class="label">Отзывов:</span>
                <span class="value">{(int)$total_feedbacks}</span>
            </div>
        </div>
    </div>

    {if $hotel->getExtendedData('total_rating')}
        <div class="hotel-info-block hotel-info-block-ratings clearfix">
            <table class="table-ratings">
                <tr>
                    <td class="label">Состояние номера:</td>
                    <td class="value">{(int)$hotel->getExtendedData('rating_room')}</td>
                    <td class="separator">&nbsp;</td>
                    <td class="label">Сервис:</td>
                    <td class="value">{(int)$hotel->getExtendedData('rating_service')}</td>
                </tr>

                <tr>
                    <td class="label">Пляж:</td>
                    <td class="value">{(int)$hotel->getExtendedData('rating_beach')}</td>
                    <td class="separator">&nbsp;</td>
                    <td class="label">Питание:</td>
                    <td class="value">{(int)$hotel->getExtendedData('rating_food')}</td>
                </tr>

                <tr>
                    <td class="label">Территория:</td>
                    <td class="value">{(int)$hotel->getExtendedData('rating_territory')}</td>
                    <td class="separator">&nbsp;</td>
                    <td class="label">Анимация:</td>
                    <td class="value">{(int)$hotel->getExtendedData('rating_anim')}</td>
                </tr>
            </table>

            <div class="rating rating-{if $hotel->getExtendedData('total_rating') > 4.49}5{elseif $hotel->getExtendedData('total_rating') > 3.49}4{elseif $hotel->getExtendedData('total_rating') > 2.49}3{elseif $hotel->getExtendedData('total_rating') > 1.49}2{else}1{/if}">
                {(float)$hotel->getExtendedData('total_rating')|round:1}
            </div>
        </div>
    {/if}

    <div class="hotel-map">
        <div class="hotel-photos clearfix">
            {if $hotel->getGalleryOperator() !== null && count($hotel->getGalleryOperator()->getImages())}
                <div class="hotel-photos-operator">
                    <a class="spoiler" onclick="$('.hotel-photos-operator-body').slideToggle(); return false;" href="#">Фотографии туроператора</a>

                    <div class="hotel-photos-operator-body clearfix jcarousel-wrapper" style="display: none;">
                        <div class="jcarousel">
                            <ul>
                                {foreach $hotel->getGalleryOperator()->getImages() as $image}
                                    <li>
                                        <a class="fancybox-hotels" rel="hotel-photo-operator-{$hotel->getId()}" href="{$__url->thumb($image->getPath(), 800)}">
                                            <img src="{$__url->thumb($image->getPath(), 150, 110)}" width="150" height="110" alt="" />
                                        </a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>

                        <div class="jcarousel-control">
                            <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
                            <a href="#" class="jcarousel-control-next">&rsaquo;</a>
                        </div>
                    </div>
                </div>
            {/if}

            {if $hotel->getGalleryTourists() !== null && count($hotel->getGalleryTourists()->getImages())}
                <div class="hotel-photos-turist">
                    <a class="spoiler" onclick="$('.hotel-photos-turist-body').slideToggle(); return false;" href="#">Фотографии туристов</a>

                    <div class="hotel-photos-turist-body clearfix jcarousel-wrapper" style="display: none;">
                        <div class="jcarousel">
                            <ul>
                                {foreach $hotel->getGalleryTourists()->getImages() as $image}
                                    <li>
                                        <a class="fancybox-hotels" rel="hotel-photo-tourists-{$hotel->getId()}" href="{$__url->thumb($image->getPath(), 800)}">
                                            <img src="{$__url->thumb($image->getPath(), 150, 110)}" width="150" height="110" alt="" />
                                        </a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>

                        <div class="jcarousel-control">
                            <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
                            <a href="#" class="jcarousel-control-next">&rsaquo;</a>
                        </div>
                    </div>
                </div>
            {/if}

            {if $hotel->getGalleryAgency() !== null && count($hotel->getGalleryAgency()->getImages())}
                <div class="hotel-photos-agenstvo">
                    <a class="spoiler" onclick="$('.hotel-photos-agenstvo-body').slideToggle(); return false;" href="#">Фотографии агенства</a>

                    <div class="hotel-photos-agenstvo-body clearfix jcarousel-wrapper" style="display: none;">
                        <div class="jcarousel">
                            <ul>
                                {foreach $hotel->getGalleryAgency()->getImages() as $image}
                                    <li>
                                        <a class="fancybox-hotels" rel="hotel-photo-agency-{$hotel->getId()}" href="{$__url->thumb($image->getPath(), 800)}">
                                            <img src="{$__url->thumb($image->getPath(), 150, 110)}" width="150" height="110" alt="" />
                                        </a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>

                        <div class="jcarousel-control">
                            <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
                            <a href="#" class="jcarousel-control-next">&rsaquo;</a>
                        </div>
                    </div>
                </div>
            {/if}
        </div>
    </div>
</div>

{if $__f->successful}
    <div class="form-successful">
        <strong>Спасибо,</strong> ваш отзыв отправлен!
    </div>
{/if}

<div id="form-feedback-wrapper"{if !$form_submitted} style="display: none;"{/if}>
    <header class="content-header" id="form">
        <h2>Написать отзыв</h2>
    </header>

    <div>
        {$form_html}
    </div>
</div>

{if !$form_submitted}
    <a href="#" onclick="$(this).remove(); $('#form-feedback-wrapper').slideDown('fast'); return false;" class="show-me-my-form">Форма отзыва</a>
{/if}

{if count($hotel->getFeedbacks()) > 0}
    <div class="hotels-otzivi-body" id="hotel-feedbacks-wrapper">
        {foreach $hotel->getFeedbacks() as $feedback}
            <div class="hotels-otzivi-message-body">
                <table border="0">
                    <tbody>
                        <tr>
                            <td width="150">
                                {if count($feedback->getExtendedData('photos')) > 0}
                                    <a href="{$__url->thumb(current($feedback->getExtendedData('photos')), 800)}" class="fancybox-hotels" rel="feedback-hotel">
                                        <img style="float: left;" src="{$__url->thumb(current($feedback->getExtendedData('photos')), 90, 90)}" alt="" />
                                    </a>
                                {/if}

                                <div class="hotels-otzivi-message-body-ocenka rating-{if $hotel->getExtendedData('total_rating') > 4.49}5{elseif $hotel->getExtendedData('total_rating') > 3.49}4{elseif $hotel->getExtendedData('total_rating') > 2.49}3{elseif $hotel->getExtendedData('total_rating') > 1.49}2{else}1{/if}">{(float)$feedback->getTotalHotelRating()|round:1}</div>
                            </td>

                            <td width="125" align="left" valign="middle">
                                Состояние номера:
                                <br />
                                <br />
                                Пляж:
                            </td>

                            <td width="70" align="left" valign="middle">
                                <b>{(int)$feedback->getExtendedData('rating_room')}</b>
                                <br />
                                <br />
                                <b>{(int)$feedback->getExtendedData('rating_beach')}</b>
                            </td>

                            <td width="60" align="left" valign="middle">
                                Сервис:
                                <br />
                                <br />
                                Питание:
                            </td>

                            <td width="70" align="left" valign="middle">
                                <b>{(int)$feedback->getExtendedData('rating_service')}</b>
                                <br />
                                <br />
                                <b>{(int)$feedback->getExtendedData('rating_food')}</b>
                            </td>

                            <td width="80" align="left" valign="middle">
                                Территория:
                                <br />
                                <br />
                                Анимация:
                            </td>

                            <td width="70" align="left" valign="middle">
                                <b>{(int)$feedback->getExtendedData('rating_territory')}</b>
                                <br />
                                <br />
                                <b>{(int)$feedback->getExtendedData('rating_anim')}</b>
                            </td>
                        </tr>

                        <tr>
                            <td valign="top">
                                {$feedback->getUserName()|escape}
                                <br />
                                {$feedback->getCreated()->setDefaultTimeZone()->format('Y-m-d')}
                            </td>

                            <td align="left" valign="top"></td>
                            <td align="left" valign="top"></td>
                            <td align="left" valign="top"></td>
                            <td align="left" valign="top"></td>
                            <td align="left" valign="top"></td>
                            <td align="left" valign="top"></td>
                        </tr>
                    </tbody>
                </table>

                <br />

                <div class="hotels-otzivi-message-text">{$feedback->getMessage()|escape}</div>

                {if count($feedback->getExtendedData('photos')) > 1}
                    <div class="feedback-images">
                        {foreach $feedback->getExtendedData('photos') as $photo}
                            {if $photo@first}{continue}{/if}

                            <a href="{$__url->thumb($photo, 800)}" class="fancybox-hotels" rel="feedback-hotel">
                                <img src="{$__url->thumb($photo, 90, 90)}" alt="" />
                            </a>
                        {/foreach}
                    </div>
                {/if}
            </div>
        {/foreach}
    </div>
{/if}