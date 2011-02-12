<?php
/*
WNMap
Copyright (C) 2011 Claudio Mignanti <c.mignanti@gmail.com>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/



require ("config.php");

echo "<h2>Manager</h2>";

$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database.');

$name = mysql_real_escape_string ($_GET["name"]);
if ($name == '')
	die ("Error: name must be specified!");
$val = mysql_real_escape_string ($_GET["val"]);

/* Status change - manager.php?name='+name+'&action=status&val='+new_value */
if (isset($_GET["action"]) &&  $_GET["action"] == "status" ) {

	$query = "UPDATE nodes SET status=" . $val . " WHERE nodeName='". $name ."';";

	$result = mysql_query ($query, $connection) or die (mysql_error());

	echo "Lo stato del tuo nodo è stato aggiornato correttamente.<br> Ricarica la pagina del mapserver se non dovesse essere aggiornata.";

}

echo "<a href=\"javascript:void(0);\" onclick=\"window.close();\">Chiudi</a>";

/* TODO: Ip Change */


?>


