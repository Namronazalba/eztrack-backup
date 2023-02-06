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
-- Table structure for table `tbl_cause_of_offline`
--

CREATE TABLE `tbl_cause_of_offline` (
  `cause_id` int(50) NOT NULL,
  `cause_of_offline_name` varchar(100) NOT NULL,
  `reason_for_offline_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_cause_of_offline`
--

INSERT INTO `tbl_cause_of_offline` (`cause_id`, `cause_of_offline_name`, `reason_for_offline_id`) VALUES
(1, 'High peak and unstable vehicle voltage (Old truck)', 1),
(2, 'Not properly inserted the sim card', 1),
(3, 'GPS device battery bloated', 2),
(4, 'Defective GPS device component', 2),
(5, 'Clogged GPS device internal memory', 2),
(6, 'GPS device firmware problem', 2),
(7, 'Accidentally hidden the GPS antenna', 3),
(8, 'Defective GPS antenna', 3),
(9, 'Accidentally disconnect the GPS device power source wiring', 4),
(10, 'Intentionally disconnect the GPS device power source wiring', 4),
(11, 'Breakdown/Repair (Intentionally disconnect/remove of vehicle battery)', 4),
(12, 'Standby/No trip (Intentionally disconnect/remove of vehicle battery)', 4),
(13, 'Vehicle was sold (Intentionally disconnect the GPS device power source)', 4),
(14, 'Decommissioned vehicle (Intentionally disconnect the GPS device power source)', 4),
(15, 'Intentionally switched off the GPS device', 5),
(16, 'Intentionally removed the GPS device sim card', 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_cause_of_offline`
--
ALTER TABLE `tbl_cause_of_offline`
  ADD PRIMARY KEY (`cause_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_cause_of_offline`
--
ALTER TABLE `tbl_cause_of_offline`
  MODIFY `cause_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
