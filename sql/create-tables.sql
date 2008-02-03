-- phpMyAdmin SQL Dump
-- version 2.11.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generated on: 07 Jan, 2008 at 09:43 PM
-- MySQL version: 5.0.44
-- PHP version: 5.2.5-pl1-gentoo

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `wnmapunstable`
--

-- --------------------------------------------------------

--
-- Structure of table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL auto_increment,
  `node1` int(11) NOT NULL default '0',
  `node2` int(11) NOT NULL default '0',
  `type` varchar(4) NOT NULL default 'wifi',
  `quality` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure of table `nodes`
--

CREATE TABLE IF NOT EXISTS `nodes` (
  `id` int(11) NOT NULL auto_increment,
  `createdOn` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL default '0',
  `adminHash` varchar(100) NOT NULL default '',
  `lng` text,
  `lat` text,
  `elevation` text,
  `userRealName` varchar(100) NOT NULL default '',
  `userEmail` varchar(100) NOT NULL default '',
  `nodeName` varchar(100) NOT NULL default '',
  `nodeDescription` text NOT NULL,
  `nodeIP` text NOT NULL,
  `userWebsite` text,
  `userJabber` text,
  `userEmailPublish` tinyint(1) default NULL,
  `streetAddress` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Structure of table `nodes_old`
--

CREATE TABLE IF NOT EXISTS `nodes_old` (
  `id` int(11) NOT NULL auto_increment,
  `createdOn` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL default '0',
  `adminHash` varchar(100) NOT NULL default '',
  `lat` double NOT NULL default '0',
  `lng` double NOT NULL default '0',
  `userRealName` varchar(100) NOT NULL default '',
  `userEmail` varchar(100) NOT NULL default '',
  `nodeName` varchar(100) NOT NULL default '',
  `nodeDescription` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure of table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `password` varchar(60) NOT NULL default '',
  `email` varchar(30) NOT NULL default '',
  `realname` varchar(60) NOT NULL default '',
  `access` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
