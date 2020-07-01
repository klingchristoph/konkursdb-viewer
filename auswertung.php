<?php

// Parameter
// GET: kmit_id
include "config.php";
$smarty->assign ( 'title', "Bild" );

if (! isset ( $_GET ['kmit_id'] )) {
	die ( "Bild-ID erforderlich." );
}

$kmit_id = $db->real_escape_string ( intval ( $_GET ['kmit_id'] ) );

$sql = "SELECT kmit.id,kmit.ausgabe,kmit.bildnr,kmit.done,kmit.bemerk,kmit.jahr,kmit.last,filme.filmnr_kurz,filme.filmnr_lang
FROM filme 
JOIN kmit ON filme.id = kmit.fid 
WHERE kmit.id=$kmit_id";
$res = $db->query ( $sql );
if ($res->num_rows == 0) {
	// das darf nicht passieren
	die ( "Kann das Bild mit der ID $kmitid nicht finden." );
} else {
	$obj = $res->fetch_object ();
	$ausgabe = $obj->ausgabe; // kann leer sein (NULL)
	$kmit_id = $obj->id; // kann nicht leer sein
	$done = $obj->done; // boolisch
	$bemerk = $obj->bemerk;
	$jahr = $obj->jahr;
	$last = $obj->last;
	$filmnr_lang = $obj->filmnr_lang;
	$filmnr_kurz = $obj->filmnr_kurz;
	$bildnr = $obj->bildnr;
	$film_url = getFilmUrl ( $filmnr_lang, $filmnr_kurz );
}

if (isset ( $_POST ['save_and_link'] )) {
	header ( "Location: " . $_POST ['save_and_link'] );
	exit ();
}

// Lade die aus dieser Ausgabe bereits erfassten Konkurseröffnungen
$sql = "SELECT E.`id`, E.`pid`, IF(EC.pid IS NULL,FALSE,TRUE) AS isparent,
E.`s_name`, E.`s_beruf`, E.`v_name`, E.`v_beruf`, E.`eroef_dat`, E.`bek_dat`, E.`s_ort`, E.`v_ort`, E.`t_dat`, E.`anz_dat`, 
E.`anm_dat`, E.`gvers_dat`, E.`pruef_dat`, E.`gericht`, E.`bemerk`, E.`last`, B.`id` AS bid, B.`aid` AS baid, FB.`filmnr_lang` AS bfilmnr_lang
FROM k_eroef AS E JOIN kmit AS K ON E.aid = K.id 
LEFT JOIN (k_beend AS B JOIN kmit AS KB ON B.aid = KB.id JOIN filme AS FB ON KB.fid = FB.id) 
ON B.`kid` = E.`id` 
LEFT JOIN k_eroef AS EC
ON E.id = EC.pid
WHERE K.id = $kmit_id 
GROUP BY E.id
ORDER BY E.gericht, E.bek_dat, COALESCE(E.pid,E.id), E.pid IS NULL DESC, E.pid
";
$res = $db->query ( $sql );
$smarty->assign ( 'num_eroef', $res->num_rows );
$k_eroef = array ();
while ( $assoc = $res->fetch_assoc () ) {
	// Dublettensuche anhand von Name, Eröffnungsdatum und Bekanntmachungsdatum
	$eroef_id = $assoc ['id'];
	$s_name = $db->real_escape_string ( $assoc ['s_name'] );
	$eroef_dat = $assoc ['eroef_dat'];
	$bek_dat = $assoc ['bek_dat'];
	if (! is_null ( $assoc ['pid'] )) {
		$eroef_pid_bedingung = "AND E.id != $assoc[pid] AND E.pid != $assoc[pid]";
	} else {
		$eroef_pid_bedingung = "";
	}
	$sql = "SELECT E.id, K.bildnr, K.jahr, K.id as kmit_id, F.filmnr_kurz, F.filmnr_lang 
		FROM k_eroef AS E 
		JOIN kmit AS K ON E.aid = K.id 
		JOIN filme AS F ON K.fid = F.id 
		WHERE E.s_name = '$s_name' AND E.eroef_dat = '$eroef_dat' AND E.bek_dat = '$bek_dat' 
		AND E.id != $eroef_id AND E.pid != $eroef_id $eroef_pid_bedingung";
	$res_dub = $db->query ( $sql );
	$assoc ['dub_num'] = $res_dub->num_rows;
	while ( $assoc_dub = $res_dub->fetch_assoc () ) {
		$assoc ['dubs'] [] = $assoc_dub;
	}
	$k_eroef [] = $assoc;
}
$smarty->assign ( 'k_eroef', $k_eroef );

