<?php

define ("MAIL_FOOTER",<<<EOF
Getting Started
===============

For information about putting up a node, visit our Getting Started page at:

http://wiki.ninux.org/GettingStarted

If you would like to discuss anything with us, you can send a message to the
appropriate mailing list (http://wiki.ninux.org/MailingLists) or attend one of
our meetings (http://wiki.ninux.org/Meetings) and get to know the group in
person.

We look forward to hearing from you!

Regards,
The Ninux.org Community
EOF
);

//index.php
define ("LINK_TO_THIS_PAGE","Link to this page");
define ("NETWORK_MAP","Network Map");
define ("FIND_LOCATION","Find Location");
define ("WELCOME_TITLE","Welcome!");
define ("WELCOME_TO_THE","Welcome to the " . ORG_NAME . " Network Map!");
define ("WHAT_IS","What is " . ORG_NAME . "?");
define ("HOW_TO_USE_MAP","How do I use this map?");
define ("ADDRESS_SUBMIT_LABEL","Search");
define ("ADDRESS_LABEL","Address, Street, and City, State or Zip:");
define ("MAP_SETTINGS_TITLE","Map Settings");
define ("SHOW_ACTIVE_NODES","Show Active Nodes");
define ("SHOW_POTENTIAL_NODES","Show Potential Node Locations");
define ("SHOW_WIRELESS_LINKS","Show Wireless Links");
define ("SHOW_INTERNET_TUNNELS","Show Internet Tunnels");
define ("NODES_","Nodes");
define ("MY_MARKERS","My Markers");
define ("LOADING_","Loading...");

//help.php
define ("THINK_ABOUT","Thinking about setting up a node? Put youself on the map!");
define ("THINK_ABOUT_DESC_1","Use the \"" . FIND_LOCATION . "\" search below to add a marker at your desired location. You can also click anywhere on the map if you do not know the address.");
define ("THINK_ABOUT_DESC_2","Rename the marker something meaningful such as \"Eric's House\".");
define ("THINK_ABOUT_DESC_3","Select the option to add the marker to the database and follow the directions.");
define ("THINK_ABOUT_DESC_4","You can click on other nodes to view photos and other information. If you think you find a node that you have line-of-sight to, get in touch with whoever owns it and set up a link!");
define ("GETTING_STARTED_TEXT","More information about putting up a node &raquo;");
define ("MAP_LEGEND_TITLE","Map Legend");
define ("MAP_LEGEND_POTENTIAL","Potential location for a node");
define ("MAP_LEGEND_ACTIVE","Active node");

//AddPotentialNode.php
define ("OUT_OF_RANGE","Point must not be more than %d miles from the center of the network.\n");
define ("ADD_NODE","Add Node");
define ("THINKING_ABOUT_NODE","Thinking about putting up a node at this location? Add it to our database! This way, other people who think that they might be able to see you can let you know and discuss setting up a link.");
define ("THINKING_ABOUT_NODE_NOTE","NOTE: Only add yourself to the map if you are actually seriously interested in linking up with other people on the network. The goal of this site is *NOT* to map every random access point in the city.");
define ("NODE_INFORMATION","Node Information");
define ("LATITUDE_","Latitude:");
define ("LONGITUDE_","Longitude:");
define ("NODE_NAME_","Node Name:");
define ("PICK_A_NAME","Pick a name for this node, such as \"Node45thAnd12th\" or \"NodeAwesomeCoffee\"...something unique to your location.");
define ("DESCRIPTION_","Description:");
define ("DESCRIPTION_DESC","Enter a brief description of the location (name of business, etc.).");
define ("NODE_IP_","Node IP:");
define ("ENTER_IP","Enter the IP address of the node(if the node is an active node of the network)");
define ("NODE_STREET_ADDRESS_","Node Street Address:");
define ("NODE_STREET_ADDRESS_DESC","This is optional, and you can be vauge if you'd prefer (specify the building name but not the apartment number, for example).");
define ("YOUR_INFORMATION_","Your Information");
define ("SOMEBODY_NEARBY","If somebody nearby thinks they can see you, they need some way to reach you!");
define ("YOUR_FULL_NAME_","Your Full Name:");
define ("EMAIL_ADDRESS_","E-mail Address:");
define ("EMAIL_ADDRESS_DESC","Used to verify node - must be valid.");
define ("PUBLISH_EMAIL_","Publish Email");
define ("JABBER_ID_","Jabber ID:");
define ("JABBER_ID_DESC","Learn more about <a href=\"http://seattlewireless.net/JabberServer\" target=\"_blank\">Jabber</a>.");
define ("WEBSITE_URL","Website URL:");
define ("WEBSITE_URL_DESC","(If you have one)");

// AddPotentialNodeSubmit.php
define ("INVALID_EMAIL","Invalid email address.");
define ("INVALID_JABBER","Invalid jabber id.");
define ("INVALID_IP_","Invalid IP.");
define ("INVALID_NAME_","Invalid name.");
define ("ADD_LOCATION_","Add Location");
define ("SPECIFY_DESCRIPTION","Please specify a description of this location.");
define ("NODE_ALREADY_EXISTS","A node at this point already exists in our database.");
define ("NODE_NAME_ALREADY_EXISTS","A node with that name already exists in our database.");
define ("ADDNODE_EMAIL_BODY","Thank you for your interest in %s!

To prevent abuse, we ask that you please visit the following URL to confirm 
your email address and have your node added to the network map.

%s/VerifyNode.php?hash=%s

If you did NOT request that a node be added to the map, or for any reason you
would like to remove this location from the map at a later time, you can use
the following URL:

%s/DeleteNode.php?hash=%s


%s");
define ("THANK_YOU_","Thank You!");
define ("HELLO_","Hello");
define ("AN_EMAIL_WAS_SENT","an email has been sent to");
define ("WITH_INSTRUCTIONS","with instructions on what to do next."); 
define ("GO_BACK_","Go back");

// DeleteNode.php
define ("ARE_YOU_SURE","Are you <strong>sure</strong> you want to delete this node?");
define ("YES_DELETE","Yes, delete it.");
define ("DONT_DELETE","Nope, I changed my mind.");

// DeleteNodeYes.php
define ("NODE_DELETED_","Node deleted.");
define ("NODE_REMOVED","Your node has been removed from the map.");
define ("VIEW_MAP","View the map!");

// VerifyNode.php
define ("EMAIL_CONFIRMED","Your email address has been confirmed and this location is now active on the map!");

?>
