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
			<li><?=THINK_ABOUT_DESC_1?><br/><br/></li>
			<li><?=THINK_ABOUT_DESC_2?><br/><br/></li>
			<li><?=THINK_ABOUT_DESC_3?><br/><br/></li>
			<li><?=THINK_ABOUT_DESC_4?></li>
		</ol>
		<p><a href="<?=GETTING_STARTED_URL?>" target="_blank"><?=GETTING_STARTED_TEXT?></a><p>

		<p><b><?=MAP_LEGEND_TITLE?></b></p>
		<p><img src="<?=MAP_URL?>/images/marker_potential_small.png"/><?=MAP_LEGEND_POTENTIAL?></p>
		<p><img src="<?=MAP_URL?>/images/marker_active_small.png"/><?=MAP_LEGEND_ACTIVE?></p>
	</body>
</html>
