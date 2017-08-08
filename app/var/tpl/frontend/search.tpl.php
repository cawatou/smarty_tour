{$__ctx->setPageTitle('Подбор тура On-line')}

<div>
    <p>&nbsp;</p>
    {literal}
        <script type="text/javascript" src="//ui.sletat.ru/module-4.0/core.js" charset="utf-8"></script>
        <script type="text/javascript">
        try {
            sletat.FrameSearch.$create({
                useForm:        true,
                classes:        "iframe-sletat-main",
                useRequest:     true,
				useCard: true,
                useManyOffices: true,
                agencyContact1: {
                    logo:   "{/literal}{$__url->img('/frontend/logo.gif')}{literal}",
                    header: "Мой горящий тур",
                    phone:  "8-800-500-01-34"
                },
                agencyContact2: {
                    logo:   "{/literal}{$__url->img('/frontend/logo.gif')}{literal}",
                    header: "Мой горящий тур",
                    phone:  "8-800-500-01-34"
                },
                disabledCurrencies: [
                    "EUR",
                    "USD"
                ]
            });
        } catch (e) {
        }
        </script>
        <span class="sletat-copyright">Идет загрузка модуля <noindex><a href="http://sletat.ru/" title="поиск туров" target="_blank" rel="nofollow">поиска туров</a></noindex> &hellip;</span>
    {/literal}

    <div class="form-intro">
        <h4>Важно!</h4>

        <p>
            При выборе варианта оплата тура on-line, стоимость тура будет увеличена на 2% (комиссия платёжной системы).
        </p>
    </div>
</div>

