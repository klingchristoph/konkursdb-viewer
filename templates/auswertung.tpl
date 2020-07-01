<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>RA {$title} {$filmnr_kurz|string_format:'%03d'}-{$filmnr_lang}#{$bildnr}</title>
{literal}
<style type="text/css">
	table.locker td, table.locker th {
		border-width: 1px;
		border-style: solid;
		border-color: #666666;
		padding: 15px 10px 15px 10px;
	}
	table.locker {
		border-collapse: collapse;
	}
	a[href] {color:blue}
</style>
{/literal}
</head>
<body>
	<h1>Konkursbekanntmachungen anzeigen</h1>
	<h2>Überblick</h2>
	<form action="" method="post">
		<table border="0" cellpadding="2">
			<tr>
				<td>Konkursbild-ID</td>
				<td>{$kmit_id}</td>
			</tr>
			<tr>
				<td>Filmnummer</td>
				<td>{$filmnr_kurz|string_format:'%03d'}-{$filmnr_lang}</td>
			</tr>
			<tr>
				<td>Bild</td>
				<td>{$bildnr}</td>
			</tr>
			<tr>
			  <td>Quelle</td>
			  <td><a href="{$film_url}/{$bildnr|string_format:'%04d'}.jp2" target="_blank"><b>Digitalisat anzeigen</b></a></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td></td>
			</tr>
			<tr>
				<td>Jahr</td>
				<td>{$jahr|default:'Jahr noch nicht erfasst. Bitte nachholen.'} (<u>geprüft?</u>)</td>
			</tr>
			<tr>
				<td>Ausgaben-Nr.</td>
				<td>{$ausgabe|default:'<b>Ausgaben-Nummer noch nicht
						erfasst. Bitte nachholen.</b>'} (<u>geprüft?</u>)
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td></td>
			</tr>
			<tr>
				<td>Erfasste Eröffnungen</td>
				<td>{$num_eroef}</td>
			</tr>
			<tr>
				<td>Erfasste Beendigungen</td>
				<td>{$num_beend}</td>
			</tr>
			<tr>
				<td>Gesamt</td>
				<td>{$num_eroef + $num_beend}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td></td>
			</tr>
			<tr>
				<td>Letzter Bearbeiter</td>
				<td>{$last}</td>
			</tr>
			<tr>
				<td>Erfassung abgeschlossen</td>
				<td align="center" width="400"
					bgcolor="{if $done == false}#FF0000{elseif $done == true}#00FF00{/if}"><b>
						{if $done == false}&nbsp;nein&nbsp;{elseif $done ==
						true}&nbsp;ja&nbsp;{/if}</b></td>
			</tr>
		</table>
		<h2>Eröffnungen</h2>
		<table class="locker">
			<tr>
				<th>KID</th>
				<th>BID</th>
				<th style="border-width: 1px 4px 1px 4px;">Gericht</th>
				<th>Eröf.-Dat.</th>
				<th>Bek.-Dat.</th>
				<th style="border-width: 1px 1px 1px 4px;">Schuldner</th>
				<th>Ort</th>
				<th>Beruf</th>
				<th>T.-Dat.</th>
				<th style="border-width: 1px 1px 1px 4px;">Verwalter</th>
				<th>Ort</th>
				<th>Beruf</th>
				<th style="border-width: 1px 1px 1px 4px;">Anz.-Frist</th>
				<th>Anm.-Frist</th>
				<th>Gl.-Vers.</th>
				<th>Pr.-Termin</th>
				<th style="border-width: 1px 1px 1px 4px;">Bemerk.</th>
				<th>Letzter<br />Bearb.</th>
			</tr>
			{foreach from=$k_eroef item=i}
			<tr{if !is_null($i.bid)} bgcolor="#81F781"{/if}>
				<td style="white-space:nowrap;">{if !is_null($i.pid)}<div style="min-height: 60px;"><div style="position: relative; vertical-align:middle; display:table-cell;">&nbsp;{$i.pid}&darr;</div>{/if}
				<button name="save_and_link" value="edit.php?art=eroef&id={$i.id}" type="submit">{$i.id}</button>
				{if $i.dub_num >= 1}
				<br /><b><font color="#FF0000">Dubl.-Warnung ({$i.dub_num}):<br />
				{foreach from=$i.dubs item=dub}
				Film {$dub.filmnr_kurz|string_format:'%03d'}-{$dub.filmnr_lang} ({$dub.jahr}), <a href="auswertung.php?kmit_id={$dub.kmit_id}" target="blank"><br />Bild {$dub.bildnr}</a>, KID {$dub.id}
				{if not $dub@last};<br />{/if}
				{/foreach}
				</font></b>{/if}</td>
				<td style="text-align:center;">{if !is_null($i.bid)}<a href="edit.php?art=beend&id={$i.bid}" target="_blank">{/if}{$i.bid|default:'unverknüpft'}{if !is_null($i.bid)}</a>{/if}</td>
				<td style="border-width: 1px 4px 1px 4px;">{$i.gericht|escape:'html'}</td>
				<td>{$i.eroef_dat|mysqlDateToGerman:true}</td>
				<td>{$i.bek_dat|mysqlDateToGerman:true}</td>
				<td style="border-width: 1px 1px 1px 4px;">{$i.s_name|escape:'html'}</td>
				<td>{$i.s_ort|escape:'html'}</td>
				<td>{$i.s_beruf|escape:'html'}</td>
				<td>{$i.t_dat|mysqlDateToGerman:true}</td>
				<td style="border-width: 1px 1px 1px 4px;">{$i.v_name|escape:'html'}</td>
				<td>{$i.v_ort|escape:'html'}</td>
				<td>{$i.v_beruf|escape:'html'}</td>
				<td style="border-width: 1px 1px 1px 4px;">{$i.anz_dat|mysqlDateToGerman:true}</td>
				<td>{$i.anm_dat|mysqlDateToGerman:true}</td>
				<td>{$i.gvers_dat|mysqlDateToGerman:true}</td>
				<td>{$i.pruef_dat|mysqlDateToGerman:true}</td>
				<td style="border-width: 1px 1px 1px 4px;">{$i.bemerk|escape:'html'}</td>
				<td>{$i.last}</td>
			</tr>
			{/foreach}
		</table>
		<h2>Beendigungen</h2>
		<table border="1" class="locker">
			<tr>
				<th rowspan="2">BID</th>
				<th rowspan="2">KID</th>
				<th colspan="2" style="border-width: 1px 4px 1px 4px;">Gericht</th>
				<th rowspan="2">Bek.-Dat.</th>
				<th rowspan="2">Typ</th>
				<th rowspan="2">Aufh.-Dat.</th>
				<th colspan="2" style="border-width: 1px 1px 1px 4px;">Schuldner</th>
				<th colspan="2">Ort</th>
				<th colspan="2">Beruf</th>
				<th colspan="2">T.-Dat.</th>
				<th colspan="2" style="border-width: 1px 1px 1px 4px;">Bemerk.</th>
				<th rowspan="2">Letzter<br />Bearb.</th>
			</tr>
			<tr>
				<th style="border-width: 1px 1px 1px 4px;"><u>K</u>ID</th>
				<th style="border-width: 1px 4px 1px 1px;"><u>B</u>ID</th>
				<th style="border-width: 1px 1px 1px 4px;"><u>K</u>ID</th>
				<th><u>B</u>ID</th>
				<th><u>K</u>ID</th>
				<th><u>B</u>ID</th>
				<th><u>K</u>ID</th>
				<th><u>B</u>ID</th>
				<th><u>K</u>ID</th>
				<th><u>B</u>ID</th>
				<th style="border-width: 1px 1px 1px 4px;"><u>K</u>ID</th>
				<th><u>B</u>ID</th>
			</tr>
			{foreach from=$k_beend item=i}
			<tr bgcolor="{if is_null($i.kid)}{if $i.isparent == true}{else}#F5A9A9{/if}{else}#81F781{/if}">
				<td style="white-space:nowrap;">{if !is_null($i.pid)}<div style="min-height: 60px;"><div style="position: relative; vertical-align:middle; display:table-cell;">&nbsp;{$i.pid}&darr;</div>{/if}
				<button name="save_and_link" value="edit.php?art=beend&id={$i.id}" type="submit">{$i.id}</button>
				{if $i.dub_num >= 1}
				<br />
				<b><font color="#FF0000">Dubl.-Warnung ({$i.dub_num}):<br />
				{foreach from=$i.dubs item=dub}
				Film {$dub.filmnr_kurz|string_format:'%03d'}-{$dub.filmnr_lang} ({$dub.jahr}), <a href="auswertung.php?{$dub.kmit_id}" target="blank">Bild<br /> {$dub.bildnr}</a>, BID {$dub.id}
				{if not $dub@last};<br />{/if}
				{/foreach}
				</font></b>{/if}{if !is_null($i.pid)}</div>{/if}</td>
				<td>{if !is_null($i.kid)}<a href="edit.php?art=eroef&id={$i.kid}" target="_blank">{/if}{$i.kid|default:'verwaist'}{if !is_null($i.kid)}</a>{/if}</td>
				<td style="border-width: 1px 1px 1px 4px;">{$i.egericht|escape:'html'}</td>
				<td style="border-width: 1px 4px 1px 1px;">{$i.bgericht|escape:'html'}</td>
				<td>{$i.bek_dat|mysqlDateToGerman:true}</td>
				<td>{$i.typ|upper}</td>
				<td>{$i.aufh_dat|mysqlDateToGerman:true}</td>
				<td style="border-width: 1px 1px 1px 4px;">{$i.es_name|escape:'html'}</td>
				<td>{$i.bs_name|escape:'html'}</td>
				<td>{$i.es_ort|escape:'html'}</td>
				<td>{$i.bs_ort|escape:'html'}</td>
				<td>{$i.es_beruf|escape:'html'}</td>
				<td>{$i.bs_beruf|escape:'html'}</td>
				<td>{$i.et_dat|mysqlDateToGerman:true}</td>
				<td>{$i.bt_dat|mysqlDateToGerman:true}</td>
				<td style="border-width: 1px 1px 1px 4px;">{$i.ebemerk|escape:'html'}</td>
				<td>{$i.bbemerk|escape:'html'}</td>
				<td>{$i.blast}</td>
			</tr>
			{/foreach}
		</table>
		<h2>Bemerkungen zu diesem Bild</h2>
    <div style="background-color: #F3F781; height: 100px; width: 850px; vertical-align: top; text-align: left;">{$bemerk|default:''|escape:'html'}</div>
	</form>
	<br />
{include file='footer.tpl'}
</body>
</html>