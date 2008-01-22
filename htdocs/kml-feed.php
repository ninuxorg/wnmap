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
	header ('Content-Disposition: attachment; filename="' . strtolower(ORG_NAME) . '.kml"');
} else {
	header ("Content-Type: text/xml");
}
?>

<?php if ($_GET["BBOX"] == null) { ?>
<kml xmlns="http://earth.google.com/kml/2.0">
	<NetworkLink>
		<name><?=ORG_NAME?> Nodes</name>
		<description><?=ORG_DESC?></description>
		<Url>
			<href><?=MAP_URL?>/kml-feed.php</href>
			<viewRefreshMode>onRequest</viewRefreshMode>
		</Url>
	</NetworkLink>
</kml>

<? } else {
	$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
	mysql_select_db (MYSQL_DB) or die ('Could not select database.');
?>
<Document>
	<name><?=ORG_NAME?></name>
	<description><?=ORG_DESC?></description>
	<LookAt>
		<longitude><?=MAP_CENTER_LONG?></longitude>
		<latitude><?=MAP_CENTER_LAT?></latitude>
		<range>100000</range>
		<tilt>0</tilt>
		<heading>0</heading>
	</LookAt>
	<Style id="activeNodeStyle">
		<IconStyle id="activeNodeIconStyle">
			<Icon>
				<href><?=MAP_URL?>/images/marker_active.png</href>
			</Icon>
		</IconStyle>
	</Style>
	<Style id="potentialNodeStyle">
		<IconStyle id="potentialNodeIconStyle">
			<Icon>
				<href><?=MAP_URL?>/images/marker_potential.png</href>
			</Icon>
		</IconStyle>
	</Style>
	<Folder>
		<name>Active Nodes</name>
		<description>Nodes that are up and running</description>
		<? DoNodes (2); ?>
	</Folder>	

	<Folder>
		<name>Potential Nodes</name>
		<description>Potential node locations</description>
		<? DoNodes (1); ?>
	</Folder>
</Document>
<? 
	mysql_close ($connection);
}

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
		<description><![CDATA[<? echo $desc ?> (<a href="<? printf (NODE_URL_FORMAT, $name); ?>">View Wiki Page</a>)]]></description>
		<name><? echo $name ?></name>

		<? if ($status == 2) { ?>
		<styleUrl>#activeNodeStyle</styleUrl>
		<? } else { ?>
		<styleUrl>#potentialNodeStyle</styleUrl>
		<? } ?>

		<LookAt>
			<longitude><?php echo $lng;?></longitude>
			<latitude><?php echo $lat; ?></latitude>
			<range>540.68</range>
			<tilt>0</tilt>
			<heading>3</heading>
		</LookAt>
		<Point>
			<coordinates><? echo $lng?>,<? echo $lat ?></coordinates>
		</Point>
	</Placemark>
	<?php
	}
}
?>

