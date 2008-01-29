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

function NodeMarker (name, base64name, owner, email, website, jabber, description, ip, state, lng, lat)
{
	this.name = name;
	this.owner = owner;
	this.email = email;
	this.website = website;
	this.jabber = jabber;
	this.base64Name = base64name;
	this.description = description;
	this.ip = ip;
	this.state = state;
	this.visible = true;
	this.streetAddress = "";
	this.tooltip = this.name;
	
	var point = new GLatLng (lat, lng);

	switch (state.toLowerCase()) {
		case 'active':
			this.statePretty = "Nodo attivo";

			var icon = new GIcon ();
			icon.image = WNMAP_MAP_URL + "/images/marker_active.png";
			icon.iconSize = new GSize(20, 34);
		 	icon.iconAnchor = new GPoint(9, 34);
			icon.infoWindowAnchor = new GPoint(20, 1);
			
			NodeMarker.baseConstructor.call (this, point, icon);
			break;
		case 'potential':
			this.statePretty = "Nodo potenziale";

			var icon = new GIcon ();
			icon.image = WNMAP_MAP_URL + "/images/marker_potential.png";
			icon.iconSize = new GSize(20, 34);
			icon.iconAnchor = new GPoint(9, 34);
			icon.infoWindowAnchor = new GPoint(20, 1);

			NodeMarker.baseConstructor.call (this, point, icon);
			break;
		case 'marker':
			this.statePretty = "segnaposto";


			NodeMarker.baseConstructor.call (this, point, {draggable:true});
			this.enableDragging();

			break;
		default:
			alert ("Invalid state");
			return;
			break;
	}

	this.getOverviewHtml = function () {
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
			renameLink.innerHTML = "Rinomina";
			title.appendChild (renameLink);

			var type = document.createElement ("div");
			type.className ="posizione";
			type.innerHTML = "<b>Tipo:</b> " + this.statePretty;
			thing.appendChild (type);

			var distance = document.createElement ("div");
			distance.className = "posizione";
			distance.innerHTML = "<b>Distanza dal centro:</b> " + distanceToCenterPretty(this.getPoint().lat(), this.getPoint().lng());
			//thing.appendChild (distance);

			var actionList = document.createElement ("ul");
			thing.appendChild (actionList);
	
			var addActionItem = document.createElement ("li");
			addActionItem.style.setProperty("background", "#ffffff url(images/add.png) no-repeat 0px 5px", null);

			var addActionLink = document.createElement ("a");
			addActionLink.innerHTML = "Aggiungi al nostro database come nodo potenziale.<br/>";

			url = WNMAP_MAP_URL + "/AddPotentialNode.php?lon=" + this.getPoint().lng() + "&lat=" + this.getPoint().lat() + "&name=" + URLEncode (this.name) + "&ip=" + this.ip + "&addr='" + encode64 (this.streetAddress) + "'";
			addActionLink.href = "javascript:window.open (url, null,'menubar=no,scrollbars=yes,addressbar=no,locationbar=no,status=no,height=530,width=440'); void(0);";
			

			addActionItem.appendChild (addActionLink);
			actionList.appendChild (addActionItem);
	
			var deleteActionItem = document.createElement ("li");
			deleteActionItem.style.setProperty("background", "url(images/remove.png) no-repeat", "");
			var deleteActionLink = document.createElement ("a");
			deleteActionLink.innerHTML = "Elimina questo segnaposto";
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

			/*
			var f = document.createElement ("div");
			f.appendChild (thing);
			return f.innerHTML;
			*/
			return thing;

		} else {
			var thing = document.createElement ("div");
			thing.className = "marker_balloon";

			var title = document.createElement ("div");

			var titleLabel = document.createElement ("span");
			titleLabel.innerHTML = "<b>Nome:</b> ";
			title.appendChild (titleLabel);

			var titleLink = document.createElement ("a");
			titleLink.className = "title";
			titleLink.innerHTML = this.name;
			titleLink.href = WNMAP_NODE_URL + this.name;
			title.appendChild (titleLink);

			var linkTo = document.createElement ("span");
			linkTo.innerHTML = ' - <a href="?select=' + this.name + '">Collegamento</a>';
			title.appendChild (linkTo);

			thing.appendChild (title);

			var description = document.createElement ("div");

			var descriptionLabel = document.createElement("span");
			descriptionLabel.innerHTML = "<b>Descrizione:</b> ";
			description.appendChild (descriptionLabel);

			var descriptionText = document.createElement("span");
			descriptionText.innerHTML = this.description;
			description.appendChild (descriptionText);
	
			thing.appendChild (description);

			var owner = document.createElement ("div");

			if (this.website != "") {
				owner.innerHTML = '<b>Proprietario:</b> <a href="' + this.website + '">' + this.owner + '</a>';
			} else {
				owner.innerHTML = "<b>Proprietario:</b> " + this.owner;
			}

			if (this.email != "") {
				owner.innerHTML += " - <a href=\"mailto:" + this.email + "\">Invia messaggio e-mail</a>";
				if (this.jabber != "") {
					owner.innerHTML += " <a href=\"xmpp:" + this.jabber + "?message\">Invia messaggio IM</a>";
				}
			} else {
				if (this.jabber != "") {
					owner.innerHTML += " - <a href=\"xmpp:" + this.jabber + "?message\">Invia messaggio IM</a>";
				}
			}

			thing.appendChild (owner);


			var type = document.createElement ("div");
			type.className ="posizione";
			type.innerHTML = "<b>Tipo:</b> " + this.statePretty;
			thing.appendChild (type);

			return thing;
			/*
			var f = document.createElement ("div");
			f.appendChild (thing);
			return f.innerHTML;
			*/
		}
	}

	this.getLocationHtml = function () {
		var thing = document.createElement ("div");
		thing.className = "marker_balloon";

		var f = document.createElement ("div");

		var pos = document.createElement ("div");
		pos.className = "posizione";
		pos.innerHTML = "<b>Latitudine:</b> " + Math.round(this.getPoint().lat()*1000000)/1000000 + "<br/><b>Longitudine:</b> " + Math.round(this.getPoint().lng()*1000000)/1000000;
		thing.appendChild (pos);

		var address = document.createElement ("div");
		address.className = "posizione";
		address.innerHTML = "<b>Indirizzo postale:</b> " + this.streetAddress;
		thing.appendChild (address);

		
		var ip = document.createElement ("div");
		address.className = "posizione";
		address.innerHTML = "<b>IP:</b> " + this.ip;
		thing.appendChild (ip);
		//var distance = document.createElement ("div");
		//distance.className = "position";
		//distance.innerHTML = "<b>Distance to center:</b> " + distanceToCenterPretty(this.getPoint().lat(), this.getPoint().lng());
		//thing.appendChild (distance);

	//	f.appendChild (thing);
	//	return f.innerHTML;	
		return thing;
	}

	this.setStreetAddress = function (addr) {
		this.streetAddress = addr;
	}

	this.select = function () {

		var infoTabs = [
			new GInfoWindowTab("Dati", this.getOverviewHtml()),
			new GInfoWindowTab("Posizione", this.getLocationHtml()),
			new GInfoWindowTab("Distanza", new DistanceCalculator(this).getContent())
		];

		this.openInfoWindowTabs (infoTabs);
	}

	this.zoomTo = function () {
		this.hideTooltip ();
		map.setCenter (this.getPoint(), 17);

		var infoTabs = [
			new GInfoWindowTab("Dati", this.getOverviewHtml()),
			new GInfoWindowTab("Posizione", this.getLocationHtml()),
			new GInfoWindowTab("Distanza", new DistanceCalculator(this).getContent())
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
