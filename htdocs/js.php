<?php
/*
WNMap
Copyright (C) 2006 Chase Phillips <shepard@ameth.org>

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

// we're sending back either an empty text doc or JavaScript
header('Content-type: text/plain');

// capture the contents of the file argument
$file = $_GET['file'];

// strip non-alphanumeric characters from the filename
$file = eregi_replace("[^[:alnum:]]", "", $file);

// if the specified file doesn't match what we expect, set $file to an empty string
if ( !ereg("^(NodeMarker|UrlEncode|DistanceCalculator|cookies|geocode|gui|nodemap|base64|textcontrol)$", $file) ) {
    $file = "";
}

// if $file contains characters, ...
if (strlen($file) > 0) {
	// grab the JavaScript file's contents
	$result = file_get_contents ("js/".$file.".js");

	// for sanity checking, echo the filename into the output
	echo "// file = $file\n";

	// define each key => value pair in the JS context
	foreach ($JS_CONFIG as $key => $value) {
		echo "var $key = \"$value\";\n";
	}
	echo "\n";

	// print the file contents to the receiver
	echo $result;
}
?>
