<?php
include "config.php";

$liste = array ();

// hole benutzerdefiniertes SELECT
$custom_select = "";
if (isset ( $_POST ['custom_select'] ) && (isset ( $_POST ['custom_submit'] ) || isset ( $_POST ['sortcol'] ))) {
	$custom_select = htmlspecialchars_decode ( $_POST ['custom_select'] );
} elseif (isset ( $_GET ['custom_select'] )) {
	$custom_select = $_GET ['custom_select'];
}

// hole benutzerdefiniertes WHERE
$custom_where = "";
if (isset ( $_POST ['custom_submit'] ) || isset ( $_POST ['sortcol'] )) {
	$custom_where = htmlspecialchars_decode ( $_POST ['custom_where'] );
} elseif (isset ( $_GET ['custom_where'] )) {
	$custom_where = $_GET ['custom_where'];
}

// hole benutzerdefiniertes GROUP BY
$custom_group = "";
if (isset ( $_POST ['custom_group'] ) && (isset ( $_POST ['custom_submit'] ) || isset ( $_POST ['sortcol'] ))) {
	$custom_group = htmlspecialchars_decode ( $_POST ['custom_group'] );
} elseif (isset ( $_GET ['custom_group'] )) {
	$custom_group = $_GET ['custom_group'];
}

// hole sortierungsspalte oder benutzerdefiniertes ORDER BY
$sortcol = "";
$custom_order = "";
if (isset ( $_POST ['sortcol'] )) {
	$sortcol = $_POST ['sortcol'];
} elseif (isset ( $_POST ['custom_submit'] )) {
	$custom_order = htmlspecialchars_decode ( $_POST ['custom_order'] );
} elseif (isset ( $_GET ['custom_order'] )) {
	$custom_order = $_GET ['custom_order'];
}

// hole KEY
$key = "";
if (isset ( $_POST ['custom_submit'] ) || isset ( $_POST ['sortcol'] )) {
	$key = htmlspecialchars_decode ( $_POST ['key'] );
} elseif (isset ( $_GET ['key'] )) {
	$key = $_GET ['key'];
}

// Optionen
$opt_sel = array ();
if (isset ( $_POST ['options'] )) {
	$opt_sel = $_POST ['options'];
} elseif (isset ( $_GET ['options'] )) {
	$opt_sel = $_GET ['options'];
}

// Auswahllisten für Listen mit Bekanntmachungen
// Eröff. gericht_cn
$e_ger_cn_sel = "";
if (isset ( $_POST ['custom_submit'] ) || isset ( $_POST ['sortcol'] )) {
	$e_ger_cn_sel = isset ( $_POST ['e_ger_cn'] ) ? $_POST ['e_ger_cn'] : "";
} elseif (isset ( $_GET ['e_ger_cn'] )) {
	$e_ger_cn_sel = $_GET ['e_ger_cn'];
}
// Beend. gericht_cn
$b_ger_cn_sel = "";
if (isset ( $_POST ['custom_submit'] ) || isset ( $_POST ['sortcol'] )) {
	$b_ger_cn_sel = isset ( $_POST ['b_ger_cn'] ) ? $_POST ['b_ger_cn'] : "";
} elseif (isset ( $_GET ['b_ger_cn'] )) {
	$b_ger_cn_sel = $_GET ['b_ger_cn'];
}

// Ab hier spezifisch für Listen mit Bekanntmachungen
// Optionen
$opt = array (
		'norm' => 'Normierungen',
		'kat_e' => 'Kategorien Eröffnung',
		'kat_b' => 'Kategorien Beendigung',
		'def_col' => 'Standardspalten',
		// 'bem_fil' => 'Bemerkungsspezialfilter',
		'purged' => 'Bereinigen' 
);

// VIEW wählen
if (isset ( $opt_sel ) && array_search ( 'purged', $opt_sel ) !== false) {
	$view ['e'] = "k_eroef_cnp";
	$view ['b'] = "k_beend_cnp";
} else {
	$view ['e'] = "k_eroef_cn";
	$view ['b'] = "k_beend_cn";
}

