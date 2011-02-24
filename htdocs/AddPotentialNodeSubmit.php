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
$nodeip =trim ($_POST["nodeip"]);
$publish_email = trim($_POST["publishEmail"]);
$lng = $_POST["lon"];
$lat = $_POST["lat"];
$ele = $_POST["ele"];

if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email) == false) {
	
	echo INVALID_EMAIL;

} else if ( tooFarFromCenter($lat, $lng) ) {

	printf(OUT_OF_RANGE, ACCEPTABLE_DISTANCE);

} else if (!eregi("^([0-9]|\.)*$",$ele)) {
	
	echo INVALID_ELEVATION_;

} else if ($yourname == "") {

	echo INVALID_NAME_;

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
$ele = mysql_real_escape_string ($ele);
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
	echo NODE_ALREADY_EXISTS;
	return;
}

$query = "SELECT status FROM " . MYSQL_NODES_TABLE . " WHERE nodeName='$nodename'";
$result = mysql_query ($query, $connection) or die (mysql_error());

if (mysql_num_rows($result) > 0) {
	echo NODE_NAME_ALREADY_EXISTS;
	return;
}


$query = "INSERT INTO " . MYSQL_NODES_TABLE . " (
			status,
			adminHash, 
			lat, 
			lng,
			elevation, 
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
			'$ele',
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

$subject = ADD_LOCATION_." - ".SITE_TITLE;

//$headers ="To: $yourname <$email>" . "\r\n" .
//	'From: '. SITE_TITLE .' <' . MAIL_FROM . '>';

$to = "$yourname <$email>";

$headers = 'From: '. SITE_TITLE .' <' . MAIL_FROM . '>';

$message = sprintf(ADDNODE_EMAIL_BODY, ORG_NAME,MAP_URL,$hash,MAP_URL,$hash,MAP_URL,$hash,MAIL_FOOTER);

mail ($to, $subject, $message, $headers) or die ("Failed to send mail!!");


?>

<h1><?php echo THANK_YOU_;?></h1>
<p><?php echo HELLO_;?> <b><? echo ($_POST["yourname"]); ?></b>, <?php echo AN_EMAIL_WAS_SENT;?> <a href="<?php echo ($_POST["email"]); ?>"><?php echo ($_POST["email"]); ?></a> <?php echo WITH_INSTRUCTIONS;?> </p>

<?php 
	return;
}
echo '<br/><a href="javascript:history.go(-1);">&laquo; '. GO_BACK_ .'</a>';
 ?>
