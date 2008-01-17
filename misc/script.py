#! /usr/bin/python
import os
import re
import MySQLdb

#Mysql variables
host = "www.bzzauz.org"
user="wnmap"
passwd="wnmap"
db="wnmapunstable"
table="links"

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
		endpoint1=line.split()[0]
		endpoint2=line.split()[1]
		if endpoint1.find('172.16.') != -1 and endpoint2.find('172.16.') != -1:
			etx=line.split()[4]
			mysql_query=mysql_query + "(%s,%s,'wifi',%s)" % (endpoint1,endpoint2,etx)
	if line.find('Destination IP') != -1:
		cursore.execute('INSERT INTO `links` (`node1`,`node2`,`type`,`quality`) VALUES '+ mysql_query )
		parsing=True

