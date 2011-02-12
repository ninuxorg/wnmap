<?php
/*
WNMap
Copyright (C) 2011 Claudio Mignanti <c.mignanti@gmail.com>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/



require ("config.php");

echo "<h2>Manager</h2>";

if ( MANAGEMENT == 0)
 	die ("Manager disable due to configuration");

/* ratelimit changes */
/* Max 2 changes per 5 mins*/
$lock = open ("/tmp/wnmap.lock","w+");
while (flock($lock, LOCK_EX) == 0);

file_get_contents ("ip2time.dat" , $iptime) || $iptime = array(0 => time()+5*60, 1=> 2);

$iptime[1] == $iptime[1] - 1;
if ( $iptime[1] < 0 ) {
	die ("Spiacente per prevenire attacchi la tua operazione non può essere eseguita ora");
}

$iptime[1] += ((time() - $iptime[0]) / 5*60)>2 ? 2 : ((time() - $iptime[0]) / 5*60);
$iptime[0] = time();

file_put_contents ("ip2time.dat" , $iptime);

flock($lock, LOCK_UN);
close($lock);

/*connect to db and escape common vars*/
$connection = mysql_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die ('Could not connect: ' . mysql_error());
mysql_select_db (MYSQL_DB) or die ('Could not select database.');

$name = mysql_real_escape_string ($_GET["name"]);
$val = mysql_real_escape_string ($_GET["val"]);

if (isset($_GET["action"])){
 
	/* Status change - manager.php?name='+name+'&action=status&val='+new_value */
	if ($_GET["action"] == "status" ) {

		$query = "UPDATE nodes SET status=" . $val . " WHERE nodeName='". $name ."';";
		$result = mysql_query ($query, $connection) or die (mysql_error());

		mail (MANAGEMENT_MAIL, "Node status change", "Il nodo $name è passato allo stato $val da ". $_GET['ex_val'] ." su richiesta di ". $_SERVER['REMOTE_ADDR'] .".");

		echo "Lo stato del tuo nodo è stato aggiornato correttamente.<br> Ricarica la pagina del mapserver per vedere le modifiche.<br>";
	}

	/* Ip change */
	if ($_GET["action"] == "ip1" ) {
		$query = "SELECT nodeIp FROM nodes WHERE nodeName='". $name ."';";
		$result = mysql_query ($query, $connection) or die (mysql_error());
		
		$row = mysql_fetch_assoc($result);
		$ip= $row['nodeIp'];	

		echo "<form method=post action='manager.php?action=ip2'>
			Modifica gli ip/subnet associate al nodo. Usa spazio come separazione.<br>
			<textarea name=new_ip>$ip</textarea>
			<input type=hidden name=old_ip value=$ip>
			<input type=hidden name=name value=$name><br>
			<input type=submit value='Modifica classe/i Ip'>
		      <form>"; 
	}
	if ($_GET["action"] == "ip2" ) {
		$ip =  mysql_real_escape_string ($_POST["new_ip"]);

		$query = "UPDATE nodes SET nodeIp='" . $ip . "' WHERE nodeName='". $_POST["name"] ."';";
		$result = mysql_query ($query, $connection) or die (mysql_error());

		mail (MANAGEMENT_MAIL, "Node ip change", "Il nodo $name è passato dall'ip --". $_POST["old_ip"] ."-- a ". $_GET['new_ip'] ." su richiesta di ". $_SERVER['REMOTE_ADDR'] .".");

		echo "Lo stato del tuo nodo è stato aggiornato correttamente.<br> Ricarica la pagina del mapserver per vedere le modifiche.<br>";
	}
}
echo "<br><a href=\"javascript:void(0);\" onclick=\"window.close();\">Chiudi</a>";


?>


