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
		<name><?php echo ORG_NAME;?> Nodes</name>
		<description><?php echo ORG_DESC;?></description>
		<Url>
			<href><?php echo MAP_URL;?>/kml-feed.php</href>
			<viewRefreshMode>onRequest</viewRefreshMode>
		</Url>
	</NetworkLink>
</kml>

<?php } else {
	$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
	mysql_select_db (MYSQL_DB) or die ('Could not select database.');
?>
<Document>
	<name><?php echo ORG_NAME;?></name>
	<description><?php echo ORG_DESC;?></description>
	<LookAt>
		<longitude><?php echo MAP_CENTER_LONG;?></longitude>
		<latitude><?php echo MAP_CENTER_LAT;?></latitude>
		<range>100000</range>
		<tilt>0</tilt>
		<heading>0</heading>
	</LookAt>
	<Style id="activeNodeStyle">
		<IconStyle id="activeNodeIconStyle">
			<Icon>
				<href><?php echo MAP_URL;?>/images/marker_active.png</href>
			</Icon>
		</IconStyle>
	</Style>
	<Style id="potentialNodeStyle">
		<IconStyle id="potentialNodeIconStyle">
			<Icon>
				<href><?php echo MAP_URL;?>/images/marker_potential.png</href>
			</Icon>
		</IconStyle>
	</Style>
    <Style id="Link1Style">
        <LineStyle>
            <color>7f00ff00</color>
            <width>4</width>
        </LineStyle>
    </Style>
    <Style id="Link2Style">
        <LineStyle>
            <color>7f00ffff</color>
            <width>4</width>
        </LineStyle>
    </Style>
    <Style id="Link3Style">
        <LineStyle>
            <color>7f0000ff</color>
            <width>4</width>
        </LineStyle>
    </Style>

	<Folder>
		<name>Active Nodes</name>
		<description>Nodes that are up and running</description>
		<?php DoNodes (2); ?>
	</Folder>	

	<Folder>
		<name>Potential Nodes</name>
		<description>Potential node locations</description>
		<?php DoNodes (1); ?>
	</Folder>
	
	<Folder>
		<name>Active Links</name>
		<description>The Links that are active</description>
		<?php DoLinks(); ?>
	</Folder>	


</Document>
<?php 
	mysql_close ($connection);
}

function DoNodes ($statusId) {
	global $connection;
	$query = "SELECT * FROM nodes WHERE status = $statusId ORDER BY status DESC, nodename";
	$result = mysql_query ($query, $connection) or die (mysql_error());

	while ($row = mysql_fetch_assoc($result)) {
		$name = htmlspecialchars ($row['nodeName']);
		$lat = $row['lat'];
		$lng = $row['lng'];
		$status = $row['status'];
		$desc = htmlspecialchars ($row['nodeDescription']);
	?>

	<Placemark>
		<description><![CDATA[<?php echo $desc ;?> (<a href="<?php printf (NODE_URL_FORMAT, $name); ?>">View Wiki Page</a>)]]></description>
		<name><?php echo $name ?></name>

		<?php if ($status == 2) { ?>
		<styleUrl>#activeNodeStyle</styleUrl>
		<?php } else { ?>
		<styleUrl>#potentialNodeStyle</styleUrl>
		<?php } ?>

		<LookAt>
			<longitude><?php echo $lng;?></longitude>
			<latitude><?php echo $lat; ?></latitude>
			<range>540.68</range>
			<tilt>0</tilt>
			<heading>3</heading>
		</LookAt>
		<Point>
			<coordinates><?php echo $lng;?>,<?php echo $lat; ?></coordinates>
		</Point>
	</Placemark>
	<?php
	}
}

function DoLinks() {
	global $connection;
	$query = "SELECT n1.lat AS lat1, n1.lng AS lng1, n2.lat AS lat2, n2.lng AS lng2, links.quality AS qlt FROM (links JOIN nodes AS n1 ON links.node1 = n1.id) JOIN nodes AS n2 ON links.node2 = n2.id";
	$result = mysql_query ($query, $connection) or die (mysql_error());
	while ($row = mysql_fetch_assoc($result)) {
		$lat1 = $row['lat1'];
		$lng1 = $row['lng1'];
		$lat2 = $row['lat2'];
		$lng2 = $row['lng2'];
		$qlt = $row['qlt'];
	?>
	<Placemark>
	<styleUrl>#Link<?php echo $qlt;?>Style</styleUrl>
	<name>LQ <?php echo $qlt;?></name>

		<LineString>
		  <coordinates><?php echo $lng1;?>,<?php echo $lat1;?> <?php echo $lng2;?>,<?php echo $lat2;?></coordinates> 
		</LineString>
	</Placemark>
<?php
	}
}
?>

