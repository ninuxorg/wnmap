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
?>

<html>
	<head>
		<style type="text/css">
			body {
				margin-left: auto;
				margin-right: auto;
				width: 400px;
			}
			body, body * {
				font-family: Bitstream Vera Sans, Verdana;
			}
			h1 {
				font-size: x-large;
			}
			p {
				font-size: small;	
			}
			label,td, input,textarea {
				font-size: small;
			}
			.reallysmall {
				font-size: x-small;
			}
			hr {
				height: 1px !important;
				border: 0px !important;
				background-color: #666;
			}
			.buttonbox {
				text-align: right;
				padding: 0px;
				margin-top: 10px;
			}
			.alt {
				background-color: #eee;
			}
			h2 {
				margin: 0px;
				font-size: large;
				width: 100%;
				display: block;
				padding: 0px;
			}
			table {
				border: 1px solid #666;
				padding: 0px;
				width: 100%;
			}
		</style>
		<script type="text/javascript">
			function setJabberId ()
			{
				if (document.getElementById ('jid').value == '') {
					document.getElementById ('jid').value = document.getElementById('email').value;
				}
			}
		</script>
	</head>
	<body>
<?php

$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database.');

$hash = mysql_real_escape_string ($_GET["hash"]);
if ($hash == '')
	die ("Hash must be specified!");

$query = "SELECT * FROM " . MYSQL_NODES_TABLE . " WHERE adminHash='$hash' AND status <> '0'";
$result = mysql_query ($query, $connection) or die (mysql_error());

while ($row = mysql_fetch_assoc($result)) {
	$name = htmlspecialchars($row['nodeName']);
	$owner = htmlspecialchars($row['userRealName']);
	$website = htmlspecialchars($row['userWebsite']);
	$jabber = htmlspecialchars($row['userJabber']);
	$email = htmlspecialchars($row['userEmail']);
	$publish_email = htmlspecialchars($row['userEmailPublish']);
	$lat = htmlspecialchars($row['lat']);
	$lon = htmlspecialchars($row['lng']);
	$ele = htmlspecialchars($row['elevation']);
	$status = htmlspecialchars($row['status']);
	$addr = htmlspecialchars($row['streetAddress']);
	$ip = htmlspecialchars($row['nodeIP']);
	$desc = htmlspecialchars ($row['nodeDescription']);
}

$y = $lat;
$x = $lon;
?>
		<h1><?php echo EDIT_NODE;?></h1>
		<form action="EditNodeSubmit.php" method="POST">
			<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td colspan="2" style="border-bottom: 1px solid #eee">
						<h2><?php echo NODE_INFORMATION;?></h2>
						<input type="hidden" value="<?php echo $hash;?>" name="hash" />
					</td>
				</tr>
				<tr>
					<td>
						<?php echo LATITUDE_;?>
					</td>
					<td>
						<input type="text" readonly="true" value="<?php echo $lat;?>" name="lat" id="y"/>
					</td>
				</tr>
				<tr class="alt">
					<td>
						<?php echo LONGITUDE_;?>
					</td>
					<td>
						<input type="text" readonly="true" value="<?php echo $lon;?>" name="lon" id="y"/>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo ELEVATION_;?>
					</td>
					<td>
						<input type="text" value="<?php echo $ele;?>" name="ele" />
					</td>
				</tr>
				<tr class="alt">
					<td>
						<label for="nodename"><?php echo NODE_NAME_;?></label>
						<br/>
						<span class="reallysmall"><?php echo PICK_A_NAME;?></span>
					</td>
					<td>
						<input type="text" id="nodename" name="nodename" value="<?php echo $name;?>"/>
					</td>
				</tr>
				<tr>
					<td>
						<label for="description"><?php echo DESCRIPTION_;?></label>
						<br/>
						<span class="reallysmall"><?php echo DESCRIPTION_DESC;?></span>
					</td>
					<td>
						<input type="text" name="description" id="description" value="<?php echo $desc;?>"/>
					</td>

				</tr>
				<tr class="alt">
					<td>
						<label for="nodeip"><?php echo NODE_IP_;?></label>
						<br/>
						<span class="reallysmall"><?php echo ENTER_IP;?></span>
					</td>
					<td>
						<input type="text" id="nodeip" name="nodeip" value="<?php echo $ip;?>"/>
					</td>
				</tr>
				<tr>
					<td>
						<label for="nodeaddr"><?php echo NODE_STREET_ADDRESS_;?></label>
						<br/>
						<span class="reallysmall"><?php echo NODE_STREET_ADDRESS_DESC;?></span>
					</td>
					<td>
						<input type="text" id="nodeaddr" name="nodeaddr" value="<?php echo $addr;?>"/>
					</td>
				</tr>
			</table>
			<br/>
			<table>
				<tr>
					<td colspan="2" style="border-bottom: 1px solid #eee;">
						<h2><?php echo YOUR_INFORMATION_;?></h2>
						<span class="reallysmall"><?php echo SOMEBODY_NEARBY;?></span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="yourname"><?php echo YOUR_FULL_NAME_;?></label>
					</td>
					<td>
						<input type="text" id="yourname" name="yourname" value="<?php echo $owner;?>"/>
					</td>
				</tr>
				<tr class="alt">
					<td>
						<label for="email"><?php echo EMAIL_ADDRESS_;?></label>
						<br/>
						<span class="reallysmall"><?php echo EMAIL_ADDRESS_DESC;?></span>
					</td>
					<td>
						<input type="text" id="email" name="email" onChange="setJabberId();" value="<?php echo $email;?>"/>
						<br/>
					<?php
					if ($publish_email == 1) {
					?>
						<input type="checkbox" id="publishEmail" name="publishEmail" checked="checked" />
					<?php  } else { ?>
						<input type="checkbox" id="publishEmail" name="publishEmail" checked="unchecked" />

					<?php
					}
					?>
						<label for="publishEmail"><?php echo PUBLISH_EMAIL_;?></label>
					</td>
				</tr>
				<tr>
					<td>
						<label for="jid"><?php echo JABBER_ID_;?></label>
						<br/>
						<span class="reallysmall"><?php echo JABBER_ID_DESC;?></span>
					</td>
					<td>
						<input type="text" id="jid" name="jid" value="<?php echo $jabber;?>"/>
					</td>
				</tr>
				<tr class="alt">
					<td>
						<label for="website"><?php echo WEBSITE_URL;?></label>
						<br/>
						<span class="reallysmall"><?php echo WEBSITE_URL_DESC;?></span>
					</td>
					<td>
						<input type="text" id="website" name="website" value="<?php echo $website;?>"/>
					</td>
				</tr>
			</table>
			<div class="buttonbox">
				<input type="submit" value="Submit" />
			</div>
		</form>
	</body>
</html>
