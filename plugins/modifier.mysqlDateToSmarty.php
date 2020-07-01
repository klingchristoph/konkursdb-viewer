<?php
/*
 * Smarty plugin
* -------------------------------------------------------------
* File:     modifier.mysqlDateToSmarty.php
* Type:     modifier
* Name:     mysqlDateToSmarty
* Purpose:  Wandle ein MySQL-Datum in ein von Smarty lesbares Datumsarray um
* -------------------------------------------------------------
*/
function smarty_modifier_mysqlDateToSmarty($array)
{
	return array('Day' => substr($array, 8, 2), 'Month' => substr($array, 5, 2), 'Year' => substr($array, 0, 4));
}
?>