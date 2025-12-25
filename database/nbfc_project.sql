-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 25, 2025 at 08:56 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nbfc_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`) VALUES
(1, 'Priyanka Singh', 'admin', 'MyPlain123!'),
(3, 'Priyanka Singh', 'admin1', '$2y$10$D5j7kWm1b1qN5YxG0HnXeOeY9vH0pOqGZ7mFq42P0xI1tS6k0vN1a'),
(5, 'Priyanka Singh', 'admin4', '$2y$10$tSqqQqI.6wEGfmrg3ifClOVqPSlmKdPtCYVkQkol3fPRmE7UL31H.'),
(6, 'Admin User', 'admin2', 'admin22');

-- --------------------------------------------------------

--
-- Table structure for table `emi_records`
--

DROP TABLE IF EXISTS `emi_records`;
CREATE TABLE IF NOT EXISTS `emi_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `counsellor_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `emi_amount` decimal(10,2) NOT NULL,
  `reset_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `date_time` datetime NOT NULL,
  `proof_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emi_records`
--

INSERT INTO `emi_records` (`id`, `student_name`, `counsellor_name`, `emi_amount`, `reset_amount`, `paid_amount`, `date_time`, `proof_image`, `created_at`) VALUES
(1, 'Ram', 'Bharti', 30000.00, 5000.00, 25000.00, '2025-10-17 16:25:00', '0', '2025-10-08 10:59:14'),
(2, 'Ram', 'Bharti', 50000.00, 20000.00, 20000.00, '2025-10-10 16:30:00', '0', '2025-10-08 11:00:35'),
(3, 'Tejassavi Prakash', 'Bharti', 20.00, 10.00, 10.00, '2025-10-17 16:44:00', '0', '2025-10-08 11:17:46'),
(4, 'Ram Singh', 'Bharti', 30000.00, 5000.00, 25000.00, '2025-10-17 16:25:00', '0', '2025-10-08 11:20:54'),
(6, 'Bharti', 'Bharti', 90.00, 80.00, 4656.00, '2025-10-17 17:08:00', 'uploads/1759927267_Screenshot 2025-10-03 182630.png', '2025-10-08 12:41:07'),
(7, 'Teena Khana', 'Bharti', 45436.00, 45.00, 345.00, '2025-10-18 18:13:00', 'uploads/1759927758_Screenshot 2025-10-03 182637.png', '2025-10-08 12:43:15');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `studentName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `courseName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `batch` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `studentEmail` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `studentNumber` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `courseFee` decimal(10,2) NOT NULL,
  `advanceFee` decimal(10,2) DEFAULT '0.00',
  `emiStart` date NOT NULL,
  `emiTenure` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `parentName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `parentMobile` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `aadhar` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `pan` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `account` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `ifsc` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `document_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nbfc_remark` text COLLATE utf8mb4_general_ci,
  `nbfc_status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `studentName`, `courseName`, `batch`, `studentEmail`, `studentNumber`, `courseFee`, `advanceFee`, `emiStart`, `emiTenure`, `parentName`, `parentMobile`, `aadhar`, `pan`, `account`, `ifsc`, `document_path`, `created_at`, `nbfc_remark`, `nbfc_status`) VALUES
