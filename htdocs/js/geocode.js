function geocode (address) {
	document.getElementById ("findLocationResponse").innerHTML = "Searching...";
	document.getElementById ("findLocationResponse").className = "";
	document.getElementById ("submitLocationSearchButton").disabled = true;

	resizeMe ();
	var geocoder = null;
	geocoder = new GClientGeocoder();
	geocoder.getLatLng(address,function(point) {


	if(!point) { document.getElementById ("findLocationResponse").innerHTML += '<p><a href="javascript:resetSearch();"> Indirizzo non trovato. Click to reset. </a></p>' 
	}
	else {
	document.getElementById ("findLocationResponse").innerHTML += '<p><a class="addressLink" href="javascript:addMarker(' + point.y + ',' + point.x + ',\'' + encode64 (address) + '\').zoomTo(); resetSearch();">'+address +"</a></p>";
	resizeMe ();
	}

	}

	);
}	

function tooFarFromCenter (lat, lon) {
	var distance = distanceToCenterInMiles (lat, lon);

	return ( distance > WNMAP_ACCEPTABLE_DISTANCE );
}

function distanceToCenterInMiles (lat, lon) {
	var p1 = new LatLong(WNMAP_MAP_START_LAT, WNMAP_MAP_START_LON);
	var p2 = new LatLong(lat, lon);
	var distInKm = LatLong.distHaversine (p1, p2);
	var distInMiles = distInKm / 1.609344;

	return distInMiles;
}

function distanceToCenterPretty (lat, lon) {
	var distInMiles = distanceToCenterInMiles(lat, lon);
	var distInFeet = distInMiles * 5280;

	var distance;

	if ( distInFeet <= 1000 ) {
		distance = Math.round(distInFeet * 100) / 100; // round to two decimal places
		distance += ' ft';
	} else {
		distance = Math.round(distInMiles * 100) / 100; // round to two decimal places
		distance += ' mi';
	}

	return distance;
}

// http://www.movable-type.co.uk/scripts/LatLong.html
// http://en.wikipedia.org/wiki/Geographic_coordinate_conversion
// http://en.wikipedia.org/wiki/Haversine_formula

/*
 * LatLong constructor:
 *
 *   arguments are in degrees: signed decimal or d-m-s + NSEW as per LatLong.llToRad()
 */
function LatLong(degLat, degLong) {
  this.lat = LatLong.llToRad(degLat);
  this.lon = LatLong.llToRad(degLong);
}

/*
 * Calculate distance (in km) between two points specified by latitude/longitude with Haversine formula
 *
 * from: Haversine formula - R. W. Sinnott, "Virtues of the Haversine",
 *       Sky and Telescope, vol 68, no 2, 1984
 *       http://www.census.gov/cgi-bin/geo/gisfaq?Q5.1
 */
LatLong.distHaversine = function (p1, p2) {
  var R = 6371; // earth's mean radius in km
  var dLat  = p2.lat - p1.lat;
  var dLong = p2.lon - p1.lon;

  var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
          Math.cos(p1.lat) * Math.cos(p2.lat) * Math.sin(dLong/2) * Math.sin(dLong/2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  var d = R * c;

  return d;
}

/*
 * convert lat/long in degrees to radians, for handling input values
 *
 *   this is very flexible on formats, allowing signed decimal degrees (numeric or text), or
 *   deg-min-sec suffixed by compass direction (NSEW). A variety of separators are accepted 
 *   (eg 3º 37' 09"W) or fixed-width format without separators (eg 0033709W). Seconds and minutes
 *   may be omitted. Minimal validation is done.
 */
LatLong.llToRad = function (brng) {
  if (!isNaN(brng)) return brng * Math.PI / 180;  // signed decimal degrees without NSEW

  var dir = brng.replace(/[\s]/g,'').slice(-1).toUpperCase(); // compass dir'n (case-insensitive)
  if (!/[NSEW]/.test(dir)) return NaN;            // check for correct compass direction
  brng = brng.slice(0,-1);                        // and lose it off the end
  var dms = brng.split(/[\s:,°º.\'.\"]/)          // check for separators indicating d/m/s
  switch (dms.length) {                           // convert to decimal degrees...
    case 3:                                       // interpret 3-part result as d/m/s
      var deg = dms[0]/1 + dms[1]/60 + dms[2]/3600; break;
    case 2:                                       // interpret 2-part result as d/m
      var deg = dms[0]/1 + dms[1]/60; break;
    case 1:                                       // non-separated format dddmmss
      if (/[NS]/.test(dir)) brng = '0' + brng;    // - normalise N/S to 3-digit degrees
      var deg = brng.slice(0,3)/1 + brng.slice(3,5)/60 + brng.slice(5)/3600; break;
    default: return NaN;
  }
  if (/[WS]/.test(dir)) deg = -deg;               // take west and south as -ve
  return deg * Math.PI / 180;                     // then convert to radians
}
