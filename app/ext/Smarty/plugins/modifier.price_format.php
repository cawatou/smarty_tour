<?php

function smarty_modifier_price_format($price, $with_zero = false, $thousands_sep = ' ')
{
    if ($with_zero) {
        return number_format($price, 2, ',', $thousands_sep);
    }
    return number_format($price, 0, '', $thousands_sep);     
}

?>
