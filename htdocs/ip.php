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

require('db/WnMapConnection.php');
require('config.php');

$sql_obj = new WnMapConnection();

echo "<html><body>\n";

echo "<table>\n";

// potential nodes
$result = $sql_obj->executeQuery(SQL_TOTAL_ACTIVE_NODE);

while ($row = pg_fetch_array($result, PGSQL_NUM)) {
	$num = htmlspecialchars($row[0]);
	
	echo "<tr class='potential'><td>Nodi potenziali</td><td>$num</td></tr>\n";
}

//active nodes
$result = $sql_obj->executeQuery(SQL_TOTAL_ACTIVE_NODE);

while ($row = pg_fetch_array($result, PGSQL_NUM)) {
	$num = htmlspecialchars($row[0]);
	
	echo "<tr class='active'><td>Nodi Attivi</td><td>$num</td></tr>\n";
}

//hotspot nodes
$result = $sql_obj->executeQuery(SQL_TOTAL_ACTIVE_NODE);

while ($row = pg_fetch_array($result, PGSQL_NUM)) {
	$num = htmlspecialchars($row[0]);

	echo "<tr class='hotspot'><td>Nodi HotSpot</td><td>$num </td></tr>\n";
}

echo "</table>";









//html end
echo "</body></html>";

?>
