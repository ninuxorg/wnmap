#! /usr/bin/env python

#
#  Copyright (C) 2011 Claudio Pisa <clauz at ninux dot org>
#
#  This is the reengineering of a script written by:
#  OrazioPirataDelloSpazio, Don@TuX, Si_Mo, Nino
#
#  This file is part of WNMap.
#
#  WNMap is free software: you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation, either version 3 of the License, or
#  (at your option) any later version.
#
#  WNMap is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
#  along with WNMap.  If not, see <http://www.gnu.org/licenses/>.
#


import urllib2
import MySQLdb
import sys

# MySQL 
DB_HOST = "localhost"
DB_USER = "wnmap"
DB_PASSWD = "wnmap" 
DB_NAME = "wnmapunstable"
DB_TABLE = "links"

# Link quality thresholds
ETX_GOOD_TRESHOLD = 1.6
ETX_BAD_TRESHOLD = 2.9
ETX_TRESHOLD = 7 # nodes with average etx over this treshold will not be drawn
ETX_GOOD = 1
ETX_MEDIUM = 2
ETX_BAD = 3
ETX_DONT_DRAW = -1


class MySQLWrapper(object):
		CLEAR_SQL = "DELETE FROM `links` WHERE `links`.`network`=\"%s\"" 
		FIND_IP_SQL = 'SELECT `id` FROM `nodes` WHERE `nodeIP` REGEXP "[[:<:]]%s[[:>:]]"' 
		INSERT_LINK_SQL = 'INSERT INTO `links` (`node1`,`node2`,`type`,`quality`,`network`) VALUES (%d, %d, "%s", %d, "%s")' 
		def __init__(self, host, user, passwd, db, city):
				try:
						self.conn = MySQLdb.connect(host, user, passwd, db)
						self.conn.autocommit(True)
						self.tconn = MySQLdb.connect(host, user, passwd, db)
						self.tconn.autocommit(False)
				except Exception:
						print("Database problems. Quitting.")
						sys.exit(2)
				self.city = city
		def __executesql(self, sqlquery, transaction=False):
				print(sqlquery)
				if transaction:
						c = self.tconn.cursor()
				else:
						c = self.conn.cursor()
				c.execute(sqlquery)
				return c
		def clear(self):
				self.__executesql(self.CLEAR_SQL % self.city, transaction=True)
		def findIpId(self, ipaddress):
				"Find an IP address' id"
				c = self.__executesql(self.FIND_IP_SQL % ipaddress)
				if c.rowcount == 0:
						return -1
				data = c.fetchone()
				return data[0]
		def insertLink(self, id1, id2, ltype, quality):
				if id1 > 0 and id2 > 0:
						self.__executesql(self.INSERT_LINK_SQL % (id1, id2, ltype, quality, self.city), transaction=True)
		def commit(self):
				self.tconn.commit()


class AliasManager(object):
		"a MID is an IP alias in OLSR terminology. This class manages all IP addresses"
		def __init__(self, mysqlwrapper):
				self.aliasdict = dict() # keys are ip addresses, values are unique ids
				self.idcounter = -2     # -1 is reserved, we start from -2
				self.mysqlwrapper = mysqlwrapper # a MySQLWrapper istance
				self.unknownIPs = list()
		def addalias(self, ip, alias):
				# all aliases of the same ip share the same unique id, stored as value of aliasdict.
				if self.aliasdict.has_key(ip):
						# if we already have this ip, use the same id for the alias
						ipid = self.aliasdict[ip] 
						self.aliasdict.update({alias: ipid})
				elif self.aliasdict.has_key(alias):
						# if we already have this alias, use the same id for the ip
						ipid = self.aliasdict[alias] 
						self.aliasdict.update({ip: ipid})
				else:
						# we need a new id
						newid = self.idcounter
						self.idcounter -= 1
						self.aliasdict.update({ip: newid, alias: newid})
		def updateIdsFromDb(self):
				"retrieve real ids from the database"
				# first pass: find id to realid (i.e. from the database) correspondances
				old2realId = dict() 
				for ip, id in self.aliasdict.iteritems():
						if not old2realId.has_key(id):
								# search in the DB
								realid = self.mysqlwrapper.findIpId(ip)
								if realid < 0: 
										continue   # no match
								old2realId.update({id: realid})

				# second pass: update 
				for ip, id in self.aliasdict.iteritems():
						try:
								realid = old2realId[id]
								self.aliasdict.update({ip: realid})
						except KeyError:
								pass
		def getIdFromIP(self, ip):
				if self.aliasdict.has_key(ip):
					return self.aliasdict[ip]
				r = self.mysqlwrapper.findIpId(ip)
				if r < 0:
					self.unknownIPs.append(ip)
				return r

		def __str__(self):
				return str(self.aliasdict)


