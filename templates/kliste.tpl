<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>RA {$title}</title>
{if $typ == "kmit"}{* Verhindere line wrap in der bek_last Spalte zur besseren Lesbarkeit *}
{literal}
<style type="text/css">
	#daten td:last-child, #daten td:nth-last-child(2) {white-space: nowrap;}
</style>
{/literal}
{/if}
{literal}
<style type="text/css">
	td.e {background-color: #00FF00;}
	td.b {background-color: #F5F6CE;}
	#daten, #daten td {border-width: 1px; border-style: solid; border-color: #666666;}
	a[href] {color:blue}
</style>
{/literal}
</head>
<body>
<h1>Daten anzeigen - {$typ_ausf}</h1>
<form action="" method="post">
<a href="raviewer.php">Hauptmen&uuml;</a>
<br />
<table>
{if $typ != "kmit"}<tr><td>Benutzerdefiniertes SELECT</td><td><input type="text" name="custom_select" value="{$custom_select|escape:'html'}" size="150" /></td></tr>{/if}
{if $ger_cn != array()}
{if $typ != "beend"}<tr><td>WHERE E.gericht_cn = </td><td>{html_options values=$ger_cn output=$ger_cn selected=$e_ger_cn_sel name='e_ger_cn'}</td></tr>{/if}
{if $typ != "eroef_offen"}<tr><td>WHERE B.gericht_cn = </td><td>{html_options values=$ger_cn output=$ger_cn selected=$b_ger_cn_sel name='b_ger_cn'}{/if}
{/if}
<tr><td>Benutzerdefiniertes WHERE</td><td><input type="text" name="custom_where" value="{$custom_where|escape:'html'}" size="150" /></td></tr>
{if $typ != "kmit" && $typ != "alle"}<tr><td>Benutzerdefiniertes GROUP BY</td><td><input type="text" name="custom_group" value="{$custom_group|escape:'html'}" size="150" /></td></tr>{/if}
<tr><td>Benutzerdefiniertes ORDER BY</td><td><input type="text" name="custom_order" value="{$custom_order|escape:'html'}" size="150" /></td></tr>
<tr><td>KEY</td><td><input type="text" name="key" value="{$key|escape:'html'}" size="150" /></td></tr>
<tr><td>Zeige</td><td>{if $options != array()}{html_checkboxes name='options' options=$options selected=$options_sel}&nbsp;{/if}<button type="submit" name="custom_submit"/>Abfrage starten</button></td></tr>
</table>
<table id="daten">
{* $tmpN Variablen sind nötig, da Zugriff auf Key "$row.K.id" und "$row.B.id" nicht möglich *}{assign var=tmp1 value='K.id'}{assign var=tmp2 value='B.id'}
{* $verknuepft initialisieren *}{assign var=verknuepft value=0}
{foreach from=$liste item=row name=liste}
<tr{if $smarty.foreach.liste.first or ($smarty.foreach.liste.iteration % 10) == 0} bgcolor="#F78181"{elseif isset($row.$tmp1) && isset($row.$tmp2) && !is_null($row.$tmp2)} bgcolor="#81F781"{/if}>
{if $smarty.foreach.liste.first or ($smarty.foreach.liste.iteration % 10) == 0}
{if isset($row.$tmp1) && isset($row.$tmp2) && !is_null($row.$tmp2)}{assign var=verknuepft value=1}{/if}
{foreach from=$row key=k item=v}
<th id="{$k}"><button name="sortcol" value="{$k}" type="submit">{if strcasecmp(substr($k,2), "kgaa_k") == 0}ka{elseif substr($k,-2) == "_k"}{substr($k,2,2)}{else}{$k}{/if}</button></th>
{/foreach}
</tr><tr{if $verknuepft == 1} bgcolor="#81F781"{* reset variable *}{assign var=verknuepft value=0}{/if}>
{/if}
{foreach from=$row key=k item=v}
<td{if substr($k,-2) == "_k" && $v == 1} class="e"{elseif (strcasecmp(substr($k,0,3), "B_s") == 0 || strcasecmp(substr($k,0,3), "B.s") == 0) && substr($k,-2) != "_k"} class="b"{else}{/if}>{if (strstr($k, '.') == ".bildnr" || strstr($k, '_') == "_bildnr") AND is_array($v)}<a href="auswertung.php?kmit_id={$v.kmit_id}" target="_blank"><b>{$v.nr}</b></a>
{elseif (strstr($k, '.') == ".ts" && !is_null($v))}{$v|mysqlTimestampToGerman}
{elseif ($k == "E.id" || $k == "E_id")}<a href="edit.php?art=eroef&id={$v}" target="_blank"><b>{$v}</b></a>
{elseif ($k == "B.id" || $k == "B_id")}<a href="edit.php?&art=beend&id={$v}" target="_blank"><b>{$v}</b></a>
{elseif (strcasecmp($k, $key) == 0 || strcasecmp($k,str_replace(".","_",$key)) == 0)}<a href="?typ={$smarty.get.typ}&e_ger_cn={$e_ger_cn_sel}&b_ger_cn={$b_ger_cn_sel}&custom_where={assign var=v_esc value=$v|escape:'quotes'}{$key_where|sprintf:$v_esc|escape:'url'}" target="_blank">{$v}</a>
{else}{$v}{/if}</td>
{/foreach}
</tr>
{/foreach}
</table>
{if isset($query)}<br />
<b>{$num_rows} Datensätze</b>
<br /><br /><b>Verwendete Abfrage:</b><br />
<div style="font-size: small;">{$query}</div>{/if}
</form>
{include file='footer.tpl'}
</body>
</html>