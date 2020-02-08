-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2020 at 05:48 PM
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
  `organizer` varchar(255) NOT NULL,
  `multimedia` tinyint(1) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `creation_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`title`, `description`, `start_date`, `end_date`, `event_id`, `room_id_num`, `type_id`, `creator_name`, `email`, `telephone`, `organizer`, `multimedia`, `ip`, `creation_time`) VALUES
('Семинар към курс по Python', 'Примерно описание', '2020-02-06 14:00:00', '2020-02-06 17:00:00', 1, 0, 1, 'Иван Иванов', 'IvanIvanov@gmail.com', 123456789, 'Иван Иванов', 0, '172.16.254.1', '2020-01-05 14:00:00'),
('Семинар към курс по Python', 'Примерно описание', '2020-02-18 14:00:00', '2020-02-18 17:00:00', 2, 0, 1, 'Иван Иванов', 'IvanIvanov@gmail.com', 123456789, 'Иван Иванов', 1, '192.168.1.100', '2020-02-06 23:38:46'),
('Семинар към курс по Python', 'Примерно описание', '2020-02-20 14:00:00', '2020-02-20 17:00:00', 3, 0, 1, 'Иван Иванов', 'IvanIvanov@gmail.com', 123456789, 'Иван Иванов', 0, '172.16.254.1', '2020-01-05 14:00:00'),
('Извънреден семинар по киберсигурност', 'Примерно описание 2', '2020-02-14 09:30:00', '2020-02-14 14:45:00', 4, 0, 4, 'Петър Петров', 'PeterPetrov@gmail.com', 987654321, 'Петър Петров', 1, '172.16.452.1', '2020-01-13 09:30:00'),
('Семинар по древни езици', 'Примерно описание 3', '2020-02-21 11:00:00', '2020-02-21 19:00:00', 5, 0, 5, 'Ангел Ангелов', 'agelagelov@gmail.com', 342123578, 'Ангел Ангелов', 0, '172.16.432.1', '2020-01-20 11:00:00'),
('Колективно задание по PHP', 'Примерно описание 4', '2020-02-14 10:00:00', '2020-02-14 16:00:00', 6, 1, 6, 'Владимир Владимиров', 'vladimirvladimirov@gmail.com', 123423432, 'Владимир Владимиров', 0, '172.16.987.1', '2020-01-13 10:00:00'),
('Happy friday', 'Примерно описание 5', '2020-02-10 17:00:00', '2020-02-10 17:15:00', 7, 1, 7, 'Александър Александров', 'alexanderalexandrov@gmail.com', 982254321, 'Александър Александров', 0, '124.16.987.1', '2020-01-07 17:00:00'),
('Happy friday', 'Примерно описание 5', '2020-02-17 17:00:00', '2020-02-17 17:15:00', 8, 1, 7, 'Александър Александров', 'alexanderalexandrov@gmail.com', 982254321, 'Александър Александров', 0, '124.16.987.1', '2020-01-07 17:00:00'),
('Happy friday', 'Примерно описание 5', '2020-02-24 17:00:00', '2020-02-24 17:15:00', 9, 1, 7, 'Александър Александров', 'alexanderalexandrov@gmail.com', 982254321, 'Александър Александров', 0, '124.16.987.1', '2020-01-07 17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(255) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `workday_open` time NOT NULL,
  `workday_close` time NOT NULL,
  `weekend_open` time NOT NULL,
  `weekend_close` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_name`, `image_path`, `color`, `workday_open`, `workday_close`, `weekend_open`, `weekend_close`) VALUES
(0, 'Семинарна зала', 'seminars-hall.jpg', '#1E90FF', '08:00:00', '21:00:00', '09:00:00', '17:30:00'),
(1, 'Зала за колективна работа', 'collective-work-hall.jpg', '#FF69B4', '08:00:00', '21:00:00', '09:00:00', '17:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `term_id` int(11) NOT NULL,
  `term_name` varchar(255) NOT NULL,
  `room_id_num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`term_id`, `term_name`, `room_id_num`) VALUES
(1, 'Да уведомя Домакина на НБУ (8110676, в. 2106), за подреждането на залата и броя на необходимите места, един ден предварително.', 1),
(2, 'Да се погрижа участниците да спазват установения от библиотека ред и да опазват библиотечното имущество.', 1),
(3, 'След използване на залата, тя да бъде приведена във вида, в който е заварена.', 1),
(4, 'При повреди нося отговорност за нанесените вреди.', 1),
(5, ' Да уведомя Домакина на НБУ (8110676, в. 2106), за подреждането на залата и броя на необходимите места, един ден предварително.', 0),
(6, 'Да се погрижа участниците да спазват установения от библиотека ред и да опазват библиотечното имущество.', 0),
(7, 'След използване на залата, тя да бъде приведена във вида, в който е заварена.', 0),
(8, 'При повреди нося отговорност за нанесените вреди.', 0);

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
  ADD KEY `room_id_num` (`room_id_num`);
ALTER TABLE `events` ADD FULLTEXT KEY `title` (`title`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`term_id`),
  ADD KEY `room_id_num` (`room_id_num`);

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
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `term_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`room_id_num`) REFERENCES `room` (`room_id`) ON UPDATE CASCADE;

--
-- Constraints for table `terms`
--
ALTER TABLE `terms`
  ADD CONSTRAINT `room_id_num` FOREIGN KEY (`room_id_num`) REFERENCES `room` (`room_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
