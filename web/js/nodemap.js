// 
// NodeMap.js: Stuff.
// 
// Authors:
//   Eric Butler <eric@extremeboredom.net>
// 


var map;
var markers = [];
var tunnels = [];
var links = [];
var gotoAddressWindow;
var markerCount = 0;

var firstLoad = true;

function createMap ()
{
	map = new GMap(document.getElementById("mapColumn"));
	map.setMapType (G_SATELLITE_TYPE);
	map.addControl(new GLargeMapControl());
	map.addControl (new GMapTypeControl());
	map.centerAndZoom(new GPoint(SWNCFG_MAP_START_LON, SWNCFG_MAP_START_LAT), parseInt(SWNCFG_MAP_START_ZOOM));
	map.addControl(new GScaleControl());
	map.registerKeyHandlers (window);

	// Set default map type to SWNCFG_MAP_START_TYPE
	var mapTypes = map.getMapTypes();
	map.setMapType(mapTypes[SWNCFG_MAP_START_TYPE]);

	GEvent.addListener (map, 'click', function (overlay, point) {
		if (!overlay) {
			addMarker (point.y, point.x, '');
		}
	});


	var request = GXmlHttp.create ();
	request.open ('GET', 'data.php', true);
	request.onreadystatechange = function () {
		if (request.readyState == 4) {

			var pointTable = [];

			var xmlDoc = request.responseXML;

			// Add Nodes
			var markersFromXml = xmlDoc.documentElement.getElementsByTagName ("nodes")[0].getElementsByTagName ("node");
			for (var i = 0; i < markersFromXml.length; i++) {

				var name = markersFromXml[i].getAttribute("name");
				var desc = markersFromXml[i].getAttribute("description");
				var state = markersFromXml[i].getAttribute("state");
				var addr = markersFromXml[i].getAttribute("streetAddress");
				var lng = parseFloat(markersFromXml[i].getAttribute("lng"));
				var lat = parseFloat(markersFromXml[i].getAttribute("lat"));

				var node = new NodeMarker (name, desc, state, lng, lat);
				node.setStreetAddress (addr);

				markers[node.name] = node;
			}

			// Add Links
			var lnks = xmlDoc.documentElement.getElementsByTagName ("links")[0].getElementsByTagName ("link");
			for (var i = 0; i < lnks.length; i++) {
				try {
					var link = new Object ();
					link.type = lnks[i].getAttribute("type");
					link.node1 = markers [lnks[i].getAttribute("node1")];
					link.node2 = markers [lnks[i].getAttribute("node2")];
					link.point1 = markers [lnks[i].getAttribute("node1")].point;
					link.point2 = markers [lnks[i].getAttribute("node2")].point;
					links.push (link);
				} catch (e) {
					alert ("ERROR WITH LINK: " + lnks[i].getAttribute("node1")  + " <--> " + lnks[i].getAttribute("node2") + ":\n\n" + e);
				}
			}


			// Add local markers
			var markersText = readCookie ("markers");
			if (markersText != null) {
				var savedMarkers = markersText.split ('|');
				for (var i = 0; i < savedMarkers.length; i++) {
					if (savedMarkers[i] != '') {
						var markerParameters = savedMarkers[i].split (',');
						var name = decode64 (markerParameters [0]);
						if (markers[name] == null) {
							var y = markerParameters [2];
							var x = markerParameters [1];

							var bad = false;
							for (key in markers) {
								if (markers[key].point.x == x & markers[key].point.y == y) {
									bad = true;
									break;
								}
							}

							if (bad) { break; }

							var marker = new NodeMarker (name, '', 'marker', x, y);
							markers[marker.name] = marker;
							markerCount ++;

							// If a street address is bundled in the cookie data, add it to the marker object.
							if (markerParameters [3]) {
								marker.setStreetAddress (decode64 (markerParameters [3]));
							}
						}
					}
				}
			}

			populateMap ();
		}
	}
	request.send (null);
}