// Lade die Konkursbeendigungen aus dieser Ausgabe und verknüpfe sie mit vorhandenen Eröffnungen
$sql = "SELECT B.`id`, B.`pid`, B.`kid`, IF(BC.pid IS NULL,FALSE,TRUE) AS isparent,
E.`s_name` AS es_name, E.`s_beruf` AS es_beruf, E.`s_ort` AS es_ort, E.`t_dat` AS et_dat, E.`gericht` AS egericht, E.bemerk as ebemerk,
KF.`filmnr_lang` AS efilmnr_lang, E.`aid` AS eaid,
B.`s_name` AS bs_name, B.`s_beruf` AS bs_beruf, B.`s_ort` AS bs_ort, B.`gericht` AS bgericht, B.`typ`, B.`aufh_dat`, B.`bek_dat`, 
B.`t_dat` AS bt_dat, B.`bemerk` AS bbemerk, B.`last` AS blast
FROM k_beend AS B 
JOIN kmit AS KB ON B.aid = KB.id 
LEFT JOIN ( 
	k_eroef AS E
	JOIN kmit AS KE ON E.aid = KE.id
	JOIN filme AS KF ON KE.fid = KF.id
)
ON B.kid = E.id
LEFT JOIN k_beend AS BC
ON B.id = BC.pid
WHERE KB.id = $kmit_id
GROUP BY B.id
ORDER BY COALESCE(bgericht, egericht), bek_dat, COALESCE(B.pid,B.id), B.pid IS NULL DESC, B.pid";
$res = $db->query ( $sql );
$smarty->assign ( 'num_beend', $res->num_rows );
$k_beend = array ();
while ( $assoc = $res->fetch_assoc () ) {
	// Dublettensuche anhand von Name und Bekanntmachungsdatum
	$beend_id = $assoc ['id'];
	$bs_name = $db->real_escape_string ( $assoc ['bs_name'] );
	$es_name = $db->real_escape_string ( $assoc ['es_name'] );
	$bek_dat = $assoc ['bek_dat'];
	if (! is_null ( $assoc ['pid'] )) {
		$beend_pid_bedingung = "AND B.id != $assoc[pid] AND B.pid != $assoc[pid]";
	} else {
		$beend_pid_bedingung = "";
	}
	$sql = "SELECT B.id, K.bildnr, K.jahr, K.id as kmit_id, F.filmnr_kurz, F.filmnr_lang
		FROM k_beend AS B
		JOIN kmit AS K ON B.aid = K.id
		JOIN filme AS F ON K.fid = F.id
		LEFT JOIN k_eroef AS E ON B.kid = E.id
		WHERE (B.s_name = '$bs_name' OR B.s_name = '$es_name' OR E.s_name = '$bs_name' OR E.s_name = '$es_name')
		AND (B.bek_dat = '$bek_dat' AND B.id != $beend_id AND B.pid != $beend_id $beend_pid_bedingung)";
	$res_dub = $db->query ( $sql );
	$assoc ['dub_num'] = $res_dub->num_rows;
	while ( $assoc_dub = $res_dub->fetch_assoc () ) {
		$assoc ['dubs'] [] = $assoc_dub;
	}
	$k_beend [] = $assoc;
}
$smarty->assign ( 'k_beend', $k_beend );

$smarty->assign ( 'film_url', $film_url );
$smarty->assign ( 'filmnr_kurz', $filmnr_kurz );
$smarty->assign ( 'filmnr_lang', $filmnr_lang );
$smarty->assign ( 'bildnr', $bildnr );
$smarty->assign ( 'jahr', $jahr );
$smarty->assign ( 'ausgabe', $ausgabe );
$smarty->assign ( 'kmit_id', $kmit_id );
$smarty->assign ( 'done', $done );
$smarty->assign ( 'bemerk', $bemerk );
$smarty->assign ( 'last', $last );

$smarty->display ( 'auswertung.tpl' );

