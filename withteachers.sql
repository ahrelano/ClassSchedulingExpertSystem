-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2017 at 01:19 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scheduling`
--

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `building` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`id`, `number`, `building`, `created`, `modified`) VALUES
(1, 1, 'Guard House', '2017-07-09 11:53:07', '2017-07-09 11:53:07'),
(2, 2, 'Senior High School Building 1', '2017-07-09 11:54:17', '2017-07-09 11:54:17'),
(3, 3, 'Senior High School Building 2', '2017-07-09 11:54:36', '2017-07-09 11:54:36'),
(4, 4, 'PMS Building', '2017-07-09 11:54:43', '2017-07-09 11:54:43'),
(5, 5, 'Materials Recovery Facility', '2017-07-09 11:54:55', '2017-07-09 11:54:55'),
(6, 6, 'Prayer Garden', '2017-07-09 11:55:03', '2017-07-09 11:55:03'),
(7, 7, 'Satur Ocampo Building', '2017-07-09 11:55:13', '2017-07-09 11:55:13'),
(8, 8, 'Fil-Chi Building', '2017-07-09 11:55:39', '2017-07-09 11:55:39'),
(9, 9, 'Faculty', '2017-07-09 11:55:47', '2017-07-09 11:55:47'),
(10, 10, 'Health and Beauty Care Room', '2017-07-09 11:56:05', '2017-07-09 11:56:05'),
(11, 11, '4 P\'s Building 1', '2017-07-09 11:56:14', '2017-07-09 11:56:14'),
(12, 12, '4 P\'s Building 2', '2017-07-09 11:56:23', '2017-07-09 11:56:23'),
(13, 13, 'SEDP Building', '2017-07-09 11:56:32', '2017-07-09 11:56:32'),
(14, 14, 'Covered Court', '2017-07-09 11:56:39', '2017-07-09 11:56:39'),
(15, 15, 'Stage', '2017-07-09 11:56:54', '2017-07-09 11:56:54'),
(16, 16, 'Senior High School Laboratory', '2017-07-09 11:57:14', '2017-07-09 11:57:14'),
(17, 17, 'Gov. Lilia Pineda Building', '2017-07-09 11:57:25', '2017-07-09 11:57:25'),
(18, 18, 'Administrative Building', '2017-07-09 11:57:35', '2017-07-09 11:57:35'),
(19, 19, 'SBM Building', '2017-07-09 11:57:42', '2017-07-09 11:57:42'),
(20, 20, 'Alumni Building', '2017-07-09 11:57:49', '2017-07-09 11:57:49'),
(21, 21, 'Pinatubo Building', '2017-07-09 11:57:57', '2017-07-09 11:57:57');

-- --------------------------------------------------------

--
-- Table structure for table `i18n`
--

CREATE TABLE `i18n` (
  `id` int(11) NOT NULL,
  `locale` varchar(6) NOT NULL,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(10) NOT NULL,
  `field` varchar(255) NOT NULL,
  `content` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `position` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `position`, `created`, `modified`) VALUES
(1, 'Master-Teacher 1', '2017-07-09 03:44:54', '2017-07-09 03:44:54'),
(2, 'Teacher 3', '2017-07-09 03:45:03', '2017-07-09 03:45:03'),
(3, 'Teacher 2', '2017-07-09 03:45:12', '2017-07-09 03:45:12'),
(4, 'Teacher 1', '2017-07-09 03:45:22', '2017-07-09 03:45:22'),
(5, 'Principal', '2017-07-09 03:56:33', '2017-07-09 03:56:33'),
(6, 'ADAS 3', '2017-07-09 03:56:46', '2017-07-09 03:56:46'),
(7, 'ADAS 2', '2017-07-09 03:56:55', '2017-07-09 03:56:55'),
(8, 'Guidance Conselor', '2017-07-09 03:57:40', '2017-07-09 03:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `building_id` int(11) NOT NULL,
  `room` varchar(255) NOT NULL,
  `grade` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `building_id`, `room`, `grade`, `created`, `modified`) VALUES
(1, 11, 'J.Rizal', 8, '2017-07-09 12:20:18', '2017-07-09 12:20:18'),
(2, 11, 'St.Mathew', 7, '2017-07-09 12:20:49', '2017-07-09 12:20:49'),
(3, 11, 'Thales', 10, '2017-07-09 12:20:58', '2017-07-09 12:20:58'),
(4, 11, 'W.Shakespear', 9, '2017-07-09 12:21:08', '2017-07-09 12:21:08'),
(5, 8, 'E.Jacinto', 8, '2017-07-09 12:21:27', '2017-07-09 12:21:27'),
(6, 8, 'Aristotle', 10, '2017-07-09 12:21:39', '2017-07-09 12:21:39'),
(7, 12, 'J.Santos', 8, '2017-07-09 12:22:00', '2017-07-09 12:22:00'),
(8, 12, 'Galileo', 10, '2017-07-09 12:22:11', '2017-07-09 12:22:11'),
(9, 12, 'M.Sakoy', 8, '2017-07-09 12:22:26', '2017-07-09 12:22:26'),
(10, 12, 'A.Mabini', 8, '2017-07-09 12:22:42', '2017-07-09 12:22:42'),
(11, 12, 'Newton', 10, '2017-07-09 12:22:52', '2017-07-09 12:22:52'),
(12, 12, 'A.Luna', 8, '2017-07-09 12:23:03', '2017-07-09 12:23:03'),
(13, 12, 'Enclid', 10, '2017-07-09 12:23:15', '2017-07-09 12:23:15'),
(14, 13, 'Science Laboratory', NULL, '2017-07-09 12:23:59', '2017-07-09 12:23:59'),
(15, 13, 'Faculty Room JHS', NULL, '2017-07-09 12:24:07', '2017-07-09 12:24:28'),
(16, 13, 'Guidance Office', NULL, '2017-07-09 12:24:44', '2017-07-09 12:24:44'),
(17, 13, 'Computer Laboratory', NULL, '2017-07-09 12:25:02', '2017-07-09 12:25:02'),
(18, 13, 'TVL-David G11', NULL, '2017-07-09 12:25:19', '2017-07-09 12:25:19'),
(19, 13, 'St.Mark', 7, '2017-07-09 12:25:30', '2017-07-09 12:25:30'),
(20, 13, 'J.Milton', 9, '2017-07-09 12:25:45', '2017-07-09 12:25:45'),
(21, 13, 'D.Silang', 8, '2017-07-09 12:25:56', '2017-07-09 12:25:56'),
(22, 13, 'St.Luke', 7, '2017-07-09 12:26:46', '2017-07-09 12:26:46'),
(23, 13, 'Einstein', 10, '2017-07-09 12:26:59', '2017-07-09 12:26:59'),
(24, 13, 'H.E Room', NULL, '2017-07-09 12:27:09', '2017-07-09 12:27:09'),
(25, 13, 'School Clinic', NULL, '2017-07-09 12:27:17', '2017-07-09 12:27:17'),
(26, 13, 'Edison', 10, '2017-07-09 12:27:32', '2017-07-09 12:27:32'),
(27, 21, 'W.Long Fellow', 9, '2017-07-09 12:28:06', '2017-07-09 12:28:06'),
(28, 21, 'St.James', 7, '2017-07-09 12:28:16', '2017-07-09 12:28:16'),
(29, 21, 'E.A Poe', 9, '2017-07-09 12:28:26', '2017-07-09 12:28:26'),
(30, 21, 'St.Peter', 7, '2017-07-09 12:28:39', '2017-07-09 12:28:39'),
(31, 21, 'N.Keller', 9, '2017-07-09 12:28:53', '2017-07-09 12:28:53'),
(32, 21, 'St.Andrew', 7, '2017-07-09 12:29:30', '2017-07-09 12:29:30'),
(33, 21, 'B.Aquino Jr.', 9, '2017-07-09 12:29:45', '2017-07-09 12:29:45'),
(34, 21, 'St.Jude', 7, '2017-07-09 12:30:18', '2017-07-09 12:30:18'),
(35, 17, 'W.E Henley', 9, '2017-07-09 12:30:36', '2017-07-09 12:30:36'),
(36, 17, 'St.John', 7, '2017-07-09 12:30:47', '2017-07-09 12:30:47'),
(37, 17, 'St.Thomas', 7, '2017-07-09 12:30:56', '2017-07-09 12:30:56'),
(38, 17, 'E.Browning', 9, '2017-07-09 12:31:08', '2017-07-09 12:31:08'),
(39, 4, 'St.Paul', 7, '2017-07-09 12:31:31', '2017-07-09 12:31:31'),
(40, 4, 'F.Bacon', 9, '2017-07-09 12:31:44', '2017-07-09 12:31:44'),
(41, 4, 'St.Simon', 7, '2017-07-09 12:31:58', '2017-07-09 12:31:58'),
(42, 4, 'C.Dickinson', 9, '2017-07-09 12:32:12', '2017-07-09 12:32:12'),
(43, 4, 'St.Philip', 7, '2017-07-09 12:32:24', '2017-07-09 12:32:24'),
(44, 4, 'H.Thoreau', 9, '2017-07-09 12:32:46', '2017-07-09 12:32:46'),
(45, 7, 'A.Bonifacio', 8, '2017-07-09 12:33:29', '2017-07-09 12:33:29'),
(46, 7, 'Library', NULL, '2017-07-09 12:33:46', '2017-07-09 12:33:46'),
(47, 7, 'STEM B', 12, '2017-07-09 12:35:18', '2017-07-13 16:10:15'),
(48, 7, 'STEM A', 12, '2017-07-09 12:35:29', '2017-07-13 16:10:31'),
(49, 7, 'TVL', 12, '2017-07-09 12:35:37', '2017-07-13 15:26:22'),
(50, 7, 'GAS A', 12, '2017-07-09 12:35:50', '2017-07-13 16:11:08'),
(51, 7, 'GAS B', 12, '2017-07-09 12:36:04', '2017-07-13 16:11:20'),
(53, 7, 'GAS A', 11, '2017-07-09 12:37:00', '2017-07-13 16:11:39'),
(54, 7, 'GAS B', 11, '2017-07-09 12:37:08', '2017-07-13 16:11:55'),
(55, 7, 'TVL', 11, '2017-07-09 12:37:16', '2017-07-13 15:26:49'),
(56, 7, 'STEM A', 11, '2017-07-09 12:37:26', '2017-07-13 16:10:42'),
(57, 7, 'STEM B', 11, '2017-07-09 12:37:36', '2017-07-13 16:10:51'),
(58, 7, 'ABM', 11, '2017-07-09 12:37:46', '2017-07-09 12:37:46'),
(59, 7, 'ABM', 12, '2017-07-14 13:49:06', '2017-07-14 13:49:06');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` char(40) NOT NULL,
  `data` text,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `data`, `expires`) VALUES
