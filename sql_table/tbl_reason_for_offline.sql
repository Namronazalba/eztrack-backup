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
-- Table structure for table `tbl_reason_for_offline`
--

CREATE TABLE `tbl_reason_for_offline` (
  `reason_id` int(50) NOT NULL,
  `reason_for_offline_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_reason_for_offline`
--

INSERT INTO `tbl_reason_for_offline` (`reason_id`, `reason_for_offline_name`) VALUES
(1, 'Sim Card Problem'),
(2, 'GPS Device Problem'),
(3, 'GPS Antenna Problem'),
(4, 'GPS Device Power Source Disconnected'),
(5, 'Tampered GPS Device'),
(6, 'Tampered GPS Device Sim Card');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_reason_for_offline`
--
ALTER TABLE `tbl_reason_for_offline`
  ADD PRIMARY KEY (`reason_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_reason_for_offline`
--
ALTER TABLE `tbl_reason_for_offline`
  MODIFY `reason_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
