{$__ctx->addCss('/backend/files_popup.css')}
{$__ctx->addJs('/backend/files_popup.js')}

{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Настройки сохранены'|t}
    </div>
{/if}
{if !empty($__f->errors)}
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Произошла ошибка, проверьте правильность заполнения полей'|t}
    </div>
{/if}

{if empty($settings)}
    <div class="alert alert-info">{'Сайт не имеет настроек'|t}</div>
{else}
<form method="post" action="{$__f->getUrl()}">
    {foreach $settings as $group => $list}
    <div class="cms-group cms-group-expanded">
        <div class="cms-group-label">{$types[$group]|t}</div>
        {foreach $list as $item}
            {if $item->getType() == 'INT'}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group form-group-required{if !is_null($__f->e("settings_{$item->getKey()}"))} has-error{/if}">
                            <label for="{$__f->encode("settings_{$item->getKey()}")}">{$item->getTitle()|escape} <i class="fa fa-check"></i></label>
                            <input type="text" class="form-control" name="{$__f->encode('settings')}[{$item->getKey()}]" value="{$item->getValInt()|escape}" id="{$__f->encode("settings_{$item->getKey()}")}">
                            {if null !== $item->getNotice()}<span class="help-block">{$item->getNotice()|escape}</span>{/if}
                            <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                        </div>
                    </div>
                </div>
            {elseif $item->getType() == 'STRING'}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group form-group-required{if !is_null($__f->e("settings_{$item->getKey()}"))} has-error{/if}">
                            <label for="{$__f->encode("settings_{$item->getKey()}")}">{$item->getTitle()|escape} <i class="fa fa-check"></i></label>
                            <input type="text" class="form-control" name="{$__f->encode('settings')}[{$item->getKey()}]" value="{$item->getValString()|escape}" id="{$__f->encode("settings_{$item->getKey()}")}">
                            {if null !== $item->getNotice()}<span class="help-block">{$item->getNotice()|escape}</span>{/if}
                            <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                        </div>
                    </div>
                </div>            
            {elseif $item->getType() == 'TEXT'}
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group form-group-required{if !is_null($__f->e("settings_{$item->getKey()}"))} has-error{/if}">
                            <label for="{$__f->encode("settings_{$item->getKey()}")}">{$item->getTitle()|escape} <i class="fa fa-check"></i></label>
                            <textarea name="{$__f->encode('settings')}[{$item->getKey()}]" id="{$__f->encode("settings_{$item->getKey()}")}" class="form-control form-textarea-vertical">{$item->getValText()|escape}</textarea>
                            {if null !== $item->getNotice()}<span class="help-block">{$item->getNotice()|escape}</span>{/if}
                            <span class="help-block help-block-error">{'Обязательное поле'|t}</span>
                        </div>
                    </div>
                </div>                  
            {elseif $item->getType() == 'BOOL'}
                <div class="cmsField cmsField-required">
                    <div class="cmsField-container">
                        <input type="hidden" name="{$__f->encode('settings')}[{$item->getKey()}]" value="0" />
                        <input type="checkbox" name="{$__f->encode('settings')}[{$item->getKey()}]" value="1" id="{$__f->encode("settings_{$item->getKey()}")}"{if !is_null($item->getValBool())} checked="checked"{/if} /><label for="{$__f->encode("settings_{$item->getKey()}")}"> {$item->getTitle()|escape}</label>
                    </div>
                    {if null !== $item->getNotice()}<div class="cmsField-notice">{$item->getNotice()|escape}</div>{/if}
                </div>
            {elseif $item->getType() == 'FILE'}
                <div class="cmsField cmsField-required{if !is_null($__f->e("settings_{$item->getKey()}"))} cmsField-invalid{/if}">
                    <div class="cmsField-label"><label for="{$__f->encode("settings_{$item->getKey()}")}">{$item->getTitle()|escape}</label></div>
                    <div class="cmsField-container">
                        <input type="text" name="{$__f->encode('settings')}[{$item->getKey()}]" value="{$item->getValString()|escape}" id="{$__f->encode("settings_{$item->getKey()}")}" class="cmsInp span3" />
                        <button class="cmsBtn" onclick="return dialog.show('{$__url->adm('.files-dialog', '?history')}', '{$__f->encode("settings_{$item->getKey()}")}');"><i class="icon-search"></i> Обзор</button>
                    </div>
                    {if null !== $item->getNotice()}<div class="cmsField-notice">{$item->getNotice()|escape}</div>{/if}
                    <div class="cmsField-error">{'Обязательное поле'|t}</div>
                </div>
            {/if}
        {/foreach}
    </div>
    {/foreach}

	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
			</div>
		</div>
	</div>
</form>
{/if}