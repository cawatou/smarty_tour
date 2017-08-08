{$__ctx->addCss('../js/backend/datepicker/jquery-ui-1.10.3.custom.css')}
{$__ctx->addJs('/backend/datepicker/jquery-ui-1.10.3.custom.js')}
{$__ctx->addJs('/backend/datepicker.js')}

{$__ctx->addCss('../js/backend/datepicker/jquery-ui-timepicker.css')}
{$__ctx->addJs('/backend/datepicker/jquery-ui-timepicker.js')}
{$__ctx->addJs('/backend/timepicker.js')}

<div class="container">
    {include file='backend/submenu/product.tpl.php'}

    <div class="row cms-body-content">
        <div class="col col-md-12">

            {if !empty($froms)}
                <form action="{$__ctx->getData('uri')}" method="post">
                    {foreach $froms as $from_id => $from}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    {$from.title|escape}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker" name="product_from[{$from_id}][date]" value="{if $from.date !== null}{$from.date->format('d.m.Y')}{/if}">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/foreach}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" name="__change" class="btn btn-primary">{'Внести изменения'|t}</button>
                            </div>
                        </div>
                    </div>
                </form>
            {else}
                <div class="alert alert-info">У вас не выбран список разрешённых городов вылета. Обратитесь к администратору.</div>
            {/if}
        </div>
    </div>
</div>