<?php
/*
WNMap
Copyright (C) 2006 Eric Butler <eric@extremeboredom.net>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

require ("config.php");
$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database.');

$query = "SELECT * FROM " . MYSQL_NODES_TABLE . " ORDER BY " . MYSQL_NODES_TABLE . ".createdOn DESC LIMIT 0, 30";
$result = mysql_query ($query, $connection) or die (mysql_error());

header('Content-type: text/xml; charset=utf-8'); 
echo('<?xml version="1.0" encoding="UTF-8"?>');

$rssout="";

?>

<rss version="2.0">
<channel> 
<title>ninux.org's mapserver</title>
<link>http://map.ninux.org/</link>
<description>wireless network community Roma</description>
<language>it</language>
<generator>WNmap</generator>

<?php
	$baseurl = "http://map.ninux.org/?select=";

	while ($row = mysql_fetch_assoc($result)) {
		$name = htmlspecialchars($row['nodeName']);
		$owner = htmlspecialchars($row['userRealName']);
		$website = htmlspecialchars($row['userWebsite']);
		$jabber = htmlspecialchars($row['userJabber']);
		$lat = htmlspecialchars($row['lat']);
		$lon = htmlspecialchars($row['lng']);
		$ele = htmlspecialchars($row['elevation']);
		$status = htmlspecialchars($row['status']);
		$addr = htmlspecialchars($row['streetAddress']);
		$ip = htmlspecialchars($row['nodeIP']);
		$desc = htmlspecialchars ($row['nodeDescription']);
		$date = htmlspecialchars ($row['createdOn']);
//		$hash = htmlspecialchars ($row['adminHash');)

		$rssout .= "<item>\n";
		$rssout .= "<title>" . $name . "</title>\n";
		$rssout .= "<link>" . $baseurl . $name . "</link>\n";
		$rssout .= "<pubDate>" . $date . "</pubDate>\n";
//		$rssout .= "<pubDate>Tue, 03 Jun 2003 09:39:21 GMT</pubDate>\n";
		$rssout .= "<description>";
//		$rssout .= $desc;
		$rssout .= $desc . "&lt;br;&gt;\n";
		$rssout .= "Name: " . $owner . "&lt;br;&gt;\n";
		$rssout .= "Address: " . $addr . $lat . $lon . $ele . "&lt;br;&gt;\n";
		$rssout .= "WebSite: " . $website . $jabber . "&lt;br;&gt;\n";
		$rssout .= "IP: " . $ip . "&lt;br;&gt;\n"; 
		$rssout .= " </description>\n"; 
		$rssout .= "</item>\n\n";
	}
	echo $rssout; 
?>
</channel>
</rss>
