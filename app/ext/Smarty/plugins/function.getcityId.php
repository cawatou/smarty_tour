<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.getcityId.php
 * Type:     function
 * Name:     getcityId
 * Purpose:  add new tour from the new site
 * -------------------------------------------------------------
 */


function smarty_function_getcityId($params, &$smarty)
{
	return $_SESSION['user_city_id'];
}