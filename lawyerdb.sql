-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2021 at 10:24 PM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lawyerdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contract` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `name`, `contract`, `address`, `date`) VALUES
(14, 'فوزي محمد فوزي', 998874444, 'ش الجلاء', '2021-02-05'),
(16, 'مصطفي محم', 55874, 'ش النحاس', '2021-02-05'),
(20, 'محمود محمد حسيني حسن', 3569984, '15 سيجر ش جامع البابلي', '2021-02-06'),
(21, 'احمد حسين', 6985744, '15 سيجر ش البابلي', '2021-02-06'),
(22, 'احمد علي محسن', 58664541, '15 سيجر منسجي', '2021-02-06'),
(23, 'احمد محمد حسيني', 2147483647, '15 سيجر ش جامع البابلي', '2021-02-06');

-- --------------------------------------------------------

--
-- Table structure for table `identity`
--

CREATE TABLE `identity` (
  `identity_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `identity`
--

INSERT INTO `identity` (`identity_id`, `name`, `type`, `date`, `client_id`) VALUES
(8, '855835_', '', '2021-02-05', 14),
(10, '8796712_Capture.JPG', 'image/jpeg', '2021-02-05', 16),
(11, '7176155_استخراج-شهادة-ميلاد-كمبيوتر.jpg', 'image/jpeg', '2021-02-06', 20),
(12, '3966747_بطاقة هويه مصرية فارغة.png', 'image/png', '2021-02-06', 20),
(13, '8218378_استخراج-شهادة-ميلاد-كمبيوتر.jpg', 'image/jpeg', '2021-02-06', 21),
(14, '9016531_استخراج-شهادة-ميلاد-كمبيوتر.jpg', 'image/jpeg', '2021-02-06', 22),
(15, '7064627_بطاقة هويه مصرية فارغة.png', 'image/png', '2021-02-06', 23),
(16, '8341199_استخراج-شهادة-ميلاد-كمبيوتر.jpg', 'image/jpeg', '2021-02-06', 23);

-- --------------------------------------------------------

--
-- Table structure for table `phones`
--

CREATE TABLE `phones` (
  `phone_id` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `client_id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `phones`
--

INSERT INTO `phones` (`phone_id`, `phone`, `client_id`, `date`) VALUES
(12, '54141445', 14, '2021-02-05'),
(14, '55563', 16, '2021-02-05'),
(19, '01007672792', 20, '2021-02-06'),
(20, '01023294823', 20, '2021-02-06'),
(21, '01005666', 21, '2021-02-06'),
(22, '01007672792', 22, '2021-02-06'),
(23, '01002665180', 23, '2021-02-06'),
(24, '0403524921', 23, '2021-02-06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `perimission` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user`, `pass`, `name`, `date`, `perimission`) VALUES
(4, 'admin', '0f4dacc1203388b25e0fb2f3825b5772b9e9b811', 'Mahmoud Hussieny', '2021-02-05', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `identity`
--
ALTER TABLE `identity`
  ADD PRIMARY KEY (`identity_id`),
  ADD KEY `id_client` (`client_id`);

--
-- Indexes for table `phones`
--
ALTER TABLE `phones`
  ADD PRIMARY KEY (`phone_id`),
  ADD KEY `phone_client` (`client_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `identity`
--
ALTER TABLE `identity`
  MODIFY `identity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `phones`
--
ALTER TABLE `phones`
  MODIFY `phone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `identity`
--
ALTER TABLE `identity`
  ADD CONSTRAINT `id_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `phones`
--
ALTER TABLE `phones`
  ADD CONSTRAINT `phone_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
