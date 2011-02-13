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

var showActiveIPv4 = true;
var showActiveIPv6 = true;
var showHotSpot = true;
var showPotential = true;
var showLinks = true;
var showTunnels = true;

var goodLinkColor = "#00ff00";
var medLinkColor = "#ffff00";
var badLinkColor = "#ee0000";


function createMap (containerId)
{
	if (containerId == null) {
		containerId = "mapColumn";
	}

	embedScript ("js.php?file=NodeMarker");
	embedScript ("js.php?file=UrlEncode");
	embedScript ("js.php?file=base64");
	embedScript ("js.php?file=cookies");
	embedScript ("js.php?file=DistanceCalculator");

	embedStyle ("themes/map.css");

	map = new GMap2(document.getElementById(containerId));

	if (getQueryVariable ('controls') != "off") {
		map.addControl(new GLargeMapControl());
		map.addControl (new GMapTypeControl());
		map.addControl(new GScaleControl());
	} else {
		map.addControl (new TextControl ("Show&nbsp;Ful&nbsp;Map", WNMAP_MAP_URL));
	}

	map.setCenter(new GLatLng(WNMAP_MAP_START_LAT, WNMAP_MAP_START_LON), parseInt(WNMAP_MAP_START_ZOOM));
	new GKeyboardHandler(map, window);

	// Set default map type to WNMAP_MAP_START_TYPE
	eval("var mapType = " + WNMAP_MAP_START_TYPE);
	map.setMapType (mapType);

	GEvent.addListener (map, 'click', function (marker, point) {
		if (!marker) {
			addMarker (point.lat(), point.lng(), '');
		}
	});

	window.addEventListener('DOMMouseScroll', wheelZoom, false);
	
	map.enableContinuousZoom();
	
	//Double Click is used to add a marker!!!
	//map.enableDoubleClickZoom(); 

	var request = GXmlHttp.create ();
	request.open ('GET', 'data.php', true);
	request.onreadystatechange = function () {
		if (request.readyState == 4) {

			var xmlDoc = request.responseXML;

			// Add Nodes
			var markersFromXml = xmlDoc.documentElement.getElementsByTagName ("nodes")[0].getElementsByTagName ("node");
			for (var i = 0; i < markersFromXml.length; i++) {

				var name = markersFromXml[i].getAttribute("name");
				var base64Name = markersFromXml[i].getAttribute("base64Name");
				var owner = markersFromXml[i].getAttribute("owner");
				var desc = markersFromXml[i].getAttribute("description");
				var ip = markersFromXml[i].getAttribute("ip");
				var website = markersFromXml[i].getAttribute("website");
				var email = markersFromXml[i].getAttribute("email");
				var jabber = markersFromXml[i].getAttribute("jabber");
				var state = markersFromXml[i].getAttribute("state");
				var addr = markersFromXml[i].getAttribute("streetAddress");
				var lng = parseFloat(markersFromXml[i].getAttribute("lng"));
				var lat = parseFloat(markersFromXml[i].getAttribute("lat"));
				var ele = parseFloat(markersFromXml[i].getAttribute("elevation"));

				var node = new NodeMarker (name, base64Name, owner, email, website, jabber, desc, ip, state, lng, lat, ele);
				node.setStreetAddress (addr);

				markers[node.name] = node;
			}

			// Add Links
			var lnks = xmlDoc.documentElement.getElementsByTagName ("links")[0].getElementsByTagName ("link");
			for (var i = 0; i < lnks.length; i++) {
				try {
					var link = new Object ();
					link.type = lnks[i].getAttribute("type");
					link.quality = lnks[i].getAttribute("quality");
					link.node1 = markers [lnks[i].getAttribute("node1")];
					link.node2 = markers [lnks[i].getAttribute("node2")];
					link.point1 = markers [lnks[i].getAttribute("node1")].getPoint();
					link.point2 = markers [lnks[i].getAttribute("node2")].getPoint();
					links.push (link);
				} catch (e) {
					alert ("ERROR WITH LINK: " + lnks[i].getAttribute("node1")  + " <--> " + lnks[i].getAttribute("node2") + ":\n\n" + e);
				}
			}


			// Add local markers
			//XXX: Move cookie foo to gui.js
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
								if (markers[key].getPoint().lng() == x & markers[key].getPoint().lat() == y) {
									bad = true;
									break;
								}
							}

							if (bad) { break; }

							var marker = new NodeMarker (name, encode64(name), '', '', '', '', '', '', 'marker', x, y);
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
	return map;
}

function populateMap ()
{
	map.clearOverlays ();

	var nodeList = document.getElementById ("nodeList");
	if (nodeList != null) {
		nodeList.innerHTML = "";
	}

	var markerList = document.getElementById ("markerList");
	if (markerList != null) {
		markerList.innerHTML = "";
	}

	// Add Markers
	for (var key in markers) {
		var node = markers[key];
	
		if (node.state == 'activeipv4' && showActiveIPv4 == false) {
			node.visible = false;
			continue;
		} else if (node.state == 'activeipv6' && showActiveIPv6 == false) {
			node.visible = false;
			continue;
		} else if (node.state == 'hotspot' && showHotSpot == false) {
			node.visible = false;
			continue;
		} else if (node.state == 'vpn' && showTun == false) {
			node.visible = false;
			continue;
		} else if (node.state == 'potential' && showPotential == false) {
			node.visible = false;
			continue;
		}

		node.visible = true;

		map.addOverlay (node);

		if (node.state == 'activeipv4' | node.state == 'activeipv6' | node.state == 'potential' | node.state == 'vpn' | node.state == 'hotspot') {
			if (nodeList != null) {
				nodeList.innerHTML += '<li onmouseover="getMarker(\'' + node.base64Name + '\').showTooltip();" onmouseout="getMarker(\'' + node.base64Name + '\').hideTooltip();" class="nodeitem-' + node.state + '"><a href="javascript:getMarker(\'' + node.base64Name + '\').select();" style="font-weight: bold;">' + node.name + '</a>&nbsp;&nbsp;<a href="javascript:getMarker(\'' + node.base64Name + '\').zoomTo();" class="zoomLink">zoom</a></li>';
			}
		} else {
			if (markerList != null) {
				markerList.innerHTML += '<li onmouseover="getMarker(\'' + node.base64Name + '\').showTooltip();" onmouseout="getMarker(\'' + node.base64Name + '\').hideTooltip();" class="nodeitem-' + node.state + '"><div style="float: right; padding-right:5px;">(<a href="javascript:getMarker(\'' + node.base64Name + '\').removeMarker();">x</a>)</div><a href="javascript:getMarker(\'' + node.base64Name + '\').select();" style="font-weight: bold;">' + node.name + '</a>&nbsp;&nbsp;<a href="javascript:getMarker(\'' + node.base64Name + '\').zoomTo();" class="zoomLink">zoom</a></li>';
			}
		}
	}

	// Add Links
	for (var i = 0; i < links.length; i++) {
		if (links[i].node1.visible == true && links[i].node2.visible == true) {
			var points = []
			points.push (links[i].point1);
			points.push (links[i].point2);

			if (links[i].type == 'wifi') {
				if (showLinks== true) {
					if (links[i].quality == "1"){ 
						map.addOverlay (new GPolyline (points,goodLinkColor));
					} else if (links[i].quality == "2"){  
						map.addOverlay (new GPolyline (points,medLinkColor));
					} else if (links[i].quality == "3"){ 
						map.addOverlay (new GPolyline (points,badLinkColor));	
					} else {
						//map.addOverlay (new GPolyline (points));		
						alert ("Link quality error. No wifi link quality information provided in the db!");
                        return;	
					}
				}
			} else if (links[i].type == 'vpn') {
				if (showTunnels == true) {
					map.addOverlay (new GPolyline (points,"#ff8080"));
				}
			}
		}
	}

	saveMarkers();

	if (firstLoad == true) {
		if (getQueryVariable("centerlat") != null && getQueryVariable("centerlng") != null && getQueryVariable("zoom") != null) {
			if (getQueryVariable ("select") != null) {
				markers[getQueryVariable("select")].select ();
			}
			map.setCenter (new GLatLng(getQueryVariable("centerlat"), getQueryVariable("centerlng")));
			map.setZoom (parseInt(getQueryVariable("zoom")));
		} else if (getQueryVariable ("select") != null) {
			if (markers[getQueryVariable("select")] != null) {
				markers[getQueryVariable("select")].zoomTo ();
			}
		}
	}

	firstLoad = false;
}

function saveMarkers()
{	
	// Save Markers
	var value = '';
	var arr = new Array();
	for (key in markers) {
		var marker = markers[key];
		if (marker.state == 'marker') {
			arr.push(encode64 (marker.name) + ',' + marker.getPoint().lng() + ',' + marker.getPoint().lat() + ',' + encode64 (marker.streetAddress));
		}
	}
	value = arr.join("|");

	//XXX: Move cookie foo to gui.js
	if (value != '') {
		createCookie ("markers", value, 300);
	} else {
		eraseCookie ("markers");
	}
}


function getMarker (b64index) {
	var index = decode64 (b64index);

	return markers[index];
}

function addMarker (lat, lng, b64addr) 
{
	var streetAddress = decode64 (b64addr);

	showMarkers ();

	for (var key in markers) {
                if (markers[key].getPoint().lng() == lng & markers[key].getPoint().lat() == lat) {
                        alert ("A marker at this point already exists.");
                        return;
                }
        }

	markerCount ++;
	var newMarkerName = "New Node " + markerCount;
	var marker = new NodeMarker (newMarkerName, encode64(newMarkerName), '', '', '', '', '', '', 'marker', lng, lat);

	// store the street address if it was passed in
	if ( streetAddress != '' ) {
		marker.setStreetAddress (streetAddress);
	}

	markers[marker.name] = marker;
	populateMap ();
	marker.select();
	resizeMe ();
	scrollMarkersToBottom ();
	return marker;
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
	marker.base64Name = encode64 (newName);

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

function instanceOf(object, constructorFunction) {
  while (object != null) {
    if (object == constructorFunction.prototype)
     {return true}
	 object = object.__proto__;
  }
  return false;
}

function embedScript (src)
{
   var head = document.getElementsByTagName("head")[0];
   script = document.createElement('script');
   script.type = 'text/javascript';
   script.src = src;
   head.appendChild(script);
}

function embedStyle (href)
{
   var head = document.getElementsByTagName("head")[0];
   script = document.createElement('link');
   script.rel = 'stylesheet';
   script.type = 'text/css';
   script.href = href;
   head.appendChild(script);
}

function wheelZoom(event) {
	// Prevent from scrolling the page when zooming the map
	if(window.event) { event.returnValue = false; } // IE
	if(event.cancelable) { event.preventDefault(); } // DOM-Standard
	if((event.detail || -event.wheelDelta) < 0) {
		map.zoomIn();
	} else {
		map.zoomOut();
	}
}





    function TextControl(text, url) {
	this.text = text;
	this.url = url;
    }
    TextControl.prototype = new GControl();

    // Creates a one DIV for each of the buttons and places them in a container
    // DIV which is returned as our control element. We add the control to
    // to the map container and return the element for the map class to
    // position properly.
    TextControl.prototype.initialize = function(map) {
      var container = document.createElement("div");
      var textDiv = document.createElement("div");
      textDiv.innerHTML = '<a style="color: black;" href="' + this.url + '">' +this.text + '</a>';
      this.setButtonStyle_(textDiv);
 	container.appendChild (textDiv);
      map.getContainer().appendChild(container);
      return container;
    }

    // By default, the control will appear in the top left corner of the
    // map with 7 pixels of padding.
    TextControl.prototype.getDefaultPosition = function() {
      return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(7, 7));
    }

    // Sets the proper CSS for the given button element.
    TextControl.prototype.setButtonStyle_ = function(button) {
      button.style.textDecoration = "underline";
      button.style.color = "#0000cc";
      button.style.backgroundColor = "white";
      button.style.font = "x-small Arial";
      button.style.border = "1px solid black";
      button.style.padding = "3px";
      button.style.marginBottom = "3px";
      button.style.textAlign = "center";
      button.style.cursor = "pointer";
      button.style.setProperty ("-moz-opacity", "0.5", "");
    }

