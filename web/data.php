<?php 
/*
SeattleWireless Map
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
?>

<map>
	<nodes>
		<?php
			$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
			mysql_select_db (MYSQL_DB) or die ('Could not select database.');
			$query = "SELECT * FROM nodes WHERE status IN (1,2) ORDER BY status DESC, nodename";
			$result = mysql_query ($query, $connection) or die (mysql_error());

			while ($row = mysql_fetch_assoc($result)) {
				$name = $row['nodeName'];
				$lat = $row['lat'];
				$lng = $row['lng'];
				$status = $row['status'];
				$addr = $row['streetAddress'];
				$desc = htmlspecialchars ($row['nodeDescription']);

				if ($status == 1)
					$state = "potential";
				else if ($status == 2)
					$state = "active";

				echo "<node name=\"$name\" lat=\"$lat\" lng=\"$lng\" state=\"$state\" description=\"$desc\" streetAddress=\"$addr\" />\n";
			}

			mysql_close ($connection);
		?>

	</nodes>
	<links>
		<!--<link node1="NodeOne" node2="NodeMultiLocal" />-->
		<!--<link node1="NodeDexter" node2="NodeMultiLocal" />-->
		<!--<link node1="NodeDexter" node2="NodeOne" type="wifi" />
		<link node1="NodeSheffield" node2="NodeDexter" type="wifi" />
		<link node1="NodeSheffield" node2="NodeOne" type="wifi" />
		<link node1="NodeDexter" node2="NodeAreis" type="wifi" />
		<link node1="NodeTheStrand" node2="NodeBelmontEast" type="wifi" />
		<link node1="NodeOne" node2="2608-swn" type="tun" />
		<link node1="NodeOne" node2="NodeBelmontEast" type="tun" />
		<link node1="Gir" node2="NodeBelmontEast" type="tun" />
		<link node1="NodeOne" node2="Gir" type="tun" /> -->
	</links>
</map>
