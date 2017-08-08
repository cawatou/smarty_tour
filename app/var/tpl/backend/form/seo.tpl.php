{if $__f->successful}
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {'Данные о корректировке изменены'|t}
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
			<div class="form-group form-group-required{if !is_null($__f->e('seo_request'))} has-error{/if}">
				<label for="{$__f->encode('seo_request')}">{'Адрес страницы'|t} <i class="fa fa-check"></i></label>
				<input type="text" class="form-control" name="{$__f->encode('seo_request')}" value="{$__f->m()->getRequest()|escape}" id="{$__f->encode('seo_request')}">
				<span class="help-block help-block-error">{if $__f->e('seo_request') == 'ALREADY_EXIST'}Указанный адрес уже добавлен для корректировки{else}{'Обязательное поле'|t}{/if}</span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group {if !is_null($__f->e('seo_title'))} has-error{/if}">
				<label for="{$__f->encode('seo_title')}">{'Заголовок'|t}</label>
				<input type="text" class="form-control" name="{$__f->encode('seo_title')}" value="{$__f->m()->getTitle()|escape}" id="{$__f->encode('seo_title')}">
			</div>
		</div>
	</div>	
    <div class="row">
        <div class="col-md-8">
            <div class="form-group{if !is_null($__f->e('seo_keywords'))} has-error{/if}">
                <label for="{$__f->encode('seo_keywords')}">Ключевые слова (keywords)</label>
                <textarea name="{$__f->encode('seo_keywords')}" id="{$__f->encode('seo_keywords')}" class="form-control form-textarea-vertical">{$__f->m()->getKeywords()|escape}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="form-group{if !is_null($__f->e('seo_description'))} has-error{/if}">
                <label for="{$__f->encode('seo_description')}">Описание (description)</label>
                <textarea name="{$__f->encode('seo_description')}" id="{$__f->encode('seo_description')}" class="form-control form-textarea-vertical">{$__f->m()->getDescription()|escape}</textarea>
            </div>
        </div>
    </div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
			<div class="radio radio-title">{'Статус'|t}</div>
			<div class="radio">
				<label>
					<input type="radio" name="{$__f->encode('seo_status')}" value="ENABLED"{if $__f->m()->getStatus() == 'ENABLED'} checked{/if}> {'Применить'|t}
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="{$__f->encode('seo_status')}" value="DISABLED"{if $__f->m()->getStatus() == 'DISABLED'} checked{/if}> {'Отложить'|t}
				</label>
			</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				{if $__f->getId() == 'seo_edit'}
				<button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Внести изменения'|t}</button>
				{else}
				<button type="submit" name="{$__f->encode('__send')}" class="btn btn-primary">{'Добавить корректировку'|t}</button>
				{/if}				
				<button class="btn btn-default btn-ref" data-href="{$__url->adm('.seo')}">{'Отмена'|t}</button>
			</div>
		</div>
	</div>    
</form>