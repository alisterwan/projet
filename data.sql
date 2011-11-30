-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 29, 2011 at 05:24 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE `information` (
  `hobbies` varchar(255) DEFAULT NULL,
  `job` varchar(255) DEFAULT NULL,
  `music` varchar(255) DEFAULT NULL,
  `films` varchar(255) DEFAULT NULL,
  `books` varchar(255) DEFAULT NULL,
  `aboutme` varchar(255) DEFAULT NULL,
  `favouritedish` varchar(255) DEFAULT NULL,
  `id_user` int(10) NOT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  FOREIGN KEY `id_user` REFERENCES users(`id`)ON DELETE CASCADE,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `information`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `firstname` varchar(40) NOT NULL,
  `surname` varchar(40) NOT NULL,
  `sex` int(3) NOT NULL,
  `address` varchar(40) NULL,
  `city` varchar(40)  NULL,
  `country` varchar(50) NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `mail` varchar(80) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `mail` (`mail`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


