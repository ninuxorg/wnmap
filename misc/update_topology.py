#! /usr/bin/python
import os
import re
import MySQLdb
from string import atof
# Authors: OrazioPirataDelloSpazio, Don@TuX, Si_Mo
# This software is released under GPL3
# Ninux.org 2008

#This script read the the txt file from olsrd_txtinfo plug-in and insert in the wnmap database active wireless links

#Mysql variables
host = "localhost"
user="wnmap"
passwd="wnmap_pwd"
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
		cursore.execute('SELECT `id` FROM `links` WHERE `node1`="%s" AND `node2`="%s"' % (endpoint1,endpoint2))
		if cursore.fetchall() == () :
			cursore.execute('SELECT `id` FROM `nodes` WHERE `nodeIP`="%s"' % (endpoint1))
			data = cursore.fetchall()
			if data==():
				print endpoint1
				continue
			else:
				print "OK"
			print data[0]
			id_endpoint1=data[0][0]
			cursore.execute('SELECT `id` FROM `nodes` WHERE `nodeIP`="%s"' % (endpoint2))
			data2 = cursore.fetchall()
			if data2==():
				print endpoint2
				continue
			else:
				print "OK2"
			print data2[0]
			id_endpoint2=data2[0][0]
			etx=line.split()[4]
			link_quality=''
			netx=atof(etx)
			if netx <= good_link:
				link_quality = good 
			elif good_link > netx > bad_link:
				link_quality = medium
			elif netx >= bad_link:
				link_quality = bad
			if mysql_query != '': 
				mysql_query=mysql_query + ','
			mysql_query=mysql_query + '(%s,%s,"wifi",%s)' % (id_endpoint1,id_endpoint2,link_quality)
			print mysql_query
	if line.find('Destination IP') != -1:
		parsing=True	
