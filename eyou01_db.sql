-- phpMyAdmin SQL Dump
-- version 3.3.7deb6
-- http://www.phpmyadmin.net
--
-- Host: sqletud.univ-mlv.fr
-- Generation Time: Nov 25, 2011 at 12:17 PM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-7+squeeze3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eyou01_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `firstname` varchar(40) NOT NULL,
  `surname` varchar(40) NOT NULL,
  `sex` int(3) NOT NULL,
  `address` varchar(40) NOT NULL,
  `city` varchar(40) NOT NULL,
  `country` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `mail` varchar(80) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `mail` (`mail`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE IF NOT EXISTS `information` (
  
  `date_birth` VARCHAR(40) DEFAULT NULL,
  `hobbies` varchar(255) NULL,
  `job` varchar(255) NULL,
  `music` varchar(255) NULL,
  `films` varchar(255) NULL,
  `books` varchar(255) NULL,
  `aboutme` varchar(255) NULL,
  `favouritefood` varchar(255) NULL,
  `id_user` int(10) NOT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   FOREIGN KEY (id_user) REFERENCES users(id)
   ON DELETE CASCADE,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
  
  `name_fr` VARCHAR(50) NOT NULL,
  `description_fr` VARCHAR(255) NULL,
  `name_en` varchar(50) NOT NULL,
  `description_en` VARCHAR(255) NULL,
  `approval` int(10) NULL,
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_ingredients_type`
--

CREATE TABLE IF NOT EXISTS `attribute_ingredients_type` (
  
  `name_fr` VARCHAR(50) NOT NULL,
  `name_en` varchar(50) NOT NULL,
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `attribute_ingredients`
--

CREATE TABLE IF NOT EXISTS `attribute_ingredients` (
  
  `id_type_attribute` int(50) NOT NULL,
  `id_ingredient` int(100) NOT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   FOREIGN KEY (id_ingredient) REFERENCES ingredients(id)
   ON DELETE CASCADE,
   FOREIGN KEY (id_type_attribute) REFERENCES attribute_ingredients_type(id)
   ON DELETE CASCADE,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--


CREATE TABLE IF NOT EXISTS `recipes` (
  
  `name_en` varchar(50) NOT NULL,
  `description_en` VARCHAR(255) NULL,
  `country_origin` VARCHAR(50) NULL,
  `difficulty` int(5) NULL,
  `num_serves` int(5) NULL,
  `duration_preparation` int(5) NULL,
  `duration_cook` int(5) NULL,
  `preparation_en` VARCHAR(255) NULL,
  `creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateupdate` timestamp NULL,
  `permission` int(3) NULL,
  `approval` int(10) NULL,
  `id_user` int(10) NOT NULL,
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT, 
   FOREIGN KEY (id_user) REFERENCES users(id)
   ON DELETE CASCADE,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table structure for table `recipes_ingredients`
--

CREATE TABLE IF NOT EXISTS `recipe_ingredients` (
  
  `id_recipe` int(10) NOT NULL,
  `id_ingredient` int(10) NOT NULL,
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
   FOREIGN KEY (id_recipe) REFERENCES recipes(id)
   ON DELETE CASCADE,
   FOREIGN KEY (id_ingredient) REFERENCES ingredients(id)
   ON DELETE CASCADE,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `recipes_photos`
--

CREATE TABLE IF NOT EXISTS `recipe_photos` (
  
  `id_recipe` int(10) NOT NULL,
  `path_source` varchar(255) NOT NULL,
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
   FOREIGN KEY (id_recipe) REFERENCES recipes(id)
   ON DELETE CASCADE,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
