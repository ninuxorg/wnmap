#! /usr/bin/env python
import os
import re
import MySQLdb

# Authors: OrazioPirataDelloSpazio, Don@TuX, Si_Mo, Nino
# This software is released under GPL3
# Copyright Ninux.org 2008

#This script reads the the txt file from olsrd_txtinfo plug-in and inserts in the wnmap database active wireless links
#Needs python-mysql and wget packages


#Mysql variables
host = "www.bzzauz.org"
user="wnmap"
passwd="wnmap"
db="wnmapunstable"
table="links"

#Link quality threshold
good_link=1.2
bad_link=2

########################### IMPLEMENTATION #############################

#download topology
os.system(" wget http://127.0.0.1:2006 -q -O topology.txt")

#open file
topology_file=open("topology.txt",'r')
parsing=False
#open 
try:
	conn = MySQLdb.connect(host,user,passwd,db)
except MySQLdb.Error, e:
	print "Error %d: %s" % (e.args[0], e.args[1])
cursore = conn.cursor()
cursore.execute('DELETE FROM `links` WHERE `links`.`type`="wifi"')
assert good_link <=  bad_link, "Good link threshold must be lesser equal than bad link threshold !"
mysql_query=''
link_quality=''
links_etx = {}
#Wireless Nodes ID
id_endpoint1=''
id_endpoint2=''
#quality_values
quality_values = {'good' : 1, 'medium' : 2, 'bad': 3}
for line in topology_file.readlines():
	if parsing:
		if line.isspace():
			print "Executing query on DB..."
			print "MySQL QUERY: INSERT INTO `links` (`node1`,`node2`,`type`,`quality`) VALUES %s" % (mysql_query)
			if mysql_query=='':
				print "NO DATA IN MY_SQL_TABLE"
			else:
				cursore.execute('INSERT INTO `links` (`node1`,`node2`,`type`,`quality`) VALUES %s' % mysql_query )
			parsing=False
			break
		endpoint1=line.split()[0] #IP address of endpoint1
		endpoint2=line.split()[1] #IP address of endpoint2
		print "searching ip: %s and the other ip %s" % (endpoint1,endpoint2) 	
		#look if there is already a link between these two nodes
		cursore.execute('SELECT `id` FROM `links` WHERE `node1`="%s" AND `node2`="%s"' % (endpoint1,endpoint2))
		if cursore.rowcount == 0 : # if no links between nodes...(i.e. no VPN links)
			#cursore.execute('SELECT `id` FROM `nodes` WHERE `nodeIP` LIKE "%s"' % ('%'+endpoint1+'%'))
			cursore.execute('SELECT `id` FROM `nodes` WHERE `nodeIP` REGEXP "%s"' % (endpoint1 + "[[:>:]]") )
			data = cursore.fetchall()
			if cursore.rowcount == 0: 
				print "No nodes with IP: %s in the nodes table" % (endpoint1)
				continue
			id_endpoint1=data[0][0]
			print "%s's Node ip is %d" % (endpoint1,id_endpoint1)	
			#cursore.execute('SELECT `id` FROM `nodes` WHERE `nodeIP` LIKE "%s"' % ('%'+endpoint2+'%'))
			cursore.execute('SELECT `id` FROM `nodes` WHERE `nodeIP` REGEXP "%s"' % (endpoint2 + "[[:>:]]") )
			data = cursore.fetchall()
			if cursore.rowcount == 0: 
				print "No nodes with IP: %s in the nodes table" % (endpoint2)
				continue
			id_endpoint2=data[0][0]
			print "%s's Node ip is %d" % (endpoint2,id_endpoint2)	

			try:
				etx=float(line.split()[4])
			except:
				etx = 999999

			if id_endpoint1 + id_endpoint2 not in links_etx:
				links_etx[id_endpoint2 + id_endpoint1] = etx
				continue #wait the other monodirectional link...
			
			# if found the other link, we can calculate the average etx and prepare the mysql query
			avg_etx = (links_etx[id_endpoint1 + id_endpoint2] + etx )/ 2
			del(links_etx[id_endpoint1 + id_endpoint2])
			
			print "Created link from node %s to node %s with medium etx %f" % (endpoint1, endpoint2, avg_etx)
			if avg_etx <= good_link:
				link_quality = quality_values['good'] 
			elif good_link < avg_etx < bad_link:
				link_quality = quality_values['medium'] 
			elif avg_etx >= bad_link:
				link_quality = quality_values['bad'] 
			if id_endpoint1 != id_endpoint2:	
				if mysql_query != '': 
					mysql_query = mysql_query + ','
				mysql_query = mysql_query + '(%s,%s,"wifi",%s)' % (id_endpoint1,id_endpoint2,link_quality)
			else: 
				print "same ids"		
				
	if line.find('Dest. IP') != -1:
		parsing=True	

