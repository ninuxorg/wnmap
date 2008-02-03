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
$lat = $_GET["lat"];
$lon = $_GET["lon"];
$ele = $_GET["ele"];
$y = $lat;
$x = $lon;
$name = $_GET["name"];
$ip = $_GET["ip"];
$addr = base64_decode($_GET["addr"]);
if ( tooFarFromCenter($y, $x) ) {
        printf(OUT_OF_RANGE, ACCEPTABLE_DISTANCE);
} else { ?>
		<h1><?=ADD_NODE?></h1>
		<p><?=THINKING_ABOUT_NODE?></p>
		<p><strong><?=THINKING_ABOUT_NODE_NOTE?></strong><p>
		<form action="AddPotentialNodeSubmit.php" method="POST">
			<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td colspan="2" style="border-bottom: 1px solid #eee">
						<h2><?=NODE_INFORMATION?></h2>
					</td>
				</tr>
				<tr>
					<td>
						<?=LATITUDE_?>
					</td>
					<td>
						<input type="text" readonly="true" value="<?=$lat?>" name="lat" id="y"/>
					</td>
				</tr>
				<tr class="alt">
					<td>
						<?=LONGITUDE_?>
					</td>
					<td>
						<input type="text" readonly="true" value="<?=$lon?>" name="lon" id="y"/>
					</td>
				</tr>
				<tr>
					<td>
						<?=ELEVATION_?>
					</td>
					<td>
						<input type="text" value="<?=$ele?>" name="ele" />
					</td>
				</tr>
				<tr class="alt">
					<td>
						<label for="nodename"><?=NODE_NAME_?></label>
						<br/>
						<span class="reallysmall"><?=PICK_A_NAME?></span>
					</td>
					<td>
						<input type="text" id="nodename" name="nodename" value="<?=$name?>"/>
					</td>
				</tr>
				<tr>
					<td>
						<label for="description"><?=DESCRIPTION_?></label>
						<br/>
						<span class="reallysmall"><?=DESCRIPTION_DESC?></span>
					</td>
					<td>
						<input type="text" name="description" id="description"/>
					</td>

				</tr>
				<tr class="alt">
					<td>
						<label for="nodeip"><?=NODE_IP_?></label>
						<br/>
						<span class="reallysmall"><?=ENTER_IP?></span>
					</td>
					<td>
						<input type="text" id="nodeip" name="nodeip" value="<?=$ip?>"/>
					</td>
				</tr>
				<tr>
					<td>
						<label for="nodeaddr"><?=NODE_STREET_ADDRESS_?></label>
						<br/>
						<span class="reallysmall"><?=NODE_STREET_ADDRESS_DESC?></span>
					</td>
					<td>
						<input type="text" id="nodeaddr" name="nodeaddr" value="<?=$addr?>"/>
					</td>
				</tr>
			</table>
			<br/>
			<table>
				<tr>
					<td colspan="2" style="border-bottom: 1px solid #eee;">
						<h2><?=YOUR_INFORMATION_?></h2>
						<span class="reallysmall"><?=SOMEBODY_NEARBY?></span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="yourname"><?=YOUR_FULL_NAME_?></label>
					</td>
					<td>
						<input type="text" id="yourname" name="yourname"/>
					</td>
				</tr>
				<tr class="alt">
					<td>
						<label for="email"><?=EMAIL_ADDRESS_?></label>
						<br/>
						<span class="reallysmall"><?=EMAIL_ADDRESS_DESC?></span>
					</td>
					<td>
						<input type="text" id="email" name="email" onChange="setJabberId();" />
						<br/>
						<input type="checkbox" id="publishEmail" name="publishEmail" checked="checked"/>
						<label for="publishEmail"><?=PUBLISH_EMAIL_?></label>
					</td>
				</tr>
				<tr>
					<td>
						<label for="jid"><?=JABBER_ID_?></label>
						<br/>
						<span class="reallysmall"><?=JABBER_ID_DESC?></span>
					</td>
					<td>
						<input type="text" id="jid" name="jid"/>
					</td>
				</tr>
				<tr class="alt">
					<td>
						<label for="website"><?=WEBSITE_URL?></label>
						<br/>
						<span class="reallysmall"><?=WEBSITE_URL_DESC?></span>
					</td>
					<td>
						<input type="text" id="website" name="website"/>
					</td>
				</tr>
			</table>
			<div class="buttonbox">
				<input type="submit" value="Submit" />
			</div>
		</form>
<? } ?>
	</body>
</html>
