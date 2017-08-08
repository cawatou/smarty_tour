<div class="container">
    {include file='backend/submenu/i18n.tpl.php'}
    <div class="row cms-body-content">
        <div class="col col-md-12">
        {if empty($list) && !$filter->isActive()}
            <div class="alert alert-info">{"Нет ни одной записи для перевода"|t}</div>
        {else}
            {$filter->draw()}
            {if empty($list)}
                <div class="alert alert-warning">{"Ничего не найдено. Вы можете <a href=':url'>сбросить</a> фильтр поиска."|t:null:[':url'=>"`$__url->adm('.i18n')`?`$filter->encode(Form_Filter::FILTER_CLEAR)`=1"]}</div>
            {else}
                <div class="table-responsive">
                    <table class="table table-hover table-responsive table-striped">
                        <thead>
                        <tr class="center">
                            <th class="left">{"Оригинал"|t}</th>
                            <th>{"Тег"|t}</th>
                            <th>{"Локаль оригинала"|t}</th>
                            <th class="left">{"Перевод"|t}</th>
                            <th>{"Локаль перевода"|t}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            {foreach $list as $i18n}
                            <tr>
                                <td>{$i18n->getSourceString()|escape|truncate:'30':'&hellip;':true}</td>
                                <td class="center">{if $i18n->getSourceTag()}{$i18n->getSourceTag()|escape}{else}&mdash;{/if}</td>
                                <td class="center">{$i18n->getSourceLocale()|escape}</td>
                                <td>{if $i18n->getTargetString()}{$i18n->getTargetString()|escape|truncate:'30':'&hellip;':true}{else}&mdash;{/if}</td>
                                <td class="center">{$i18n->getTargetLocale()|escape}</td>
                                <td  class="right nowrap">
                                    <a href="{$__url->adm('.i18n.edit')}?i18n_id={$i18n->getId()|escape}" class="btn btn-xs btn-warning" title="{"Редактировать"|t}"><i class="fa fa-pencil"></i></a>
                                    <a href="{$__url->adm('.i18n.delete')}?i18n_id={$i18n->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{"Вы уверены?"|t}');" title="{"Удалить"|t}"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
                {include file='backend/common_paginator.tpl.php' state=$state}
            {/if}
        {/if}
        </div>
    </div>
</div>