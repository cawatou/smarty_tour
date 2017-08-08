{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Данные о поиске попутчика изменены'|t}
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
        <div class="col-md-8">
            <div class="form-group form-group-required{if $__f->e('companion_notes') !== null} has-error{/if}">
                <label for="{$__f->encode('companion_notes')}">{'Сообщение'|t} <i class="fa fa-check"></i></label>
                <textarea name="{$__f->encode('companion_notes')}" id="{$__f->encode('companion_notes')}" class="form-control form-textarea-vertical">{$__f->m()->getNotes()|escape}</textarea>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group{if !is_null($__f->e('companion_agency_notes'))} has-error{/if}">
                <label for="{$__f->encode('companion_agency_notes')}">{'Заметка агентства'|t}</label>
                <textarea name="{$__f->encode('companion_agency_notes')}" id="{$__f->encode('companion_agency_notes')}" class="form-control form-textarea-vertical">{$__f->m()->getAgencyNotes()|escape}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="radio radio-title">{'Статус'|t}</div>

                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('companion_status')}" value="ENABLED"{if $__f->m()->getStatus() == 'ENABLED'} checked{/if}> {'Показывать'|t}
                    </label>
                </div>

                <div class="radio">
                    <label>
                        <input type="radio" name="{$__f->encode('companion_status')}" value="DISABLED"{if $__f->m()->getStatus() == 'DISABLED'} checked{/if}> {'Скрывать'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {if $__f->getId() == 'companion_edit'}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
                {else}
                    <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить запись'|t}</button>
                {/if}

                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.companion')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>