function populateMap ()
{
	map.clearOverlays ();

	var nodeList = document.getElementById ("nodeList");
	var markerList = document.getElementById ("markerList");
	nodeList.innerHTML = "";
	markerList.innerHTML = "";

	// Add Markers
	for (key in markers) {
		var node = markers[key];
	
		if (node.state == 'active' && document.getElementById ("showActive").checked == false) {
			node.visible = false;
			continue;
		} else if (node.state == 'potential' && document.getElementById ("showPotential").checked == false) {
			node.visible = false;
			continue;
		}

		node.visible = true;

		map.addOverlay (node);

		if (node.state == 'active' | node.state == 'potential') {
			nodeList.innerHTML += '<li onmouseover="getMarker(\'' + encode64(node.name) + '\').showTooltip();" onmouseout="getMarker(\'' + encode64(node.name) + '\').destroyTooltip();" class="nodeitem-' + node.state + '"><a href="javascript:getMarker(\'' + encode64(node.name) + '\').select();" style="font-weight: bold;">' + node.name + '</a>&nbsp;&nbsp;<a href="javascript:getMarker(\'' + encode64(node.name) + '\').zoomTo();" class="zoomLink">zoom</a></li>';
		} else {
			markerList.innerHTML += '<li onmouseover="getMarker(\'' + encode64(node.name) + '\').showTooltip();" onmouseout="getMarker(\'' + encode64(node.name) + '\').destroyTooltip();" class="nodeitem-' + node.state + '"><div style="float: right; padding-right:5px;">(<a href="javascript:getMarker(\'' + encode64(node.name) + '\').removeMarker();">x</a>)</div><a href="javascript:getMarker(\'' + encode64(node.name) + '\').select();" style="font-weight: bold;">' + node.name + '</a>&nbsp;&nbsp;<a href="javascript:getMarker(\'' + encode64(node.name) + '\').zoomTo();" class="zoomLink">zoom</a></li>';
		}

	}

	// Add Links
	for (var i = 0; i < links.length; i++) {
		if (links[i].node1.visible == true && links[i].node2.visible == true) {
			var points = []
			points.push (links[i].point1);
			points.push (links[i].point2);

			if (links[i].type == 'wifi') {
				if (document.getElementById ("showLinks").checked == true) {
					map.addOverlay (new GPolyline (points));
				}
			} else {
				if (document.getElementById ("showTun").checked == true) {
					map.addOverlay (new GPolyline (points,"#ff8080"));
				}
			}
		}
	}

	// Save Markers
	var value = '';
	var arr = new Array();
	for (key in markers) {
		var marker = markers[key];
		if (marker.state == 'marker') {
			arr.push(encode64 (marker.name) + ',' + marker.point.x + ',' + marker.point.y + ',' + encode64 (marker.streetAddress));
		}
	}
	value = arr.join("|");

	if (value != '') {
		createCookie ("markers", value, 300);
	} else {
		eraseCookie ("markers");
	}

	if (firstLoad == true) {
		if (getQueryVariable ("select") != null) {
			if (markers[getQueryVariable("select")] != null) {
				markers[getQueryVariable("select")].zoomTo ();
				markers[getQueryVariable("select")].select ();
			}
		}
	}

	firstLoad = false;
}

function getMarker (b64index) {
	var index = decode64 (b64index);

	return markers[index];
}

function addMarker (y, x, b64addr) 
{
	var streetAddress = decode64 (b64addr);

	showMarkers ();

	for (key in markers) {
                if (markers[key].point.x == x & markers[key].point.y == y) {
                        alert ("A marker at this point already exists.");
                        return;
                }
        }

	markerCount ++;
	var marker = new NodeMarker ("Untitled Marker " + markerCount, '', 'marker', x, y);

	// store the street address if it was passed in
	if ( streetAddress != '' ) {
		marker.setStreetAddress (streetAddress);
	}

	markers[marker.name] = marker;
	populateMap ();
	marker.openInfoWindowHtml (marker.getHtml());
	resizeMe ();
	scrollMarkersToBottom ();
}

function renamePrompt (b64name) {
	var name = decode64 (b64name);

	return prompt ('Enter a new name for this node:', name);
}

function renameMarker (oldB64Name, newName)
{
	var oldName = decode64 (oldB64Name);

	if ( markers[newName] != null ) {
		alert("A marker named '" + newName + "' already exists!");
		return 0;
	}

	var marker = markers [oldName];
	marker.name = newName;

	markers [newName] = marker;
	markers [oldName] = null;
	delete markers[oldName];

	populateMap ();
}

// some helper functions

// based on code from
// http://www.activsoftware.com/code_samples/code.cfm/CodeID/59/JavaScript/Get_Query_String_variables_in_JavaScript
function getQueryVariable (variable) {
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
    if (pair[0] == variable) {
      return pair[1];
    }
  } 
  return null;
}
