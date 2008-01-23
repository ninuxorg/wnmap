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
<html>
	<head>
		<title><?=SITE_TITLE?> - Help</title>
	</head>
	<body>
		<p><b><?=THINK_ABOUT?></b></p>
		<ol style="padding-left: 2em;">
			<li>Use the "Find Location" search below to add a marker at your desired location. You can also click anywhere on the map if you do not know the address.<br/><br/></li>
			<li>Rename the marker something meaningful such as "Eric's House".<br/><br/></li>
			<li>Select the option to add the marker to the database and follow the directions.<br/><br/></li>
			<li>You can click on other nodes to view photos and other information. If you think you find a node that you have line-of-sight to, get in touch with whoever owns it and set up a link!</li>
		</ol>
		<p><a href="<?=GETTING_STARTED_URL?>" target="_blank">More information about putting up a node &raquo;</a><p>

		<p><b>Map Legend</b></p>
		<p><img src="<?=MAP_URL?>/images/marker_potential_small.png"/> Potential location for a node</p>
		<p><img src="<?=MAP_URL?>/images/marker_active_small.png"/> Active node</p>
	</body>
</html>
