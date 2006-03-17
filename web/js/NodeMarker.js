//NodeMarker.js

var url;

function NodeMarker (name, description, state, lng, lat)
{
	this.name = name;
	this.description = description;
	this.state = state;
	this.visible = true;
	this.streetAddress = "n/a";
	
	var point = new GPoint (lng, lat);

	switch (state.toLowerCase()) {
		case 'active':
			this.statePretty = "Active Node";

			var icon = new GIcon ();
			icon.image = SWNCFG_MAP_URL + "/images/marker_active.png";
			icon.iconSize = new GSize(20, 34);
		 	icon.iconAnchor = new GPoint(9, 34);
			icon.infoWindowAnchor = new GPoint(20, 1);
			
			GMarker.call (this, point, icon);
			break;
		case 'potential':
			this.statePretty = "Potential Node";

			var icon = new GIcon ();
			icon.image = SWNCFG_MAP_URL + "/images/marker_potential.png";
			icon.iconSize = new GSize(20, 34);
			icon.iconAnchor = new GPoint(9, 34);
			icon.infoWindowAnchor = new GPoint(20, 1);
			GMarker.call (this, point, icon);
			break;
		case 'marker':
			this.statePretty = "Marker";

			GMarker.call (this, point);
			this.step = 1;
			break;
		default:
			alert ("Invalid state");
			return;
			break;
	}
	

	this.getHtml = function () {
		var html = "";

		if (state == "marker") {
			var thing = document.createElement ("div");
			thing.className = "marker_balloon";

			var title = document.createElement ("div");
			title.className = "title";
			title.innerHTML = this.name;
			thing.appendChild (title);
			
			var renameLink = document.createElement ("a");
			renameLink.href = "javascript:var newname = renamePrompt ('" + encode64 (this.name) + "'); if (newname != null) { renameMarker ('" + encode64 (this.name) + "', newname); getMarker (newname).select(); }";
			renameLink.innerHTML = "Rename";
			title.appendChild (renameLink);

			var type = document.createElement ("div");
			type.className ="position";
			type.innerHTML = "<b>Type:</b> " + this.statePretty;
			thing.appendChild (type);

			var pos = document.createElement ("div");
			pos.className = "position";
			pos.innerHTML = "<b>Latitude:</b> " + Math.round(this.point.y*1000000)/1000000 + "<br/><b>Longitude:</b> " + Math.round(this.point.x*1000000)/1000000;
			thing.appendChild (pos);

			var address = document.createElement ("div");
			address.className = "position";
			address.innerHTML = "<b>Street Address:</b> " + this.streetAddress;
			thing.appendChild (address);

			var distance = document.createElement ("div");
			distance.className = "position";
			distance.innerHTML = "<b>Distance to center:</b> " + distanceToCenterPretty(this.point.y, this.point.x);
			//thing.appendChild (distance);

			var actionList = document.createElement ("ul");
			thing.appendChild (actionList);
	
			var addActionItem = document.createElement ("li");
			var addActionLink = document.createElement ("a");
			addActionLink.innerHTML = "Add this to our database as a location for a potential node.<br/>";

			url = SWNCFG_MAP_URL + "/AddPotentialNode.php?lon=" + this.point.x + "&lat=" + this.point.y + "&name=" + URLEncode (this.name) + "&addr='" + encode64 (this.streetAddress) + "'";
			addActionLink.href = "javascript:window.open (url, null,'menubar=no,scrollbars=yes,addressbar=no,locationbar=no,status=no,height=530,width=440'); void(0);";
			
			addActionItem.appendChild (addActionLink);
			actionList.appendChild (addActionItem);
	
			var deleteActionItem = document.createElement ("li");
			var deleteActionLink = document.createElement ("a");
			deleteActionLink.innerHTML = "Remove this marker";
			deleteActionLink.href = "javascript:getMarker('" + encode64 (this.name) + "').removeMarker ();";
			deleteActionItem.appendChild (deleteActionLink);
			actionList.appendChild (deleteActionItem);


			/*
			var form = document.createElement ("form");
			thing.appendChild (form);

			var stepTitle = document.createElement ("div");
			stepTitle.innerHTML = "Step 1. Pick a name for this location.";
			stepTitle.className = "stepTitle";
			form.appendChild (stepTitle);

			var directionsText = document.createElement ("div");
			directionsText.innerHTML = "Names should begin with the word \"Node\" and may only contain letters and numbers.";
			form.appendChild (directionsText);

			form.appendChild (document.createElement ("br"));

			var nameLabel = document.createElement ("label");
			nameLabel.htmlFor = "nameInput";
			nameLabel.innerHTML = "Name:";
			form.appendChild (nameLabel);

			form.appendChild (document.createElement ("br"));

			var nameInput = document.createElement ("input");
			nameInput.style.width = "100%";
			nameInput.type = "text";
			nameInput.id = "nameInput";
			form.appendChild (nameInput);	

			form.appendChild (document.createElement ("br"));

			var exampleText = document.createElement ("div");
			exampleText.className = "exampleText";
			exampleText.innerHTML = 'eg. "NodeQueenAnnSouth", "Node4578" (Your house number), "NodeIsBetterThanYours", etc.';
			form.appendChild (exampleText);

			var buttonBox = document.createElement ("div");
			buttonBox.className = "buttonBox";
			thing.appendChild (buttonBox);

			var cancelLink = document.createElement ("a");
			cancelLink.href = "javascript:markers['" + this.name + "'].removeMarker();";
			cancelLink.innerHTML = "Cancel";
			buttonBox.appendChild (cancelLink);

			var nextLink = document.createElement ("a");
			nextLink.href = 'javascript:nextStep();';
			nextLink.innerHTML = "Next &raquo;";
			buttonBox.appendChild (nextLink);

			*/

			var f = document.createElement ("div");
			f.appendChild (thing);
			return f.innerHTML;

		} else {
			var thing = document.createElement ("div");
			thing.className = "marker_balloon";

			var title = document.createElement ("div");
			var titleLink = document.createElement ("a");
			titleLink.className = "title";
			titleLink.innerHTML = this.name;
			titleLink.href = SWNCFG_NODE_URL + this.name;
			title.appendChild (titleLink);
			title.appendChild (document.createTextNode (' (' + this.description + ')'));
			thing.appendChild (title);
			
			var type = document.createElement ("div");
			type.className ="position";
			type.innerHTML = "<b>Type:</b> " + this.statePretty;
			thing.appendChild (type);

			var pos = document.createElement ("div");
			pos.className = "position";
			pos.innerHTML = "<b>Latitude:</b> " + Math.round(this.point.y*1000000)/1000000 + "<br/><b>Longitude:</b> " + Math.round(this.point.x*1000000)/1000000;
			thing.appendChild (pos);

			var address = document.createElement ("div");
			address.className = "position";
			address.innerHTML = "<b>Street Address:</b> " + this.streetAddress;
			thing.appendChild (address);

			var distance = document.createElement ("div");
			distance.className = "position";
			distance.innerHTML = "<b>Distance to center:</b> " + distanceToCenterPretty(this.point.y, this.point.x);
			//thing.appendChild (distance);

			var f = document.createElement ("div");
			f.appendChild (thing);
			return f.innerHTML;
		}
	}

	this.setStreetAddress = function (addr) {
		this.streetAddress = addr;
	}

	this.select = function () {
		this.openInfoWindowHtml (this.getHtml());
	}

	this.zoomTo = function () {
		this.destroyTooltip ();
		map.centerAndZoom (this.point, 0);
	}

	this.showTooltip = function () {
		if (!this.tooltip) {
			var tooltip = document.createElement ('div');
			tooltip.innerHTML = this.name;

			var opacity = .70;
			className ="tooltip";
			tooltip.className ="tooltip";
			tooltip.style.position = 'absolute';
			tooltip.style.background = 'white';
			tooltip.style.border = '1px solid black';
			tooltip.style.padding = '2px';
			tooltip.style.zIndex = 50000;
	            	tooltip.style.filter = "alpha(opacity=" + opacity + ")";
	                tooltip.style.opacity = opacity;

			var b = map.spec.getBitmapCoordinate (this.point.y, this.point.x, map.getZoomLevel());
		        var c = map.getDivCoordinate (b.x, b.y);
			tooltip.style.left = c.x + (this.icon.iconAnchor.x + 5) + "px";
			tooltip.style.top = c.y - (this.icon.iconAnchor.y) + "px";
			tooltip.style.display = "block";
	
			map.div.appendChild (tooltip);

			this.tooltip = tooltip;
		}
	}

	this.destroyTooltip = function () {
		if (this.tooltip) {
			map.div.removeChild (this.tooltip);
			this.tooltip = null;
		}
	}

	this.removeMarker = function () {
		this.destroyTooltip ();

		delete markers[this.name];

		populateMap ();
	}

	GEvent.addListener (this, 'click', this.select);
	GEvent.addListener (this, 'mouseover', this.showTooltip);
	GEvent.addListener (this, 'mouseout', this.destroyTooltip);

}

NodeMarker.prototype = new GMarker;
