{assign var="model" value=$__f->m()}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Данные о туроператоре изменены
    </div>
{/if}

{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Произошла ошибка, проверьте правильность заполнения полей
    </div>
{/if}

<form method="post" action="{$__f->getUrl()}">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if $__f->e('touroperator_title') !== null} has-error{/if}">
                <label for="{$__f->encode('touroperator_title')}">Название <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('touroperator_title')}" value="{$model->getTitle()|escape}" id="{$__f->encode('touroperator_title')}">
                <span class="help-block help-block-error">{if $__f->e('touroperator_title') === 'ALREADY_EXISTS'}Туроператор с таким названием уже существует{else}Обязательное поле{/if}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">Статус</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('touroperator_status')}" value="ENABLED"{if $model->getStatus() === 'ENABLED'} checked="checked"{/if}> Показывать
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('touroperator_status')}" value="DISABLED"{if $model->getStatus() === 'DISABLED'} checked="checked"{/if}> Скрывать
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {if $__f->getId() === 'touroperator_edit'}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">Внести изменения</button>
                {else}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">Добавить туроператор</button>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.touroperator')}">Отмена</button>
            </div>
        </div>
    </div>
</form>