-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2024 at 12:57 PM
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
-- Database: `hostel_allocation`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'melchizedek', '$2y$10$VCbE/PwPZOTAhMF/AyOf8.vhevr0gUC6r9GPOozvKUmKCakP/vXaa');

-- --------------------------------------------------------

--
-- Table structure for table `allocations`
--

CREATE TABLE `allocations` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `allocation_date` date NOT NULL,
  `status` enum('pending','accepted','refused') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allocations`
--

INSERT INTO `allocations` (`id`, `client_id`, `room_id`, `allocation_date`, `status`) VALUES
(1, 3, 1, '2024-07-24', 'accepted'),
(2, 6, 1, '2024-07-24', 'accepted'),
(3, 5, 3, '2024-07-24', 'pending'),
(4, 2, 3, '2024-07-24', 'accepted'),
(5, 18, 4, '2024-07-25', 'accepted'),
(6, 19, 6, '2024-07-28', 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `approved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `username`, `email`, `password`, `approved`) VALUES
(1, 'trevor', 'trevorbanu@gmail.com', '$2y$10$cF0LgaxtbkrsFDHzrs4etOofMt4OujDCSnKMtGjCP23najGag4wR.', 1),
(2, 'shaba', 'shabalangat@gmail.com', '$2y$10$h.ZCXx/Gq1OXj2ts4pjFqulOG/hFgZc9BRpZA3xDpejzl.cLHlgnW', 1),
(3, 'manu', 'manu@gmail.com', '$2y$10$5K3QuGWFSf69PlrVKXk1YuaTkE0vK/gQNWBANLUFTTkYUkvzILleW', 1),
(5, 'adrian', 'adriankaranja@gmail.com', '$2y$10$YOFKb4SuyDHKpjekUftU4eRuJHJndNDzAjL.jvFzUprzC1jksBra.', 1),
(6, 'morgan', 'morgan@gmail.com', '$2y$10$5nxbg//cNILcvrYAhMXNF.tYQ4gpq64/DOvWC.02WYzXVIQN.PGfG', 1),
(8, 'kevo', 'kevo@gmail.com', '$2y$10$3MterJtqer7oVIBUvqaFOuqcAXzpA6RdOxD6JRUqx7dSkTFEiQqwO', 0),
(10, 'lucy', 'lucy@gmail.com', '$2y$10$TRZL7S3pW.Ed085VruRRFOPDdNV2PhLWfWhZloce5QaStPz52Wulq', 0),
(11, 'lucykoitelel', 'lucykoitelel@gmail.com', '$2y$10$.okK4OaOomcLw/GLv.dHkeuueXJoxGsXi0OuGpqIIJqS2mlkKNRfC', 0),
(13, 'namah', 'namah@gmail.com', '$2y$10$Lb6Dr9FSaclxWCvuXvaC6.rqc9QA2QPHohKrDf8yILhkuQFTwgXV6', 0),
(15, 'juma', 'juma@gmail.com', '$2y$10$JgJoBSULhpl6kbq7j3Oxg..eTSluOJANpU20NEYvEXo5p2JY18zg2', 0),
(16, 'koitelel', 'koitelel@gmail.com', '$2y$10$DPETIS70.L98EQfTft0Cs.Snd7GqQFEs99W0aMhJuOhq1/8RUtS5e', 0),
(17, 'dolly', 'dolly@gmail.com', '$2y$10$1/Anhb5uNTQOPzckSezk7OdmTdc9VOo1.yyj.4amLb/e1jRArebaC', 0),
(18, 'super', 'super@gmail.com', '$2y$10$YFJ0mU6hGtfyZgopX1FiQeqX8WuIyckxc4a.pWelyDxrczxLG2G0O', 0),
(19, 'her', 'her@gmail.com', '$2y$10$kC7rnkFMaGLGa6.2KmIK5.O2lL.Klnt3bKjsFqrfN2/9gznchveCO', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `room_category` varchar(50) NOT NULL,
  `bathroom_option` enum('Shared','Ensuite') NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `room_type`, `room_category`, `bathroom_option`, `is_available`, `description`) VALUES
(1, '1', 'Individual', 'Male', 'Shared', 0, 'is available'),
(3, '2', 'Individual', 'Male', 'Shared', 0, 'is available'),
(4, '3', 'Individual', 'Female', 'Shared', 0, 'is available'),
(5, '4', 'Shared', 'Male', 'Shared', 1, 'is available'),
(6, '5', 'Shared', 'Female', 'Ensuite', 0, 'is available'),
(7, '6', 'Individual', 'Female', 'Ensuite', 1, 'is availale'),
(8, '7', 'Individual', 'Male', 'Ensuite', 1, 'is available'),
(9, '8', 'Shared', 'Male', 'Ensuite', 1, 'is available'),
(10, '10', 'Shared', 'Female', 'Shared', 1, 'available');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `allocations`
--
ALTER TABLE `allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_room_id` (`room_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `allocations`
--
ALTER TABLE `allocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allocations`
--
ALTER TABLE `allocations`
  ADD CONSTRAINT `allocations_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `allocations_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
