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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

		<script src="http://maps.google.com/maps?file=api&v=1&key=<?=GOOGLE_MAP_KEY?>" type="text/javascript"></script>
		<script src="js.php?file=nodemap" type="text/javascript"></script>
		<script src="js.php?file=gui" type="text/javascript"></script>
		<script src="js.php?file=geocode" type="text/javascript"></script>
		<script src="js.php?file=cookies" type="text/javascript"></script>
		<script src="js.php?file=NodeMarker" type="text/javascript"></script>
		<script src="js.php?file=UrlEncode" type="text/javascript"></script>
		<script src="js.php?file=base64" type="text/javascript"></script>

		<title><? echo SITE_TITLE; ?></title>

		<style type="text/css">v\:* {behavior:url(#default#VML);}</style>
		<link rel="stylesheet" href="themes/<?=THEME_NAME?>/theme.css" type="text/css" media="screen" title="Right sidebar - Blue/Gray" />
		<link rel="alternate stylesheet" href="themes/rightsidebar.css" type="text/css" media="screen" title="Right sidebar - No Theme"/>
	</head>

	<body onLoad="createMap(); resizeMe();" onResize="resizeMe();">
		<div id="main">
			<div id="header">
				<h1><span><?=ORG_NAME?></span></h1>
			</div>
			<div id="pageTitle">
				<!--
				<div id="accountInfo">
					<div style="padding-top: 10px; padding-right: 10px;">
						<div id="accountOptions">
							<a href="javascript:showLogin();">Log In</a> | Create Account
						</div>
						<form id="login">
							<label for="username">Username:</label> <input type="text" id="username" class="text" style="width: 100px; font-size: x-small; padding: 1px;" />&nbsp;
							<label for="pasword">Password:</label> <input type="password" id="password" class="text" style="width: 100px; font-size: x-small; padding: 1px;" />
							<input type="submit" value="Log In" style="font-size: x-small; padding: 1px;" />
							<input type="button" value="Cancel" style="font-size: x-small; padding: 1px;" onClick="cancelLogin();" />
						</form>
					</div>
				</div>
				-->
				<h2><span>Network Map</span></h2>
			</div>
			<div id="columns">
				<div id="mapColumn">
					<!-- Map is inserted here -->
				</div>
				<div id="sideColumn">
					<div class="sideItem">
						<div class="sideItemTitle">
							<h3>Welcome!</h3>
						</div>
						<div class="sideItemContent">
							<p style="margin-top: 0px;">Welcome to the <?=ORG_NAME?> Network Map!</p>
							<ul style="padding-left: 2em; list-style: square;">
								<li style="padding-bottom: 0.5em;"><a href="<?=ORG_URL?>">What is <?=ORG_NAME?>?</a></li>
								<li><a href="javascript:void(0);" onClick="window.open ('help.php', 'help', 'scrollbars=yes,menubar=no,toolbar=no,status=no,personalbar=no,width=600,height=400');">How do I use this map?</a></li>
							</ul>
						</div>
					</div>	
					<div id="findLocation" class="sideItem">
						<div class="sideItemTitle">
							<h3>Find Location</h3>
						</div>
						<div class="sideItemContent" id="findLocationContent">
							<form onSubmit="geocode(document.getElementById('address').value); return false;">
								<label for="address">Address, Street, and City, State or Zip:</label>
								<br/>
								<input type="text" id="address" class="text" />
								<p class="buttonBox">
									<input type="submit" value="Search" class="button" id="submitLocationSearchButton"/>
								</p>
							</form>
							<div id="findLocationResponse"></div>
						</div>
					</div>
					<div id="mapSettings" class="sideItem">
						<div class="sideItemTitle">
							<h3>Map Settings</h3>
						</div>
						<div class="sideItemContent" id="mapSettingsContent">
							<ul class="nobullets">
								<li>
									<input class="checkbox" type="checkbox" id="showActive" checked="checked" onClick="populateMap();"/>
									<label for="showActive">Show Active Nodes</label>
								</li>
								<li>
									<input class="checkbox" type="checkbox" id="showPotential" checked="checked" onClick="populateMap();"/>
									<label for="showPotential">Show Potential Node Locations</label>
								</li>
								<li>
									<input class="checkbox" type="checkbox" id="showLinks" checked="checked" onClick="populateMap();"/>
									<label for="showLinks">Show Wireless Links</label>
								</li>
								<li>
									<input class="checkbox" type="checkbox" id="showTun" onClick="populateMap();"/>
									<label for="showTun">Show Internet Tunnels</label>
								</li>
							</ul>
						</div>
					</div>
					<div id="tabs" class="sideItem">
						<ul class="tabSwitcher">
							<li id="nodesTab" class="selected"><a href="javascript:showNodes();">Nodes</a></li>
							<li id="myMarkersTab"><a href="javascript:showMarkers();">My Markers</a></li>
						</ul>
						<div id="nodesTabContent" class="tabContent">
							<ul id="nodeList">
							</ul>
						</div>
						<div id="myMarkersTabContent" class="tabContent" style="display: none;">
							<ul id="markerList">
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div id="footer">
				<div style="float: right;"><a href="<?=MAP_URL?>/kml-feed.php"><img src="images/google_earth_feed.gif" alt="Google Earth Feed" style="border: 0px;" /></a></div>
				<b><a href="<?=ORG_URL?>"><?=ORG_NAME?></a>'s Network Map is powered by <a href="http://wnmap.sourceforge.net/">WNMap</a></b>
			</div>
		</div>
<!--[if IE]>
		<div style="display: none;">
		<![endif]-->
	</body>
</html>