class TopologyParser(object):
		def __init__(self, topology_url, mysqlwrapper):
				self.mysqlwrapper = mysqlwrapper
				print ("Retrieving topology...")
				self.topologylines = urllib2.urlopen(TOPOLOGY_URL).readlines()
				print ("Done...")
				self.linklist = list()
				self.aliasmanager = AliasManager(mysqlwrapper)
		def parse(self):
				"parse the txtinfo plugin output and make two lists: a link list and an alias (MID) list"
				# parse Topology info
				print ("Parsing Toplogy Information...")
				i = 0
				line = self.topologylines[i]
				while line.find('Table: Topology') == -1:
						i += 1
						line = self.topologylines[i]

				i += 2 # skip the heading line
				line = self.topologylines[i]
				while not line.isspace():
						try:
								ipaddr1, ipaddr2, lq, nlq, etx = line.split()
								self.linklist.append((ipaddr1, ipaddr2, float(etx)))
						except Exception:
								pass
						i+=1
						line = self.topologylines[i]
				
				# parse MID info
				print ("Parsing MID Information...")
				while line.find('Table: MID') == -1:
						i += 1
						line = self.topologylines[i]

				i += 1 # skip the heading line
				line = self.topologylines[i]
				while not line.isspace():
						try:
								ipaddr, alias = line.split()
								self.aliasmanager.addalias(ipaddr, alias)
						except Exception:
								pass
						i+=1
						line = self.topologylines[i]

		def processAndDraw(self):
				"should be called after calling parse()"
				# retrieve id info from the DB
				self.aliasmanager.updateIdsFromDb()

				linkdict = dict()
				for ipaddr1, ipaddr2, etx in self.linklist:
						id1 = self.aliasmanager.getIdFromIP(ipaddr1)
						id2 = self.aliasmanager.getIdFromIP(ipaddr2)
						if id1 < id2:
								k = (id1, id2)
						else:
								k = (id2, id1)

						if linkdict.has_key(k):
								etx0 = linkdict[k]
								linkdict.update({k: (etx0 + etx)*0.5}) # average
						else:
								linkdict.update({k: etx})

				# draw the links
				self.mysqlwrapper.clear() # clear the map
				for k, etx in linkdict.iteritems():
						if etx >= ETX_TRESHOLD:
								continue # don't draw this link

						if etx >= ETX_BAD_TRESHOLD:
								lq = ETX_BAD
						elif etx <= ETX_GOOD_TRESHOLD:
								lq = ETX_GOOD
						else:
								lq = ETX_MEDIUM
						id1, id2 = k
						self.mysqlwrapper.insertLink(id1, id2, 'wifi', lq)

				self.mysqlwrapper.commit()

				print("Unknown IP Addressess:")
				print(self.aliasmanager.unknownIPs)


if __name__ == "__main__":
		try:
				dbcity = sys.argv[1]
				topologyurl = sys.argv[2]
		except Exception:
				print("Usage: %s <city name> <olsrd txtinfo URL>\nExample: %s roma http://127.0.0.1:2006/all" % (sys.argv[0]), sys.argv[0])
				sys.exit(1)
		msr = MySQLWrapper(DB_HOST, DB_USER, DB_PASSWD, DB_NAME, dbcity)
		tp = TopologyParser(topologyurl, msr)
		tp.parse()
		tp.processAndDraw()