(2, 'Ram', 'BCA', 'CSE', 'priyankasingh9336@gmail.com', '56526864', 45000.00, 35000.00, '2025-10-11', '6 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 181249.png', '2025-10-07 07:13:53', '', 'Pending'),
(3, 'Madhav', 'BCA', 'CSE', 'Madhavkumar@gmail.com', '56526864', 45000.00, 35000.00, '2025-10-11', '6 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 181249.png', '2025-10-07 07:14:53', '', 'Pending'),
(4, 'Madhav', 'BCA', 'CSE', 'Madhavkumar@gmail.com', '56526864', 45000.00, 35000.00, '2025-10-11', '6 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 181249.png', '2025-10-07 07:17:14', '', 'Approved'),
(5, 'Madhav', 'BCA', 'CSE', 'Madhavkumar@gmail.com', '56526864', 45000.00, 35000.00, '2025-10-11', '6 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 181249.png', '2025-10-07 07:17:34', '', 'Pending'),
(6, 'Madhav', 'BCA', 'CSE', 'Madhavkumar@gmail.com', '56526864', 45000.00, 35000.00, '2025-10-11', '6 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 181249.png', '2025-10-07 07:20:06', '', 'Pending'),
(7, 'Madhav', 'BCA', 'CSE', 'Madhavkumar@gmail.com', '56526864', 45000.00, 35000.00, '2025-10-11', '6 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 181249.png', '2025-10-07 07:26:34', '', 'Pending'),
(8, 'Madhav', 'BCA', 'CSE', 'Madhavkumar@gmail.com', '56526864', 45000.00, 35000.00, '2025-10-11', '6 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 181249.png', '2025-10-07 07:27:38', '', 'Pending'),
(9, 'Madhav', 'BCA', 'CSE', 'Madhavkumar@gmail.com', '56526864', 45000.00, 35000.00, '2025-10-11', '6 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 181249.png', '2025-10-07 07:32:10', '', 'Pending'),
(10, 'Madhav', 'BCA', 'CSE', 'Madhavkumar@gmail.com', '56526864', 45000.00, 35000.00, '2025-10-11', '6 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 181249.png', '2025-10-07 07:45:30', '', 'Pending'),
(11, 'Gita', 'BCA', 'CSE', 'gita@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182637.png', '2025-10-07 07:46:59', '', 'Pending'),
(12, 'Gita', 'BCA', 'CSE', 'gita@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182637.png', '2025-10-07 07:52:11', '', 'Pending'),
(13, 'Gita', 'BCA', 'CSE', 'gita@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182637.png', '2025-10-07 07:59:42', '', 'Pending'),
(14, 'Sagar', 'BCA', 'CSE', 'sagar@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182637.png', '2025-10-07 08:13:34', '', 'Pending'),
(15, 'Amit Kumar', 'BCA', 'CSE', 'kumaramit@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182637.png', '2025-10-07 08:17:37', '', 'Pending'),
(16, 'Amit Kumar', 'BCA', 'CSE', 'kumaramit@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182637.png', '2025-10-07 08:17:59', '', 'Pending'),
(17, 'Amit Kumar', 'BCA', 'CSE', 'kumaramit@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182637.png', '2025-10-07 08:35:37', '', 'Pending'),
(18, 'Shyam Soni', 'MCA', 'CSE', 'kumarshyam@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182637.png', '2025-10-07 09:34:58', '', 'Pending'),
(19, 'Riya', 'BCA', 'CSE', 'yadavriya@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182637.png', '2025-10-07 09:58:07', '', 'Pending'),
(20, 'Radha', 'BCA', 'CSE', 'radhass@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182637.png', '2025-10-07 10:05:45', '', 'Pending'),
(21, 'Radha', 'BCA', 'CSE', 'radhass@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182637.png', '2025-10-07 10:07:54', '', 'Pending'),
(22, 'Nitya', 'BCA', 'CSE', 'nityass@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/alia.jpg', '2025-10-07 10:44:54', 'done', 'Pending'),
(23, 'Nitya', 'BCA', 'CSE', 'nityass@gmail.com', '657896789', 90000.00, 70000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/alia.jpg', '2025-10-07 12:16:08', 'not done...', 'Pending'),
(24, 'Pooja', 'MCA', 'CSE', 'vermapooja@gmail.com', '78953132', 80000.00, 50000.00, '2025-09-07', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/alia.jpg', '2025-10-07 12:22:14', 'ita done', 'Approved'),
(25, 'Madhav', 'MCA', 'CSE', 'Madhavkumar@gmail.com', '78953132', 90000.00, 50000.00, '2025-10-11', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/alia.jpg', '2025-10-07 12:31:50', '', 'Pending'),
(26, 'Raghav', 'MCA', 'CSE', 'raghavkumar@gmail.com', '78953132', 90000.00, 50000.00, '2025-09-11', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/alia.jpg', '2025-10-07 12:44:17', '', 'Pending'),
(27, 'Raghav', 'MCA', 'CSE', 'raghavkumar@gmail.com', '78953132', 90000.00, 50000.00, '2025-09-11', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/alia.jpg', '2025-10-07 12:44:59', '', 'Pending'),
(28, 'Radha', 'MCA', 'CSE', 'radhass@gmail.com', '56526864', 90000.00, 40000.00, '2025-10-17', '6 Months', 'Kishan Kumar', '98732689', '432467t89', '5657898', '43456790067', '435889054567', 'uploads/alia.jpg', '2025-10-08 07:14:28', NULL, 'Pending'),
(29, 'Ritika', 'MCA', 'CSE', 'ritikakumari@gmail.com', '56526864', 90000.00, 40000.00, '2025-10-17', '6 Months', 'Kishan Kumar', '98732689', '432467t89', '5657898', '43456790067', '435889054567', 'uploads/alia.jpg', '2025-10-08 07:15:30', NULL, 'Pending'),
(30, 'Anjali', 'MCA', 'CSE', 'anjalikumari@gmail.com', '56526864', 90000.00, 40000.00, '2025-10-17', '6 Months', 'Kishan Kumar', '98732689', '432467t89', '5657898', '43456790067', '435889054567', 'uploads/alia.jpg', '2025-10-08 07:18:57', 'Not Done', 'Rejected'),
(31, 'Ram', 'BCA', 'CSE', 'ram@123gmail.com', '56526864', 90000.00, 40000.00, '2025-10-18', '9 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '5657898', '578984576789', '435889054567', 'uploads/alia (4).jpg', '2025-10-08 07:58:06', 'OK', 'Approved'),
(32, 'Nikhil', 'Nikhil', 'Nikhil', 'Nikhil@GMAILCOM', '1122334455', 122.00, 100.00, '2025-10-08', '3 Months', 'Nikhil', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/alia.jpg', '2025-10-08 09:29:18', 'Ok', 'Approved'),
(33, 'Ram', 'BCA', 'CSE', 'ram@123gmail.com', '78953132', 90000.00, 50000.00, '2025-10-18', '3 Months', 'Kishan Kumar', '98732689', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 150340.png', '2025-10-13 05:55:52', NULL, 'Pending'),
(34, 'Ram', 'BCA', 'CSE', 'ram@123gmail.com', '56526864', 90000.00, 50000.00, '2025-10-10', '3 Months', 'Kishan Kumar', '9080908990', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182630.png', '2025-10-13 12:04:52', NULL, 'Pending'),
(35, 'Madhav', 'BCA', 'CSE', 'Madhavkumar@gmail.com', '56526864', 50000.00, 30000.00, '2025-10-12', '6 Months', 'Kishan Kumar', '98732689', '5787e3w2789', '4678098', '578984576789', '435889054567', 'uploads/Screenshot 2025-10-03 182630.png', '2025-10-14 08:00:52', NULL, 'Pending'),
(36, 'Madhav', 'Madhav', 'Madhav', 'madhavkumar@gmail.com', '566787900', 78000.00, 8900.00, '2025-10-10', '9 Months', 'Ram Kumar', '34567890', '34567890', '98765437654', '345678987654', '876543234567', 'uploads/3721672-instagram_108066.png', '2025-10-22 14:23:18', NULL, 'Pending'),
(37, 'Madhav', 'Madhav', 'Madhav', 'madhavkumar@gmail.com', '48519845', 78000.00, 8900.00, '2025-10-10', '3 Months', 'Ram Kumar', '34567890', '34567890', '98765437654', '345678987654', '876543234567', 'uploads/istockphoto-2032721609.jpg', '2025-12-10 15:14:20', NULL, 'Pending');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
