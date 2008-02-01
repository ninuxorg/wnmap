function DistanceCalculator (node)
{
	this.node = node;
	var overlay = null;

	GEvent.addListener (map, "infowindowclose", function () {
		map.removeOverlay(overlay);
	});

	this.getContent = function()
	{
		var select = document.createElement ("select");
		select.style.width = "125px";
		var option = document.createElement("option");
		option.appendChild(document.createTextNode(WNMAP_SELECT_));
		select.appendChild(option);
		
		var activegroup = document.createElement("optgroup");
		activegroup.label = WNMAP_ACTIVE_NODES; 

		var inactivegroup = document.createElement("optgroup");
		inactivegroup.label = WNMAP_POTENTIAL_NODES;

		var markergroup = document.createElement("optgroup");
		markergroup.label = WNMAP_MY_MARKERS;

		for (key in markers) {
			var marker = markers[key];
			if (marker != node) {
				option = document.createElement("option");
				option.appendChild(document.createTextNode(marker.name));

				if (marker.state == "active") {
					activegroup.appendChild(option);
				} else if (marker.state == "marker") {
					markergroup.appendChild(option);
				} else {
					inactivegroup.appendChild(option);
				}
			}
		}

		if (markergroup.childNodes.length > 0) {
			select.appendChild(markergroup);
		}	
		if (activegroup.childNodes.length > 0) {
			select.appendChild(activegroup);
		}
		if (inactivegroup.childNodes.length > 0) {
			select.appendChild(inactivegroup);
		}
	
		var container = document.createElement ("div");

		var text = document.createElement ("span");
		text.innerHTML = WNMAP_CALCULATE_DISTANCE_FROM + " <b>" + this.node.name + " </b> " + WNMAP_TO_ + " ";

		container.appendChild (text);
		container.appendChild (select);

		var result = document.createElement("p");
		result.style.setProperty ("font-size", "large", "");
		result.style.setProperty ("margin-bottom", "0px", "");
		result.style.setProperty ("margin-top", "10px", "");
		container.appendChild (result);

		select.onchange = function () {
			if (overlay != null) {
				map.removeOverlay(overlay);
			}
			for (key in markers) {
				var otherMarker = markers[key];
				if (otherMarker.name == select.value) {
					var meters = node.getPoint().distanceFrom (otherMarker.getPoint());
					var yards = meters * 1.0936;
					var km =  meters / 1000;
					var miles = km * 0.6214;

					if (km > 2) {
						result.innerHTML = roundNumber(km,2) + " " + WNMAP_KM_;
					} else {
						result.innerHTML = roundNumber(meters,2) + " " + WNMAP_METERS_;
					}

					result.innerHTML += "<br/>";

					if (miles > 1) {
						result.innerHTML += roundNumber(miles,2) + " " + WNMAP_MILES_;
					} else {
						result.innerHTML += roundNumber(yards,2) + " " + WNMAP_YARDS_;
					}

					overlay = new GPolyline([node.getPoint(), otherMarker.getPoint()], "#FFF73B", 10);
					map.addOverlay(overlay);

					return;
				}
			}
			result.innerHTML = "";
		}

		return container;
	}

}

function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}
