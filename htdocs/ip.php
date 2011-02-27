<?php

/*
WNMap
Copyright (C) 2011 Claudio Mignanti <c.mignanti@gmail.com>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
*/

error_reporting(E_ALL);

require ("config.php");
$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database.');

echo "<html><body>\n<table>\n";

$query = "SELECT COUNT(*) FROM " . MYSQL_NODES_TABLE . " WHERE status=";

//potential nodes
$result = mysql_query ($query."1;", $connection) or die (mysql_error());

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	$num = htmlspecialchars($row[0]);
	
	echo "<tr class='potential'><td>Nodi potenziali</td><td>$num</td></tr>\n";
}

//active nodes
$result = mysql_query ($query."2;", $connection) or die (mysql_error());

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	$num = htmlspecialchars($row[0]);
	
	echo "<tr class='active'><td>Nodi Attivi</td><td>$num</td></tr>\n";
}

//hotspot nodes
$result = mysql_query ($query."3;", $connection) or die (mysql_error());

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	$num = htmlspecialchars($row[0]);
	
	echo "<tr class='hotspot'><td>Nodi HotSpot</td><td>$num </td></tr>\n";
}

echo "</table>\n<h1>Lista indirizzi in uso</h1><br>\n<i>In ordine di numero di nodi</i><br>\n";


//We need to refactor db (network name should be stored in a saparete table)

/* Ninux city fighter. The city that mount more nodes is the winner */
//$query = "SELECT network, count('network') as num  FROM links ORDER by num";
$query = "SELECT DISTINCT(network) FROM links";

$q1 = mysql_query ($query, $connection) or die (mysql_error());
	
while ($rq1 = mysql_fetch_array($q1, MYSQL_NUM)) {
	$net=$rq1[0];	//network city

	echo "<b>Rete di $net:</b><br>\n<table class='ips_". $net ."'>\n ";

	$query = "SELECT DISTINCT(nodes.id), nodeName, nodeIP  FROM " . MYSQL_NODES_TABLE . ",links  WHERE status IN (2,3) AND nodes.id=links.node1 AND network='". $net ."' ORDER by nodeIP";
	
	//lista ips per city
	$q2 = mysql_query ($query, $connection) or die (mysql_error());
	echo "<table>";
	//We should divide each ip and keep reference with node.
	unset($id);
	unset($nome);
	unset($ips);
	//This shold be made  with database restructure... 
	while ($rq2 = mysql_fetch_array($q2, MYSQL_NUM)) {
		$ips = explode(" ", $rq2[2]);
		foreach ($ips as $one_ip) { 
			$id[] = $rq2[0];
			$nome[] = $rq2[1];
			$ip[] = $one_ip;
		}			 
	}		

	for ($i=0; $i < count($id); $i +=1) {
		echo "<tr><td> <a class='manager' target=\"popup\" onclick=\"window.open('manager.php?action=manager&id=".$id[$i]."','popup','toolbar=no, location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=420,height=400,left=430,top=23'); return false;\">edit</a> </td><td> $nome[$i] </td><td> $ip[$i] </td></tr>\n";
	}
	echo "</table>";

}


//html end
echo "</body></html>";

?>
