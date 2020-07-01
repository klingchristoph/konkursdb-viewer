<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>RA {if $art == "eroef"}KID{elseif $art == "beend"}BID{/if}
{if isset($edit.id)} {$edit.id}{/if}
{if isset($edit.pid) && !is_null($edit.pid)} [{$edit.pid}]){/if}</title>
{literal}
<style type="text/css">
	table.sucherg td, table.sucherg th {
		border-width: 1px;
		border-style: solid;
		padding: 5px;
	}
	table.sucherg {
		border-width: 1px;
		border-spacing: 0;
		border-collapse: collapse;
	}
	a[href] {color:blue}
</style>
{/literal}
</head>
<body>
	<h1>{if $art == "eroef"}Eröffnung{elseif $art == "beend"}Beendigung{/if} 
	  ID #{$edit.id}
		{if isset ($edit.pid) && !is_null($edit.pid)} (abgezweigt von #ID {$edit.pid}){/if}</h1>
	<h2>Quellenangaben</h2>
	<table border="0">
		<tr>
			<td>Konkursbild-ID</td>
			<td>{$kmit_id}</td>
		</tr>
		<tr>
			<td>Filmnummer:</td>
			<td>{$filmnr_kurz|string_format:'%03d'}-{$filmnr_lang}</td>
		</tr>
		<tr>
			<td>Jahrgang des Films:</td>
			<td>{$jahr|default:'Jahr noch nicht erfasst. Bitte nachholen.'}</td>
		</tr>
		<tr>
			<td>Ausgaben-Nr.:</td>
			<td>{$ausgabe|default:'<b>Ausgaben-Nummer noch nicht
					erfasst. Bitte nachholen.</b>'}
			</td>
		</tr>
		<tr>
			<td>Bild:</td>
			<td>{$bildnr} <a
				href="{$film_url}/{$bildnr|string_format:'%04d'}.jp2"
				target="_blank">[ Digitalisat anzeigen ]</a>&nbsp;<a href="auswertung.php?kmit_id={$kmit_id}">[ Zur Bild&uuml;bersicht ]</a></td>
		</tr>
	</table>
	<h2>
		<a name="Eingabe">Inhalt</a>
	</h2>
		<table style="width: 850px; border-width: 1px; border-style: solid; border-color: #666666;">
			{if isset($edit.id) && !is_null($edit.pid)}
			<tr bgcolor="#F5A9F2">
				<td colspan=2 align="center"><b>{if $art == "eroef"}Eröffnung{elseif $art == "beend"}Beendigung{/if} ist abgezweigt von #ID {$edit.pid}</b></td>
			</tr>
			<tr>
				<td colspan=2>&nbsp;</td>
			</tr>
			{/if}
			{if $art == "eroef"}
			<tr>
				<td colspan=2 align="center"><b>Daten zur Eröffnung</b></td>
			</tr>
			<tr>
				<td style="width: 150px;"><b><u>Gericht</u></b></td>
				<td style="background-color: #F3F781;">{$edit.gericht|default:''|escape:'html'}</td>
			</tr>
			<tr>
				<td>Bekanntmachung</td>
				<td style="background-color: #F3F781;">{$edit.bek_dat|default:null|mysqlDateToGerman:true}</td>
			</tr>
			<tr>
				<td><b><u>Schuldner</u></b></td>
				<td style="background-color: #F3F781;">{$edit.s_name|default:''|escape:'html'}</td>
			</tr>
			<tr>
				<td>Beruf</td>
				<td style="background-color: #F3F781;">{$edit.s_beruf|default:''|escape:'html'}</td>
			</tr>
			<tr>
				<td>Ort</td>
				<td style="background-color: #F3F781;">{$edit.s_ort|default:''|escape:'html'}</td>
			</tr>
			<tr>
				<td>Todesdatum</td>
				<td style="background-color: #F3F781;">{$edit.t_dat|default:null|mysqlDateToGerman:true}</td>
			</tr>
			<tr>
				<td>Eröffnung</td>
				<td style="background-color: #F3F781;">{$edit.eroef_dat|default:null|mysqlDateToGerman:true}</td>
			</tr>
			<tr>
				<td><b><u>Verwalter</u></b></td>
				<td style="background-color: #F3F781;">{$edit.v_name|default:''|escape:'html'}</td>
			</tr>
			<tr>
				<td>Beruf</td>
				<td style="background-color: #F3F781;">{$edit.v_beruf|default:''|escape:'html'}</td>
			</tr>
			<tr>
				<td>Ort</td>
				<td style="background-color: #F3F781;"><{$edit.v_ort|default:''|escape:'html'}</td>
			</tr>
			<tr>
				<td>Anzeige-Frist</td>
				<td style="background-color: #F3F781;">{$edit.anz_dat|default:null|mysqlDateToGerman:true}</td>
			</tr>
			<tr>
				<td>Anmelde-Frist</td>
				<td style="background-color: #F3F781;">{$edit.anm_dat|default:null|mysqlDateToGerman:true}</td>
			</tr>
			<tr>
				<td>Gläubigerversammlung</td>
				<td style="background-color: #F3F781;">{$edit.gvers_dat|default:null|mysqlDateToGerman:true}</td>
			</tr>
			<tr>
				<td>Prüfungstermin</td>
				<td style="background-color: #F3F781;">{$edit.pruef_dat|default:null|mysqlDateToGerman:true}</td>
			</tr>
			{elseif $art == "beend"}{if isset($eroef_auswahl)}
			<tr>
				<td colspan=2 align="center"><b>Verknüpfte Eröffnung</b></td>
			</tr>
			<tr>
				<td>KID</td>
				<td bgcolor="#81BEF7">{$eroef_auswahl.id}
			</tr>
			<tr>
				<td>Film</td>
				<td bgcolor="#81BEF7">{$eroef_auswahl.filmnr_kurz|string_format:'%03d'}-{$eroef_auswahl.filmnr_lang}</td>
			</tr>
			<tr>
				<td>Bild</td>
				<td bgcolor="#81BEF7">{if !is_null($eroef_auswahl.kmit_id)}<a
					href="auswertung.php?kmit_id={$eroef_auswahl.kmit_id}"
					target="_blank">{/if}{$eroef_auswahl.bildnr}{if
						!is_null($eroef_auswahl.kmit_id)}</a>{/if}
				</td>
			</tr>
			<tr>
				<td>Schuldner</td>
				<td bgcolor="#81BEF7">{$eroef_auswahl.s_name}</td>
			</tr>
			<tr>
				<td>Beruf</td>
				<td bgcolor="#81BEF7">{$eroef_auswahl.s_beruf}</td>
			</tr>
			<tr>
				<td>Ort</td>
				<td bgcolor="#81BEF7">{$eroef_auswahl.s_ort}</td>
			</tr>
			<tr>
				<td>Eröf.-Dat.</td>
				<td bgcolor="#81BEF7">{$eroef_auswahl.eroef_dat|mysqlDateToGerman:true}</td>
			</tr>
			<tr>
				<td>Gericht</td>
				<td bgcolor="#81BEF7">{$eroef_auswahl.gericht}</td>
			</tr>
			<tr>
				<td>Bek.-Dat.</td>
				<td bgcolor="#81BEF7">{$eroef_auswahl.bek_dat|mysqlDateToGerman:true}</td>
			</tr>
			<tr>
				<td>Bemerk.</td>
				<td bgcolor="#81BEF7">{$eroef_auswahl.bemerk}</td>
			</tr>
			<tr>
				<td colspan=2>&nbsp;</td>
			</tr>
			{elseif isset($edit.id) && is_null($edit.kid)}
			<tr bgcolor="#F5A9A9">
				<td colspan=2 align="center"><b>Verwaist - keine Eröeffnung
						verknüpft</b></td>
			</tr>
			<tr>
				<td colspan=2>&nbsp;</td>
			</tr>
			{/if}
			<tr>
				<td colspan=2 align="center"><b>Daten zur Beendigung</b></td>
			</tr>
			<tr>
				<td style="width: 150px;">Bekanntmachung</td>
				<td style="background-color: #F3F781;">{$edit.bek_dat|default:null|mysqlDateToGerman:true}</td>
			</tr>
			<tr>
				<td>Art der Beendigung</td>
				<td style="background-color: #F3F781;">{$typen[$edit.typ|default:'s']}</td>
			</tr>
			<tr>
				<td>Aufhebungsdatum</td>
				<td style="background-color: #F3F781;">{$edit.aufh_dat|default:null|mysqlDateToGerman:true}</td>
			</tr>
			<tr>
				<td colspan=2>&nbsp;</td>
			</tr>
			<tr>
				<td colspan=2 align="center"><i><b>{if
							isset($eroef_auswahl)}Abweichungen bei Beendigung{else}Eröffnung
							nicht ermittelbar - nur Beendigung{/if}</b></i></td>
			</tr>
			<tr>
				<td><i>Gericht</i></td>
				<td style="background-color: #F3F781;">{$edit.gericht|default:''|escape:'html'}</td>
			</tr>
			<tr>
				<td><i>Schuldner</i></td>
				<td style="background-color: #F3F781;">{$edit.s_name|default:''|escape:'html'}</td>
			</tr>
			<tr>
				<td><i>Beruf</i></td>
				<td style="background-color: #F3F781;">{$edit.s_beruf|default:''|escape:'html'}</td>
			</tr>
			<tr>
				<td><i>Ort</i></td>
				<td style="background-color: #F3F781;">{$edit.s_ort|default:''|escape:'html'}</td>
			</tr>
			<tr>
				<td><i>Todesdatum</i></td>
				<td style="background-color: #F3F781;">{$edit.t_dat|default:null|mysqlDateToGerman:true}</td>
			</tr>
			{/if}{if $art == "eroef" && isset($edit.id) && !is_null($edit.beend)}
      <tr>
        <td colspan=2>&nbsp;</td>
      </tr>
      <tr>
        <td><b><u>Beendigung ID</u></b></td>
        <td style="background-color: #F3F781;"><a href="edit.php?art=beend&id={$edit.beend.id}" target="_blank">{$edit.beend.id}</a></td>
      </tr>{/if}
			<tr>
				<td colspan=2>&nbsp;</td>
			</tr>
			<tr>
				<td colspan=2 align="center"><b>Bemerkungen zu dieser {if
						$art == "eroef"}Eröffnung{else}Beendigung{/if}</b></td>
			</tr>
			<tr>
				<td colspan=2 align="center" style="background-color: #F3F781; height: 100px; vertical-align: top; text-align: left;">{$edit.bemerk|default:''|escape:'html'}</td>
			</tr>
		</table>
		<br />
	<br />
{include file='footer.tpl'}
</body>
</html>