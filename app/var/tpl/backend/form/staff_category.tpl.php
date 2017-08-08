{if $__f->successful}
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {'Данные об отделе изменены'|t}
</div>
{/if}
{if !empty($__f->errors)}
<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {'Произошла ошибка, проверьте правильность заполнения полей'|t}
</div>
{/if}

<form role="form" method="post" action="{$__f->getUrl()}">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('staff_category_title'))} has-error{/if}">
                <label for="{$__f->encode('staff_category_title')}">{'Имя'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('staff_category_title')}" value="{$__f->m()->getTitle()|escape}" id="{$__f->encode('staff_category_title')}">
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group{if !is_null($__f->e('staff_category_description'))} has-error{/if}">
                <label for="{$__f->encode('staff_category_description')}">Описание</label>
                <textarea name="{$__f->encode('staff_category_description')}" id="{$__f->encode('staff_category_description')}" class="form-control form-textarea-vertical">{$__f->m()->getDescription()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">{'Статус'|t}</div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('staff_category_status')}" value="ENABLED"{if $__f->m()->getStatus() == 'ENABLED'} checked{/if}> {'Показывать'|t}
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('staff_category_status')}" value="DISABLED"{if $__f->m()->getStatus() == 'DISABLED'} checked{/if}> {'Скрывать'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
            {if $__f->getId() == 'staff_category_edit'}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
                {else}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить отдел'|t}</button>
            {/if}
                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.staff.category')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>