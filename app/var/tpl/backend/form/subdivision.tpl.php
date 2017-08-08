{assign var="model" value=$__f->getModel()}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Данные о подразделении изменены'|t}
    </div>
{/if}

{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Произошла ошибка, проверьте правильность заполнения полей'|t}
    </div>
{/if}

<form method="POST" action="{$__f->getUrl()}">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('subdivision_title') !== null} has-error{/if}">
                <label for="{$__f->encode('subdivision_title')}">{'Название'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('subdivision_title')}" value="{$model->getTitle()|escape}" id="{$__f->encode('subdivision_title')}">
                <span class="help-block help-block-error">{if $__f->e('subdivision_title') === 'ALREADY_EXISTS'}Такой город уже существует{else}{'Обязательное поле'|t}{/if}</span>
            </div>
        </div>
    </div>

    <div class="row hidden">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('subdivision_alias') !== null} has-error{/if}">
                <label for="{$__f->encode('subdivision_alias')}">{'Alias'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('subdivision_alias')}" value="{$model->getAlias()|escape}" id="{$__f->encode('subdivision_alias')}">
                <span class="help-block help-block-error">{if $__f->e('subdivision_alias') === 'ALREADY_EXISTS'}Город с таким alias уже существует{elseif $__f->e('subdivision_alias') == 'INVALID_FORMAT'}Неверный формат. Допускаются только a-z, -, _{else}Не может быть пустым{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">{'Статус'|t}</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('subdivision_status')}" value="ENABLED"{if $model->getStatus() === 'ENABLED'} checked="checked"{/if}> {'Показывать'|t}
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('subdivision_status')}" value="DISABLED"{if $model->getStatus() === 'DISABLED'} checked="checked"{/if}> {'Скрывать'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {if $__f->getId() === 'subdivision_edit'}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
                {else}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить подразделение'|t}</button>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.subdivision')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>