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

?><?xml version='1.0' encoding='UTF-8' standalone='yes'?><kml xmlns="http://www.opengis.net/kml/2.2"><Document><name><?php echo ORG_NAME;?></name><description><?php echo ORG_DESC;?></description><?php DoNodes (2); ?><?php DoNodes (1); ?></Document></kml>
<?php 
	mysql_close ($connection);

function DoNodes ($statusId) {
	global $connection;
	$query = "SELECT * FROM nodes WHERE status = $statusId ORDER BY status DESC, nodename";
	$result = mysql_query ($query, $connection) or die (mysql_error());

	while ($row = mysql_fetch_assoc($result)) {
		$name = $row['nodeName'];
		$lat = round($row['lat'], 10);
		$lng = round($row['lng'], 10);
	?><Placemark><name><?php echo $name ?></name><Point><coordinates><?php echo $lng;?>,<?php echo $lat; ?>,0.0</coordinates></Point></Placemark><?php
	}
}

?>

