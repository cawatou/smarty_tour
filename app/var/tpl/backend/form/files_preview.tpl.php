{$__ctx->addCss('/backend/form/files_preview.css')}
{if !empty($__f->errors)}
<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {'Произошла ошибка, проверьте правильность заполнения полей'|t}
</div>
{/if}

<form role="form" method="post" action="{$__f->getUrl()}">
    <div class="cms-group cms-group-white">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-group-required{if !is_null($__f->e('master'))} has-error{/if}">
                    <label for="{$__f->encode('master')}">{'Метод создания'|t}</label>
                    <select name="{$__f->encode('master')}" id="{$__f->encode('master')}" class="form-control" onchange="visibilityCropTable()">
                        <option value="RESIZE"{if $__f->v('master') == 'RESIZE'} selected{/if}>{'Масштабировать'|t}</option>
                        <option value="CROP"{if $__f->v('master') == 'CROP'} selected{/if}>{'Обрезать точно по размеру'|t}</option>
                        <option value="RESIZECROP"{if $__f->v('master') == 'RESIZECROP'} selected{/if}>{'Масштабировать и обрезать'|t}</option>
                    </select>
                    <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                </div>
            </div>
        </div>
        <div class="row" id="crop_table">
            <div class="col-md-4">
                <div class="form-group">
                    <label>{'Видимая область'|t}</label>
                    <table class="cropTable">
                        <tr>
                            <td><input type="radio" name="{$__f->encode('crop')}" value="LT"{if $__f->v('crop') == 'LT'} checked="checked"{/if} title="{'Слева сверху'|t}" /></td>
                            <td><input type="radio" name="{$__f->encode('crop')}" value="CT"{if $__f->v('crop') == 'CT'} checked="checked"{/if} title="{'По центру сверху'|t}" /></td>
                            <td><input type="radio" name="{$__f->encode('crop')}" value="RT"{if $__f->v('crop') == 'RT'} checked="checked"{/if} title="{'Справа сверху'|t}" /></td>
                        </tr>
                        <tr>
                            <td><input type="radio" name="{$__f->encode('crop')}" value="LM"{if $__f->v('crop') == 'LM'} checked="checked"{/if} title="{'Cлева посередине'|t}" /></td>
                            <td><input type="radio" name="{$__f->encode('crop')}" value="CM"{if $__f->v('crop') == 'CM'} checked="checked"{/if} title="{'По центру посередине'|t}" /></td>
                            <td><input type="radio" name="{$__f->encode('crop')}" value="RM"{if $__f->v('crop') == 'RM'} checked="checked"{/if} title="{'Справа посередине'|t}" /></td>

                        </tr>
                        <tr>
                            <td><input type="radio" name="{$__f->encode('crop')}" value="LB"{if $__f->v('crop') == 'LB'} checked="checked"{/if} title="{'Слева снизу'|t}" /></td>
                            <td><input type="radio" name="{$__f->encode('crop')}" value="CB"{if $__f->v('crop') == 'CB'} checked="checked"{/if} title="{'По центру снизу'|t}" /></td>
                            <td><input type="radio" name="{$__f->encode('crop')}" value="RB"{if $__f->v('crop') == 'RB'} checked="checked"{/if} title="{'Справа снизу'|t}" /></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="cms-group cms-group-white">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-group-required{if !is_null($__f->e('fill_color'))} has-error{/if}">
                    <label for="{$__f->encode('fill_color')}">{'Цвет подложки'|t} <i class="fa fa-check"></i></label>
                    <input type="text" class="form-control" name="{$__f->encode('fill_color')}" value="{$__f->v('fill_color')|escape}" id="{$__f->encode('fill_color')}">
                    <span class="help-block help-block-error">{'Не верный формат'|t}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="{$__f->encode('fill_transparent')}" value="1"{if $__f->v('fill_transparent')} checked{/if}> {'По возможности прозрачная'|t}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('thumb_quality'))} has-error{/if}">
                <label for="{$__f->encode('thumb_quality')}">{'Качество'|t} <i class="fa fa-check"></i></label>
                <input type="text" class="form-control" name="{$__f->encode('thumb_quality')}" value="{$__f->v('thumb_quality')|escape}" id="{$__f->encode('thumb_quality')}">
                <span class="help-block">{'Укажите значение от 50 до 100%'|t}</span>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Сохранить'|t}</button>
                <button class="btn btn-default btn-ref" data-href="{$__url->adm($route)}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>
{literal}
<script type="text/javascript">
    var
        sel = document.querySelector('#{/literal}{$__f->encode('master')}{literal}'),
        tbl = document.querySelector('#crop_table');
        function visibilityCropTable() {
            if (sel.value == 'RESIZE') {
                tbl.style.display = 'none';
            } else {
                tbl.style.display = 'block';
            }
        }
        visibilityCropTable();
</script>
{/literal}