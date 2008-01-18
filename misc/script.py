#! /usr/bin/python
import os
import re
import MySQLdb

# Authors: OrazioPirataDelloSpazio, Don@TuX, Si_Mo
# This software is released under GPL3
# Ninux.org 2008

#This script read the the txt file from olsrd_txtinfo plug-in and insert in the wnmap database active wireless links

#Mysql variables
host = "www.bzzauz.org"
user="wnmap"
passwd="wnmap"
db="wnmapunstable"
table="links"

#Link quality threshold
good_link=1.2
bad_link=2
good=1
medium=2
bad=3

#Wireless Nodes ID
id_endpoint1=''
id_endpoint2=''

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
mysql_query=''
for line in topology_file.readlines():
	if parsing:
		if line.isspace():
			parsing=False
			break
		endpoint1=line.split()[0] #IP address of endpoint1
		endpoint2=line.split()[1] #IP address of endpoint2
		if endpoint1.find('172.16.') != -1 and endpoint2.find('172.16.') != -1:
			id_endpoint1=cursore.execute('SELECT `id` FROM `nodes` WHERE `nodeIP`="%s"' % (endpoint1))
			id_endpoint2=cursore.execute('SELECT `id` FROM `nodes` WHERE `nodeIP`="%s"' % (endpoint2))
			etx=line.split()[4]
			link_quality=''
			if etx <= good_link:
				etx = good 
			elif good_link > etx > bad_link:
				etx = medium
			elif etx >= bad_link:
				etx = bad
			if mysql_query != '': 
				mysql_query=mysql_query + ','
			mysql_query=mysql_query + "(%s,%s,'wifi',%s)" % (id_endpoint1,id_endpoint2,etx)
	if line.find('Destination IP') != -1:
		if id_endpoint1 != '' or id_endpoint2 != '' :
			print ("ESECUZIONE QUERY")
			cursore.execute('INSERT INTO `links` (`node1`,`node2`,`type`,`quality`) VALUES %s' % mysql_query )
		parsing=True	
