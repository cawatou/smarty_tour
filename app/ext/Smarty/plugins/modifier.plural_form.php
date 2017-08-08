<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 *
 * Smarty plural_form modifier plugin
 * @param $n
 * @param $form1
 * @param $form2
 * @param $form5
 * @return string
 */
function smarty_modifier_plural_form($n, $form1, $form2, $form5)
{
    $n  = abs($n) % 100;
    $n1 = $n % 10;
    if ($n > 10 && $n < 20) return $form5;
    if ($n1 > 1 && $n1 < 5) return $form2;
    if ($n1 == 1) return $form1;

    return $form5;
}