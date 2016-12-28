-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2016 at 10:17 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 7.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digipass`
--

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `img_url` varchar(100) NOT NULL,
  `where_from` varchar(100) NOT NULL,
  `to_see` varchar(60) NOT NULL,
  `check_in_time` datetime NOT NULL,
  `check_out_time` datetime DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `name`, `email`, `phone`, `img_url`, `where_from`, `to_see`, `check_in_time`, `check_out_time`, `status`) VALUES
(1, 'Amir', 'amir@gmail.com', '0703167606', 'visitors/1461933969.jpeg', 'Skylar', 'Principal', '2016-04-29 13:46:09', '2016-05-01 21:54:22', '1'),
(2, 'Amir', 'amirsanni@gmail.com', '07086201801', 'visitors/1461934033.jpeg', 'Ibadan', 'CTO', '2016-04-29 13:47:13', '2016-05-01 21:54:58', '1'),
(3, 'Amir', 'amirsanni@gmail.com', '07086201801', 'visitors/1461938062.jpeg', 'Ibadan', 'CTO', '2016-04-29 14:54:22', '0000-00-00 00:00:00', '0'),
(4, 'Amir', 'amirsanni@gmail.com', '07086201801', 'visitors/1461939439.jpeg', 'Ibadan', 'CTO', '2016-04-29 15:17:19', '2016-05-01 22:02:51', '1'),
(5, 'Zenith Wogwugwu', 'zenith@gmail.com', '08055678904', 'visitors/1461943491.jpeg', 'Kwara', 'Amir', '2016-04-29 16:24:51', '2016-05-01 22:06:21', '1'),
(6, 'Zenith Wogs', 'wogs@g.com', '09083618913', 'visitors/1461943685.jpeg', 'Kwara', 'Amir', '2016-04-29 16:28:05', '2016-05-01 22:03:31', '1'),
(7, 'Amir Sanni', 'amirsanni@gmail.com', '07086201801', 'visitors/1461943759.jpeg', 'Ibadan', 'TK', '2016-04-29 16:29:19', '2016-05-01 23:10:59', '1'),
(8, 'Amir Sanni', 'amirsanni@gmail.com', '07086201801', 'visitors/1461943853.jpeg', 'Ibadan', 'Muneer', '2016-04-29 16:30:53', '2016-05-01 21:56:33', '1'),
(9, 'Amir Sanni', 'amirsanni@gmail.com', '07086201801', 'visitors/1461944076.jpeg', 'Ibadan', 'TK', '2016-04-29 16:34:36', '0000-00-00 00:00:00', '0'),
(10, 'Amir Sanni', 'amirsanni@gmail.com', '07086201801', 'visitors/1461944281.jpeg', 'Ibadan', 'Zenith', '2016-04-29 16:38:01', '2016-05-01 21:37:23', '1'),
(11, 'Ibitoye ', 'tayo@skylar.com.ng', '070879443546', 'visitors/1461948396.jpeg', 'Skylar', 'Gold', '2016-04-29 17:46:36', '2016-05-01 21:57:01', '1'),
(12, 'Balogun', 'shitu@google.com', '08076567541', 'visitors/1461948534.jpeg', 'Microsoft', 'Mark', '2016-04-29 17:48:54', '2016-05-01 21:37:15', '1'),
(13, 'Amir Sanni', 'amirsanni@gmail.com', '07086201801', 'visitors/1461949053.jpeg', 'Ibadan', 'Zenith', '2016-04-29 17:57:33', '0000-00-00 00:00:00', '0'),
(14, 'Amir Sanni', 'amirsanni@gmail.com', '07086201801', 'visitors/1461965493.jpeg', 'Ibadan', 'CEO', '2016-04-29 22:31:33', '2016-05-01 22:05:33', '1'),
(15, 'Amir Sanni', 'amirsanni@gmail.com', '07086201801', 'visitors/1461968120.jpeg', 'Ibadan', 'Zainab', '2016-04-29 23:15:20', '2016-05-01 21:34:32', '1'),
(16, 'Amir Sanni', 'amirsanni@gmail.com', '07086201801', 'visitors/1461968475.jpeg', 'Ibadan', 'Zaynab Jibril', '2016-04-29 23:21:15', '2016-05-01 21:35:18', '1'),
(17, 'Trojan', 'trojanbond@google.ca', '08067542547', 'visitors/1462094129.jpeg', 'Ajah', 'Amir Sanni', '2016-05-01 10:15:29', '2016-05-01 21:55:03', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
