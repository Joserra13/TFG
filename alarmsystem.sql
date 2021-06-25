-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 01, 2021 at 01:36 PM
-- Server version: 10.3.27-MariaDB-0+deb10u1
-- PHP Version: 7.3.27-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alarmsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `detable`
--

CREATE TABLE `detable` (
  `detections` text COLLATE utf8_spanish_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `detable`
--

INSERT INTO `detable` (`detections`) VALUES
('0');

-- --------------------------------------------------------

--
-- Table structure for table `registry`
--

CREATE TABLE `registry` (
  `state` int(11) NOT NULL,
  `method` text COLLATE utf8_spanish_ci NOT NULL,
  `detections` int(3) NOT NULL DEFAULT 0,
  `day` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `hour` int(11) NOT NULL,
  `minutes` int(11) NOT NULL,
  `seconds` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `registry`
--

INSERT INTO `registry` (`state`, `method`, `detections`, `day`, `month`, `year`, `hour`, `minutes`, `seconds`) VALUES
(1, 'RFID', 0, 1, 6, 2021, 12, 19, 1),
(0, 'App', 1, 1, 6, 2021, 12, 19, 8),
(1, 'App', 0, 1, 6, 2021, 12, 19, 11),
(0, 'RFID', 0, 1, 6, 2021, 12, 19, 23),
(1, 'RFID', 0, 1, 6, 2021, 12, 19, 28),
(0, 'App', 2, 1, 6, 2021, 12, 19, 54),
(1, 'RFID', 0, 1, 6, 2021, 12, 24, 42),
(0, 'App', 3, 1, 6, 2021, 12, 24, 55),
(1, 'App', 0, 1, 6, 2021, 12, 24, 59),
(0, 'App', 1, 1, 6, 2021, 12, 25, 13);

-- --------------------------------------------------------

--
-- Table structure for table `stateAlarm`
--

CREATE TABLE `stateAlarm` (
  `state` int(11) NOT NULL COMMENT '0:OFF/1:ON'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `stateAlarm`
--

INSERT INTO `stateAlarm` (`state`) VALUES
(0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
