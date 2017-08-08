<tr class="{if $product->getIsHighlight()}success{/if}{if $product->getLinkedId() !== null} warning text-muted{/if}{if count($product->getLinkedProducts()) > 0} warning{/if}">
    <td class="middle nowrap">
        {if $product->getLinkedId() !== null}
            <i class="fa fa-link"></i>
        {/if}
        {$product->getId()|escape}
    </td>

    <td class="middle">
        {$product->getTitle()|escape}
    </td>

    <td class="middle">
        {if $product->getFromId()}
            {$product->getFrom('title_from')}
        {else}
            &mdash;
        {/if}
    </td>
    <td class="middle">
        {$product->getCountry()->getTitle()|escape}/{if $product->getResortName() !== null}{$product->getResortName()|escape}{else}&mdash;{/if}
    </td>
    <td class="middle">
        {foreach $product->getDepartures() as $d}
            {$d->getDate()->format('d.m')}{if !$d@last}, {/if}
        {foreachelse}
            &mdash;
        {/foreach}
    </td>
    <td class="right nowrap middle">
        {if $product->getDiscountPrice() !== null}
            <small title="Цена без скидки"><s>{$product->getPrice()|price_format:true}</s></small>
        {/if}
        {$product->getSalePrice()|price_format:true}
    </td>
    <td class="nowrap middle">
        {if $product->getTouroperator()}
            <a href="{$__url->adm('.touroperator.edit')}?touroperator_id={$product->getTouroperatorId()|escape}">
                {$product->getTouroperator()->getTitle()|escape|default:"&mdash;"}
            </a>
        {else}
            {$product->getOperator()|escape|default:"&mdash;"}
        {/if}
    </td>
    <td class="center middle">
        {if $product->getStatus() == 'ENABLED'}
            <a href="{$__url->adm('.product.status')}?product_id={$product->getId()|escape}" title="Сменить статус на «Скрывать»" class="scrollfix">Показывать</a>
        {elseif $product->getStatus() == 'DISABLED'}
            <a href="{$__url->adm('.product.status')}?product_id={$product->getId()|escape}" title="Сменить статус на «Показывать»" class="scrollfix highlight-warning">Скрывать</a>
        {/if}
    </td>

    {if $__ctx->getCurrentUser()->canEdit('.adm.product')}
        <td class="center middle" width="100">
            <input type="text" name="product_qnt[{$product->getId()}]" value="{$product->getQnt()|escape}" class="form-control" />
        </td>
    {/if}

    <td class="right nowrap middle">
        {if $__ctx->getCurrentUser()->canEdit('.adm.product')}
            <a href="{$__url->adm('.product.edit')}?product_id={$product->getId()|escape}" class="btn btn-xs btn-warning" title="{'Редактировать'|t}"><i class="fa fa-pencil"></i></a>
        {/if}

        {if $__ctx->getCurrentUser()->canEdit('.adm.product')}
            <a href="{$__url->adm('.product.copy')}?product_id={$product->getId()|escape}" class="btn btn-xs btn-success" title="{'Копировать'|t}"><i class="fa fa-copy"></i></a>
        {/if}

        {if $__ctx->getCurrentUser()->canDelete('.adm.product')}
            <a href="{$__url->adm('.product.delete')}?product_id={$product->getId()|escape}" class="btn btn-xs btn-danger scrollfix" onclick="return confirm('{'Вы уверены?'|t}');" title="{'Удалить'|t}"><i class="fa fa-trash-o"></i></a>
        {/if}
    </td>
</tr>