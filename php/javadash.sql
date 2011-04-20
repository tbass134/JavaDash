-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 16, 2011 at 07:11 PM
-- Server version: 5.0.41
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `javadash`
--

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `yelp_id` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` VALUES(1, 'Dunkin Donuts', '123 Main St', '20j209jlkjljlkj');
INSERT INTO `locations` VALUES(2, 'Starbucks', '456 Buzz Blvd', '209dk20d9k');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) NOT NULL auto_increment,
  `user_id` bigint(20) NOT NULL,
  `drink` mediumtext NOT NULL,
  `run_id` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` VALUES(1, 2, 'large coffee', 1);
INSERT INTO `orders` VALUES(2, 3, 'medium tea', 1);
INSERT INTO `orders` VALUES(3, 2, '', 2);
INSERT INTO `orders` VALUES(4, 3, 'bagel with cream cheese', 1);

-- --------------------------------------------------------

--
-- Table structure for table `runs`
--

CREATE TABLE `runs` (
  `id` bigint(20) NOT NULL auto_increment,
  `location_id` bigint(20) NOT NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `user_id` bigint(20) NOT NULL,
  `completed` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `runs`
--

INSERT INTO `runs` VALUES(1, 1, '2011-04-16 16:57:18', 1, 0);
INSERT INTO `runs` VALUES(2, 2, '2011-04-16 17:56:29', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL auto_increment,
  `deviceid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES(1, '5830495830930349t340ijgg0fg990349', 'Jason Lawton');
INSERT INTO `users` VALUES(2, '204gn240g9n4g204ign204gi2n4', 'Tony Hung');
INSERT INTO `users` VALUES(3, '2d1bi3ribbn3rib30ir04004904', 'Jeff Deliberto');
INSERT INTO `users` VALUES(6, 'imamadeupuid', '');
