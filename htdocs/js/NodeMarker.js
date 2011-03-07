/*
 * NodeMarker.js
 *
 * Authors:
 *    Claudio Mignanti <c.mignanti@gmail.com>
 *
 * Original version written by:
 *    Eric Butler <eric@extremeboredom.net>
 *
 * Part of the WNMap Project - http://wnmap.sf.net/
 *
 */

var url;

function urlmanager (get, h, w, linkname) {
	return "<a href=\"javascript:void(0);\" onclick=\"window.open ('manager.php?" + get + "', 'Manager', 'scrollbars=yes,menubar=no,toolbar=no,status=no,personalbar=no,width=" + w + " ,height=" + h + "' );\">" + linkname + "</a> ";
}
function NodeMarker (id, name, owner, description, state, lng, lat)
{
	this.name = name;
	this.owner = owner;
	this.id = id;
	this.description = description;
	this.state = state;
	this.visible = true;
	this.tooltip = this.name;
	
	switch (this.state) {
		case 'active':
			var statePretty = WNMAP_ACTIVE_NODE;
			var image = WNMAP_MAP_URL + "/images/marker_active.png";
			break;
		case 'potential':
			var statePretty = WNMAP_POTENTIAL_NODE;
			var image = WNMAP_MAP_URL + "/images/marker_potential.png";
			break;
		case 'hotspot':
			var statePretty = WNMAP_HOTSPOT_NODE;
			var image = WNMAP_MAP_URL + "/images/marker_hotspot.png";
			break;
		default:
			var statePretty = WNMAP_MARKER; 
			var image = WNMAP_MAP_URL + "/images/marker.png";
			//alert(name + state) 
	}

	//Enable dragging for marker node AFTER that it was added to the map
	if (this.state == "marker") {
		var is_draggable=true;
	} else {
		var is_draggable=false;
	}

	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(lat, lng),
		map: map,
		icon: image,
		draggable: is_draggable,
		title: this.name,
	});

	/* baloon string */
	if (state == "marker") {
		 var contentString = "<div class='marker_balloon'> \
				<div class='title'> \
				<span>"+ this.name +"</span> \
				</div> \
				<ul style='background, #ffffff url(images/add.png) no-repeat 0px 5px'> \
				<a href='" + WNMAP_MAP_URL + "/AddPotentialNode.php?lon=" + this.getPoint().lng() + "&lat=" + this.getPoint().lat() + "&name=" + escape(this.name) + "' target='_blank'>" +  WNMAP_ADD_THIS "</a> \
				</ul> \
			</div>\n</div>";
	} else {
		var contentString = "<div class='marker_balloon'> \
					<div> \
					<span><b>" + WNMAP_NAME + "</b></span> \
					- <a href='?select=" + this.id + "'>" + WNMAP_MAP_LINK + "</a></div> \
					<div><b>" + WNMAP_DESCRIPTION + "</b> " + this.description + "</div> \
					<div><b>" + WNMAP_OWNER + "</b> " + urlmanager ("id=" + this.id
						+ "&action=contatti", 400, 600, "Contatta") + "</div> \
					<div>" + urlmanager ("id="+this.id+"&action=manager", 400, 600, "Altro >>") + "</div>\n</div>";
	}

	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});
	/* EO baloon string */

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map, marker);
	});

/*	this.select = function () {

		var infoTabs = [
			new GInfoWindowTab(WNMAP_OVERVIEW_, this.getOverviewHtml()),
			new GInfoWindowTab(WNMAP_DISTANCE_, new DistanceCalculator(this).getContent())
		];

		this.openInfoWindowTabs (infoTabs);
	}
*/
	this.zoomTo = function () {
		this.hideTooltip ();
		map.setCenter (this.getPoint(), 15);

		var infoTabs = [
			new GInfoWindowTab(WNMAP_OVERVIEW_, this.getOverviewHtml()),
			new GInfoWindowTab(WNMAP_DISTANCE_, new DistanceCalculator(this).getContent())
		];

		this.openInfoWindowTabsHtml (infoTabs);
	}

	this.showTooltip = function () {
		if (this.tooltip) {
			if (!this.tooltipObject) {
				this.tooltipObject = document.createElement ('div');

				var opacity = .70;
				this.tooltipObject.className ="tooltip";
				this.tooltipObject.style.position = 'relative';
				this.tooltipObject.style.background = 'white';
				this.tooltipObject.style.border = '1px solid black';
				this.tooltipObject.style.padding = '2px';
				this.tooltipObject.style.zIndex = 50000;
	            		this.tooltipObject.style.filter = "alpha(opacity=" + opacity + ")";
	                	this.tooltipObject.style.opacity = opacity;

				map.getPane(G_MAP_MARKER_PANE).appendChild(this.tooltipObject);
			}

			// The name might have changed
			this.tooltipObject.innerHTML = this.name;

			var c = map.fromLatLngToDivPixel(new GLatLng(this.getPoint().lat(), this.getPoint().lng()));

			try {
        			this.tooltipObject.style.top  = c.y - ( this.getIcon().iconAnchor.y + 5 ) + "px";
        			this.tooltipObject.style.left = c.x + ( this.getIcon().iconSize.width - this.getIcon().iconAnchor.x + 5 ) + "px";
        			this.tooltipObject.style.display = "block";
			} catch(e) {
				alert(e);
			}
		}
	}

	this.hideTooltip = function () {
		if (this.tooltipObject) {
			this.tooltipObject.style.display = "none";
		}
	}

	this.removeMarker = function () {
		this.hideTooltip ();

		delete markers[this.name];

		populateMap ();
	}

	this.onDragStart = function () {
		this.hideTooltip ();
		map.closeInfoWindow();
	}

	google.maps.event.addListener (this, 'dragstart', this.onDragStart);
	google.maps.event.addListener (this, 'dragend', this.onDragEnd);
}

extend = function(subClass, baseClass) {
   function inheritance() {}
   inheritance.prototype = baseClass.prototype;

   subClass.prototype = new inheritance();
   subClass.prototype.constructor = subClass;
   subClass.baseConstructor = baseClass;
   subClass.superClass = baseClass.prototype;
}

extend(NodeMarker, google.maps.Marker);
