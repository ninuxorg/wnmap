/*
 * NodeMarker.js
 *
 * Authors:
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

	var point = new GLatLng (lat, lng);

	/* Prepare new marker */
	var icon = new GIcon ();
	icon.iconSize = new GSize(20, 34);
 	icon.iconAnchor = new GPoint(9, 34);
	icon.infoWindowAnchor = new GPoint(20, 1);
			
	switch (this.state) {
		case 'active':
			this.statePretty = WNMAP_ACTIVE_NODE;
			icon.image = WNMAP_MAP_URL + "/images/marker_active.png";
			break;
		case 'potential':
			this.statePretty = WNMAP_POTENTIAL_NODE;
			icon.image = WNMAP_MAP_URL + "/images/marker_potential.png";
			break;
		case 'hotspot':
			this.statePretty = WNMAP_HOTSPOT_NODE;
			icon.image = WNMAP_MAP_URL + "/images/marker_hotspot.png";
			break;
		default:
			this.statePretty = WNMAP_MARKER; 
			icon.image = WNMAP_MAP_URL + "/images/marker.png";
			//alert(name + state)
			this.enableDragging();
	}

	/* Add the node to the maps */
	NodeMarker.baseConstructor.call (this, point, icon);


	this.getOverviewHtml = function () {
		if (state == "marker") {
			return "<div class='marker_balloon'> \
					<div class='title'> \
					"+ this.name +" \
					</div> \
					<ul style='background, #ffffff url(images/add.png) no-repeat 0px 5px'> \
					<a href='" + WNMAP_MAP_URL + "/AddPotentialNode.php?lon=" + this.getPoint().lng() + "&lat=" + this.getPoint().lat() + "&name=" + escape(this.name) + "' target='_blank'>" +  WNMAP_ADD_THIS "</a> \
					</ul> \
				</div>";
		} else {
			var thing = document.createElement ("div");
			thing.className = "marker_balloon";

			var title = document.createElement ("div");

			var titleLabel = document.createElement ("span");
			titleLabel.innerHTML = "<b>" + WNMAP_NAME_ + "</b> ";
			title.appendChild (titleLabel);

			var titleLink = document.createElement ("span");
			titleLink.innerHTML = this.name;
			title.appendChild (titleLink);

			var linkTo = document.createElement ("span");
			linkTo.innerHTML = ' - <a href="?select=' + this.id + '">' + WNMAP_MAP_LINK_ + '</a>';
			title.appendChild (linkTo);

			thing.appendChild (title);

			var description = document.createElement ("div");

			var descriptionLabel = document.createElement("span");
			descriptionLabel.innerHTML = "<b>" + WNMAP_DESCRIPTION_ +"</b> ";
			description.appendChild (descriptionLabel);

			var descriptionText = document.createElement("span");
			descriptionText.innerHTML = this.description;
			description.appendChild (descriptionText);
	
			thing.appendChild (description);

			var owner = document.createElement ("div");
			owner.innerHTML = "<b>" + WNMAP_OWNER_ + "</b> " + this.owner + " ";
			owner.innerHTML += urlmanager ("id="+this.id+"&action=contatti", 400, 600, "Contatta")
			thing.appendChild (owner);


			var type = document.createElement ("div");
			type.className ="position";
			type.innerHTML ="<b>" + WNMAP_TYPE_ + "</b> " + this.statePretty;
			thing.appendChild (type);

			var type = document.createElement ("div");
			type.className ="position";
			type.innerHTML += urlmanager ("id="+this.id+"&action=manager", 400, 600, "Altro >>")
			thing.appendChild (type);

			return thing;
		}
	}


	this.select = function () {

		var infoTabs = [
			new GInfoWindowTab(WNMAP_OVERVIEW_, this.getOverviewHtml()),
			new GInfoWindowTab(WNMAP_DISTANCE_, new DistanceCalculator(this).getContent())
		];

		this.openInfoWindowTabs (infoTabs);
	}

	this.zoomTo = function () {
		this.hideTooltip ();
		map.setCenter (this.getPoint(), 17);

		var infoTabs = [
			new GInfoWindowTab(WNMAP_OVERVIEW_, this.getOverviewHtml()),
			new GInfoWindowTab(WNMAP_LOCATION_, this.getLocationHtml()),
			new GInfoWindowTab(WNMAP_DISTANCE_, new DistanceCalculator(this).getContent())
		];

		this.openInfoWindowTabsHtml (infoTabs);
	}

	// In GMap2, the maps API changed sufficiently to break the original
	// tooltip code contained here.  As a part of the port to GMap2, I'd
	// like to thank toEat.com and Robert Aspinall for doing the heavy
	// lifting in that code.  It showed the way for how to re-implement
	// tooltips in GMap2.
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

	this.onDragEnd = function () {
		saveMarkers();
	}

	GEvent.addListener (this, 'click', this.select);
	GEvent.addListener (this, 'mouseover', this.showTooltip);
	GEvent.addListener (this, 'mouseout', this.hideTooltip);

	GEvent.addListener (this, 'dragstart', this.onDragStart);
	GEvent.addListener (this, 'dragend', this.onDragEnd);

}

extend = function(subClass, baseClass) {
   function inheritance() {}
   inheritance.prototype = baseClass.prototype;

   subClass.prototype = new inheritance();
   subClass.prototype.constructor = subClass;
   subClass.baseConstructor = baseClass;
   subClass.superClass = baseClass.prototype;
}

extend(NodeMarker, GMarker);
