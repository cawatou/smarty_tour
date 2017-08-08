{$__ctx->setPageTitle('Как купить тур он-лайн')}

<div class="content content-common content-common-howtobuy">
    <h1>Как купить тур on-line</h1>

    <div class="content-body">
        <div class="bullet-point">
            <h2>1. Выберите тур на главной</h2>

            <p>
                Наши специалисты ежедневно работают над поддержанием базы горящих туров в актуальном состоянии.
            </p>

            <p>
                <b>Шаг 1:</b>
                Выберите понравившийся тур на главной странице сайта и щёлкните по нему левой кнопкой мыши
            </p>

            <a href="{$__url->main()}" class="helper-image">
                <img src="{$__url->img('/frontend/howtobuy-1-1.png')}" alt="" />

                <span></span>
            </a>

            <p>
                <b>Шаг 2:</b>
                Выберите отель и дату вылета, которые вам больше всего подходят и перейдите к покупке тура с помощью кнопки &laquo;Купить&raquo;.
            </p>

            <a href="{$__url->main()}" class="helper-image">
                <img src="{$__url->img('/frontend/howtobuy-1-2.png')}" alt="" />

                <span></span>
            </a>
        </div>

        <div class="bullet-point">
            <h2>2. Найдите тур самостоятельно</h2>

            <p>
                <b>Шаг 1:</b>
                В верхнем меня выберите пункт &laquo;Поиск тура&raquo;.
            </p>

            <a href="{$__url->main()}" class="helper-image">
                <img src="{$__url->img('/frontend/howtobuy-2-1.png')}" alt="" />

                <span></span>
            </a>

            <p>
                <b>Шаг 2:</b>
                Выберите подходящие опции для фильтра и нажмите на &laquo;Искать туры!&raquo;.
            </p>

            <a href="{$__url->url('/search')}" class="helper-image">
                <img src="{$__url->img('/frontend/howtobuy-2-2.png')}" alt="" />

                <span></span>
            </a>

            <p>
                <b>Шаг 3:</b>
                Выберите подходящий тур, который соответствует вашим предпочтениям в цене и отеле и нажмите на &laquo;Подробнее&raquo;.
            </p>

            <a href="{$__url->url('/search')}" class="helper-image">
                <img src="{$__url->img('/frontend/howtobuy-2-3.png')}" alt="" />

                <span></span>
            </a>
        </div>

        <div class="bullet-point">
            <h2>3. Закажите подбор тура</h2>

            <p>
                Доверьтесь нашим профессионалам с огромным опытом в выборе отдыха по вашим желаниям.
            </p>

            <a href="{$__url->url('/order')}" class="request-a-tour-button">Заявка на покупку тура on-line</a>
        </div>
    </div>
</div>