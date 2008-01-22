function showNodes ()
{
	document.getElementById ("nodesTabContent").style.display = "block";
	document.getElementById ("myMarkersTabContent").style.display = "none";

	document.getElementById ("myMarkersTab").className = "";
	document.getElementById ("nodesTab").className = "selected";

	resizeMe ();
}

function showMarkers ()
{
	document.getElementById ("nodesTabContent").style.display = "none";
	document.getElementById ("myMarkersTabContent").style.display = "block";

	document.getElementById ("myMarkersTab").className = "selected";
	document.getElementById ("nodesTab").className = "";

	resizeMe ();
}


function showLogin () {
	document.getElementById ("accountOptions").style.display = "none";
	document.getElementById ("login").style.display = "block";
}
function cancelLogin () {
	document.getElementById ("accountOptions").style.display = "block";
	document.getElementById ("login").style.display = "none";
}

function resetSearch ()
{
	document.getElementById ("findLocationResponse").innerHTML = "";
	document.getElementById ("findLocationResponse").className = "";
	document.getElementById ("submitLocationSearchButton").disabled = false;
	document.getElementById ("address").value = "";

	resizeMe ();
}

function scrollMarkersToBottom ()
{
	var myMarkersTabContentDiv = document.getElementById ('myMarkersTabContent');
	myMarkersTabContentDiv.scrollTop = myMarkersTabContentDiv.scrollHeight;
}

// http://www.howtocreate.co.uk/tutorials/javascript/browserwindow
function getWindowHeight() {
	var myWidth = 0, myHeight = 0;
	if( typeof( window.innerWidth ) == 'number' ) {
		//Non-IE
		myWidth = window.innerWidth;
		myHeight = window.innerHeight;
	} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		//IE 6+ in 'standards compliant mode'
		myWidth = document.documentElement.clientWidth;
		myHeight = document.documentElement.clientHeight;
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		//IE 4 compatible
		myWidth = document.body.clientWidth;
		myHeight = document.body.clientHeight;
	}

	return myHeight;
}

// resize stuff

var minHeight = 450;

function resizeMe()
{
	var containerDiv = document.getElementById ('mapColumn');
	var sideDiv = document.getElementById ('sideColumn');
	var nodesTabContentDiv = document.getElementById ('nodesTabContent');
	var myMarkersTabContentDiv = document.getElementById ('myMarkersTabContent');
	var footerDiv = document.getElementById ('footer');

	var paddingTop = sideDiv.offsetTop;
	var paddingBottom = footerDiv.offsetHeight;

	var newHeight = getWindowHeight () - paddingTop - paddingBottom;
	
	if (newHeight < minHeight) {
		newHeight = minHeight;
	}
	
	containerDiv.style.height = newHeight + "px";
	sideDiv.style.height = newHeight + "px";

	nodesTabContentDiv.style.height = (newHeight - nodesTabContentDiv.offsetTop) + "px";
	myMarkersTabContentDiv.style.height = (newHeight - myMarkersTabContentDiv.offsetTop) + "px";

	if (typeof(map.onResize) == 'function')
		map.onResize ();

}
