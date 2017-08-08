{$__ctx->addCss('/backend/form/files_upload.css')}
{literal}
<script type="text/javascript">
    function listFiles() {
        document.querySelector('#bag').style.display = 'block';
        var input = document.querySelector("input[type='file']"),
            ul = document.querySelector("#bag>ul");
        while (ul.hasChildNodes()) {
            ul.removeChild(ul.firstChild);
        }
        for (var i = 0; i < input.files.length; i++) {
            var li = document.createElement("li");
            li.innerHTML = input.files[i].name;
            ul.appendChild(li);
        }
    }
</script>
{/literal}

{if !empty($__f->errors)}
<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {'Произошла ошибка, проверьте правильность заполнения полей'|t}
</div>
{/if}

<form role="form" method="post" action="{$__f->getUrl()}" enctype="multipart/form-data">
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
        <div class="col-md-5">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="{$__f->encode('image_compress')}" value="1" checked> {'Сжимать все загружаемые изображения (Ширина: 1000 px, качество: 75%)'|t}
                </label>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-required{if !is_null($__f->e('files'))} has-error{/if}">
                <label for="{$__f->encode('files')}">{'Файлы'|t} <i class="fa fa-check"></i></label>
                <input type="file" name="{$__f->encode('files')}[]" value="" id="{$__f->encode('files')}" multiple onchange="listFiles()">
                <span class="help-block">{'Количество файлов загружаемых за один раз:'|t} {$max_file_uploads}<br />{'Максимальный размер файлов:'|t} {$max_upload} Mb</span>
                <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
            </div>
        </div>
    </div>



    <div id="bag"><ul><!-- --></ul></div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Начать загрузку'|t}</button>
                <button class="btn btn-default btn-ref" data-href="{$__url->adm($route)}{if !is_null($__f->v('path'))}?path={$__f->v('path')}{/if}">{'Отмена'|t}</button>
            </div>
        </div>
    </div>
</form>