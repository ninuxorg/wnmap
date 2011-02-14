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

require ("../htdocs/config.php");

if ( isset ($_SERVER['REMOTE_ADDR'])) 
	die ("You can call this script ONLY from cli if.");

$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database.');

$query = "SELECT * FROM " . MYSQL_NODES_TABLE . ";";
$result = mysql_query ($query, $connection) or die (mysql_error());

$fd = fopen ("mail_text.txy", "r");
$mail_txt= fread ($fd, 8000);

while ($row = mysql_fetch_assoc($result)) {
	$name = htmlspecialchars($row['nodeName']);
	$owner = htmlspecialchars($row['userRealName']);
	$status = htmlspecialchars($row['status']);
	$email = htmlspecialchars($row['userEmail']);

	$mail_txt2 = str_replace ( "@user" , $owner , $mail_txt);
	$mail_txt2 = str_replace ( "@nodename" , $name , $mail_txt2);


	echo "Sending  $email....\n";
	mail ($email, "Messaggio dalla comunità ninux.org", $mail_txt2);
	echo "$mail_txt2";
}
