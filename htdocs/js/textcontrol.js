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

	container.innerHTML = this.text;
      return container;
    }

    // By default, the control will appear in the top left corner of the
    // map with 7 pixels of padding.
    TextControl.prototype.getDefaultPosition = function() {
      return new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(7, 7));
    }

    // Sets the proper CSS for the given button element.
    TextControl.prototype.setButtonStyle_ = function(button) {
      button.style.textDecoration = "underline";
      button.style.color = "#0000cc";
      button.style.backgroundColor = "white";
      button.style.font = "small Arial";
      button.style.border = "1px solid black";
      button.style.padding = "2px";
      button.style.marginBottom = "3px";
      button.style.textAlign = "center";
      button.style.width = "6em";
      button.style.cursor = "pointer";
    }

