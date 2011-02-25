<?php 
/*
WNMap
Copyright (C) 2006 Eric Butler <eric@extremeboredom.net>

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

header ("Content-Type: text/xml");
$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database.');

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n
<map>\n
<nodes>\n";

/* Push the nodes */
$query = "SELECT * FROM " . MYSQL_NODES_TABLE . " WHERE status IN (1, 2, 3);";
$result = mysql_query ($query, $connection) or die (mysql_error());

while ($row = mysql_fetch_assoc($result)) {
	$id = htmlspecialchars($row['id']);
	$name = htmlspecialchars($row['nodeName']);
	$owner = htmlspecialchars($row['userRealName']);
	$desc = htmlspecialchars ($row['nodeDescription']);
	$lat = htmlspecialchars($row['lat']);
	$lng = htmlspecialchars($row['lng']);
	$status = htmlspecialchars($row['status']);

	if ($status == 1)
		$state = "potential";
	else if ($status == 2)
		$state = "active";
	else if ($status == 3)
		$state = "hotspot";
	
echo "<node name=\"$name\" owner=\"$owner\" lat=\"$lat\" lng=\"$lng\" id = \"$id\" state=\"$state\" description=\"$desc\"/>\n";
}

/* Now push the links */
echo "</nodes>
	<links>";
		
$query = "SELECT * FROM " . MYSQL_LINKS_TABLE .";";
$result = mysql_query ($query, $connection) or die (mysql_error());

while ($row = mysql_fetch_assoc($result)) {
	echo "<link node1=\"" . $row['node1'] . "\" node2=\"" . $row['node2'] . "\" type=\"" . $row['type'] . "\" quality=\"" . $row['quality'] . "\" />\n";
}

echo "</links>";

mysql_close ($connection);
echo "</map>";

?>