// Normierungen
$norm [1] = "E.gericht_cn, E.gerichtort_cn, E.regiogericht_cn,";
$norm [2] = "E.v_beruf_cn,";
$norm [3] = "E.v_ort_cn,";
$norm [4] = "E.eroef_dat_c,";
$norm [5] = "B.gericht_cn, B.gerichtort_cn, B.regiogericht_cn,";
$norm [6] = "B.aufh_dat_c,";

// Kategorien
$kat [1] = "";
foreach ( $ekat as $i ) {
	$kat [1] .= "E." . $i . ",";
}
$kat [2] = "";
foreach ( $bkat as $i ) {
	$kat [2] .= "B." . $i . ",";
}

// Generiere die Auswahlliste der normierten Gerichte
$sql = "
(SELECT DISTINCT gericht_cn FROM k_eroef_cn WHERE gericht_cn IS NOT NULL) 
UNION DISTINCT 
(SELECT DISTINCT gericht_cn FROM k_beend_cn WHERE gericht_cn IS NOT NULL) 
ORDER BY gericht_cn";
$res = $db->query ( $sql );
$ger_cn [0] = "";
while ( $data = $res->fetch_row () ) {
	if ($data != null) {
		$ger_cn [] = $data [0];
	}
}
$res->free ();
unset ( $res );

// $norm und $kat leeren, falls Typ "kmit" oder falls die Option nicht angeklickt ist
if ($_GET ['typ'] == "kmit" || ! isset ( $opt_sel ) || array_search ( 'norm', $opt_sel ) === false) {
	foreach ( $norm as &$i ) {
		$i = "";
	}
}
unset ( $i ); // notwendig, da sonst $i als Referenz weiterbesteht

if ($_GET ['typ'] == "kmit" || ! isset ( $opt_sel ) || array_search ( 'kat_e', $opt_sel ) === false) {
	$kat [1] = "";
}
if ($_GET ['typ'] == "kmit" || ! isset ( $opt_sel ) || array_search ( 'kat_b', $opt_sel ) === false) {
	$kat [2] = "";
}

// Spezielle Optionen und keine Auswahllisten bei Typ "kmit"
if ($_GET ['typ'] == "kmit") {
	$opt = array ();
	if ($_GET ['typ'] == "kmit") {
		$opt = array ()
		// 'bem_fil' => 'Bemerkungsspezialfilter'
		;
	}
	$ger_cn = array ();
}

// generiere die WHERE-Klausel für Key, die an das Template geht zur Linkgenerierung
$escape = array (
		"gericht",
		"s_name",
		"s_beruf",
		"s_ort",
		"v_name",
		"v_beruf",
		"v_ort",
		"bemerk" 
);
$key_where = "$key = ";
$do_esc = false;
foreach ( $escape as $i ) {
	if (strpos ( $key, $i ) !== false) {
		$do_esc = true;
		break;
	}
}
if ($do_esc) {
	$key_where .= "'%s'";
} else {
	$key_where .= "%s";
}

