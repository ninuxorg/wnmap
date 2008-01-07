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
$y = $lat;
$x = $lon;
$name = $_GET["name"];
$addr = base64_decode($_GET["addr"]);
if ( tooFarFromCenter($y, $x) ) {
        printf("Point must not be more than %d miles from the center of the network.\n", ACCEPTABLE_DISTANCE);
} else { ?>
		<h1>Add Node</h1>
		<p>Thinking about putting up a node at this location? Add it to our database! This way, other people who think that they might be able to see you can let you know and discuss setting up a link.</p>
		<p><strong>NOTE: Only add yourself to the map if you are actually seriously interested in linking up with other people on the network. The goal of this site is *NOT* to map every random access point in the city.</strong><p>
		<form action="AddPotentialNodeSubmit.php" method="POST">
			<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td colspan="2" style="border-bottom: 1px solid #eee">
						<h2>Node Information</h2>
					</td>
				</tr>
				<tr>
					<td>
						Latitude:
					</td>
					<td>
						<input type="text" readonly="true" value="<?=$lat?>" name="lat" id="y"/>
					</td>
				</tr>
				<tr class="alt">
					<td>
						Longitude:
					</td>
					<td>
						<input type="text" readonly="true" value="<?=$lon?>" name="lon" id="y"/>
					</td>
				</tr>
				<tr>
					<td>
						<label for="nodename">Node Name:</label>
						<br/>
						<span class="reallysmall">Pick a name for this node, such as "Node45thAnd12th" or "NodeAwesomeCoffee"...something unique to your location.</span>
					</td>
					<td>
						<input type="text" id="nodename" name="nodename" value="<?=$name?>"/>
					</td>
				</tr>
				<tr class="alt">
					<td>
						<label for="description">Description:</label>
						<br/>
						<span class="reallysmall">Enter a brief description of the location (name of business, etc.).</span>
					</td>
					<td>
						<input type="text" name="description" id="description"/>
					</td>

				</tr>
				<tr>
					<td>
						<label for="nodeaddr">Node Street Address:</label>
						<br/>
						<span class="reallysmall">This is optional, and you can be vauge if you'd prefer (specify the building name but not the apartment number, for example).</span>
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
						<h2>Your Information</h2>
						<span class="reallysmall">If somebody nearby thinks they can see you, they need some way to reach you!</span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="yourname">Your Full Name:</label>
					</td>
					<td>
						<input type="text" id="yourname" name="yourname"/>
					</td>
				</tr>
				<tr class="alt">
					<td>
						<label for="email">E-mail Address:</label>
						<br/>
						<span class="reallysmall">Used to verify node - must be valid.</span>
					</td>
					<td>
						<input type="text" id="email" name="email" onChange="setJabberId();" />
						<br/>
						<input type="checkbox" id="publishEmail" name="publishEmail" checked="checked"/>
						<label for="publishEmail">Publish Email</label>
					</td>
				</tr>
				<tr>
					<td>
						<label for="jid">Jabber ID:</label>
						<br/>
						<span class="reallysmall">Learn more about <a href="http://seattlewireless.net/JabberServer" target="_blank">Jabber</a>.</span>
					</td>
					<td>
						<input type="text" id="jid" name="jid"/>
					</td>
				</tr>
				<tr class="alt class="alt"">
					<td>
						<label for="website">Website URL:</label>
						<br/>
						<span class="reallysmall">(If you have one)</span>
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
