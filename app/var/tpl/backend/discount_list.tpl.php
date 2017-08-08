{$__ctx->addJs('/base.js')}
{$__ctx->addJs('/base.many_rows.js')}

{$__ctx->addJs('/backend/classes/discount.js')}

<div class="container">
    {include file='backend/submenu/product.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">
            <form action="{$__ctx->getData('uri')}" method="post" id="form-discounts">
                <div class="row">
                    <div class="col col-md-2">
                        <div class="form-group">
                            <label>Скидка по умолчанию</label>

                            <div class="input-group">
                                <input type="text" class="form-control" value="{$default_discount->getPercent()|price_format:false}" name="default_discount">
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>
                </div>

                {include file="backend/include/form-discount.tpl.php" item=null field='discounts' class="row-template hidden" item_key="#ID#"}

                {if !empty($existing_discounts)}
                    {foreach $existing_discounts as $discount}
                        {include file="backend/include/form-discount.tpl.php" model=$discount field='discounts' item_key=$discount->getId()}
                    {/foreach}
                {else}
                    {include file="backend/include/form-discount.tpl.php" item=null field='discounts' item_key="_0"}
                {/if}

                {literal}
                    <script type="text/javascript">
                        var DISCOUNTS_STORAGE = {
                            container: '#form-discounts',
                            tpl:       '.row-template',
                            row:       '.row',
                            add:       '.add-row',
                            del:       '.del-row',
                            up:        '.up-row',
                            down:      '.down-row'
                        };
                    </script>
                {/literal}

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" name="__change" class="btn btn-primary">Внести изменения</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>