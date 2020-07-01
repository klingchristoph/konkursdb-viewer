<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>RA {$title}</title>
{literal}
<style type="text/css">
  * {font-family: Arial;}
  div {margin-top: 10px; line-height: 1.3}
  a[href] {color:blue}
</style>
{/literal}
</head>
<body>
<h1>Konkurshistorische Datenbank zum Deutschen Kaiserreich (1879-1914)</h1>
<h2>I. Benutzungshinweise</h2>
<div>Die unten aufgef�hrten Recherchem�glichkeiten �ffnen alle ein neues Fenster zur Ergebnisanzeige.</div>
<div>Die <b>Schnellsuche</b> erlaubt die Suche nach Schuldnern und Verwaltern. Es sind keine SQL-Kenntnisse erforderlich.</div>
<div>Mit dem <b>Schnellzugriff</b> lassen sich einzelne Konkurser�ffnungen und Beendigungen mittels der jeweiligen Identifikationsnummer direkt aufrufen.</div>
<div>Der <b>SQL-Abfragegenerator</b> erlaubt individuelle Abfragen, erfordert aber zumindest grunds�tzliche SQL-Kenntnisse sowie Kenntnis der Datenstruktur, insbesondere der Tabellen- und Spaltennamen.</div>
<div>Bitte beachten Sie, dass die Zahl der Ergebniszeilen aufgrund von Ressourcenbeschr�nkungen zurzeit auf 1000 begrenzt ist (LIMIT 1000).</div>
<h2>II. Schnellsuche</h2>
<div>
<form action="" method="post" target="_blank">
<h3>1. Schuldner</h3>
Die Schuldnersuche findet alle Konkurser�ffnungen oder Beendigungen, in denen der Schuldnername enthalten ist. Das Ergebnis differenziert nach dem Schuldnernamen, wie er in der Er�ffnung und wie er in der Beendigung erschienen ist (E_s_name bzw. B_s_name). Weichen die Namen nicht signifikant voneinander ab, wird nur der Name aus der Er�ffnung angezeigt. Angezeigt wird au�erdem der Name des Gerichts, das den Konkurs er�ffnet oder beendet hat (E_gericht_cn bzw. B_gericht_cn). Zur Anzeige n�herer Informationen �ber einzelne Verfahren gelangt man durch Klick auf die E_id (Konkurser�ffnungsidentifikationsnummer, KID) oder B_id (Beendigungsidentifikationsnummer, BID):
<br /><br />
<table border="0">
<tr>
<td style="width: 130px;">Schuldnername</td>
<td><form action="" method="post" target="_blank">
<input type="text" name="s_name" size="20" />&nbsp;
<input name="schuldner_suche" type="submit" value="Suchen" />
</form></td>
</tr>
</table>
<h3>2. Verwalter</h3>
Die Verwaltersuche zeigt alle Verwalter mit dem angegebenen Namen an. Die erste Ergebnisspalte zeigt die eindeutige Verwalteridentifikationsnummer (VID). Ein und derselbe Verwalter erschien im Laufe der Zeit allerdings h�ufig mit unterschiedlichen Namensvarianten und Berufs- oder Ortsangaben. Das Ergebnis listet alle Versionen auf. Durch Klick auf die Verwalteridentifikationsnummer (VID) lassen sich alle Verfahren des jeweiligen Verwalters aufgerufen und n�her betrachten.   
<br /><br />
<table border="0">
<tr>
<td style="width: 130px;">Verwaltername</td>
<td><form action="" method="post" target="_blank">
<input type="text" name="v_name" size="20" />&nbsp;
<input name="verwalter_suche" type="submit" value="Suchen" />
</form></td>
</tr>
</table>
</form>
</div>
<h2>III. Schnellzugriff</h2>
<div>Sind die Konkursidentifikationsnummer (KID) oder Beendigungsidentifikationsnummer (BID) schon bekannt, k�nnen die entsprechenden Bekanntmachungen hier direkt aufgerufen werden:</div>
<div>
<form action="" method="post" target="_blank">
<table border="0">
<tr><td style="width: 130px;">KID anzeigen</td><td><input type="text" name="kid" size="6" />&nbsp;<input name="kid_anzeigen" type="submit" value="Anzeigen" /></td></tr>
<tr><td style="width: 130px;">BID anzeigen</td><td><input type="text" name="bid" size="6" />&nbsp;<input name="bid_anzeigen" type="submit" value="Anzeigen" /></td></tr>
</table>
</form>
</div>
<h2>IV. SQL-Abfragengenerator</h2>
<div>
Es sind drei verschiedene Varianten verf�gbar, die auf unterschiedliche Teildatens�tze zugreifen.
<ul>
<li><a href="kliste.php?typ=alle" target="_blank">Alle Verfahren</a>: Anzeige s�mtlicher Verfahren, auch wenn nur eine Er�ffnung oder nur eine Beendigung vorliegt. Die Ergebniszahl der Verfahren wird hierdurch maximiert, jedoch liegen nicht immer alle Informationen vor. Fehlt die Er�ffnung, sind beispielsweise keine Daten �ber den Konkursverwalter verf�gbar. Fehlt die Beendigung, l�sst sich beispielsweise die Beendigungsart nicht ermitteln. Wegen einiger Beschr�nkungen des verwendeten SQL-Servers sind f�r diese Variante einige Funktionen nicht verf�gbar (beispielsweise ist keine Gruppierung m�glich).</li>
<li><a href="kliste.php?typ=eroef" target="_blank">Nur Verfahren mit Er�ffnung</a>: Verfahren, f�r die keine Er�ffnung vorliegt, bleiben au�er Betracht. In dieser Variante kann allenfalls die Beendigung fehlen. Der Funktionsumfang ist gr��er als bei Anzeige aller Verfahren (z.B. ist Gruppierung m�glich).</li>
<li><a href="kliste.php?typ=beend" target="_blank">Nur Verfahren mit verwaister Beendigung</a>: Es werden nur Verfahren angezeigt, f�r die ausschlie�lich die Beendigung vorliegt. Es handelt sich um diejenigen Verfahren, die in der Variante "Nur Verfahren mit Er�ffnung" fehlen. Der Funktionsumfang ist derselbe wie dort.</li>
</ul>
</div>
{include file='footer.tpl'}
</body>
</html>