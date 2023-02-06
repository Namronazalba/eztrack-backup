-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2023 at 09:46 AM
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
-- Table structure for table `tbl_pivot_cause_action`
--

CREATE TABLE `tbl_pivot_cause_action` (
  `cause_action_id` int(11) NOT NULL,
  `cause_of_offline_id` int(50) NOT NULL,
  `action_taken_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_pivot_cause_action`
--

INSERT INTO `tbl_pivot_cause_action` (`cause_action_id`, `cause_of_offline_id`, `action_taken_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 3),
(4, 3, 4),
(5, 3, 17),
(6, 4, 4),
(7, 4, 17),
(8, 5, 5),
(9, 6, 6),
(10, 7, 7),
(11, 8, 8),
(12, 8, 9),
(13, 9, 10),
(14, 10, 10),
(15, 11, 11),
(16, 12, 11),
(17, 13, 12),
(18, 13, 13),
(19, 14, 13),
(20, 15, 14),
(21, 16, 15),
(22, 16, 16),
(23, 16, 17),
(24, 11, 18),
(25, 11, 12),
(26, 11, 17),
(27, 11, 4),
(28, 12, 8),
(29, 12, 12),
(30, 12, 17),
(31, 12, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_pivot_cause_action`
--
ALTER TABLE `tbl_pivot_cause_action`
  ADD PRIMARY KEY (`cause_action_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_pivot_cause_action`
--
ALTER TABLE `tbl_pivot_cause_action`
  MODIFY `cause_action_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
