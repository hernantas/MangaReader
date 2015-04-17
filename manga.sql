-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2015 at 01:53 PM
-- Server version: 5.6.11
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `manga`
--
CREATE DATABASE IF NOT EXISTS `manga` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `manga`;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_manga` int(11) NOT NULL,
  `id_chapter` int(11) NOT NULL,
  `id_page` int(11) NOT NULL,
  `user` int(64) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_manga` (`id_manga`),
  KEY `id_chapter` (`id_chapter`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=124272 ;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;

-- --------------------------------------------------------

--
-- Table structure for table `manga_chapter`
--

CREATE TABLE IF NOT EXISTS `manga_chapter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `id_manga` int(11) NOT NULL,
  `date_add` int(11) NOT NULL,
  `valid` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_manga` (`id_manga`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50524 ;

-- --------------------------------------------------------

--
-- Table structure for table `manga_info`
--

CREATE TABLE IF NOT EXISTS `manga_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_manga` int(11) NOT NULL,
  `image` varchar(256) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `manga_name`
--

CREATE TABLE IF NOT EXISTS `manga_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `add_time` int(11) NOT NULL,
  `last_update` int(11) NOT NULL,
  `read_count` int(11) NOT NULL,
  `completed` enum('0','1') NOT NULL DEFAULT '1',
  `valid` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=487 ;

-- --------------------------------------------------------

--
-- Table structure for table `manga_pict`
--

CREATE TABLE IF NOT EXISTS `manga_pict` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `id_manga` int(11) NOT NULL,
  `id_chapter` int(11) NOT NULL,
  `page` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_chapter` (`id_chapter`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2180315 ;

-- --------------------------------------------------------

--
-- Table structure for table `report_pict`
--

CREATE TABLE IF NOT EXISTS `report_pict` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pict` int(11) NOT NULL,
  `fixed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1759 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL,
  `privilege` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
