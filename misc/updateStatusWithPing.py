#! /usr/bin/python
import os
import time
import MySQLdb

# Authors: OrazioPirataDelloSpazio
# This software is released under GPL3
# Ninux.org 2007

# The software monitors the nodes in the network and set the status of the nodes on the map according with the ping responses.

# UNDER CONSTRUCTION: 
# Non settare il valore di polling troppo basso!!! sempre maggiore
# di NUMERO_NODI * NUMERO_PING_PER_NODO * TEMPO_TIMEOUT_UN_PING 
# altrimenti esplode

pingcmd = "ping -c 1 " # you could set how many ping for node here
host = "127.0.0.1"
user="wnmap"
passwd="wnmap"
db="wnmapunstable"
table="nodes"
name_fieldn=8 # the id of the field "name"
ip_fieldn=10 # the id of the field "ip"
status_fieldn=2 # The second field contains the status of the node
status_active=2 # the number associated to the active status
status_nonactive=1 # the number associated to the non-active status
polling_time=1800 # how often we want to ping

#############################################################
while 1:
  try:
     conn = MySQLdb.connect(host,user,passwd,db)
  except MySQLdb.Error, e:
     print "Error %d: %s" % (e.args[0], e.args[1])
  cursore = conn.cursor()
  cursore.execute('SELECT * FROM nodes')
  for record in cursore.fetchall():
     if os.system(pingcmd + record[ip_fieldn]) != 0: # node not active
        print "Node %s seems not to be up" % (record[name_fieldn])
        if record[status_fieldn] == status_active: #update
          cursore.execute('UPDATE nodes SET status=%s WHERE id=%s', (status_nonactive, record[0]))
     else: # node active
       print "Node %s is up" % (record[name_fieldn]) 
       if record[status_fieldn] == status_nonactive:
          cursore.execute('UPDATE nodes SET status=%s WHERE id=%s',( status_active, record[0]))
  time.sleep(polling_time)

