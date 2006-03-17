<?
/*
SeattleWireless Map
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

$hash = mysql_real_escape_string ($_GET["hash"]);
if ($hash == '')
	die ("Hash must be specified!");

$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database.');

$query = "UPDATE nodes SET status=-1 WHERE adminHash='$hash'";

$result = mysql_query ($query, $connection) or die (mysql_error ());

if (mysql_affected_rows () != 1) {
	echo "Hash not found.";
} else {
	echo '<h1>Node deleted.</h1>
		<p>Your node has been removed from the map.</p>
		<p><a href="' . MAP_URL . '/">View the map!</a></p>';
}
mysql_close ($connection);
?>
