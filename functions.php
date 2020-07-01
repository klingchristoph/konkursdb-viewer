<?php

// falls noch nicht inkludiert
require_once ("config.php");

// vielleicht gibt es noch einen eleganteren weg, das smarty plugin im code hier aufzurufen...
require_once ("plugins/modifier.mysqlDateToGerman.php");

// die Funktion gibt die absolute URL für den Film zurück
function getFilmUrl($filmnr_lang, $filmnr_kurz) {
	if ($filmnr_lang >= 1879 && $filmnr_lang <= 1914) {
		return RABASEURL . "/scan/"  . sprintf('%03d', $filmnr_kurz) . "-" . sprintf('%04d', $filmnr_lang);
	}
	else {
		return RABASEURL . "/film/"  . sprintf('%03d', $filmnr_kurz) . "-" . sprintf('%04d', $filmnr_lang);
	}
}	

?>