// prüfe den Bemerkungstext auf Erledigung aller aufgeworfenen Fragen
function checkBemerkDone($b, $bild = false) {
	if (is_null ( $b ) || $b == "") { // Bemerkung ist leer
		return 0;
	} elseif (strpos ( $b, '#' ) === false) { // Bemerkung hat Inhalt, aber noch keine Kennzeichnung
		return 1;
	}
	// Kennzeichnungen, die einzeln auftreten ohne Erledigung als abgeschlossen gelten
	if ($bild == false) {
		$kennz_ohne_el = array (
				"#EAEZ",
				"#VGP",
				"#ELV",
				"#IJZ",
				"#MOD",
				"#KGV",
				"#VDU",
				"#OZK",
				"#BZK",
				"#BIA",
				"#DAGP",
				"#KVGP",
				"#SGP",
				"#MFEEL",
				"#MFBEL",
				"#UB",
				"#BGT",
				"#ZTR",
				"#SVV",
				"#VZH",
				"#IGN",
				"#EDA",
				"#KA",
				"#DGAP" 
		);
	} else {
		$kennz_ohne_el = array (
				"#UB",
				"#KA" 
		);
	}
	$parts = preg_split ( '/(#[A-Z]+)/', $b, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
	// var_dump ( $parts );
	if ($parts [0] [0] != "#") { // Bemerkung beginnt nicht mit einer Kennzeichnung
		return 2;
	}
	$lkenn = false; // letzte Kennzeichnung
	foreach ( $parts as $t ) {
		if ($t [0] == "#") {
			if ($lkenn === false) {
				$lkenn = $t;
				if ($t == "#EL") {
					// ein #EL muss immer auf eine andere Kennzeichnung folgen
					return 3;
				}
			} else {
				if (in_array ( $lkenn, $kennz_ohne_el )) {
					// es ist egal, ob ein #EL folgt oder nicht
					if ($t == "#EL") {
						$lkenn = false; // starte beim nächsten Mal frisch
					} else {
						$lkenn = $t; // starte beim nächsten Mal mit einer früheren Kennung
					}
				} else {
					if ($t == "#EL") {
						// gut, letzte Kennzeichnung wurde erledigt, wir starten wieder frisch neu
						$lkenn = false;
					} else {
						// schlecht, auf die letzte Kennzeichnung hätte ein #EL folgen müssen
						return 4;
					}
				}
			}
		}
	}
	// letzte Kennung auf Abgeschlossenheit prüfen
	if ($lkenn !== false) {
		if (in_array ( $lkenn, $kennz_ohne_el )) {
			; // muss nicht abgeschlossen werden mit #EL
		} else {
			// schlecht, auf die letzte Kennzeichnung hätte ein #EL folgen müssen
			return 5;
		}
	}
	return 0;
}
// detektiere, ob der Bild- oder der Bekanntmachungsspezialfilter benutzt werden muss für die Bemerkungen
$bild = $_GET ['typ'] == "kmit" ? true : false;

// genFields generiert die abzufragenden Datenfelder aus drei kommaseparierten Listen:
// $default_fields, $custom_select, $required_fields
function genFields($default_fields, $custom_select, $required_fields) {
	if ($custom_select) {
		$f = $custom_select;
		// erforderliche Felder, die fehlen, hinzufügen
		$add = "";
		// Array durchgehen
		foreach ( $required_fields as $r ) {
			if (stripos ( $f, $r ) === false) {
				if ($add != "") {
					$add .= ",";
				}
				$add .= $r;
			}
		}
		// falls fehlende Felder gefunden, füge sie hinzu
		if ($add != "") {
			// wenn ein Komma am Ende fehlt, füge es hinzu
			if (strrpos ( trim ( $f ), "," ) != strlen ( $f ) - 1) {
				$f .= ",";
			}
			// dann die fehlenden Felder
			$f .= $add;
		}
		$select = $f;
	} else {
		$f = $default_fields;
	}
	return $f;
}

// verschiedene $default_fields
// Sortierung für Kategorisierungen etc.
$default_fields = "
E.id, E.pid, KE.bildnr,
E.bemerk, B.bemerk,
B.id, B.pid, KB.bildnr,
E.gericht, $norm[1] B.gericht, $norm[5]
E.s_name, B.s_name,
E.s_beruf ,B.s_beruf,
E.s_ort, B.s_ort,
$kat[1]
E.t_dat, B.t_dat,
E.eroef_dat, $norm[4] E.bek_dat,
$kat[2]
B.aufh_dat, $norm[6] B.bek_dat,
B.typ,
E.vid, E.v_name, E.v_beruf, $norm[2] E.v_ort, $norm[3]
E.anz_dat, E.anm_dat, E.gvers_dat, E.pruef_dat,
E.last, FE.filmnr_lang, FE.filmnr_kurz,
KE.jahr, KE.ausgabe, KE.id, KE.done, KE.bemerk, KE.last,
B.last, FB.filmnr_lang, FB.filmnr_kurz,
KB.jahr, KB.ausgabe, KB.id, KB.done, KB.bemerk, KB.last
";

// Sortierung nach Eröffnung / Beendigung
/*
 * $default_fields = "
 * E.id, E.gericht, $norm[1]
 * E.s_name, E.s_beruf, E.s_ort,
 * $kat[1]
 * E.v_name, E.v_beruf, $norm[2] E.v_ort, $norm[3]
 * E.eroef_dat, $norm[4] E.bek_dat, E.anz_dat, E.anm_dat, E.gvers_dat, E.pruef_dat,
 * E.bemerk, E.last,
 * FE.filmnr_kurz, FE.filmnr_lang, KE.bildnr,
 * KE.jahr, KE.ausgabe, KE.id, KE.done, KE.bemerk, KE.last,
 * B.id, B.gericht, $norm[5]
 * B.s_name, B.s_beruf, B.s_ort,
 * B.typ,
 * B.aufh_dat, $norm[6]
 * B.bek_dat, $kat[2]
 * B.bemerk, B.last,
 * FB.filmnr_kurz, FB.filmnr_lang, KB.bildnr,
 * KB.jahr, KB.ausgabe, KB.id, KB.done, KB.bemerk, KB.last
 * ";
 */

// Mit diesen $required_fields können die Links unten erzeugt werden
if (isset ( $opt_sel ) && array_search ( 'def_col', $opt_sel ) !== false) {
	$required_fields = array (
			"E.id",
			"KE.bildnr",
			"KE.id",
			"B.id",
			"KB.bildnr",
			"KB.id",
	);
} else {
	$required_fields = array ();
}

//
//
// Ab hier wird die Anfrage verarbeitet und angezeigt
//
//
if (isset ( $_POST ['custom_submit'] ) || isset ( $_POST ['sortcol'] ) || isset ( $_GET ['custom_select'] ) || isset ( $_GET ['custom_where'] ) || isset ( $_GET ['custom_order'] )) {
	// unterscheide Listentypen
	if ($_GET ['typ'] == "alle") {
		// MySQL kann kein FULL JOIN, daher ist ein UNION mit einem LEFT JOIN und einem RIGHT JOIN nötig
		// table alias sind hier nötig, da ansonsten in MySQL keine Sortierung über das komplette union result set möglich ist
		
		$f = genFields ( $default_fields, $custom_select, $required_fields );
		
		// Diese Funktion ist spezifisch für diese Liste, da wir für jedes Feld ein Alias brauchen.
		// Die Funktion erwartet eine kommaseparierte Liste von Feldnamen und gibt sie mit Aliasen
		// wieder zurück.
		function genAlias($i) {
			$tok = strtok ( $i, "," );
			$j = "";
			while ( $tok !== false ) {
				$j .= $tok . " AS " . str_replace ( ".", "_", $tok );
				$tok = strtok ( "," );
				if ($tok !== false) {
					// das vergangene war nicht das letzte token
					$j .= ",";
				}
			}
			$i = $j;
			return $i;
		}
		
		$select = genAlias ( $f );
		
		$sql = "
(SELECT $select
FROM $view[e] AS E
JOIN kmit AS KE ON E.aid = KE.id
JOIN filme AS FE ON KE.fid = FE.id
LEFT JOIN $view[b] AS B ON B.`kid` = E.`id`
LEFT JOIN kmit AS KB ON B.aid = KB.id
LEFT JOIN filme AS FB ON KB.fid = FB.id
WHERE %s)

UNION

(SELECT $select	
FROM $view[e] AS E
JOIN kmit AS KE ON E.aid = KE.id
JOIN filme AS FE ON KE.fid = FE.id
RIGHT JOIN $view[b] AS B ON B.`kid` = E.`id`
LEFT JOIN kmit AS KB ON B.aid = KB.id
LEFT JOIN filme AS FB ON KB.fid = FB.id
WHERE %s)";
		// $defsort = " ORDER BY COALESCE(KE_jahr, KB_jahr), COALESCE(KE_ausgabe, KB_ausgabe)";
		$defsort = " ORDER BY COALESCE(IF(e_bek_dat = \"0000-00-00\",e_eroef_dat,e_bek_dat),IF(b_bek_dat = \"0000-00-00\",b_aufh_dat,b_bek_dat)),
				coalesce(e_pid,e_id),e_pid is null desc,coalesce(b_pid,b_id),b_pid is null desc";
	} elseif ($_GET ['typ'] == "eroef") {
		// generiere eine Liste der Eröffnungen mit verknüpften beendigungen
		
		$select = genFields ( $default_fields, $custom_select, $required_fields );
		
		// die beiden letzten left joins sind eigentlich normale joins, da jede beendigung
		// auch eine ausgabe und einen film hat, aber wenn man die letzten beiden joins als
		// normale cross joins deklariert, erhält man insgesamt nur die verfahren, die eine
		// verknüpfte beendigung haben. der query könnte deswegen theoretisch sicher noch
		// verbessert werden, produziert aber wegen der fremdschlüsselverknüpfungen keine
		// falschen ergebnisse
		
		$sql = "
SELECT $select
FROM $view[e] AS E 
JOIN kmit AS KE ON E.aid = KE.id 
JOIN filme AS FE ON KE.fid = FE.id 
LEFT JOIN $view[b] AS B ON B.`kid` = E.`id` 
LEFT JOIN kmit AS KB ON B.aid = KB.id 
LEFT JOIN filme AS FB ON KB.fid = FB.id 
WHERE 1";
		// $defsort = " ORDER BY FE.filmnr_lang, KE.bildnr";
		$defsort = " ORDER BY COALESCE(IF(e.bek_dat = \"0000-00-00\",e.eroef_dat,e.bek_dat),IF(b.bek_dat = \"0000-00-00\",b.aufh_dat,b.bek_dat)),
				coalesce(e.pid,e.id),e.pid is null desc,coalesce(b.pid,b.id),b.pid is null desc";
	} elseif ($_GET ['typ'] == "eroef_offen") {
		// generiere eine Liste der Verfahren, die noch offen sind
		
		// hier brauchen wir eine Liste von Feldern ohne Beendigungen
		$default_fields = "
			E.id, E.pid, E.gericht, $norm[1]
			E.s_name, E.s_beruf, E.s_ort, $kat[1]
			E.v_name, E.v_beruf, $norm[2] E.v_ort, $norm[3]
			E.t_dat, E.eroef_dat, $norm[4] E.bek_dat, E.anz_dat, E.anm_dat, E.gvers_dat, E.pruef_dat,
			E.bemerk, E.last,
			KE.jahr, KE.ausgabe, KE.id,
			FE.filmnr_kurz, FE.filmnr_lang, KE.bildnr, KE.done, KE.bemerk, KE.last
		";
		
		$select = genFields ( $default_fields, $custom_select, $required_fields );
		
		$sql = "
SELECT $select
FROM $view[e] AS E
JOIN kmit AS KE ON E.aid = KE.id
JOIN filme AS FE ON KE.fid = FE.id
LEFT JOIN k_beend AS B ON B.`kid` = E.`id`
WHERE B.kid IS NULL";
		$defsort = " ORDER BY FE.filmnr_lang, KE.bildnr";
	} elseif ($_GET ['typ'] == "beend") {
		// generiere eine Liste der verwaisten Beendigungen
		
		// hier brauchen wir eine Liste von Feldern ohne Eröffnungen
		$default_fields = "
			B.id, B.pid, KB.bildnr,
			B.bemerk, B.gericht, $norm[5]
			B.s_name, B.s_beruf, B.s_ort,
			B.typ, $kat[2]
			B.t_dat,
			B.aufh_dat, $norm[6]
			B.bek_dat,
			B.last,
			KB.jahr, KB.ausgabe, KB.id,
			FB.filmnr_kurz, FB.filmnr_lang, KB.done, KB.bemerk, KB.last
		";
		
		$select = genFields ( $default_fields, $custom_select, $required_fields );
		
		$sql = "
SELECT $select
FROM $view[b] AS B 
JOIN kmit AS KB ON B.aid = KB.id 
JOIN filme AS FB ON KB.fid = FB.id
WHERE B.kid IS NULL";
		// $defsort = " ORDER BY FB.filmnr_lang, KB.bildnr";
		$defsort = " ORDER BY IF(b.bek_dat = \"0000-00-00\",b.aufh_dat,b.bek_dat),COALESCE(b.pid,b.id),b.pid is null desc";
	} elseif ($_GET ['typ'] == "kmit") {
		// generiere eine Liste der Konkursbilder:
		// Das Alias für k_eroef und k_beend in den beiden innersten Queries ist absichtlich "X", damit man eine gemeinsame
		// WHERE Klausel für beide Queries verwenden kann.
		
		// die Felder sind hier statisch wegen des verschachtelten Queries
		
		$sql = '
SELECT jahr AS K_jahr, ausgabe AS K_ausgabe, filmnr_kurz AS F_filmnr_kurz, filmnr_lang AS F_filmnr_lang, bildnr AS K_bildnr, 
	id as K_id, done AS K_done, bemerk AS K_bemerk, ts AS K_ts, last AS K_last, 
	GROUP_CONCAT(CONCAT(IF(bek_last = "","(n/a)", bek_last), ": ", num) ORDER BY num DESC, bek_last SEPARATOR "<br />") AS bek_last 
FROM
(SELECT id, jahr, ausgabe, filmnr_kurz, filmnr_lang, bildnr, done, bemerk, ts, last, bek_last, count(bek_last) as num FROM
((SELECT 
	K.id,K.jahr, K.ausgabe,
	F.filmnr_kurz, F.filmnr_lang, K.bildnr, K.done, K.bemerk, 
	K.ts, K.last AS last, X.last AS bek_last
FROM kmit AS K
JOIN FILME AS F ON K.fid = F.id
LEFT JOIN k_eroef AS X ON X.aid = K.id
WHERE %s)
UNION ALL
(SELECT 
	K.id, K.jahr, K.ausgabe,
	F.filmnr_kurz, F.filmnr_lang, K.bildnr, K.done, K.bemerk, 
	K.ts, K.last last, X.last AS bek_last
FROM kmit AS K
JOIN FILME AS F ON K.fid = F.id
LEFT JOIN k_beend AS X ON X.aid = K.id
WHERE %s))
AS t1
GROUP BY id,bek_last)
AS t2
GROUP BY id';
		$defsort = " ORDER BY K_jahr,K_ausgabe,F_filmnr_lang";
	} else {
		die ( "Kein Listentyp gegeben oder Typ nicht gefunden." );
	}
	// füge WHERE und GROUP BY hinzu;
	$where = "";
	if ($custom_where != "") {
		$where = "(" . $custom_where . ")";
	}
	$group = $custom_group;
	// Selektiertes Eröff. Gericht der WHERE-Klausel hinzufügen
	if ($e_ger_cn_sel != "") {
		if ($where == "") {
			$where = "E.gericht_cn = \"" . $e_ger_cn_sel . "\"";
		} else { // ggf. voranstellen und mit AND verknüpfen
			$where = "E.gericht_cn = \"" . $e_ger_cn_sel . "\" AND " . $where;
		}
	}
	// Selektiertes Beend. Gericht der WHERE-Klausel hinzufügen
	if ($b_ger_cn_sel != "") {
		if ($where == "") {
			$where = "B.gericht_cn = \"" . $b_ger_cn_sel . "\"";
		} else { // ggf. voranstellen und mit AND verknüpfen
			$where = "B.gericht_cn = \"" . $b_ger_cn_sel . "\" AND " . $where;
		}
	}
	// in die Queries schreiben
	if ($_GET ['typ'] != "alle" && $_GET ['typ'] != "kmit") {
		if ($where) {
			$sql .= " AND " . $where;
		}
		if ($group) {
			$sql .= " GROUP BY " . $group;
		}
	} elseif ($_GET ['typ'] == "alle" || $_GET ['typ'] == "kmit") {
		if ($where == "") {
			$where = "1";
		}
		$sql = sprintf ( $sql, $where, $where );
	}
	// füge Sortierung hinzu
	if ($sortcol) {
		$sql .= " ORDER BY " . $sortcol;
	} elseif ($custom_order) {
		$sql .= " ORDER BY " . $custom_order;
	} else {
		// default sort
		// wenn ein $custom_select verwendet wird, können wir das Vorhandensein der
		// Sortierspalten nicht garantieren
		if ($custom_select == "") {
			$sql .= $defsort;
		}
	}
	
	// Verkürze die Liste zum schnelleren Debuggen
	$sql .= " LIMIT 1000";
	
	// Zeige Query zum Debuggen
	// echo $sql;
	
	$res = $db->query ( $sql );
	
	// zum Anzeigen des Query
	$smarty->assign ( 'query', $sql );
	
	// konstruiere für jede zeile ein assoziatives array, das auch tabellennamen enthält,
	// es sei denn, es handelt sich um die Liste "alle" oder "kmit", da bei einem UNION result set keine
	// Tabellennamen zurückgegeben werden
	$columns = array ();
	while ( $field = $res->fetch_field () ) {
		$fields [] = $field;
	}
	while ( $row = $res->fetch_row () ) {
		// skip Variable zurücksetzen
		if (isset ( $opt_sel ) && array_search ( 'bem_fil', $opt_sel ) !== false) {
			$bemerkTODO = 0;
		}
		// generisch Operationen
		foreach ( $row as $i => $j ) {
			// Bemerkungsspezialfilter
			if (isset ( $opt_sel ) && array_search ( 'bem_fil', $opt_sel ) !== false && $bemerkTODO == 0) {
				if (($_GET ['typ'] != "alle" && ((strcasecmp ( $fields [$i]->table, "E" ) == 0 || strcasecmp ( $fields [$i]->table, "B" ) == 0) && $fields [$i]->name == "bemerk")) || ($_GET ['typ'] == "alle" && ($fields [$i]->name == "E_bemerk" || $fields [$i]->name == "e_bemerk" || $fields [$i]->name == "B_bemerk" || $fields [$i]->name == "b_bemerk")) || ($_GET ['typ'] == "kmit" && strcasecmp ( $fields [$i]->name, "K_bemerk" ) == 0)) {
					$bemerkTODO = checkBemerkDone ( $j, $bild );
				}
			}
			// wandle Datumsformat um für die Datumsfelder, identifiziert anhand ihres originalen Spaltennamens
			if (substr ( $fields [$i]->orgname, - 4 ) == "_dat" || substr ( $fields [$i]->orgname, - 6 ) == "_dat_c") {
				$j = smarty_modifier_mysqlDateToGerman ( $j );
			}
			// ergänze hier den Tabellennamen, außer bei Liste "alle" oder "kmit", dort nur Spalte
			if ($fields [$i]->table && $_GET ['typ'] != "kmit" && $_GET ['typ'] != "alle") {
				$row [$fields [$i]->table . "." . $fields [$i]->name] = $j;
			} else {
				$row [$fields [$i]->name] = $j;
			}
			
			unset ( $row [$i] );
		}
		// listenspezifische Operationen
		if ($_GET ['typ'] == "eroef") {
			// Bildnummern sollen verlinkt werden, wenn die Filme gerade im Datenverzeichnis sind:
			// - prüfe, ob der jeweilige Film gerade verfügbar ist
			// - wenn ja: überschreibe die Bildnummer mit einem Array
			// - wenn nein: belasse es bei der einfachen Bildnummer
			if (isset ( $row ['KE.id'] )) {
				if (isset ( $row ['KE.bildnr'] )) {
					$ke_bildnr = $row ['KE.bildnr'];
					$row ['KE.bildnr'] = array ();
					$row ['KE.bildnr'] ['nr'] = $ke_bildnr;
					$row ['KE.bildnr'] ['kmit_id'] = $row ['KE.id'];
				}
			}
			if (isset ( $row ['KB.id'] )) {
				if (isset ( $row ['KB.bildnr'] )) {
					$kb_bildnr = $row ['KB.bildnr'];
					$row ['KB.bildnr'] = array ();
					$row ['KB.bildnr'] ['nr'] = $kb_bildnr;
					$row ['KB.bildnr'] ['kmit_id'] = $row ['KB.id'];
				}
			}
		} elseif ($_GET ['typ'] == "alle") {
			// mache dasselbe bei der kombinierten Liste
			if (isset ( $row ['KE_id'] )) {
				if (isset ( $row ['KE_bildnr'] )) {
					$ke_bildnr = $row ['KE_bildnr'];
					$row ['KE_bildnr'] = array ();
					$row ['KE_bildnr'] ['nr'] = $ke_bildnr;
					$row ['KE_bildnr'] ['kmit_id'] = $row ['KE_id'];
				}
			}
			if (isset ( $row ['KB_id'] )) {
				if (isset ( $row ['KB_bildnr'] )) {
					$kb_bildnr = $row ['KB_bildnr'];
					$row ['KB_bildnr'] = array ();
					$row ['KB_bildnr'] ['nr'] = $kb_bildnr;
					$row ['KB_bildnr'] ['kmit_id'] = $row ['KB_id'];
				}
			}
		} elseif ($_GET ['typ'] == "beend") {
			// mache dasselbe bei den Beendigungen
			if (isset ( $row ['KB.id'] )) {
				if (isset ( $row ['KB.bildnr'] )) {
					$ke_bildnr = $row ['KB.bildnr'];
					$row ['KB.bildnr'] = array ();
					$row ['KB.bildnr'] ['nr'] = $ke_bildnr;
					$row ['KB.bildnr'] ['kmit_id'] = $row ['KB.id'];
				}
			}
		} elseif ($_GET ['typ'] == "eroef_offen") {
			// mache dasselbe bei den offenen Verfahren
			if (isset ( $row ['KE.id'] )) {
				if (isset ( $row ['KE.bildnr'] )) {
					$ke_bildnr = $row ['KE.bildnr'];
					$row ['KE.bildnr'] = array ();
					$row ['KE.bildnr'] ['nr'] = $ke_bildnr;
					$row ['KE.bildnr'] ['kmit.id'] = $row ['KE.id'];
				}
			}
		} elseif ($_GET ['typ'] == "kmit") {
			// und auch bei den Konkursmitteilungen
			if (isset ( $row ['K_id'] )) {
				$k_bildnr = $row ['K_bildnr'];
				$row ['K_bildnr'] = array ();
				$row ['K_bildnr'] ['nr'] = $k_bildnr;
				$row ['K_bildnr'] ['kmit_id'] = $row ['K_id'];
			}
		}
		if (isset ( $opt_sel ) && array_search ( 'bem_fil', $opt_sel ) !== false) {
			if ($bemerkTODO != 0) {
				$liste [] = $row;
			}
		} else {
			$liste [] = $row;
		}
	}
}
// funktioniert nicht, wenn man mit PHP noch einzelne Zeilen filtert
/*
 * if (isset ( $res )) {
 * $num_rows = $res->num_rows;
 * } else {
 * $num_rows = 0;
 * }
 */
$num_rows = count ( $liste );

switch ($_GET ['typ']) {
	case "alle" :
		$typ_ausf = "Alle Verfahren";
		break;
	case "eroef" :
		$typ_ausf = "Nur Eröffnungen sowie verknüpften Beendigungen";
		break;
	case "eroef_offen" :
		$typ_ausf = "Nur offene Verfahren";
		break;
	case "beend" :
		$typ_ausf = "Nur verwaiste Beendigungen";
		break;
	case "kmit" :
		$typ_ausf = "Bilder mit Konkursen";
		break;
}

$smarty->assign ( 'title', $typ_ausf );
$smarty->assign ( 'options', $opt );
$smarty->assign ( 'options_sel', $opt_sel );
$smarty->assign ( 'ger_cn', $ger_cn );
$smarty->assign ( 'e_ger_cn_sel', $e_ger_cn_sel );
$smarty->assign ( 'b_ger_cn_sel', $b_ger_cn_sel );
$smarty->assign ( 'typ', $_GET ['typ'] );
$smarty->assign ( 'typ_ausf', $typ_ausf );
$smarty->assign ( 'custom_select', $custom_select );
$smarty->assign ( 'custom_where', $custom_where );
$smarty->assign ( 'custom_group', $custom_group );
$smarty->assign ( 'custom_order', $custom_order );
$smarty->assign ( 'key', $key );
$smarty->assign ( 'key_where', $key_where );
$smarty->assign ( 'num_rows', $num_rows );
$smarty->assign ( 'liste', $liste );
// Braucht zu viel Speicher für das PHP-Skript und hilft Firefox nicht
// $smarty->loadFilter('output', 'trimwhitespace');
$smarty->display ( 'kliste.tpl' );

?>