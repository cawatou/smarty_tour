<div class="row row-attr{if !empty($class)} {$class}{/if}">
    <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="{$field}[{$item_key}][]" value="{if !empty($item)}{$item|escape}{/if}" class="form-control" />
        </div>
    </div>

    <div class="col-md-1" style="width: 68px;">
        <div class="form-group">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="{$field}_{$field_key}" data-toggle="dropdown">
                    <i class="fa fa-bars"></i>
                </button>

                <ul class="dropdown-menu pull-right" aria-labelledby="{$field}_{$field_key}">
                    <li>
                        <a href="#" class="add-row">
                            <i class="fa fa-plus"></i>
                            {'Добавить ниже'|t}
                        </a>
                    </li>

                    <li>
                        <a href="#" class="up-row">
                            <i class="fa fa-arrow-up"></i>
                            {'Вверх'|t}
                        </a>
                    </li>

                    <li>
                        <a href="#" class="down-row">
                            <i class="fa fa-arrow-down"></i>
                            {'Вниз'|t}
                        </a>
                    </li>

                    <li>
                        <a href="#" class="del-row">
                            <i class="fa fa-times"></i>
                            {'Удалить'|t}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>