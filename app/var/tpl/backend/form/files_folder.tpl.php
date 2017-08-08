{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Произошла ошибка, проверьте правильность заполнения полей'|t}
    </div>
{/if}
<form role="form" method="post" action="{$__f->getUrl()}">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('path'))} has-error{/if}">
                <label for="{$__f->encode('path')}">{'Путь'|t}</label>
                <select name="{$__f->encode('path')}" id="{$__f->encode('path')}" class="form-control">
                {foreach from=$dir_tree item='i'}
                <option value="{$i.path|escape}"{if $i.path == $__f->v('path')} selected="selected"{/if}>{'&bull;'|str_repeat:($i.level-1)} {$i.name|escape}</option>
                {/foreach}
                </select>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>    

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('name'))} has-error{/if}">
                <label for="{$__f->encode('name')}">{'Имя папки'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('name')}" value="{$__f->v('name')|escape}" id="{$__f->encode('name')}">
                <span class="help-block help-block-error">{'Не может быть пустым, может содержать только латинские символы или цифры'|t}</span>
            </div>
        </div>
    </div>    

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Создать папку'|t}</button>
                <button class="btn btn-default btn-ref" data-href="{$__url->adm($route)}{if !is_null($__f->v('path'))}?path={$__f->v('path')}{/if}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>    
</form>