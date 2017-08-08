{$__ctx->setPageTitle('Поиск попутчика')}

<div class="content content-common">
    {if $__f->successful}
        <div class="form-successful"><strong>Спасибо,</strong> ваша заявка на поиск партнёра отправлена!</div>
    {/if}

    <div id="form-companion-wrapper"{if !$form_submitted} style="display: none;"{/if}>
        <header class="content-header" id="form">
            <h2>Поиск попутчика</h2>
        </header>

        <div>
            {$form_html}
        </div>
    </div>

    {if !$form_submitted}
        <a href="#" onclick="{literal}$(this).remove(); $('#form-companion-wrapper').slideDown('fast', function () { $('#form-companion-wrapper .ik-select').ikSelect('redraw'); }); return false;{/literal}" class="show-me-my-form">Форма заявки</a>
    {/if}

    {if !empty($list)}
        <div class="companions-list">
            {foreach $list as $companion}
                <div class="companion clearfix">
                    {if $companion->getUserPhoto()}
                        <img src="{$__url->thumb($companion->getUserPhoto(), 205, 205)}" alt="Фотография" />
                    {else}
                        <img src="{$__url->img('/frontend/content/companion-no-photo.gif')}" alt="Нет фотографии" />
                    {/if}

                    <div class="companion-description clearfix">
                        <div class="companion-description-right">
                            {$companion->getUserGenderAsString()|escape} ищет {if $companion->getTargetGender() !== 'UNKNOWN'}{$companion->getTargetGenderAsString(true)|escape|lower}{else}попутчика{/if}

                            {$companion->getNotes()|escape}
                        </div>

                        <div class="companion-description-left">
                            <div class="param">
                                <span class="label">
                                    Страна:
                                </span>

                                <span class="value">
                                    {$companion->getLocation()|escape}
                                </span>
                            </div>

                            <div class="param">
                                <span class="label">
                                    Дата вылета:
                                </span>

                                <span class="value">
                                    с {$companion->getDateFrom()->setDefaultTimeZone()->format('d.m.Y')} по {$companion->getDateTo()->setDefaultTimeZone()->format('d.m.Y')}
                                </span>
                            </div>

                            <div class="param">
                                <span class="label">
                                    Количество дней:
                                </span>

                                <span class="value">
                                    от {(int)$companion->getDaynumFrom()} по {(int)$companion->getDaynumTo()}
                                </span>
                            </div>

                            <div class="param">
                                <span class="label">
                                    Бюджет:
                                </span>

                                <span class="value">
                                    {$companion->getPrice()|escape|default:"не указан"}
                                </span>
                            </div>

                            <div class="param">
                                <span class="label">
                                    Имя:
                                </span>

                                <span class="value">
                                    {$companion->getUserName()|escape}
                                    {if $companion->getUserAge() > 0}({(int)$companion->getUserAge()}){/if}
                                </span>
                            </div>

                            <div class="param">
                                <span class="label">
                                    Город:
                                </span>

                                <span class="value">
                                    {$companion->getUserCity()|escape}
                                </span>
                            </div>

                            <div class="param">
                                <span class="label">
                                    Тел.:
                                </span>

                                <span class="value">
                                    {$companion->getUserPhone()|escape}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>

        {include file="frontend/include/paginator.tpl.php" state=$state}
    {/if}
</div>