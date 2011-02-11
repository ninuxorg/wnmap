/*
WNMap
Copyright (C) 2011 Claudio Mignanti <c.mignanti@gmail.com>

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

<?php

require ("config.php");


$name = mysql_real_escape_string ($_GET["name"]);
if ($hash == '')
	die ("Error name must be specified!");
$val = mysql_real_escape_string ($_GET["val"]);

$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database.');

/* Status change - manager.php?hash='+hash+'&action=status&val='+new_value */
if (isset($_GET["action"]) &&  $_GET["action"] == "status" ) {

	$query = "UPDATE nodes SET status=" . $val . " WHERE nodeName=". $name .";";
	
	mail ("");

}

/* TODO: Ip Change */


?>


