-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2024 at 10:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `roomavailability`
--

CREATE TABLE `roomavailability` (
  `RoomID` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomavailability`
--

INSERT INTO `roomavailability` (`RoomID`, `Date`) VALUES

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `RoomID` int(11) NOT NULL,
  `RoomNumber` int(11) DEFAULT NULL,
  `RoomType` varchar(255) NOT NULL,
  `RoomPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`RoomID`, `RoomNumber`, `RoomType`, `RoomPrice`) VALUES
(1, 101, 'President', 1100000.00),
(2, 102, 'Deluxe', 700000.00),
(3, 103, 'Suite', 470000.00),
(4, 301, 'President', 1100000.00),
(5, 302, 'Deluxe', 700000.00),
(6, 303, 'Suite', 470000.00),
(7, 104, 'Deluxe', 700000.00),
(8, 105, 'Suite', 470000.00),
(9, 106, 'President', 1100000.00),
(10, 304, 'Suite', 470000.00),
(11, 305, 'Deluxe', 700000.00),
(12, 306, 'President', 1100000.00),
(13, 107, 'Deluxe', 700000.00),
(14, 108, 'President', 1100000.00),
(15, 109, 'Suite', 470000.00),
(16, 307, 'President', 1100000.00),
(17, 308, 'Deluxe', 700000.00),
(18, 309, 'Suite', 470000.00),
(19, 110, 'Deluxe', 700000.00);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transactionID` int(11) NOT NULL,
  `transactionDate` date NOT NULL,
  `userIdCardNum` varchar(20) NOT NULL,
  `paymentStatus` enum('Pending','Paid','Cancelled') NOT NULL DEFAULT 'Pending',
  `RoomID` int(11) NOT NULL,
  `transactionPrice` decimal(10,2) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `userTelNum` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transactionID`, `transactionDate`, `userIdCardNum`, `paymentStatus`, `RoomID`, `transactionPrice`, `userName`, `userTelNum`) VALUES


--
-- Indexes for dumped tables
--

--
-- Indexes for table `roomavailability`
--
ALTER TABLE `roomavailability`
  ADD KEY `fk_room_availability` (`RoomID`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`RoomID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transactionID`),
  ADD KEY `RoomID` (`RoomID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `RoomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `roomavailability`
--
ALTER TABLE `roomavailability`
  ADD CONSTRAINT `fk_room_availability` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`RoomID`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`RoomID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
