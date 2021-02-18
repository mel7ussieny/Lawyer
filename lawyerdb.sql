-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2021 at 01:14 PM
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
  `contract` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `name`, `contract`, `address`, `date`) VALUES
(14, 'فوزي محمد فوزي', '998874444', 'ش الجلاء', '2021-02-05'),
(16, 'مصطفي محم', '55874', 'ش النحاس', '2021-02-05'),
(20, 'محمود محمد حسيني حسن', '3569984', '15 سيجر ش جامع البابلي', '2021-02-06'),
(21, 'احمد حسين', '6985744', '15 سيجر ش البابلي', '2021-02-06'),
(22, 'احمد علي محسن', '58664541', '15 سيجر منسجي', '2021-02-06'),
(23, 'احمد محمد حسيني', '2147483647', '15 سيجر ش جامع البابلي', '2021-02-06'),
(24, 'مؤمن وائل', '5988544', 'الجلاء', '2021-02-10'),
(27, 'حسام السيد الشناوي', '3002/د لسنة 2018', '6 ش المنسي - سيجر - اول طنطا', '2021-02-10'),
(28, 'حسام السيد الشناوى', 'الاتلتالت', 'كتلاالنال', '2021-02-10'),
(29, 'أحمد علي حسن', '256354', '15 سيجر ش المنسي', '2021-02-13'),
(30, 'محمود محمد حسيني', '589554', '15 سيجر ش المنسي', '2021-02-13'),
(31, 'محمود محمد الحسيني', '5896441', '15 ش المنسي', '2021-02-15'),
(32, 'محمود محمد حسيني', '589654', '15 سيجر ش المنسي', '2021-02-15');

-- --------------------------------------------------------

--
-- Table structure for table `disputes`
--

