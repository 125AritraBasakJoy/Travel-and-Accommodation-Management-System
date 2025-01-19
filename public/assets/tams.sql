-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2025 at 06:16 PM
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
-- Database: `tams`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_car`
--

CREATE TABLE `booking_car` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_flight`
--

CREATE TABLE `booking_flight` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `seat_number` varchar(20) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_hotel`
--

CREATE TABLE `booking_hotel` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `car_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `model` varchar(100) NOT NULL,
  `make_year` year(4) NOT NULL,
  `seats` int(11) NOT NULL,
  `price_per_hour` decimal(10,2) NOT NULL,
  `carPhoto` longtext NOT NULL,
  `location_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`car_id`, `vendor_id`, `model`, `make_year`, `seats`, `price_per_hour`, `carPhoto`, `location_id`, `created_at`) VALUES
(1, 1, 'Toyota Corolla', '2020', 5, 15.00, '', 1, '2023-01-15 04:00:00'),
(2, 1, 'Honda Civic', '2019', 5, 18.00, '', 2, '2023-02-20 08:30:00'),
(3, 3, 'Ford Mustang', '2021', 4, 25.00, '', 3, '2023-03-05 06:00:00'),
(4, 4, 'Chevrolet Malibu', '2018', 5, 20.00, '', 1, '2023-04-10 10:15:00'),
(5, 5, 'Tesla Model 3', '2022', 5, 30.00, '', 2, '2023-05-25 02:45:00'),
(6, 1, 'BMW X5', '2021', 7, 40.00, '', 3, '2023-06-15 03:30:00'),
(7, 1, 'Mercedes-Benz C-Class', '2020', 5, 35.00, '', 1, '2023-07-01 05:00:00'),
(8, 1, 'Audi A4', '2019', 5, 33.00, '', 2, '2023-08-10 08:00:00'),
(9, 9, 'Hyundai Elantra', '2020', 5, 20.00, '', 3, '2023-09-05 07:45:00'),
(10, 1, 'Kia Seltos', '2021', 5, 22.00, '', 1, '2023-10-12 09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `complain`
--

CREATE TABLE `complain` (
  `complain_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('hotel','car','flight') NOT NULL,
  `item_id` int(11) NOT NULL,
  `complain_text` text NOT NULL,
  `status` enum('pending','resolved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flight`
--

CREATE TABLE `flight` (
  `flight_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `flight_number` varchar(50) NOT NULL,
  `departure_location_id` int(11) NOT NULL,
  `arrival_location_id` int(11) NOT NULL,
  `departure_time` datetime NOT NULL,
  `arrival_time` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`flight_id`, `vendor_id`, `flight_number`, `departure_location_id`, `arrival_location_id`, `departure_time`, `arrival_time`, `price`, `created_at`) VALUES
(1, 1, 'AI101', 1, 2, '2025-01-20 08:00:00', '2025-01-20 11:00:00', 150.00, '2025-01-15 04:00:00'),
(2, 2, 'EK202', 2, 3, '2025-01-21 14:30:00', '2025-01-21 18:30:00', 200.00, '2025-01-15 05:30:00'),
(3, 3, 'BA303', 3, 4, '2025-01-22 09:45:00', '2025-01-22 13:15:00', 180.00, '2025-01-15 06:00:00'),
(4, 4, 'UA404', 4, 5, '2025-01-23 13:00:00', '2025-01-23 16:45:00', 220.00, '2025-01-15 06:30:00'),
(5, 5, 'LH505', 5, 6, '2025-01-24 07:15:00', '2025-01-24 10:30:00', 170.00, '2025-01-15 07:00:00'),
(6, 6, 'CX606', 6, 7, '2025-01-25 18:00:00', '2025-01-25 21:30:00', 250.00, '2025-01-15 07:30:00'),
(7, 7, 'SQ707', 7, 8, '2025-01-26 06:30:00', '2025-01-26 10:00:00', 190.00, '2025-01-15 08:00:00'),
(8, 8, 'AF808', 8, 9, '2025-01-27 11:00:00', '2025-01-27 14:30:00', 160.00, '2025-01-15 08:30:00'),
(9, 9, 'QF909', 9, 10, '2025-01-28 15:00:00', '2025-01-28 19:15:00', 240.00, '2025-01-15 09:00:00'),
(10, 10, 'DL1010', 10, 1, '2025-01-29 08:30:00', '2025-01-29 12:45:00', 210.00, '2025-01-15 09:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `hotel_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `hotelPhoto` longtext NOT NULL,
  `capacity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`hotel_id`, `vendor_id`, `name`, `location_id`, `description`, `price_per_night`, `hotelPhoto`, `capacity`, `created_at`) VALUES
(1, 1, 'Grand Royal Hotel', 1, 'A luxurious 5-star hotel offering modern amenities and exceptional service.', 200.00, '', 200, '2023-01-01 06:00:00'),
(2, 2, 'Beachside Resort', 2, 'A beautiful resort located by the beach, offering a peaceful and relaxing experience.', 150.00, '', 100, '2023-02-15 08:30:00'),
(3, 3, 'Mountain View Lodge', 3, 'A cozy lodge with scenic mountain views, perfect for hiking and nature lovers.', 120.00, '', 50, '2023-03-05 03:45:00'),
(4, 4, 'City Center Inn', 4, 'A modern inn located in the heart of the city, close to shopping and entertainment centers.', 80.00, '', 70, '2023-04-10 10:20:00'),
(5, 5, 'Desert Oasis Hotel', 5, 'A luxurious hotel in the desert offering both relaxation and adventure in a unique environment.', 180.00, '', 120, '2023-05-25 05:00:00'),
(6, 6, 'Lakeview Resort', 6, 'A peaceful resort by the lake, ideal for a family getaway with a variety of water sports.', 130.00, '', 90, '2023-06-15 09:30:00'),
(7, 7, 'Historical Palace Hotel', 7, 'A heritage hotel offering a royal experience, located in an ancient palace.', 250.00, '', 50, '2023-07-01 07:00:00'),
(8, 8, 'Countryside Bungalows', 8, 'Charming bungalows located in the countryside, ideal for a quiet retreat.', 100.00, '', 60, '2023-08-12 04:15:00'),
(9, 9, 'Skyline Hotel', 9, 'A modern hotel with breathtaking views of the city skyline, perfect for business and leisure travelers.', 220.00, '', 150, '2023-09-05 11:30:00'),
(10, 10, 'Forest Retreat Lodge', 10, 'A secluded lodge surrounded by nature, offering peace and tranquility for guests.', 140.00, '', 80, '2023-10-15 02:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `location_id` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `locationPhoto` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`location_id`, `city`, `state`, `country`, `locationPhoto`) VALUES
(1, 'Cox\'s Bazar', NULL, 'Bangladesh', ''),
(2, 'Dhaka', 'Dhaka', 'Bangladesh', 'https://example.com/dhaka.jpg'),
(3, 'Sylhet', 'Sylhet', 'Bangladesh', 'https://example.com/sylhet.jpg'),
(4, 'Sundarbans', 'Khulna', 'Bangladesh', 'https://example.com/sundarbans.jpg'),
(5, 'Chittagong', 'Chattogram', 'Bangladesh', 'https://example.com/chittagong.jpg'),
(6, 'Rangamati', 'Chattogram', 'Bangladesh', 'https://example.com/rangamati.jpg'),
(7, 'Bandarban', 'Chattogram', 'Bangladesh', 'https://example.com/bandarban.jpg'),
(8, 'Rajshahi', 'Rajshahi', 'Bangladesh', 'https://example.com/rajshahi.jpg'),
(9, 'Kuakata', 'Barisal', 'Bangladesh', 'https://example.com/kuakata.jpg'),
(10, 'Jaflong', 'Sylhet', 'Bangladesh', 'https://example.com/jaflong.jpg'),
(11, 'Srimangal', 'Sylhet', 'Bangladesh', 'https://example.com/srimangal.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `ratingandreview`
--

CREATE TABLE `ratingandreview` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('hotel','car','flight') NOT NULL,
  `item_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `vendor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `type` enum('car','hotel','flight') NOT NULL,
  `profile_status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`vendor_id`, `name`, `email`, `password`, `phone`, `type`, `profile_status`, `created_at`) VALUES
(1, 'John Doe', 'john.doe@example.com', 'hashed_password_123', '1234567890', '', 0, '2023-01-15 04:00:00'),
(2, 'Jane Smith', 'jane.smith@example.com', 'hashed_password_456', '0987654321', '', 0, '2023-02-20 08:30:00'),
(3, 'Robert Brown', 'robert.brown@example.com', 'hashed_password_789', '1122334455', '', 0, '2023-03-05 06:00:00'),
(4, 'Emily Davis', 'emily.davis@example.com', 'hashed_password_101', '6677889900', '', 0, '2023-04-10 10:15:00'),
(5, 'Michael Wilson', 'michael.wilson@example.com', 'hashed_password_202', '1230984567', '', 0, '2023-05-25 02:45:00'),
(6, 'Sarah Johnson', 'sarah.johnson@example.com', 'hashed_password_303', '9876543210', '', 0, '2023-06-15 03:30:00'),
(7, 'Daniel Martinez', 'daniel.martinez@example.com', 'hashed_password_404', '5566778899', '', 0, '2023-07-01 05:00:00'),
(8, 'Sophia Anderson', 'sophia.anderson@example.com', 'hashed_password_505', '7788996655', '', 0, '2023-08-10 08:00:00'),
(9, 'William Thompson', 'william.thompson@example.com', 'hashed_password_606', '3344556677', '', 0, '2023-09-05 07:45:00'),
(10, 'Olivia Martinez', 'olivia.martinez@example.com', 'hashed_password_707', '8899001122', '', 0, '2023-10-12 09:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `booking_car`
--
ALTER TABLE `booking_car`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `booking_flight`
--
ALTER TABLE `booking_flight`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `flight_id` (`flight_id`);

--
-- Indexes for table `booking_hotel`
--
ALTER TABLE `booking_hotel`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`car_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `complain`
--
ALTER TABLE `complain`
  ADD PRIMARY KEY (`complain_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `flight`
--
ALTER TABLE `flight`
  ADD PRIMARY KEY (`flight_id`),
  ADD UNIQUE KEY `flight_number` (`flight_number`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `departure_location_id` (`departure_location_id`),
  ADD KEY `arrival_location_id` (`arrival_location_id`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`hotel_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `ratingandreview`
--
ALTER TABLE `ratingandreview`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`vendor_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_car`
--
ALTER TABLE `booking_car`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_flight`
--
ALTER TABLE `booking_flight`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_hotel`
--
ALTER TABLE `booking_hotel`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `complain`
--
ALTER TABLE `complain`
  MODIFY `complain_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `flight_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `hotel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ratingandreview`
--
ALTER TABLE `ratingandreview`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_car`
--
ALTER TABLE `booking_car`
  ADD CONSTRAINT `booking_car_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `booking_car_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `car` (`car_id`);

--
-- Constraints for table `booking_flight`
--
ALTER TABLE `booking_flight`
  ADD CONSTRAINT `booking_flight_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `booking_flight_ibfk_2` FOREIGN KEY (`flight_id`) REFERENCES `flight` (`flight_id`);

--
-- Constraints for table `booking_hotel`
--
ALTER TABLE `booking_hotel`
  ADD CONSTRAINT `booking_hotel_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `booking_hotel_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`hotel_id`);

--
-- Constraints for table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `car_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`),
  ADD CONSTRAINT `car_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`);

--
-- Constraints for table `complain`
--
ALTER TABLE `complain`
  ADD CONSTRAINT `complain_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `flight`
--
ALTER TABLE `flight`
  ADD CONSTRAINT `flight_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`),
  ADD CONSTRAINT `flight_ibfk_2` FOREIGN KEY (`departure_location_id`) REFERENCES `location` (`location_id`),
  ADD CONSTRAINT `flight_ibfk_3` FOREIGN KEY (`arrival_location_id`) REFERENCES `location` (`location_id`);

--
-- Constraints for table `hotel`
--
ALTER TABLE `hotel`
  ADD CONSTRAINT `hotel_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`),
  ADD CONSTRAINT `hotel_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`);

--
-- Constraints for table `ratingandreview`
--
ALTER TABLE `ratingandreview`
  ADD CONSTRAINT `ratingandreview_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
