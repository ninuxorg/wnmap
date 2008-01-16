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
require ("geocode_lib.php");

$email = trim ($_POST["email"]);
$jabber = trim ($_POST["jid"]);
$website = trim ($_POST["website"]);
$yourname = trim ($_POST["yourname"]);
$description = trim ($_POST["description"]);
$nodename = trim ($_POST["nodename"]);
$nodeaddr = trim ($_POST["nodeaddr"]);
$nodeip = trim ($_POST["nodeip"]);
$publish_email = trim($_POST["publishEmail"]);
$lng = $_POST["lon"];
$lat = $_POST["lat"];

if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email) == false) {
	
	echo "Invalid email address.";

} else if ($jabber != "" && eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $jabber) == false) {
	
	echo "Invalid jabber id.";

} else if ($nodeip != "" && eregi("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}(/[0-9]{1,2}){0,1}$", $nodeip) == false) {
	
	echo "Invalid IP.";

} else if ( tooFarFromCenter($lat, $lng) ) {

	printf("Point must not be more than %d miles from the center of the network.\n", ACCEPTABLE_DISTANCE);

} else if ($yourname == "") {

	echo "Invalid name.";

} else if ($description == "") {

	echo "Please specify a description of this location.";

} else {

$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database');

$email = mysql_real_escape_string ($email);
$website = mysql_real_escape_string ($website);
$yourname = mysql_real_escape_string ($yourname);
$jabber = mysql_real_escape_string($jabber);
$description = mysql_real_escape_string ($description);
$lng = mysql_real_escape_string ($lng);
$lat = mysql_real_escape_string ($lat);
$nodename = mysql_real_escape_string ($nodename);
$nodeaddr = mysql_real_escape_string ($nodeaddr);
$nodeip = mysql_real_escape_string ($nodeip);
$publish_email = mysql_real_escape_string ($publish_email);

if ($publish_email == "on") {
	$publish_email = 1;
} else {
	$publish_email = 0;
}

$hash = md5 (rand ());

$query = "SELECT status FROM " . MYSQL_NODES_TABLE . " WHERE lat='$lat' AND lng='$lng'";
$result = mysql_query ($query, $connection) or die (mysql_error());

if (mysql_num_rows($result) > 0) {
	echo "A node at this point already exists in our database.";
	return;
}

$query = "SELECT status FROM " . MYSQL_NODES_TABLE . " WHERE nodeName='$nodename'";
$result = mysql_query ($query, $connection) or die (mysql_error());

if (mysql_num_rows($result) > 0) {
	echo "A node with that name already exists in our database.";
	return;
}

$query = "SELECT status FROM " . MYSQL_NODES_TABLE . " WHERE nodeip='$nodeip'";
$result = mysql_query ($query, $connection) or die (mysql_error());

if (mysql_num_rows($result) > 0) {
	echo "A node with that IP already exists in our database.";
	return;
}

$query = "INSERT INTO " . MYSQL_NODES_TABLE . " (
			status,
			adminHash, 
			lat, 
			lng, 
			streetAddress,
			userRealName, 
			userEmail, 
			nodeName, 
			nodeDescription,
			nodeIP,
			userJabber,
			userWebsite,
			userEmailPublish
		)
		VALUES (
			0,
			'$hash',
			'$lat',
			'$lng',
			'$nodeaddr',
			'$yourname',
			'$email',
			'$nodename',
			'$description',
			'$nodeip',
			'$jabber',
			'$website',
			'$publish_email'
		)";

mysql_query ($query, $connection) or die (mysql_error());

mysql_close ($connection);

$subject = "Add Location - ".SITE_TITLE;

//$headers ="To: $yourname <$email>" . "\r\n" .
//	'From: '. SITE_TITLE .' <' . MAIL_FROM . '>';

$to = "$yourname <$email>";

$headers = 'From: '. SITE_TITLE .' <' . MAIL_FROM . '>';

$message = "Thank you for your interest in " . ORG_NAME . "!

To prevent abuse, we ask that you please visit the following URL to confirm 
your email address and have your node added to the network map.

" . MAP_URL . "/VerifyNode.php?hash=$hash

If you did NOT request that a node be added to the map, or for any reason you
would like to remove this location from the map at a later time, you can use
the following URL:

" . MAP_URL . "/DeleteNode.php?hash=$hash


" . MAIL_FOOTER;

mail ($to, $subject, $message, $headers) or die ("Failed to send mail!!");


?>

<h1>Thank You!</h1>
<p>Hello <b><? echo ($_POST["yourname"]); ?></b>, an email has been sent to <a href="<? echo ($_POST["email"]); ?>"><? echo ($_POST["email"]); ?></a> with instructions on what to do next.</p>

<? 
	return;
}
echo '<br/><a href="javascript:history.go(-1);">&laquo; Go back</a>';
 ?>
