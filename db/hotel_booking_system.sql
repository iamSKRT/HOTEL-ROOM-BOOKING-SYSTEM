-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2026 at 07:07 AM
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
-- Database: `hotel_booking_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$YourHashedPasswordHere', 'admin@palmwave.com', '2026-05-01 03:31:34');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `number_of_guests` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT 'unpaid',
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `customer_id`, `room_id`, `check_in_date`, `check_out_date`, `number_of_guests`, `total_price`, `status`, `payment_method`, `payment_status`, `booking_date`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '2026-05-07', '2026-05-08', 1, 5500.00, 'pending', 'pending', 'unpaid', '2026-05-07 01:57:55', '2026-05-07 01:57:55', '2026-05-07 01:57:55'),
(2, 1, 4, '2026-05-07', '2026-05-08', 1, 5500.00, 'pending', 'pending', 'unpaid', '2026-05-07 02:00:05', '2026-05-07 02:00:05', '2026-05-07 02:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `created_at`) VALUES
(1, 'wadwada', 'cdalvarez0868ant@student.fatima.edu.ph', '09618765316', NULL, '2026-05-07 01:57:55');

-- --------------------------------------------------------

--
-- Table structure for table `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `reference_number` varchar(100) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `capacity` int(11) NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `available` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `photo_url_2` varchar(255) DEFAULT NULL,
  `photo_url_3` varchar(255) DEFAULT NULL,
  `photo_url_4` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `category_id`, `name`, `description`, `price`, `capacity`, `photo_url`, `amenities`, `available`, `created_at`, `updated_at`, `photo_url_2`, `photo_url_3`, `photo_url_4`) VALUES
(1, 1, 'Coral Breeze Room', 'Charming room with ocean breeze and modern amenities', 3500.00, 2, 'coral.png', 'Wi-Fi, Air-conditioning, TV, Private Bathroom', 1, '2026-05-01 03:31:34', '2026-05-01 03:31:34', NULL, NULL, NULL),
(2, 1, 'Seabreeze Comfort Room', 'Comfortable seaside room with relaxing atmosphere', 3500.00, 2, 'seabreeze.png', 'Wi-Fi, Air-conditioning, TV, Private Bathroom', 1, '2026-05-01 03:31:34', '2026-05-01 03:31:34', NULL, NULL, NULL),
(3, 2, 'Azure Horizon Deluxe', 'Premium deluxe room with stunning horizon views', 5500.00, 3, 'Azure.png', 'Wi-Fi, Air-conditioning, Flat-screen TV, Bathrobe, Premium Toiletries', 1, '2026-05-01 03:31:34', '2026-05-01 03:31:34', NULL, NULL, NULL),
(4, 2, 'Golden Palm Deluxe Room', 'Luxury deluxe room with palm garden view', 5500.00, 3, 'Golden.png', 'Wi-Fi, Air-conditioning, Flat-screen TV, Bathrobe, Premium Toiletries', 1, '2026-05-01 03:31:34', '2026-05-01 03:31:34', NULL, NULL, NULL),
(5, 3, 'Ocean Pearl Executive Suite', 'Elegant suite with ocean pearl design elements', 8500.00, 4, 'Ocean.png', 'Wi-Fi, Air-conditioning, Living Area, Kitchenette, Premium Bathroom', 1, '2026-05-01 03:31:34', '2026-05-01 03:31:34', NULL, NULL, NULL),
(6, 3, 'Sapphire Wave Suite', 'Luxurious suite with wave-inspired décor', 8500.00, 4, 'Sapphire.png', 'Wi-Fi, Air-conditioning, Living Area, Kitchenette, Premium Bathroom', 1, '2026-05-01 03:31:34', '2026-05-01 03:31:34', NULL, NULL, NULL),
(7, 3, 'Sunset Mirage Suite', 'Spectacular suite with sunset views', 8500.00, 4, 'Sunset Mirage Suite.png', 'Wi-Fi, Air-conditioning, Living Area, Kitchenette, Premium Bathroom', 1, '2026-05-01 03:31:34', '2026-05-01 03:31:34', NULL, NULL, NULL),
(8, 4, 'Palm Royale Villa', 'Exclusive villa with royal amenities', 12000.00, 6, 'Palm Royale Villa.png', 'Private Pool, Wi-Fi, Air-conditioning, Living Area, Full Kitchen, Terrace', 1, '2026-05-01 03:31:34', '2026-05-01 03:31:34', NULL, NULL, NULL),
(9, 4, 'Lagoon Crest Villa', 'Private villa overlooking lagoon', 12000.00, 6, 'Lagoon Crest Villa.png', 'Private Pool, Wi-Fi, Air-conditioning, Living Area, Full Kitchen, Terrace', 1, '2026-05-01 03:31:34', '2026-05-01 03:31:34', NULL, NULL, NULL),
(10, 5, 'Royal Tides Oceanfront Penthouse', 'Ultimate luxury penthouse with oceanfront location', 20000.00, 8, 'Royal Tides Oceanfront Penthouse.png', 'Private Infinity Pool, Concierge, Wi-Fi, Living Area, Full Kitchen, Spa', 1, '2026-05-01 03:31:34', '2026-05-01 03:31:34', NULL, NULL, NULL),
(11, 5, 'The Presidential Sky', 'Experience elevated luxury at The Presidential Sky, a sophisticated penthouse suite designed for comfort, elegance, and exclusivity. Featuring breathtaking skyline views, modern interiors, and premium facilities, this luxury space offers a perfect blend of relaxation and prestige for guests seeking a first-class stay.', 30000.00, 20, 'uploads/room_images/The-Presidential-Sky-1779354261.jpg', 'Private Sky Lounge\r\nKing-Size Luxury Bed\r\nFloor-to-Ceiling Glass Windows\r\nPanoramic City View Balcony\r\nSmart TV with Streaming Services\r\nHigh-Speed Wi-Fi\r\nExecutive Work Desk\r\nMini Bar &amp; Coffee Station\r\nWalk-In Closet\r\nJacuzzi Bathtub\r\nRainfall Shower\r\nAir Conditioning\r\nIn-Room Safe\r\nDining Area\r\n24/7 Room Service\r\nComplimentary Toiletries\r\nPrivate Kitchenette\r\nMood Lighting System\r\nVIP Concierge Service\r\nFree Parking Access', 1, '2026-05-21 09:04:21', '2026-05-21 09:06:05', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `room_categories`
--

CREATE TABLE `room_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_categories`
--

INSERT INTO `room_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Standard Rooms', 'Comfortable rooms perfect for budget-conscious travelers', '2026-05-01 03:31:34'),
(2, 'Deluxe Rooms', 'Spacious rooms with premium amenities', '2026-05-01 03:31:34'),
(3, 'Suites', 'Luxurious suites for ultimate comfort', '2026-05-01 03:31:34'),
(4, 'Villas', 'Private villas with exclusive features', '2026-05-01 03:31:34'),
(5, 'Penthouse', 'Premium penthouses with breathtaking views', '2026-05-01 03:31:34');

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
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `room_categories`
--
ALTER TABLE `room_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `room_categories`
--
ALTER TABLE `room_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `payment_transactions_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `room_categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