CREATE TABLE `disputes` (
  `dispute_id` int(11) NOT NULL,
  `ref_number` varchar(255) NOT NULL,
  `court` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `dclient_id` int(11) NOT NULL,
  `client_type` int(1) NOT NULL,
  `en_name` varchar(255) NOT NULL,
  `en_address` varchar(255) NOT NULL,
  `en_lawyer` varchar(255) NOT NULL,
  `en_type` int(1) NOT NULL,
  `price` int(11) NOT NULL,
  `dis_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `disputes`
--

INSERT INTO `disputes` (`dispute_id`, `ref_number`, `court`, `title`, `district`, `date`, `dclient_id`, `client_type`, `en_name`, `en_address`, `en_lawyer`, `en_type`, `price`, `dis_status`) VALUES
(1, '56985745', 'طنطا', 'ترخيص مباني', 'سيجر', '2021-02-18', 21, 2, 'احمد علي حسن', 'ش الجلاء', 'فريد الديب', 1, 1000, 1),
(2, '599857', 'طنطا', 'قتل', 'سيجر', '2021-02-26', 21, 3, 'احمد محسن فوزي', 'الجلاء', 'مصطفي لطقي', 4, 10000, 1),
(3, '344لسنة 2019', 'اول طنطا', 'صحة توقيع', '4(السبت)', '2021-02-11', 27, 1, 'جمال احمد اسماعيل', 'ثانى طنطا', 'فريد الديب', 2, 100000, 0),
(4, '20200 لس 2018', 'طنطا', 'سجر', 'الرابعه', '2021-02-23', 23, 4, 'محمود محمد حسيني', 'سيجر', 'حسام الشناوي', 3, 20000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dispute_files`
--

CREATE TABLE `dispute_files` (
  `id` int(11) NOT NULL,
  `detail_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dispute_files`
--

INSERT INTO `dispute_files` (`id`, `detail_id`, `name`, `type`, `date`) VALUES
(7, 2, '9240103_photo-1512663150964-d8f43c899f76.jpg', 'image/jpeg', '2021-02-13'),
(10, 1, '4054695_photo-1524504388940-b1c1722653e1.jpg', 'image/jpeg', '2021-02-13'),
(13, 2, '263699_photo-1512663150964-d8f43c899f76.jpg', 'image/jpeg', '2021-02-13'),
(14, 2, '1956138_photo-1524504388940-b1c1722653e1.png', 'image/png', '2021-02-13'),
(15, 3, '5850160_photo-1512663150964-d8f43c899f76.jpg', 'image/jpeg', '2021-02-15'),
(16, 3, '7350509_photo-1524504388940-b1c1722653e1.png', 'image/png', '2021-02-15');

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
(16, '8341199_استخراج-شهادة-ميلاد-كمبيوتر.jpg', 'image/jpeg', '2021-02-06', 23),
(17, '8099937_photo-1512663150964-d8f43c899f76.jpg', 'image/jpeg', '2021-02-10', 24),
(18, '4343690_photo-1524504388940-b1c1722653e1.jpg', 'image/jpeg', '2021-02-10', 24),
(19, '8462137_photo-1524504388940-b1c1722653e1.png', 'image/png', '2021-02-10', 24),
(20, '9245269_photo-1512663150964-d8f43c899f76.jpg', 'image/jpeg', '2021-02-13', 29),
(21, '3572249_photo-1524504388940-b1c1722653e1.jpg', 'image/jpeg', '2021-02-13', 30);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `note` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `dispute_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `note`, `date`, `dispute_id`) VALUES
(5, 'جلسة', '2021-02-17', 1),
(6, 'دفع كهرباء', '2021-02-17', NULL),
(7, 'دفع إجار', '2021-02-17', NULL),
(8, 'جلسه', '2021-02-22', 2);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id_payment` int(11) NOT NULL,
  `dispute_id` int(11) DEFAULT NULL,
  `type` tinyint(1) NOT NULL,
  `display` tinyint(1) NOT NULL,
  `amount` varchar(11) NOT NULL,
  `pay_for` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id_payment`, `dispute_id`, `type`, `display`, `amount`, `pay_for`, `date`) VALUES
(7, NULL, 0, 0, '2500', 'إجار', '2021-02-13'),
(8, 2, 1, 1, '1000', 'a', '2021-02-13'),
(10, NULL, 0, 0, '500', 'كهرباء', '2021-02-13'),
(13, 2, 0, 1, '', '', '0000-00-00'),
(14, 2, 1, 1, '1000', '', '2021-02-13'),
(15, 2, 1, 1, '1000', '', '2021-02-14'),
(16, 1, 0, 1, '', '', '0000-00-00'),
(17, 3, 1, 1, '2000', '', '2021-02-09'),
(18, 4, 0, 1, '3000', '', '2021-02-09'),
(19, 3, 1, 1, '3000', '', '2021-02-24'),
(20, 2, 0, 1, '3000', '', '2021-02-15'),
(21, 1, 1, 1, '1000', '', '0000-00-00'),
(22, 2, 0, 1, '10000', '', '2021-02-08'),
(23, 2, 1, 1, '20000', '', '2021-02-08');

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
(24, '0403524921', 23, '2021-02-06'),
(25, '02555444', 24, '2021-02-10'),
(26, '2554455988', 24, '2021-02-10'),
(29, '01007672792', 27, '2021-02-10'),
(30, '35436865', 28, '2021-02-10'),
(31, '01007672792', 29, '2021-02-13'),
(32, '01007672792', 30, '2021-02-13'),
(33, '01007670792', 31, '2021-02-15'),
(34, '01007672792', 32, '2021-02-15'),
(35, '01023294823', 32, '2021-02-15');

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE `updates` (
  `id` int(11) NOT NULL,
  `dispute_id` int(11) NOT NULL,
  `roll_number` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `notes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `updates`
--

INSERT INTO `updates` (`id`, `dispute_id`, `roll_number`, `date`, `description`, `notes`) VALUES
(17, 2, 6, '2021-02-08', 'حجز الدعوى للحكم', 'لا يوجد'),
(18, 2, 8, '2021-03-16', 'للاستجواب المدعى عليه', 'تم تقديم مذكرة من الخصم و نبه القاضي علي ضرورة تنفيذ حكم الاستجواب'),
(22, 1, 9654152, '2021-02-13', 'شبشش', 'يشسيشس'),
(23, 2, 0, '2021-02-17', '', '');

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
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `disputes`
--
ALTER TABLE `disputes`
  ADD PRIMARY KEY (`dispute_id`),
  ADD KEY `dispute` (`dclient_id`);

--
-- Indexes for table `dispute_files`
--
ALTER TABLE `dispute_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dispute_files` (`detail_id`);

--
-- Indexes for table `identity`
--
ALTER TABLE `identity`
  ADD PRIMARY KEY (`identity_id`),
  ADD KEY `id_client` (`client_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dispute_note` (`dispute_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `dispute_payments` (`dispute_id`);

--
-- Indexes for table `phones`
--
ALTER TABLE `phones`
  ADD PRIMARY KEY (`phone_id`),
  ADD KEY `phone_client` (`client_id`);

--
-- Indexes for table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dispute_id` (`dispute_id`);

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
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `disputes`
--
ALTER TABLE `disputes`
  MODIFY `dispute_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dispute_files`
--
ALTER TABLE `dispute_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `identity`
--
ALTER TABLE `identity`
  MODIFY `identity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id_payment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `phones`
--
ALTER TABLE `phones`
  MODIFY `phone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `updates`
--
ALTER TABLE `updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `disputes`
--
ALTER TABLE `disputes`
  ADD CONSTRAINT `dispute` FOREIGN KEY (`dclient_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dispute_files`
--
ALTER TABLE `dispute_files`
  ADD CONSTRAINT `dispute_files` FOREIGN KEY (`detail_id`) REFERENCES `disputes` (`dispute_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `identity`
--
ALTER TABLE `identity`
  ADD CONSTRAINT `id_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `dispute_note` FOREIGN KEY (`dispute_id`) REFERENCES `disputes` (`dispute_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `dispute_payments` FOREIGN KEY (`dispute_id`) REFERENCES `disputes` (`dispute_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `phones`
--
ALTER TABLE `phones`
  ADD CONSTRAINT `phone_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `updates`
--
ALTER TABLE `updates`
  ADD CONSTRAINT `dispute_id` FOREIGN KEY (`dispute_id`) REFERENCES `disputes` (`dispute_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
