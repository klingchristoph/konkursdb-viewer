<?php
require_once ("config.php");
$smarty->assign ( 'title', "Hauptmenü" );

if (isset ( $_POST ['kid_anzeigen'] )) {
	if (isset ( $_POST ['kid'] ) && $_POST ['kid'] != "") {
		$kid = $db->real_escape_string ( intval ( $_POST ['kid'] ) );
		$sql = "SELECT id FROM k_eroef WHERE id = $kid";
		$res = $db->query ( $sql );
		if ($res->num_rows == 0) {
			// das darf nicht passieren
			die ( "Kann die KID $kid nicht finden." );
		} else {
			$link = "edit.php?art=eroef&id=$kid";
			header ( "Location: $link" );
			exit ();
		}
	} else {
		die ( "Keine KID angegeben." );
	}
} elseif (isset ( $_POST ['bid_anzeigen'] )) {
	if (isset ( $_POST ['bid'] ) && $_POST ['bid'] != "") {
		$bid = $db->real_escape_string ( intval ( $_POST ['bid'] ) );
		$sql = "SELECT id FROM k_beend WHERE id = $bid";
		$res = $db->query ( $sql );
		if ($res->num_rows == 0) {
			// das darf nicht passieren
			die ( "Kann die BID $bid nicht finden." );
		} else {
			$link = "edit.php?art=beend&id=$bid";
			header ( "Location: $link" );
			exit ();
		}
	} else {
		die ( "Keine BID angegeben." );
	}
} elseif (isset ( $_POST ['schuldner_suche'] )) {
	if (isset ( $_POST ['s_name'] ) && isset ( $_POST ['s_name'] ) != "") {
		$s_name = htmlspecialchars_decode($_POST['s_name']);
		
		$query = array();
		$query["typ"] = 'alle';
		$query["custom_where"] = 'E.s_name like "%' . $s_name . '%" OR B.s_name like "%' . $s_name . '%"';
		$query["custom_select"] = 'E.s_name, B.s_name, E.gericht_cn, B.gericht_cn, E.id, B.id';
		$query["custom_order"] = 'COALESCE(E_s_name,B_s_name)';
		$query["options"] = array("norm", "purged");

		$link = "kliste.php";
		$link .= "?" . http_build_query($query);
		
		header ( "Location: $link" );
		exit ();
	} else {
		die ( "Kein Schuldnername angegeben." );
	}
} elseif (isset ( $_POST ['verwalter_suche'] )) {
	if (isset ( $_POST ['v_name'] ) && isset ( $_POST ['v_name'] ) != "") {
		$v_name = htmlspecialchars_decode($_POST['v_name']);
		
		$query = array();
		$query["typ"] = 'eroef';
		$query["custom_where"] = 'E.v_name like "%' . $v_name . '%"';
		$query["custom_select"] = 'E.vid, E.v_name AS Verwaltername, E.v_beruf_cn AS Beruf, E.v_ort_cn AS Ort, COUNT(*) AS Verfahrenszahl';
		$query["custom_group"] = 'E.v_name, E.v_beruf_cn, E.v_ort_cn';
		$query["custom_order"] = 'E.vid, E.v_name, E.v_beruf_cn, E.v_ort_cn';
		$query["key"] = 'E.vid';
		$query["options"] = array("norm", "purged");
		
		$link = "kliste.php";
		$link .= "?" . http_build_query($query);
		
		header ( "Location: $link" );
		exit ();
	} else {
		die ( "Kein Verwaltername angegeben." );
	}
}

$smarty->display ( 'raviewer.tpl' );

?>