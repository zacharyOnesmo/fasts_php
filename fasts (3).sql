-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2025 at 10:46 AM
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
-- Database: `fasts`
--

-- --------------------------------------------------------

--
-- Table structure for table `crops`
--

CREATE TABLE `crops` (
  `id` int(11) NOT NULL,
  `crop_name` varchar(100) NOT NULL,
  `season_id` int(11) NOT NULL,
  `rainfall_level` enum('low','medium','high') NOT NULL,
  `temperature_level` enum('low','medium','high') NOT NULL,
  `soil_type` enum('sandy','clay','loamy','silty') NOT NULL,
  `regions` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crops`
--

INSERT INTO `crops` (`id`, `crop_name`, `season_id`, `rainfall_level`, `temperature_level`, `soil_type`, `regions`) VALUES
(1, 'Maize', 2, 'medium', 'medium', 'loamy', 'Most regions'),
(2, 'Beans', 1, 'medium', 'medium', 'loamy', 'Northern, Southern Highlands'),
(3, 'Rice', 1, 'high', 'high', 'clay', 'Mbeya, Morogoro, Shinyanga'),
(4, 'Sunflower', 1, 'low', 'high', 'loamy', 'Dodoma, Singida'),
(5, 'Green Grams', 2, 'low', 'high', 'sandy', 'Central Zone'),
(6, 'Pigeon Peas', 2, 'low', 'high', 'sandy', 'Eastern Zone'),
(7, 'Sorghum', 2, 'low', 'high', 'sandy', 'Dodoma, Shinyanga'),
(8, 'Cassava', 3, 'low', 'high', 'sandy', 'Coastal, Lake Zones'),
(9, 'Sweet Potatoes', 3, 'medium', 'medium', 'loamy', 'Southern Highlands'),
(10, 'Sugarcane', 3, 'high', 'high', 'clay', 'Kilimanjaro, Kagera');

-- --------------------------------------------------------

--
-- Table structure for table `crop_suggestions`
--

CREATE TABLE `crop_suggestions` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `crop_name` varchar(100) NOT NULL,
  `suggested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rainfall_level` enum('low','medium','high') DEFAULT NULL,
  `temperature_level` enum('low','medium','high') DEFAULT NULL,
  `soil_type` enum('sandy','clay','loamy','silty') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seasons`
--

CREATE TABLE `seasons` (
  `id` int(11) NOT NULL,
  `season_name` varchar(50) NOT NULL,
  `months` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seasons`
--

INSERT INTO `seasons` (`id`, `season_name`, `months`, `description`) VALUES
(1, 'Masika (Long Rains)', 'March-May', 'Main planting season for most crops'),
(2, 'Vuli (Short Rains)', 'October-December', 'Secondary growing season'),
(3, 'Kiango (Dry Season)', 'June-September', 'Drought-resistant crops only');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('farmer','officer','admin') NOT NULL DEFAULT 'farmer',
  `region` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `region`, `created_at`) VALUES
(31, 'zikry kamwela', 'zikry', '$2y$10$dUn80P.vwgyR5PT6OMI5aORdP2ymJoGzCcDASBqZJ26uc3hiMTI5m', 'officer', NULL, '2025-05-04 08:32:49'),
(32, 'suleman kamwela', 'suleman', '$2y$10$LVFfnkDRMwgU3tI4KjYu7.h3eOjuZbYf7iEgKaR55lkfM6yIIcK/q', 'farmer', NULL, '2025-05-04 08:35:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `crops`
--
ALTER TABLE `crops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `season_id` (`season_id`);

--
-- Indexes for table `crop_suggestions`
--
ALTER TABLE `crop_suggestions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `seasons`
--
ALTER TABLE `seasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `crops`
--
ALTER TABLE `crops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `crop_suggestions`
--
ALTER TABLE `crop_suggestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seasons`
--
ALTER TABLE `seasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `crops`
--
ALTER TABLE `crops`
  ADD CONSTRAINT `crops_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`);

--
-- Constraints for table `crop_suggestions`
--
ALTER TABLE `crop_suggestions`
  ADD CONSTRAINT `crop_suggestions_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
