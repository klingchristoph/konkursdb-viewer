<?php

// Parameter
// GET (erforderlich): art, id
include "config.php";

if (! isset ( $_GET ['art'] )) {
	die ( "Bekanntmachungsart nicht angegeben." );
} elseif ($_GET ['art'] != "eroef" && $_GET ['art'] != "beend") {
	die ( "Bekanntmachungsart nicht bekannt." );
} else {
	$art = $_GET ['art'];
}

if (! isset ( $_GET ['id'] )) {
	die ( "ID nicht angegeben." );
} else {
	$id = $db->real_escape_string ( intval ( $_GET ['id'] ) );
}

$sql = "SELECT filme.filmnr_kurz,filme.filmnr_lang,kmit.bildnr,kmit.jahr,kmit.ausgabe,kmit.id
FROM kmit 
JOIN filme ON kmit.fid = filme.id 
LEFT JOIN k_eroef ON kmit.id = k_eroef.aid
LEFT JOIN k_beend ON kmit.id = k_beend.aid
WHERE k_" . $art . ".id = " . $id;
$res = $db->query ( $sql );
if ($res->num_rows == 0) {
	// das darf nicht passieren
	die ( "Kann das Konkursbild mit der ID $kmit_id nicht anhand der Bekanntmachungs-ID $id in der Datenbank finden." );
} else {
	$info = $res->fetch_object ();
}

// ermittle die URL des Films

$film_url = getFilmUrl ( $info->filmnr_lang, $info->filmnr_kurz );

$smarty->assign ( 'art', $art );
$smarty->assign ( 'kmit_id', $info->id );
$smarty->assign ( 'ausgabe', $info->ausgabe );
$smarty->assign ( 'jahr', $info->jahr );
$smarty->assign ( 'filmnr_kurz', $info->filmnr_kurz );
$smarty->assign ( 'filmnr_lang', $info->filmnr_lang );
$smarty->assign ( 'bildnr', $info->bildnr );
$smarty->assign ( 'film_url', $film_url );

// +++ Zurück ohne zu speichern +++
if (isset ( $_POST ['discard_and_return'] )) {
	// keine Aktionen
}

// wenn eine dieser buttons geklickt wurde, springe zurück und beende das skript (nicht bei delete_kid!)
if (isset ( $_POST ['save_and_return'] ) || isset ( $_POST ['delete_and_return'] ) || isset ( $_POST ['discard_and_return'] )) {
	// echo $sql;
	header ( "Location: auswertung.php?kmit_id=" . $kmit_id );
	exit ();
}

// +++ Anzeigen / Suchen +++
if ($art == "eroef") {
	$sql = "SELECT * FROM k_eroef WHERE id = $id";
	$res = $db->query ( $sql );
	$edit = $res->fetch_assoc ();
	
	$sql = "SELECT * FROM k_eroef WHERE pid = $id";
	$res = $db->query ( $sql );
	$edit ['children'] = $res->fetch_assoc ();
	
	$sql = "SELECT B.id,B.aid,F.filmnr_lang,F.filmnr_kurz FROM k_beend AS B JOIN kmit AS K ON B.aid = K.id JOIN filme AS F ON K.fid = F.id WHERE B.kid = $id";
	$res = $db->query ( $sql );
	$edit ['beend'] = $res->fetch_assoc ();
	
	if (! is_null ( $edit ['beend'] )) {
		$edit ['beend'] ['bfilm_url'] = getFilmUrl ( $edit ['beend'] ['filmnr_lang'], $edit ['beend'] ['filmnr_kurz'] );
	}
	$smarty->assign ( 'edit', $edit );
} elseif ($art == "beend") {
	// $edit initialisieren, bleibt leer, wenn Beendigung neu angelegt wird
	$edit = array ();
	
	// die $select_kid wird hier initialisiert und kann später gesetzt werden:
	// - im Änderungsmodus bei vorhandener Verknüpfung in der Datenbank
	// - wenn aus einem Suchergebnis eine Eröffnung ausgewählt wurde (überschreibt
	// im Änderungsmodus die bereits gespeicherte Verknüpfung)
	// - wenn die ausgewählte Eröffnung nach dem Klicken eines anderen Buttons
	// "wiederhergestellt" wird (überschreibt im Änderungsmodus ebenfalls die
	// bereits gespeicherte Verknüpfung
	$select_kid = null;
	
	$sql = "SELECT * FROM k_beend WHERE id = $id";
	$res = $db->query ( $sql );
	$edit = $res->fetch_assoc ();
	
	// initialisiere Abzweigungsvariablen
	$edit ['children'] = null;
	
	// Children nur ermitteln (ggf. leeres Resultat), wenn nicht abgezweigt wird
	$sql = "SELECT * FROM k_beend WHERE pid = $id";
	$res = $db->query ( $sql );
	$edit ['children'] = $res->fetch_assoc ();
	
	// Sorge außerdem dafür, dass später die verknüpfte Eröffnung geladen wird.
	// Dieser Eintrag kann an späterer Stelle aber noch überschrieben werden.
	if (! is_null ( $edit ['kid'] )) {
		$select_kid = $edit ['kid'];
	}
	
	// Weise die $edit zu, auch wenn wir nicht im Änderungsmodus sind, um vorhandene Daten zu übergeben
	$smarty->assign ( 'edit', $edit );
	
	// Wenn Eröffnung gewählt, diese laden
	if (! is_null ( $select_kid )) {
		$sql = "SELECT k_eroef.id, k_eroef.s_name, k_eroef.s_ort, k_eroef.s_beruf, k_eroef.gericht, k_eroef.bek_dat, k_eroef.eroef_dat, k_eroef.bemerk, 
		kmit.bildnr, kmit.id as kmit_id, filme.filmnr_lang, filme.filmnr_kurz
		FROM k_eroef
		JOIN kmit ON k_eroef.aid = kmit.id
		JOIN filme ON kmit.fid = filme.id
		WHERE k_eroef.id = $select_kid";
		
		$res = $db->query ( $sql );
		$assoc = $res->fetch_assoc ();
		
		$smarty->assign ( 'eroef_auswahl', $assoc );
	}
	
	// Übergebe mögliche Werte für den Typ der Beendigung
	$typen = array (
			's' => 'Schlussverteilung',
			'z' => 'Zwangsvergleich',
			'm' => 'mangels Masse',
			'a' => 'allgemeine Zustimmung',
			'e' => 'Eröffnungsbeschluss aufgehoben',
			'u' => 'unklar' 
	);
	$smarty->assign ( 'typen', $typen );
}

$smarty->display ( 'edit.tpl' );

?>