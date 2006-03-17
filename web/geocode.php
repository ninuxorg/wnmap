<?php
/*
SeattleWireless Map
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

header('Content-type: text/xml');

$address = $_GET['address'];
//$result = file_get_contents ("http://rpc.geocoder.us/service/rest?address=".urlencode($address));
$result = file_get_contents ("http://api.local.yahoo.com/MapsService/V1/geocode?appid=".YAHOO_MAP_ID."&location=".urlencode($address));
echo $result;
?>
