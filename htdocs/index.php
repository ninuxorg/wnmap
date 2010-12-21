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

if (file_exists ("config.php")) {
	require ("config.php");
} else {
	echo "Whoop! It seems that you haven't configured WNMap yet! Edit <code>config.php.dist</code>, rename it to <code>config.php</code>, and then reload this page.";
	return;
}
//Apply selected language
if (file_exists ("languages/".LANGUAGE.".php")) {
        require ("languages/".LANGUAGE.".php");
	} else {
	        echo "Whoop! It seems that the configured language is not supported in this version of WNMAP Edit <code>config.php</code>, try with another language, and then reload this page.";
		        return;
			}
			echo '<?xml version="1.0" encoding="UTF-8"?>';




echo '<?xml version="1.0" encoding="UTF-8"?>';

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

		<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?php echo GOOGLE_MAP_KEY;?>" type="text/javascript"></script>
		<script src="js.php?file=currentLanguage" type="text/javascript"></script>
		<script src="js.php?file=nodemap" type="text/javascript"></script>
		<script src="js.php?file=gui" type="text/javascript"></script>
		<script src="js.php?file=geocode" type="text/javascript"></script>
		<script src="js.php?file=cookies" type="text/javascript"></script>

		<title><?php echo SITE_TITLE; ?></title>

		<style type="text/css">v\:* {behavior:url(#default#VML);}</style>
		<link rel="stylesheet" href="themes/<?php echo THEME_NAME;?>/theme.css" type="text/css" media="screen" title="Right sidebar - Blue/Gray" />
		<link rel="alternate stylesheet" href="themes/rightsidebar.css" type="text/css" media="screen" title="Right sidebar - No Theme"/>
    		<link rel="alternate" title="ninux.org map RSS 2.0 feed" type="application/rss+xml" href="rss.php" />

		<script type="text/javascript">
			function load() {
				if (GBrowserIsCompatible()) {
					var map = createMap();
					initGui();
					updatePageLink();
					resizeMe();
					window.onresize = function () {
						resize();
					}
				}
			}

			function resize() {
				if (GBrowserIsCompatible()) {
					resizeMe();
				}
			}

			function unload() {
				if (GBrowserIsCompatible()) {
					GUnload();
				}
			}
		</script>
	</head>

	<body onload="load();" onunload="unload()">
		<div id="main">
			<div id="header">
				<h1><span><?php echo ORG_NAME;?></span></h1>
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
							<input type="button" value="Cancel" style="font-size: x-small; padding: 1px;" onclick="cancelLogin();" />
						</form>
					</div>
				</div>
				-->
				<div style="float: right; padding: 11px;"><a href="#" id="pageLink"><?php echo LINK_TO_THIS_PAGE;?></a></div>
				<h2><span><?php echo NETWORK_MAP;?></span></h2>
			</div>
			<div id="columns">
				<div id="mapColumn">
					<!-- Map is inserted here -->
				</div>
				<div id="sideColumn">
					<div class="sideItem" id="welcomeSideItem">
						<div class="sideItemTitle">
						<h3 style="float: left;"><?php echo WELCOME_TITLE;?></h3>
							<a href="javascript:toggleVisible ('welcomeContent'); swapImage ('welcomeCollapseImage', '<?php echo MAP_URL;?>images/collapse.png', '<?php echo MAP_URL;?>images/expand.png');"><img src="images/collapse.png" alt="Toggle" id="welcomeCollapseImage" /></a>
						</div>
						<div class="sideItemContent" id="welcomeContent">
						<p style="margin-top: 0px;"><?php echo WELCOME_TO_THE;?></p>
							<ul style="padding-left: 2em; list-style: square;">
								<li style="padding-bottom: 0.5em;"><a href="<?php echo ORG_URL;?>"><?php echo WHAT_IS;?></a></li>
								<li><a href="javascript:void(0);" onclick="window.open ('help.php', 'help', 'scrollbars=yes,menubar=no,toolbar=no,status=no,personalbar=no,width=600,height=400');"><?php echo HOW_TO_USE_MAP;?></a></li>
							</ul>
						</div>
					</div>	
					<div id="findLocation" class="sideItem">
						<div class="sideItemTitle">
							<h3><?php echo FIND_LOCATION;?></h3>
							<a href="javascript:toggleVisible ('findLocationContent'); swapImage ('findLocationCollapseImage', '<?php echo MAP_URL;?>images/collapse.png', '<?php echo MAP_URL;?>images/expand.png');" ><img id="findLocationCollapseImage" src="images/collapse.png" alt="Toggle" /></a>
						</div>
						<div class="sideItemContent" id="findLocationContent">
							<form onsubmit="geocode(document.getElementById('address').value); return false;" action="">
								<p style="margin: 0px;"><label for="address"><?php echo ADDRESS_LABEL;?></label>
								<br/>
								<input type="text" id="address" class="text" />
								</p>
								<p class="buttonBox">
									<input type="submit" value="<?php echo ADDRESS_SUBMIT_LABEL;?>" class="button" id="submitLocationSearchButton"/>
								</p>
							</form>
							<div id="findLocationResponse"></div>
						</div>
					</div>
					<div id="mapSettings" class="sideItem">
						<div class="sideItemTitle">
							<h3><?php echo MAP_SETTINGS_TITLE;?></h3>
							<a href="javascript:toggleVisible ('mapSettingsContent'); swapImage ('mapSettingsCollapseImage', '<?php echo MAP_URL;?>images/collapse.png', '<?php echo MAP_URL;?>images/expand.png');" ><img id="mapSettingsCollapseImage" src="images/collapse.png" alt="Toggle" /></a>
						</div>
						<div class="sideItemContent" id="mapSettingsContent">
							<ul class="nobullets">
								<li>
									<input class="checkbox" type="checkbox" id="showActive" checked="checked" onclick="settingChanged();"/>
									<label for="showActive"><?php echo SHOW_ACTIVE_NODES;?></label>
								</li>
								<li>
									<input class="checkbox" type="checkbox" id="showPotential" checked="checked" onclick="settingChanged();"/>
									<label for="showPotential"><?php echo SHOW_POTENTIAL_NODES;?></label>
								</li>
								<li>
									<input class="checkbox" type="checkbox" id="showLinks" checked="checked" onclick="settingChanged();"/>
									<label for="showLinks"><?php echo SHOW_WIRELESS_LINKS;?></label>
								</li>
								<li>
									<input class="checkbox" type="checkbox" id="showTun" onclick="settingChanged();"/>
									<label for="showTun"><?php echo SHOW_INTERNET_TUNNELS;?></label>
								</li>
							</ul>
						</div>
					</div>
					<div id="tabs" class="sideItem">
						<ul class="tabSwitcher">
							<li id="nodesTab" class="selected"><a href="javascript:showNodes();"><?php echo NODES_;?></a></li>
							<li id="myMarkersTab"><a href="javascript:showMarkers();"><?php echo MY_MARKERS;?></a></li>
						</ul>
						<div id="nodesTabContent" class="tabContent">
							<ul id="nodeList">
								<li><?php echo LOADING_;?></li>
							</ul>
						</div>
						<div id="myMarkersTabContent" class="tabContent" style="display: none;">
							<ul id="markerList">
								<li><?php echo LOADING_;?></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div id="footer">
				<div style="float: right; margin-top: -2px;">
				    <a href="<?php echo MOBILE_MAP_URL;?>"><img src="images/addandroid.png" alt="Mobile Map for Android" style="border: 0px;" /></a>
					<a href="<?php echo MAP_URL;?>/kml-feed.php"><img src="images/google_earth_feed.png" alt="Google Earth Feed" style="border: 0px;" /></a>
				</div>
				The <a href="<?php echo ORG_URL;?>"><?php echo ORG_NAME;?></a> Network Map is powered by <a href="http://hg.ninux.org/wnmap">WNMap</a>.
			</div>
		</div>
	</body>
</html>
