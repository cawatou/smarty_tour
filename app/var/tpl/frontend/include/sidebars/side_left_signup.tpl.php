{$email_alias = $__ctx->getCity()->getAlias()}
{$sms_id   = $__ctx->getCity()->getSmsSubscriptionId()}

{if $email_alias !== null}
    <div class="signups signup-email">
        <header>
            <h3>E-mail рассылка</h3>

            <small>
                горящих Туров из {$__ctx->getCity()->getTitle()|escape}a
            </small>
        </header>

        <form action="http://pro.subscribe.ru/member/fynvby/join" method="post" target="_blank" class="b-form" id="form-signup-email">
            <input name="email" class="input-subscribe" type="text" placeholder="Ваш E-MAIL" style="margin-bottom: 5px;"/>
            <a name="a378"> </a>
            <input type="hidden" name="try_a_a378_q647_" value="1" />
            <input type="hidden" name="a_a378_q647_" value="{$email_alias|escape}" />

            <input class="subscribe" type="submit" value="" alt="Подписаться" />
{*
            <a class="blank unsubscribe" href="http://pro.subscribe.ru/member/fynvby/astalavista" target="_blank">
                ОТКАЗАТЬСЯ ОТ РАССЫЛКИ
            </a>
 *}
        </form>
    </div>
{/if}

{if $sms_id !== null}
    <div class="signups signup-sms">
        <header>
            <h3>SMS РАССЫЛКА</h3>
            <small>
                горящих Туров из {$__ctx->getCity()->getTitleParental()|escape}
            </small>
        </header>

        <form action="{$__url->url('/signup/sms')}" method="post" target="_blank" class="b-form" id="form-signup-sms">
            <input name="NumTel" class="input-subscribe" type="text" placeholder="Ваш моб. телефон" style="margin-bottom: 5px;"/>

            <button class="subscribe">&nbsp;</button>
{*
            <a href="#" class="unsubscribe">
                ОТКАЗАТЬСЯ ОТ РАССЫЛКИ
            </a>
  *}
        </form>

        <form action="{$__url->url('/signup/sms')}" method="post" target="_blank" id="form-signup-sms-code">
            <input name="code" class="input-code-code" type="text" placeholder="Код подтверждения" />

            <button type="submit" style="display: block;">Отправить</button>
        </form>

        <div id="form-signup-sms-congratz" class="hidden">
            Спасибо за подписку!
        </div>
    </div>
{/if}

{$__ctx->addJs('/frontend/form-signup.js')}