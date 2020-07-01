<?php
/*
 * Smarty plugin
* -------------------------------------------------------------
* File:     modifier.mysqlDateToGerman.php
* Type:     modifier
* Name:     mysqlDateToGerman
* Purpose:  Wandle ein MySQL-Datum in ein deutsches Datum um
* -------------------------------------------------------------
*/
function smarty_modifier_mysqlTimestampToGerman($string)
{
	return date('d.m.Y H:i:s', strtotime($string));
}
?>