('4gkpr1ucbave0p9gist1advvt6', 'Config|a:1:{s:4:\"time\";i:1504466938;}Auth|a:0:{}Flash|a:0:{}', 1504468378),
('5h1ojaiqt4q4tb3akm72s99np4', 'Config|a:1:{s:4:\"time\";i:1504419018;}Auth|a:1:{s:4:\"User\";a:2:{s:2:\"id\";i:1;s:8:\"username\";s:5:\"admin\";}}Flash|a:0:{}', 1504420459);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject`, `created`, `modified`) VALUES
(1, 'AP', '2017-07-09 03:39:55', '2017-07-09 03:39:55'),
(2, 'TLE', '2017-07-09 03:40:01', '2017-07-09 03:40:01'),
(3, 'MAPEH', '2017-07-09 03:40:14', '2017-07-09 03:40:14'),
(4, 'Science', '2017-07-09 03:40:19', '2017-07-09 03:40:19'),
(5, 'Math', '2017-07-09 03:40:27', '2017-07-09 03:40:27'),
(6, 'English', '2017-07-09 03:40:34', '2017-07-09 03:40:39'),
(7, 'Filipino', '2017-07-09 03:40:48', '2017-07-09 03:40:48'),
(8, 'P.E', '2017-07-09 03:41:00', '2017-07-13 14:18:32'),
(9, 'Bio. Chem.', '2017-07-13 04:14:07', '2017-07-13 14:18:58'),
(10, 'CPA Master in Business Management', '2017-07-13 04:14:29', '2017-07-13 14:19:25'),
(11, '18 Units Educ', '2017-07-13 04:20:06', '2017-07-13 14:19:41'),
(12, 'Major in Food and Service Management', '2017-07-13 04:20:35', '2017-07-13 14:20:00'),
(13, 'Food Tech.', '2017-07-13 04:20:57', '2017-07-13 14:20:13'),
(14, 'MAE-Math', '2017-07-13 04:21:25', '2017-07-13 14:20:44'),
(15, 'Commerce - Accounting', '2017-07-13 04:23:07', '2017-07-13 14:21:51');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middle` varchar(1) DEFAULT NULL,
  `position_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `loads` int(11) DEFAULT '6',
  `generate` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `lastname`, `firstname`, `middle`, `position_id`, `subject_id`, `loads`, `generate`, `created`, `modified`) VALUES
(1, 'Cinco', 'Carmina', 'O', 1, 6, 5, 1, '2017-07-09 04:00:11', '2017-09-04 03:24:48'),
(2, 'Flores', 'Micheal', 'M', 1, 14, 6, 0, '2017-07-09 04:00:57', '2017-09-04 03:24:01'),
(3, 'Lopes', 'Rosario', 'D', 1, 10, 6, 0, '2017-07-09 04:09:09', '2017-07-13 14:30:52'),
(4, 'Villanueva', 'Abel', 'N', 2, 8, 6, 0, '2017-07-09 04:11:13', '2017-07-09 04:11:13'),
(5, 'Sedino', 'Marion', 'A', 2, 6, 6, 0, '2017-07-09 04:11:31', '2017-07-13 14:32:59'),
(6, 'Pineda', 'Angelito', 'L', 2, 6, 6, 0, '2017-07-09 04:11:49', '2017-07-14 09:09:03'),
(7, 'Sampang', 'Joydee', 'D', 2, 9, 6, 0, '2017-07-09 04:12:05', '2017-07-13 14:33:56'),
(8, 'Tolintino', 'Yvonne', 'L', 2, 15, 6, 0, '2017-07-09 04:12:31', '2017-07-13 14:34:25'),
(9, 'Maniago', 'Mhaika', 'S', 3, 12, 6, 0, '2017-07-09 04:12:54', '2017-07-13 14:34:50'),
(10, 'Malang', 'Jan Ryan', 'V', 3, 13, 6, 0, '2017-07-09 04:13:13', '2017-07-13 14:35:17'),
(11, 'Ocampo', 'Paula Jean', 'S', 3, 11, 6, 0, '2017-07-09 04:13:32', '2017-07-13 14:35:37'),
(12, 'Batac', 'Noel', 'R', 1, 1, 6, 0, '2017-07-09 04:13:49', '2017-07-09 04:13:49'),
(13, 'Dabu', 'Alma', 'R', 2, 1, 6, 0, '2017-07-09 04:14:02', '2017-07-09 04:14:02'),
(14, 'David', 'Lewelyn', 'A', 2, 1, 6, 0, '2017-07-09 04:14:21', '2017-07-09 04:14:21'),
(15, 'Hernandez', 'Josephine', 'V', 4, 1, 6, 0, '2017-07-09 04:14:45', '2017-07-09 04:14:45'),
(16, 'Eda', 'Joren', 'N', 4, 1, 6, 0, '2017-07-09 04:15:01', '2017-07-09 04:15:01'),
(17, 'Bucud', 'Yvonne Cecille', 'T', 4, 1, 6, 0, '2017-07-09 04:15:22', '2017-07-09 04:15:22'),
(18, 'Henson', 'Maria Angelyn', 'P', 1, 2, 6, 0, '2017-07-09 04:15:44', '2017-07-09 04:15:44'),
(19, 'Duque', 'Kristina', 'S', 2, 2, 6, 0, '2017-07-09 04:16:05', '2017-07-09 04:16:05'),
(20, 'David', 'Beatriz', 'M', 3, 2, 6, 0, '2017-07-09 04:16:29', '2017-07-09 04:16:29'),
(21, 'Caballa', 'Bernadeth Irma', 'S', 3, 2, 6, 0, '2017-07-09 04:16:51', '2017-07-09 04:16:51'),
(22, 'Laxamana', 'Joseph', 'P', 4, 2, 6, 0, '2017-07-09 04:17:11', '2017-07-09 04:17:11'),
(23, 'Miranda', 'Willie', 'V', 4, 2, 6, 0, '2017-07-09 04:17:25', '2017-07-09 04:17:25'),
(24, 'Guanlao', 'Cheryll', 'O', 4, 2, 6, 0, '2017-07-09 04:17:51', '2017-07-09 04:17:51'),
(25, 'Utulo', 'Christian May', 'B', 4, 2, 6, 0, '2017-07-09 04:18:13', '2017-07-09 04:18:13'),
(26, 'Serrano', 'Zeny', 'M', 4, 2, 6, 0, '2017-07-09 04:18:37', '2017-07-09 04:18:37'),
(27, 'Soriano', 'Richelle', 'D', 4, 2, 6, 0, '2017-07-09 04:18:53', '2017-07-09 04:18:53'),
(28, 'Guinto', 'Marivic', 'M', 4, 2, 6, 0, '2017-07-09 04:19:07', '2017-07-09 04:19:07'),
(29, 'Caguioa', 'Cheryll', 'A', 4, 2, 6, 0, '2017-07-09 04:19:24', '2017-07-09 04:19:24'),
(30, 'Olegario', 'Hidelisa', 'G', 1, 3, 6, 0, '2017-07-09 04:20:01', '2017-07-09 04:20:01'),
(31, 'Olegrio', 'Willy', 'T', 1, 3, 6, 0, '2017-07-09 04:20:17', '2017-07-09 04:20:17'),
(32, 'Guevarra', 'Nataniel', 'S', 2, 3, 6, 0, '2017-07-09 04:20:34', '2017-07-09 04:20:34'),
(33, 'Lapira', 'Amabelle', 'M', 4, 3, 6, 0, '2017-07-09 04:20:48', '2017-07-09 04:20:48'),
(34, 'Buan', 'Abigail', 'M', 4, 3, 6, 0, '2017-07-09 04:21:20', '2017-07-09 04:21:20'),
(35, 'Malig', 'Irene', '', 4, 3, 6, 0, '2017-07-09 04:21:31', '2017-07-09 04:21:31'),
(36, 'Olegario', 'Weslee', 'G', 4, 3, 6, 0, '2017-07-09 04:21:51', '2017-07-09 04:21:51'),
(37, 'Pascual', 'MA. Aida', 'M', 1, 4, 6, 0, '2017-07-09 04:22:22', '2017-07-09 04:22:22'),
(38, 'Agustin', 'Generosa', 'T', 1, 4, 6, 0, '2017-07-09 04:23:30', '2017-07-09 04:23:30'),
(39, 'Cuevas', 'Consuelo', 'O', 2, 4, 6, 0, '2017-07-09 04:23:49', '2017-07-09 04:23:49'),
(40, 'Dizon', 'Krisha Paula', 'P', 2, 4, 6, 0, '2017-07-09 04:24:25', '2017-07-09 04:24:25'),
(41, 'Flores', 'Alicia', 'G', 3, 4, 6, 0, '2017-07-09 04:24:40', '2017-07-09 04:24:40'),
(42, 'Gumin', 'Richard', 'S', 4, 4, 6, 0, '2017-07-09 04:24:58', '2017-07-09 04:24:58'),
(43, 'Arbas', 'Rowena', 'B', 4, 4, 6, 0, '2017-07-09 04:25:29', '2017-07-09 04:25:29'),
(44, 'Tatel', 'Ionna', '', 4, 4, 6, 0, '2017-07-09 04:25:42', '2017-07-09 04:25:42'),
(45, 'Liwanag', 'Editha', 'G', 1, 5, 6, 0, '2017-07-09 04:26:11', '2017-07-09 04:26:11'),
(46, 'Santos', 'Rafael', 'F', 1, 5, 6, 0, '2017-07-09 04:26:26', '2017-07-09 04:26:26'),
(47, 'David', 'Phany', 'M', 2, 5, 6, 0, '2017-07-09 04:26:37', '2017-07-09 04:26:37'),
(48, 'Antigo', 'Liezel', 'D', 4, 5, 6, 0, '2017-07-09 04:26:50', '2017-07-09 04:26:50'),
(49, 'David', 'Ofelia', 'N', 4, 5, 6, 0, '2017-07-09 04:27:03', '2017-07-09 04:27:03'),
(50, 'Cuevas', 'Roeder John', 'O', 4, 5, 6, 0, '2017-07-09 04:27:28', '2017-07-09 04:27:28'),
(51, 'Ignacio', 'Michelle', 'D', 4, 5, 6, 0, '2017-07-09 04:27:48', '2017-07-09 04:27:48'),
(52, 'Guanlao', 'Liberty', 'B', 1, 6, 6, 0, '2017-07-09 04:28:12', '2017-07-09 04:28:12'),
(53, 'Tungul', 'Flordeliza', 'L', 2, 6, 6, 0, '2017-07-09 04:28:29', '2017-07-09 04:28:29'),
(54, 'Santos', 'Janet', 'N', 4, 6, 6, 0, '2017-07-09 04:28:42', '2017-07-09 04:28:42'),
(55, 'Pineda', 'Ria Angelli', 'G', 4, 6, 6, 0, '2017-07-09 04:29:05', '2017-07-09 04:29:05'),
(56, 'Tuazon', 'Maan Pauline', 'A', 4, 6, 6, 0, '2017-07-09 04:29:27', '2017-07-09 04:29:27'),
(57, 'Miclat', 'John Paul', 'A', 4, 6, 6, 0, '2017-07-09 04:29:45', '2017-07-09 04:29:45'),
(58, 'Isla', 'Abigail', 'T', 4, 6, 6, 0, '2017-07-09 04:30:09', '2017-07-09 04:30:09'),
(59, 'Durana', 'John', 'B', 4, 6, 6, 0, '2017-07-09 04:30:30', '2017-07-09 04:30:30'),
(60, 'De Dios', 'Ella', 'R', 4, 6, 6, 0, '2017-07-09 04:30:43', '2017-07-09 04:30:43'),
(61, 'Ocampo', 'Edgar', '', 4, 6, 6, 0, '2017-07-09 04:30:57', '2017-07-09 04:30:57'),
(62, 'Fahardo', 'Irene', 'M', 1, 7, 6, 0, '2017-07-09 04:31:11', '2017-07-09 04:31:11'),
(63, 'Mendiola', 'Rowena', 'P', 1, 7, 6, 0, '2017-07-09 04:31:29', '2017-07-09 04:31:29'),
(64, 'Pineda', 'Myrna', 'I', 2, 7, 6, 0, '2017-07-09 04:31:48', '2017-07-09 04:31:48'),
(65, 'Lanuza', 'Ronald', 'S', 4, 7, 6, 0, '2017-07-09 04:32:03', '2017-07-09 04:32:03'),
(66, 'Miranda', 'Ailyanne', 'S', 4, 7, 6, 0, '2017-07-09 04:32:33', '2017-07-09 04:32:33'),
(67, 'Tapang', 'Charmaine', 'M', 4, 7, 6, 0, '2017-07-09 04:32:57', '2017-07-09 04:32:57'),
(68, 'Rodriguez', 'Pinky', 'D', 4, 7, 6, 0, '2017-07-09 04:33:11', '2017-07-09 04:33:11'),
(69, 'Lopez', 'Lady Allaine', '', 4, 7, 6, 0, '2017-07-09 04:33:28', '2017-07-09 04:33:28'),
(70, 'Bacani', 'Noel', '', 5, NULL, 0, 0, '2017-07-09 04:33:55', '2017-07-09 04:33:55'),
(71, 'Martin', 'Raquel', 'M', 6, NULL, 0, 0, '2017-07-09 04:34:16', '2017-07-09 04:34:16'),
(72, 'Batac', 'Charlyn', 'S', 7, NULL, 0, 0, '2017-07-09 04:34:27', '2017-07-09 04:34:27'),
(73, 'Marilla', 'Viola', 'D', 8, NULL, 0, 0, '2017-07-09 04:34:40', '2017-07-09 04:34:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$jlCNQSnTjgRjNvLK/LodqOUg0QBpQAAFFeXQ3SnQSAneIcd6Ljmd2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `i18n`
--
ALTER TABLE `i18n`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `I18N_LOCALE_FIELD` (`locale`,`model`,`foreign_key`,`field`),
  ADD KEY `I18N_FIELD` (`model`,`foreign_key`,`field`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `i18n`
--
ALTER TABLE `i18n`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
