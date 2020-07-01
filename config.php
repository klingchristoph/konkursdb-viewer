<?php

set_time_limit(300);
error_reporting(E_ALL);

setlocale(LC_ALL, ''); // setzt das locale bei einem Windowsrechner, das in der Systemsteuerung eingestellt ist.
ini_set("default_charset", "ISO-8859-1");
define ('SMARTY_RESOURCE_CHAR_SET', 'ISO-8859-1');

// vor allem für kliste.php
ini_set("memory_limit","2048M");

// lokale Test-Datenbank und lokale Test-Bilder
define ( "MYSQLDB", "reichsanzeiger" );
define ( "MYSQLHOST", "localhost" );
define ( "MYSQLUSER", "reichsanzeiger" );
define ( "MYSQLPASS", "!§$%&" );
define ( "MYSQLFLAGS", null);
define ( "RABASEURL", "https://digi.bib.uni-mannheim.de/viewer/reichsanzeiger");

$db = new mysqli();
$db->real_connect(MYSQLHOST, MYSQLUSER, MYSQLPASS, MYSQLDB, null, MYSQLFLAGS);
if (mysqli_connect_errno () != 0) {
	die ( "Datenbankverbindung fehlgeschlagen." );
}
mysqli_report ( MYSQLI_REPORT_ERROR );

$db->set_charset("latin1");

require_once ('smarty/Smarty.class.php');
$smarty = new Smarty ();

$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$smarty->config_dir = 'configs/';
$smarty->cache_dir = 'cache/';
$smarty->addPluginsDir('plugins/');;

$smarty->force_compile = false;
$smarty->force_cache = false;
$smarty->caching = Smarty::CACHING_OFF;
$smarty->error_reporting = error_reporting();
$smarty->debugging = true;

require_once ('functions.php');

// definiere kategoriale Variablen
$ekat = array(
		"done_k", "w_k", "eheleute_k", "firma_k", "nachlass_k", "ohg_k", "gesellter_k", 
		"kg_k", "gmbh_k", "eg_k", "ag_k", "kgaa_k", "vvag_k", "verein_k", "sgs_k",
		"liq_k", "mj_k", "gu_k",  "getr_k", "gg_k", "gt_k",
		"flucht_k", "haft_k", "abwesend_k", "unbaufent_k"
);
$bkat = array(
		"done_k", "w_k", "eheleute_k", "firma_k", "nachlass_k", "ohg_k", "gesellter_k", 
		"kg_k", "gmbh_k", "eg_k", "ag_k", "kgaa_k", "vvag_k", "verein_k", "sgs_k",
		"liq_k", "mj_k", "gu_k",  "getr_k", "gg_k", "gt_k",
		"flucht_k", "haft_k", "abwesend_k", "unbaufent_k", 
		"eaez_k"
);

?>