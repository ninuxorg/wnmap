#! /usr/bin/python
import os
import time
import MySQLdb

# Ninux.org 2007

pingcmd = "ping -c 1 "
host = "127.0.0.1"
user="root"
passwd="ciao"
db="wnmap"
table="nodes"
ip_fieldn="6" #FIXME add the number of the field that contains the IP Address
status_fieldn="2" # The second field contains the status of the node
status_active="2" 
status_nonactive="1" 
polling_time="3600" # how often we want to ping

while 1:
  try:
     conn = MySQLdb.connect(host,user,passwd,db)
  except MySQLdb.Error, e:
     print "Error %d: %s" % (e.args[0], e.args[1])
  cursore = conn.cursor()
  cursore.execute('SELECT * FROM nodes')
  for record in cursore.fetchall():
     if os.system(pingcmd + record[ip_fieldn]) != 0: # node not active
        if record[status_fieldn] == status_active: #update
          cursore.execute('UPDATE nodes SET status=%s WHERE id=%s', status_nonactive, record[0])
     else: # node active 
       if record[status_fieldn] == status_nonactive:
          cursore.execute('UPDATE nodes SET status=%s WHERE id=%s', status_active, record[0])
  time.sleep(polling_time)

#bye OrazioPirataDelloSpazio :)
