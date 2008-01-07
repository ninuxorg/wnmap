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

// Configure all of this accordingly!

define ("MYSQL_HOST", "127.0.0.1");  
define ("MYSQL_USER", "");     
define ("MYSQL_PASS", "");
define ("MYSQL_DB", "");
define ("MYSQL_NODES_TABLE", "nodes");
define ("MYSQL_LINKS_TABLE", "links");
define ("SITE_TITLE", "Ninux Network Map");

define ("ORG_NAME", "Ninux.org");
define ("ORG_URL", "http://wiki.ninux.org/");
define ("ORG_DESC", "Rome Wireless Community");

//Choose "en" or "it"
define ("LANGUAGE", "it");


// No trailing slash
define ("MAP_URL", 'http://map.ninux.org');

define ("GETTING_STARTED_URL", "http://wiki.ninux.org/NuovoNodo");

define ("MAIL_FROM", "noreply@ninux.org");

// Get this from http://www.google.com/apis/maps/signup.html
define ("GOOGLE_MAP_KEY", "");

// Get this from http://api.search.yahoo.com/webservices/register_application
// (used for geocoding)
define ("YAHOO_MAP_ID", "");

define ("THEME_NAME", "rightsidebar-swnbluegray");

// %s = node name
define ("NODE_URL_FORMAT", "http://map.ninux.org/%s");

define ("MAP_CENTER_LAT", 41.89);
define ("MAP_CENTER_LONG", 12.48);
define ("ACCEPTABLE_DISTANCE", 70); // in miles

$JS_CONFIG = array(
	// WNMAP_GEOCODE_STATE_ABBR
	//   Abbreviation of the state name we wish to restrict geocoding to.
	
	//NOT USED
	//"WNMAP_GEOCODE_STATE_ABBR" => "IT",

	// WNMAP_GEOCODE_STATE_NAME
	//   Name of the state we wish to restrict geocoding to.
	
	//NOT USED
	//"WNMAP_GEOCODE_STATE_NAME" => "Italy",

	"WNMAP_MAP_START_LON" => MAP_CENTER_LONG,
	"WNMAP_MAP_START_LAT" => MAP_CENTER_LAT,
	"WNMAP_ACCEPTABLE_DISTANCE" => ACCEPTABLE_DISTANCE,

        // WNMAP_MAP_START_ZOOM
        //   Default zoom level
        // Possible values: <Int: 0-17>
        "WNMAP_MAP_START_ZOOM" => 11,

	// WNMAP_MAP_START_TYPE
	//   Default map type
	// Possible values: <Enum>
	//   Map => G_NORMAL_MAP
	//   Satellite => G_SATELLITE_MAP
	//   Hybrid => G_HYBRID_MAP
	"WNMAP_MAP_START_TYPE" => G_NORMAL_MAP,

        // WNMAP_MAP_URL
        //   Base URL of the map application
        // Possible values: <URL>
        "WNMAP_MAP_URL" => MAP_URL,

        // WNMAP_NODE_URL
        //   Base URL of the node database
        // Possible values: <URL>
        "WNMAP_NODE_URL" => "http://wiki.ninux.org/",
);

// remove this line
//die ("Please configure the map!");
?>
