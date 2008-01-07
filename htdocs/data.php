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
?>

<map>
	<nodes>
		<?php
			$query = "SELECT * FROM " . MYSQL_NODES_TABLE . " WHERE status IN (1,2) ORDER BY status DESC, nodename";
			$result = mysql_query ($query, $connection) or die (mysql_error());

			while ($row = mysql_fetch_assoc($result)) {
				$name = htmlspecialchars($row['nodeName']);
				$owner = htmlspecialchars($row['userRealName']);
				$website = htmlspecialchars($row['userWebsite']);
				$jabber = htmlspecialchars($row['userJabber']);
				$email = htmlspecialchars($row['userEmail']);
				$publish_email = htmlspecialchars($row['userEmailPublish']);
				$lat = htmlspecialchars($row['lat']);
				$lng = htmlspecialchars($row['lng']);
				$status = htmlspecialchars($row['status']);
				$addr = htmlspecialchars($row['streetAddress']);
				$desc = htmlspecialchars ($row['nodeDescription']);

				if ($status == 1)
					$state = "potential";
				else if ($status == 2)
					$state = "active";

				if ($publish_email == 1) {
					echo "<node name=\"$name\" base64Name=\"" . base64_encode($name) . "\" owner=\"$owner\" website=\"$website\" jabber=\"$jabber\" lat=\"$lat\" lng=\"$lng\" state=\"$state\" description=\"$desc\" streetAddress=\"$addr\" email=\"$email\" />\n";
				} else {
					echo "<node name=\"$name\" base64Name=\"" . base64_encode($name) . "\" owner=\"$owner\" website=\"$website\" jabber=\"$jabber\" lat=\"$lat\" lng=\"$lng\" state=\"$state\" description=\"$desc\" streetAddress=\"$addr\" email=\"\" />\n";
				}
			}

		?>

	</nodes>
	<links>
		<?php
			$query = "SELECT * FROM " . MYSQL_LINKS_TABLE . " ORDER BY type DESC";
			$result = mysql_query ($query, $connection) or die (mysql_error());

			while ($row = mysql_fetch_assoc($result)) {
				$query = "SELECT nodeName FROM " . MYSQL_NODES_TABLE . " WHERE id = '" . $row['node1'] . "'";
				$node_result = mysql_query ($query, $connection) or die (mysql_error());
				$node1row = mysql_fetch_row($node_result);
				$node1name = htmlspecialchars($node1row[0]);

				$query = "SELECT nodeName FROM " . MYSQL_NODES_TABLE . " WHERE id = '" . $row['node2'] . "'";
				$node_result = mysql_query ($query, $connection) or die (mysql_error());
				$node2row = mysql_fetch_row($node_result);
				$node2name = htmlspecialchars($node2row[0]);

				$type = htmlspecialchars($row['type']);

				# FIXME: We should probaby make a node type table
				echo "<link node1=\"" . $node1name . "\" node2=\"" . $node2name . "\" type=\"" . $type . "\" />\n";
			}
		?>

	</links>
<?php
mysql_close ($connection);
?>
</map>


