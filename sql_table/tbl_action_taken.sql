-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2023 at 09:45 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ezpht`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_action_taken`
--

CREATE TABLE `tbl_action_taken` (
  `action_id` int(11) NOT NULL,
  `action_taken_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_action_taken`
--

INSERT INTO `tbl_action_taken` (`action_id`, `action_taken_name`) VALUES
(1, 'Replaced sim card'),
(2, 'Do nothing (Subject to sim card replacement)'),
(3, 'Properly inserted the sim card'),
(4, 'Replaced GPS device'),
(5, 'Clear GPS device internal memory c/o APSYS'),
(6, 'Reconfigured GPS device firmware c/o APSYS\r\n'),
(7, 'Reconnect/Fix the GPS antenna'),
(8, 'Replaced GPS antenna'),
(9, 'Do nothing (Subject to GPS device antenna replacement)'),
(10, 'Reconnect GPS device power source wiring'),
(11, 'Do nothing (Continue observation)'),
(12, 'Pulled out the GPS device'),
(13, 'Do nothing (Subject to GPS device pull-out)'),
(14, 'Turned on the GPS device'),
(15, 'Replaced new sim card'),
(16, 'Re-inserted the sim card'),
(17, 'Do nothing (Subject to GPS device replacement)'),
(18, 'Reconnect GPS device');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_action_taken`
--
ALTER TABLE `tbl_action_taken`
  ADD PRIMARY KEY (`action_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_action_taken`
--
ALTER TABLE `tbl_action_taken`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
