<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File: modifier.mysqlDateToGerman.php
 * Type: modifier
 * Name: mysqlDateToGerman
 * Purpose: Wandle ein MySQL-Datum in ein deutsches Datum um
 * -------------------------------------------------------------
 */
function smarty_modifier_mysqlDateToGerman($string, $ausschreiben = false) {
	if (isset ( $string ) && strlen ( $string ) == 10) {
		$tag_num = substr ( $string, 8, 2 );
		$monat_num = substr ( $string, 5, 2 );
		$jahr_num = substr ( $string, 0, 4 );
		
		if ($tag_num != 0 && $monat_num != 0 && $jahr_num != 0) {
			if ($ausschreiben) {
				$erg = $tag_num . ". %s " . $jahr_num;
				$deu_monate = array (
						"Januar",
						"Februar",
						"März",
						"April",
						"Mai",
						"Juni",
						"Juli",
						"August",
						"September",
						"Oktober",
						"November",
						"Dezember" 
				);
				return sprintf ( $erg, $deu_monate [$monat_num - 1] );
			} else {
				return $tag_num . "." . $monat_num . "." . $jahr_num;
			}
		} else {
			return "";
		}
	} else {
		return "";
	}
}
?>