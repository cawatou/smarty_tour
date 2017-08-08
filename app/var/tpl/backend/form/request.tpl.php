{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Данные о заявке изменены
    </div>
{/if}

{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Произошла ошибка, проверьте правильность заполнения полей
    </div>
{/if}
<form role="form" method="post" action="{$__f->getUrl()}">
    <div class="row">
        <div class="col-md-8">
            {if $__ctx->getCurrentUser()->isUserInRoles(array('DIRECTOR', 'OPERATOR', 'SELLER')) && $__f->m()->getUserId()}
                <div style="margin-bottom: 10px;">
                    Пользователь <strong>{$__f->m()->getUser()->getName()|escape}</strong> обрабатывает данное сообщение

                    <input type="hidden" name="{$__f->encode('user_id')}" value="{$__f->m()->getUserId()}">
                </div>
            {else}
                <div class="form-group{if !is_null($__f->e('user_id'))} has-error{/if}">
                    <label for="{$__f->encode('user_id')}">Обрабатывает сообщение</label>

                    <select name="{$__f->encode('user_id')}" class="form-control" id="{$__f->encode('user_id')}">
                        <option value="">Выберите пользователя</option>

                        {foreach $__ctx->getCurrentUser()->getNeighborUsers() as $user}
                            <option value="{$user->getId()}"{if $__f->m()->getUserId() && $user->getId() == $__f->m()->getUserId()} selected{elseif !$__f->m()->getUserId() && $user->getId() == $__ctx->getCurrentUser()->getId()}{/if}>
                                {$user->getName()|escape}
                            </option>
                        {/foreach}
                    </select>

                    <span class="help-block help-block-error">Неверное значение</span>
                </div>
            {/if}
        </div>
    </div>

    {if $__f->m()->getType() != 'CALLBACK'}
        {if $__f->m()->getType() != 'SLETAT_ORDER'}
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group form-group-required{if !is_null($__f->e('request_message'))} has-error{/if}">
                        <label for="{$__f->encode('request_message')}">Сообщение <i class="fa fa-check"></i></label>
                        <textarea name="{$__f->encode('request_message')}" id="{$__f->encode('request_message')}" class="form-control form-textarea-vertical">{$__f->m()->getMessage()|escape}</textarea>
                        <span class="help-block help-block-error">Обязательное поле</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group{if !is_null($__f->e('request_answer'))} has-error{/if}">
                        <label for="{$__f->encode('request_answer')}">Ответ</label>
                        <textarea name="{$__f->encode('request_answer')}" id="{$__f->encode('request_answer')}" class="form-control form-textarea-vertical">{$__f->m()->getAnswer()|escape}</textarea>
                        <span class="help-block help-block-error">Неверное значение</span>
                    </div>
                </div>
            </div>
        {else}
            <label>Сообщение</label>

            <p>
                {$__f->m()->getMessage()|escape|nl2br}
            </p>

            <textarea name="{$__f->encode('request_message')}" id="{$__f->encode('request_message')}" class="form-control form-textarea-vertical" style="display: none;">{$__f->m()->getMessage()|escape}</textarea>
        {/if}
    {/if}

    {if $__f->m()->getUserEmail() !== null && $__f->m()->getType() != 'SLETAT_ORDER'}
        <div class="row">
            <div class="col-md-8">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="{$__f->encode('send_notification')}" value="1"> Выслать уведомление об ответе на {$__f->m()->getUserEmail()|escape}
                    </label>
                </div>
            </div>
        </div>
    {/if}

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">Статус</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('request_status')}" value="ENABLED"{if $__f->m()->getStatus() == 'ENABLED'} checked{/if}> Новая заявка
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('request_status')}" value="DISABLED"{if $__f->m()->getStatus() == 'DISABLED'} checked{/if}> Обработано
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {if $__f->getId() == 'request_edit'}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">Внести изменения</button>
                {else}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">Добавить заявку</button>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.request')}">Отмена</button>
            </div>
        </div>
    </div>
</form>