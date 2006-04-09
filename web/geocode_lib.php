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

class LatLong {
  var $lat;
  var $long;
  var $latRad;
  var $longRad;

  function LatLong ($lat, $long) {
    $this->setLatLong($lat, $long);
  }

  function setLatLong ($lat, $long) {
    $this->lat = $lat;
    $this->long = $long;

    $this->latRad = $this->llToRad ($lat);
    $this->longRad = $this->llToRad ($long);
  }

  function getLatRad () {
    return $this->latRad;
  }

  function getLongRad () {
    return $this->longRad;
  }

  function llToRad ($brng) {
    return deg2rad($brng);
  }

  function distanceToCenter () {
    return $this->distanceInMiles( $this->getCenterLatLong() );
  }

  function distanceInMiles ($otherLatLong) {
    $distInKm = $this->distanceHaversine ($otherLatLong);
    $distInMiles = $distInKm / 1.609344;

    return $distInMiles;
  }

  function distanceHaversine ($otherLatLong) {
    $R = 6371; // earth's mean radius in km
    $dLat = $otherLatLong->getLatRad() - $this->getLatRad();
    $dLong = $otherLatLong->getLongRad() - $this->getLongRad();

    $a = sin($dLat/2) * sin($dLat/2) +
         cos($this->getLatRad()) * cos($otherLatLong->getLatRad()) * sin($dLong/2) * sin($dLong/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $d = $R * $c;

    return $d;
  }

  function getCenterLatLong() {
    return new LatLong(MAP_CENTER_LAT, MAP_CENTER_LONG);
  }
}

function distanceToCenter ($lat, $lon) {
  $myLatLong = new LatLong ($lat, $lon);
  return $myLatLong->distanceToCenter();
}

function tooFarFromCenter ($lat, $lon) {
  $distance = distanceToCenter($lat, $lon);

  return ( $distance > ACCEPTABLE_DISTANCE );
}

?>
