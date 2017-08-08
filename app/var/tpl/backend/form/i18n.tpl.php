{if $__f->successful}
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {'Данные изменены'|t}
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
            <div class="form-group form-group-required{if !is_null($__f->e('i18n_source_string'))} has-error{/if}">
                <label for="{$__f->encode('i18n_source_string')}">{'Оригинал'|t}</label>
                <textarea name="{$__f->encode('i18n_source_string')}" id="{$__f->encode('i18n_source_string')}" class="form-control form-textarea-vertical" readonly>{$__f->m()->getSourceString()|escape}</textarea>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group form-group-required{if !is_null($__f->e('i18n_target_string'))} has-error{/if}">
                <label for="{$__f->encode('i18n_target_string')}">{'Перевод'|t} <i class="fa fa-check"></i></label>
                <textarea name="{$__f->encode('i18n_target_string')}" id="{$__f->encode('i18n_target_string')}" class="form-control form-textarea-vertical">{$__f->m()->getTargetString()|escape}</textarea>
                <span class="help-block help-block-error">{'Обязательное пол'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('i18n_source_locale'))} has-error{/if}">
                <label for="{$__f->encode('i18n_source_locale')}">{'Локаль оригинала'|t}</label>
                <input type="text" class="form-control" name="{$__f->encode('i18n_source_locale')}" value="{$__f->m()->getSourceLocale()|escape}" id="{$__f->encode('i18n_source_locale')}" readonly>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('i18n_target_locale'))} has-error{/if}">
                <label for="{$__f->encode('i18n_target_locale')}">{'Локаль перевода'|t}</label>
                <input type="text" class="form-control" name="{$__f->encode('i18n_target_locale')}" value="{$__f->m()->getTargetLocale()|escape}" id="{$__f->encode('i18n_target_locale')}" readonly>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('i18n_source_tag'))} has-error{/if}">
                <label for="{$__f->encode('i18n_source_tag')}">{'Тег'|t}</label>
                <input type="text" class="form-control" name="{$__f->encode('i18n_source_tag')}" value="{$__f->m()->getSourceTag()|escape}" id="{$__f->encode('i18n_source_tag')}" readonly>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
            {if $__f->getId() == 'i18n_edit'}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
            {else}
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить'|t}</button>
            {/if}
                <button class="btn btn-default btn-ref" data-href="{$__url->adm('.i18n')}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>