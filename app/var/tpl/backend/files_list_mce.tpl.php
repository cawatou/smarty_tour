{$__ctx->addJs('/backend/tiny_mce/tiny_mce_popup.js')}
{literal}
<script type="text/javascript">
var FileBrowserDialogue = {
    init : function () {
        // Here goes your code for setting your custom things onLoad.
    },
    mySubmit : function (url) {
        var win = tinyMCEPopup.getWindowArg("window");
        // insert information now
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = url;
        // close popup window
        tinyMCEPopup.close();
    }
}
tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);
</script>
{/literal}

<div class="container container-files" style="padding: 20px 0;">
    {include file='backend/submenu/files.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
            <ol class="breadcrumb">
                <li><strong>{'Путь'|t}:</strong></li>
                {if $path == '/'}
                    <li><a href="{$__url->cmd($cmd, '?path=/')}">files</a></li>
                {else}
                    <li><a href="{$__url->cmd($cmd, '?path=/')}">files</a></li>
                    {assign var='_path' value=''}
                    {foreach $path_parts as $i}
                    {assign var='_path' value="`$_path`/`$i`"}
                    <li><a href="{$__url->cmd($cmd, "?path=`$_path`")}">{$i}</a></li>
                    {/foreach}
                {/if}
            </ol>
        {if !$is_writable}
            <div class="alert alert-danger">
                {'Внимание! На эту папку отсутствуют права на запись. Обратитесь к администратору.'|t}
            </div>
            {else}
            {if empty($files)}
                <div class="alert alert-info">
                    {if $__ctx->userCanCreate($cmd)}
                    {"Папка пуста. Вы можете <a href=':url_file'>добавить</a> файлы или <a href=':url_dir'>создать</a> вложенную папку."|t:null:[':url_file'=>$__url->cmd("`$cmd`.addFile", "?path=`$path`"),':url_dir'=>$__url->cmd("`$cmd`.addFolder", "?path=`$path`")]}
                    {else}
                    {'Папка пуста'|t}
                    {/if}
                </div>
                {else}
                <div class="table-responsive">
                    <table class="table table-responsive table-striped stupidtable">
                        <colgroup>
                            <col width="45%" />
                            <col width="10%" />
                            <col width="10%" />
                            <col width="10%" />
                            <col width="15%" />
                            <col width="10%" />
                        </colgroup>
                        <thead>
                        <tr class="center">
                            <th class="stupidtable-sorting" data-sort="string-ins" data-fs="fs">{'Имя'|t} <span class="desc"><i class="fa fa-sort-alpha-desc"></i></span><span class="asc"><i class="fa fa-sort-alpha-asc"></i></span></th>
                            <th class="stupidtable-sorting" data-sort="string-ins" data-fs="fs">{'Тип'|t} <span class="desc"><i class="fa fa-sort-amount-desc"></i></span><span class="asc"><i class="fa fa-sort-amount-asc"></i></span></th>
                            <th>{'Права'|t}</th>
                            <th class="stupidtable-sorting" data-sort="int" data-fs="fs">{'Размер'|t} <span class="desc"><i class="fa fa-sort-numeric-desc"></i></span><span class="asc"><i class="fa fa-sort-numeric-asc"></i></span></th>
                            <th class="stupidtable-sorting sorting-desc" data-sort="int" data-fs="fs">{'Дата'|t} <span class="desc"><i class="fa fa-sort-amount-desc"></i></span><span class="asc"><i class="fa fa-sort-amount-asc"></i></span></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            {foreach $files as $i}
                            <tr{if in_array($i.name, $backlight)} class="warning"{/if} {if $i.type == 'DIR'}data-fs-value="dir"{else}data-fs-value="file"{/if}>
                                {if $i.type == 'DIR'}
                                    <td data-sort-value="{$i.name|escape}">
                                        <i class="fa fa-folder-open-o"></i> <a href="{$__url->cmd($cmd)}?path={$i.path|escape}">{$i.name|escape}</a>
                                    </td>
                                    <td class="center">&mdash;</td>
                                    <td class="center">{$i.mode|escape}</td>
                                    <td class="center" data-sort-value="0">&lt;dir&gt;</td>
                                    <td class="center small" data-sort-value="{$i.time|escape}">{$i.time|date_format:"%d.%m.%y %H:%M"}</td>
                                    {else}
                                    <td data-sort-value="{$i.file_name|escape}">
                                        {if $i.is_img}<i class="fa fa-picture-o"></i>{else}<i class="fa fa-file-o"></i>{/if}
                                        <span class="filename" title="{$i.name|escape}" onclick="FileBrowserDialogue.mySubmit('{$i.uri|escape}')">{$i.file_name|escape|truncate:35:"…":true}</span>
                                        {if $i.is_img}
                                            {if $__ctx->userCanEdit($cmd)}
                                                <a href="{$__url->cmd("`$cmd`.preview")}?path={$i.path|escape}" class="thumb" title="{'Параметры превью'|t}"><img src="{$__url->thumb($i.uri, 100, 100)}{if in_array($i.name, $backlight)}?1{/if}" /></a>
                                            {else}
                                                <span class="thumb"><img src="{$__url->thumb($i.uri, 100, 100)}{if in_array($i.name, $backlight)}?1{/if}" /></span>
                                            {/if}
                                        {/if}
                                    </td>
                                    <td class="center">{$i.file_ext|escape}</td>
                                    <td class="center">{$i.mode|escape}</td>
                                    <td class="right" data-sort-value="{$i.size|escape}">{$i.size}</td>
                                    <td class="center small" data-sort-value="{$i.time|escape}">{$i.time|date_format:"%d.%m.%y %H:%M"}</td>
                                {/if}
                                <td class="right nowrap">
                                    {if $i.type == 'DIR' && $__ctx->userCanCreate($cmd)}
                                        <a href="{$__url->cmd("`$cmd`.addFile")}?path={$i.path|escape}" class="btn btn-xs btn-success" title="{'Добавить файлы в эту папку'|t}"><i class="fa fa-plus"></i></a>
                                    {/if}
                                    {if $__ctx->userCanEdit($cmd)}
                                        <a href="{$__url->cmd("`$cmd`.rename")}?path={$i.path|escape}" class="btn btn-xs btn-warning" title="{'Переименовать объект'|t}"><i class="fa fa-pencil"></i></a>
                                    {/if}
                                    {if $__ctx->userCanDelete($cmd)}
                                        <a href="{$__url->cmd("`$cmd`.delete")}?path={$i.path|escape}" onclick="return confirm('{'Вы уверены?'|t}')" class="btn btn-xs btn-danger scrollfix" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
                                    {/if}
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            {/if}
        {/if}
        </div>
    </div>
</div>