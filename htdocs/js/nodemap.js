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
var markerid = -1;

var firstLoad = true;

var showActive = true;
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
			var nome = prompt ('Inserisci il nome di questo nuovo nodo:', 'nodo_potenziale');
			addMarker (nome, point.lat(), point.lng());
		}
	});

	window.addEventListener('DOMMouseScroll', wheelZoom, false);
	
	map.enableContinuousZoom();
	//we use single-click to add markers
	map.enableDoubleClickZoom();

	var request = GXmlHttp.create ();
	request.open ('GET', 'data.php', true);
	request.onreadystatechange = function () {
		if (request.readyState == 4) {

			var xmlDoc = request.responseXML;

			// Add Nodes
			var markersFromXml = xmlDoc.documentElement.getElementsByTagName ("nodes")[0].getElementsByTagName ("node");
			for (var i = 0; i < markersFromXml.length; i++) {

				var id = markersFromXml[i].getAttribute("id");
				var name = markersFromXml[i].getAttribute("name");
				var id = markersFromXml[i].getAttribute("id");
				var owner = markersFromXml[i].getAttribute("owner");
				var desc = markersFromXml[i].getAttribute("description");
				var state = markersFromXml[i].getAttribute("state");
				var lng = parseFloat(markersFromXml[i].getAttribute("lng"));
				var lat = parseFloat(markersFromXml[i].getAttribute("lat"));

				var node = new NodeMarker (id, name, owner, desc, state, lng, lat);

				markers[node.id] = node;
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
					alert ("ERROR WITH LINK: " + lnks[i].getAttribute("noed1")  + " <--> " + lnks[i].getAttribute("node2") + ":\n\n" + e);
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
	
		if (node.state == 'active' && showActive == false) {
			node.visible = false;
			continue;
		} else if (node.state == 'potential' && showPotential == false) {
			node.visible = false;
			continue;
		}

		map.addOverlay (node);

		if (node.state != 0) {
			if (nodeList != null) {
				nodeList.innerHTML += '<li onmouseover="getMarker(\'' + node.id + '\').showTooltip();" onmouseout="getMarker(\'' + node.id + '\').hideTooltip();" class="nodeitem-' + node.state + '"><a href="javascript:getMarker(\'' + node.id + '\').select();" style="font-weight: bold;">' + node.name + '</a>&nbsp;&nbsp;<a href="javascript:getMarker(\'' + node.id + '\').zoomTo();" class="zoomLink">zoom</a></li>';
			}
		} else {
			if (markerList != null) {
				markerList.innerHTML += '<li onmouseover="getMarker(\'' + node.id + '\').showTooltip();" onmouseout="getMarker(\'' + node.id + '\').hideTooltip();" class="nodeitem-' + node.state + '"><div style="float: right; padding-right:5px;">(<a href="javascript:getMarker(\'' + node.id + '\').removeMarker();">x</a>)</div><a href="javascript:getMarker(\'' + node.id + '\').select();" style="font-weight: bold;">' + node.name + '</a>&nbsp;&nbsp;<a href="javascript:getMarker(\'' + node.id + '\').zoomTo();" class="zoomLink">zoom</a></li>';
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
			} else {
				if (showTunnels == true) {
					map.addOverlay (new GPolyline (points,"#ff8080"));
				}
			}
		}
	}

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


function getMarker (id) {
	return markers[index];
}

function addMarker (nome, lat, lng) 
{

	showMarkers ();

	for (var key in markers) {
                if (markers[key].name == nome) {
                        alert ("A marker with this name already exists. Sorry!.");
                        return;
                }
                if (markers[key].getPoint().lng() == lng & markers[key].getPoint().lat() == lat) {
                        alert ("A marker at this point already exists.");
                        return;
                }		
        }

	markerid --;
	//NodeMarker (id, name, owner, description, state, lng, lat)
	var marker = new NodeMarker (markerid, nome, '', '', 'marker', lng, lat);

	markers[marker.id] = marker;
	populateMap ();
	marker.select();
	resizeMe ();
	scrollMarkersToBottom ();
	return marker;
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

