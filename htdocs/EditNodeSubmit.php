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

$hash = trim ($_POST["hash"]);
$email = trim ($_POST["email"]);
$jabber = trim ($_POST["jid"]);
$website = trim ($_POST["website"]);
$yourname = trim ($_POST["yourname"]);
$description = trim ($_POST["description"]);
$nodename = trim ($_POST["nodename"]);
$nodeaddr = trim ($_POST["nodeaddr"]);
$nodeip =trim ($_POST["nodeip"]);
$publish_email = trim($_POST["publishEmail"]);
$ele = $_POST["ele"];

if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email) == false) {
	
	echo INVALID_EMAIL;

} else if ($jabber != "" && eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $jabber) == false) {
	
	echo INVALID_JABBER;

} 

else if ($nodeip != "" && eregi("^(([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}(/[0-9]{1,2}){0,1})([[:space:]]|$))*$", $nodeip) == false) {
	
	echo INVALID_IP_;

} else if (!eregi("^([0-9]|\.)*$",$ele)) {
	
	echo INVALID_ELEVATION_;

} else if ($yourname == "") {

	echo INVALID_NAME_;

} else if ($description == "") {

	echo SPECIFY_DESCRIPTION; 

} else {

$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database');

$email = mysql_real_escape_string ($email);
$website = mysql_real_escape_string ($website);
$yourname = mysql_real_escape_string ($yourname);
$jabber = mysql_real_escape_string($jabber);
$description = mysql_real_escape_string ($description);
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


$query = "UPDATE " . MYSQL_NODES_TABLE . " SET 
			elevation = $ele, 
			streetAddress = '$nodeaddr',
			userRealName = '$yourname', 
			userEmail = '$email', 
			nodeName = '$nodename', 
			nodeDescription = '$description',
			nodeIP = '$nodeip',
			userJabber = '$jabber',
			userWebsite = '$website',
			userEmailPublish = '$publish_email'
		WHERE  adminHash = '$hash' and status > 0";

mysql_query ($query, $connection) or die (mysql_error());

mysql_close ($connection);

?>

<h1><?php echo THANK_YOU_;?></h1>
<?php echo UPDATE_SUCCESSFUL;?>
<?php 
	return;
}
echo '<br/><a href="javascript:history.go(-1);">&laquo; '. GO_BACK_ .'</a>';
 ?>
