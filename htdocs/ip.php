<?php

/*
WNMap
Copyright (C) 2011 Claudio Mignanti <c.mignanti@gmail.com>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
*/

error_reporting(E_ALL);

require ("config.php");
$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database.');

echo "<html><body><table>";

$query = "SELECT COUNT(*) FROM " . MYSQL_NODES_TABLE . " WHERE status=";

//potential nodes
$result = mysql_query ($query."1;", $connection) or die (mysql_error());

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	$num = htmlspecialchars($row[0]);
	
	echo "<tr><td>Nodi potenziali</td><td>$num</td></tr>";
}

//active nodes
$result = mysql_query ($query."2;", $connection) or die (mysql_error());

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	$num = htmlspecialchars($row[0]);
	
	echo "<tr><td>Nodi Attivi</td><td>$num</td></tr>";
}

//potential nodes
$result = mysql_query ($query."3;", $connection) or die (mysql_error());

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	$num = htmlspecialchars($row[0]);
	
	echo "<tr><td>Nodi HotSpot</td><td>$num </td></tr>";
}

echo "</table><h1>Lista indirizzi in uso</h1><table>";

$query = "SELECT id, nodeName, nodeIP  FROM " . MYSQL_NODES_TABLE . " WHERE status IN (2,3) ORDER by nodeIP";

//lista ips
$result = mysql_query ($query, $connection) or die (mysql_error());

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	$id = htmlspecialchars($row[0]);
	$nome = htmlspecialchars($row[1]);
	$ips = htmlspecialchars($row[2]);
	
	echo "<tr><td> $id </td><td> $nome </td><td> $ips </td></tr>";
}


echo "</table></body></html>";
?>
