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

if ($_GET["xml"] == null) {
	header ("Content-Type: application/vnd.google-earth.kml+xml"); 
} else {
	header ("Content-Type: text/xml");
}
echo "ciao"

$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database.');

?>
<kml xmlns="http://earth.google.com/kml/2.0">
<Document>
	<name><?php echo ORG_NAME;?></name>
	<description><?php echo ORG_DESC;?></description>
	<Style id="activeNodeStyle">
		<IconStyle id="activeNodeIconStyle"><Icon><href><?php echo MAP_URL;?>/images/marker_active.png</href></Icon></IconStyle>
	</Style>
	<Style id="potentialNodeStyle">
		<IconStyle id="potentialNodeIconStyle"><Icon><href><?php echo MAP_URL;?>/images/marker_potential.png</href></Icon></IconStyle>
	</Style>
	<Style id="Link1Style">
        	<LineStyle><color>7f00ff00</color><width>4</width></LineStyle>
	</Style>
	<Style id="Link2Style">
		<LineStyle><color>7f00ffff</color><width>4</width></LineStyle>
	</Style>
	<Style id="Link3Style">
		<LineStyle><color>7f0000ff</color><width>4</width></LineStyle>
	</Style>
	<?php DoNodes (2); ?>
	<?php DoNodes (1); ?>
	<?php DoLinks(); ?>
</Document>
<?php 
	mysql_close ($connection);

function DoNodes ($statusId) {
	global $connection;
	$query = "SELECT * FROM nodes WHERE status = $statusId ORDER BY status DESC, nodename";
	$result = mysql_query ($query, $connection) or die (mysql_error());

	while ($row = mysql_fetch_assoc($result)) {
		$name = $row['nodeName'];
		$lat = $row['lat'];
		$lng = $row['lng'];
		$status = $row['status'];
		$desc = htmlspecialchars ($row['nodeDescription']);
	?>

	<Placemark>
		<name><?php echo $name ?></name>
		<?php if ($status == 2) { ?>
		<styleUrl>#activeNodeStyle</styleUrl>
		<?php } else { ?>
		<styleUrl>#potentialNodeStyle</styleUrl>
		<?php } ?>
		<Point><coordinates><?php echo $lng;?>,<?php echo $lat; ?></coordinates></Point>
	</Placemark>
<?php
	}
}

function DoLinks() {
	global $connection;
	$query = "SELECT n1.name AS name1, n1.lat AS lat1, n1.lng AS lng1, n2.name AS name2, n2.lat AS lat2, n2.lng AS lng2, links.quality AS qlt FROM (links JOIN nodes AS n1 ON links.node1 = n1.id) JOIN nodes AS n2 ON links.node2 = n2.id";
	$result = mysql_query ($query, $connection) or die (mysql_error());
	while ($row = mysql_fetch_assoc($result)) {
		$lat1 = $row['lat1'];
		$lng1 = $row['lng1'];
		$lat2 = $row['lat2'];
		$lng2 = $row['lng2'];
		$name1 = $row['name1'];
		$name2 = $row['name2'];
		$qlt = $row['qlt'];
	?>
	<Placemark>
	<name><?php echo $name1 . "-" $name2 . " LQ " . $qlt;?></name>
	<styleUrl>#Link<?php echo $qlt;?>Style</styleUrl>
		<LineString>
		  <coordinates><?php echo $lng1;?>,<?php echo $lat1;?> <?php echo $lng2;?>,<?php echo $lat2;?></coordinates> 
		</LineString>
	</Placemark>
<?php
	}
}
?>
</kml>

