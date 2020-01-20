-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2020 at 10:46 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `name` varchar(255) NOT NULL,
  `password` varchar(60) NOT NULL,
  `admin_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`name`, `password`, `admin_id`) VALUES
('admin', '$2y$10$OUz/MLF.KQIvCB.PnjLcReEliAspB8ePllL0IHq4rHAhfQS2B/5Xu', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `event_id` int(11) NOT NULL,
  `room_id_num` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `creator_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` int(15) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `creation_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`title`, `description`, `start_date`, `end_date`, `event_id`, `room_id_num`, `type_id`, `creator_name`, `email`, `telephone`, `ip`, `creation_time`) VALUES
('Семинар към курс по Python', 'Примерно описание', '2020-01-06 14:00:00', '2020-01-06 17:00:00', 1, 0, 1, 'Иван Иванов', 'IvanIvanov@gmail.com', 123456789, '172.16.254.1', '2020-01-05 14:00:00'),
('Семинар към курс по Python', 'Примерно описание', '2020-01-13 14:00:00', '2020-01-13 17:00:00', 2, 0, 1, 'Иван Иванов', 'IvanIvanov@gmail.com', 123456789, '172.16.254.1', '2020-01-05 14:00:00'),
('Семинар към курс по Python', 'Примерно описание', '2020-01-20 14:00:00', '2020-01-20 17:00:00', 3, 0, 1, 'Иван Иванов', 'IvanIvanov@gmail.com', 123456789, '172.16.254.1', '2020-01-05 14:00:00'),
('Извънреден семинар по киберсигурност', 'Примерно описание 2', '2020-01-14 09:30:00', '2020-01-14 14:45:00', 4, 0, 4, 'Петър Петров', 'PeterPetrov@gmail.com', 987654321, '172.16.452.1', '2020-01-13 09:30:00'),
('Семинар по древни езици', 'Примерно описание 3', '2020-01-21 11:00:00', '2020-01-21 19:00:00', 5, 0, 5, 'Ангел Ангелов', 'agelagelov@gmail.com', 342123578, '172.16.432.1', '2020-01-20 11:00:00'),
('Колективно задание по PHP', 'Примерно описание 4', '2020-01-14 10:00:00', '2020-01-14 16:00:00', 6, 1, 6, 'Владимир Владимиров', 'vladimirvladimirov@gmail.com', 123423432, '172.16.987.1', '2020-01-13 10:00:00'),
('Happy friday', 'Примерно описание 5', '2020-01-10 17:00:00', '2020-01-10 17:15:00', 7, 1, 7, 'Александър Александров', 'alexanderalexandrov@gmail.com', 982254321, '124.16.987.1', '2020-01-07 17:00:00'),
('Happy friday', 'Примерно описание 5', '2020-01-24 17:00:00', '2020-01-24 17:15:00', 8, 1, 7, 'Александър Александров', 'alexanderalexandrov@gmail.com', 982254321, '124.16.987.1', '2020-01-07 17:00:00'),
('Happy friday', 'Примерно описание 5', '2020-01-17 17:00:00', '2020-01-24 17:15:00', 9, 1, 7, 'Александър Александров', 'alexanderalexandrov@gmail.com', 982254321, '124.16.987.1', '2020-01-07 17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(255) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_name`, `image_path`, `color`) VALUES
(0, 'Семинарна зала', 'seminars-hall.jpg', 'dodgerblue'),
(1, 'Зала за колективна работа', 'collective-work-hall.jpg', 'hotpink');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD UNIQUE KEY `start_date` (`start_date`,`end_date`),
  ADD KEY `room_id_num` (`room_id_num`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`room_id_num`) REFERENCES `room` (`room_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
