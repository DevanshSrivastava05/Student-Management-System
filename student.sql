-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2018 at 10:50 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student`
--

-- --------------------------------------------------------

--
-- Table structure for table `student_acedemic_details`
--

CREATE TABLE `student_acedemic_details` (
  `sad_id` int(10) NOT NULL,
  `sad_student_id` int(10) DEFAULT NULL,
  `sad_course_name` varchar(100) DEFAULT NULL,
  `sad_board` varchar(100) DEFAULT NULL,
  `sad_percentage` varchar(4) DEFAULT NULL,
  `sad_year_of_passing` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student_acedemic_details`
--

INSERT INTO `student_acedemic_details` (`sad_id`, `sad_student_id`, `sad_course_name`, `sad_board`, `sad_percentage`, `sad_year_of_passing`) VALUES
(1, 1, 'X', 'CBSE', '78', '2008'),
(2, 1, 'XII', 'CBSE', '88', '2010'),
(3, 2, 'X', 'CBSE', '78', '2004'),
(4, 2, 'XII', 'CBSE', '88', '2006'),
(5, 3, 'X', 'UP', '68', '2010'),
(6, 4, 'X', 'CBSE', '86', '2006'),
(7, 5, 'X', 'UP', '81', '2006'),
(8, 6, 'X', 'CBSE', '68', '2009'),
(9, 2, 'X', 'CBSE', '78', '2006'),
(10, 9, 'X', 'UP', '98', '2006'),
(11, 10, 'X', 'CBSE', '48', '2003'),
(12, 11, 'X', 'CBSE', '58', '2006'),
(13, 8, 'X', 'UP', '68', '2007'),
(14, 3, 'XII', 'CBSE', '78', '2012'),
(15, 4, 'XII', 'CBSE', '98', '2006'),
(16, 5, 'XII', 'UP', '88', '2006'),
(17, 6, 'XII', 'CBSE', '56', '2011'),
(18, 2, 'XII', 'UP', '77', '2006'),
(19, 9, 'XII', 'CBSE', '66', '2006'),
(20, 10, 'XII', 'UP', '55', '2005'),
(21, 11, 'XII', 'CBSE', '66', '2006'),
(22, 8, 'XII', 'CBSE', '77', '2009');

-- --------------------------------------------------------

--
-- Table structure for table `student_details`
--

CREATE TABLE `student_details` (
  `sd_student_id` int(10) NOT NULL,
  `sd_first_name` varchar(30) DEFAULT NULL,
  `sd_last_name` varchar(30) DEFAULT NULL,
  `sd_dob` date DEFAULT NULL,
  `sd_email` varchar(50) DEFAULT NULL,
  `sd_password` varchar(60) DEFAULT NULL,
  `sd_phone` varchar(15) DEFAULT NULL,
  `sd_gender` enum('Male','Female') DEFAULT NULL,
  `sd_address` varchar(60) DEFAULT NULL,
  `sd_city` varchar(60) DEFAULT NULL,
  `sd_zip_code` varchar(20) DEFAULT NULL,
  `sd_state` varchar(60) DEFAULT NULL,
  `sd_country` varchar(80) DEFAULT NULL,
  `sd_hobbies` varchar(60) DEFAULT NULL,
  `sd_applied_course` varchar(255) DEFAULT NULL,
  `sd_image` varchar(255) DEFAULT NULL,
  `sd_date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sd_date_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student_details`
--

INSERT INTO `student_details` (`sd_student_id`, `sd_first_name`, `sd_last_name`, `sd_dob`, `sd_email`, `sd_password`, `sd_phone`, `sd_gender`, `sd_address`, `sd_city`, `sd_zip_code`, `sd_state`, `sd_country`, `sd_hobbies`, `sd_applied_course`, `sd_image`, `sd_date_added`, `sd_date_modified`) VALUES
(1, 'Arjun', 'Teotia', '1993-01-10', 'ateotia@velsof.com', '21232f297a57a5a743894a0e4a801fc3', '1234567890', 'male', 'e-23, sec 63', 'Noida', '201301', 'UP', 'INDIA', 'Drawing,Singing', 'B.Tech', 'passport.jpg', '2018-01-09 18:30:00', '2018-01-09 18:30:00'),
(2, 'Rahul', 'Singh', '1993-01-10', 'rsingh@velsof.com', '21232f297a57a5a743894a0e4a801fc3', '1234567890', 'male', 'e-23, sec 63', 'Noida', '201301', 'UP', 'INDIA', 'Drawing,Sketching', 'B.Tech', 'rahulpassport.jpg', '2018-01-09 18:30:00', '2018-01-09 18:30:00'),
(3, 'Shivika', 'Chaudhary', '1993-01-10', 'stomar@velsof.com', '21232f297a57a5a743894a0e4a801fc3', '1234567890', 'male', 'e-23, sec 63', 'Noida', '201301', 'UP', 'INDIA', 'Drawing,Singing', 'B.Tech', 'shicipassport.jpg', '2018-01-09 18:30:00', '2018-01-09 18:30:00'),
(4, 'Sunil', 'Singh', '1993-01-10', 'ssignh@velsof.com', '21232f297a57a5a743894a0e4a801fc3', '1234567890', 'male', 'e-23, sec 63', 'Noida', '201301', 'UP', 'INDIA', 'Dancing,Singing', 'B.Tech', 'sunilpassport.jpg', '2018-01-09 18:30:00', '2018-01-09 18:30:00'),
(5, 'Vivek', 'Bisht', '1993-01-10', 'vbisht@velsof.com', '21232f297a57a5a743894a0e4a801fc3', '1234567890', 'male', 'e-23, sec 63', 'Noida', '201301', 'UP', 'INDIA', 'Sketching,Singing', 'B.Tech', 'bivekpassport.jpg', '2018-01-09 18:30:00', '2018-01-09 18:30:00'),
(6, 'Akshit', 'Kansal', '1993-01-10', 'akansal@velsof.com', '21232f297a57a5a743894a0e4a801fc3', '1234567890', 'male', 'e-23, sec 63', 'Noida', '201301', 'UP', 'INDIA', 'Drawing,Dancing', 'B.Tech', 'akshitpassport.jpg', '2018-01-09 18:30:00', '2018-01-09 18:30:00'),
(7, 'Kamal', 'Jain', '1993-01-10', 'kjain@velsof.com', '21232f297a57a5a743894a0e4a801fc3', '1234567890', 'male', 'e-23, sec 63', 'Noida', '201301', 'UP', 'INDIA', 'Sketching,Singing', 'B.Tech', 'jainpassport.jpg', '2018-01-09 18:30:00', '2018-01-09 18:30:00'),
(8, 'Renu', 'Tomar', '1993-01-10', 'rtomar@velsof.com', '21232f297a57a5a743894a0e4a801fc3', '1234567890', 'male', 'e-23, sec 63', 'Noida', '201301', 'UP', 'INDIA', 'Drawing,Dancing', 'B.Tech', 'tomarpassport.jpg', '2018-01-09 18:30:00', '2018-01-09 18:30:00'),
(9, 'Arihant', 'Singh', '1993-01-10', 'ajain@velsof.com', '21232f297a57a5a743894a0e4a801fc3', '1234567890', 'male', 'e-23, sec 63', 'Noida', '201301', 'UP', 'INDIA', 'Sketching,Singing', 'B.Tech', 'ajainpassport.jpg', '2018-01-09 18:30:00', '2018-01-09 18:30:00'),
(10, 'Shashank', 'Agarwal', '1993-01-10', 'sagarwal@velsof.com', '21232f297a57a5a743894a0e4a801fc3', '1234567890', 'male', 'e-23, sec 63', 'Noida', '201301', 'UP', 'INDIA', 'Drawing,Singing', 'B.Tech', 'agarwalpassport.jpg', '2018-01-09 18:30:00', '2018-01-09 18:30:00'),
(11, 'Hemant', 'Singh', '1993-01-10', 'hsingh@velsof.com', '21232f297a57a5a743894a0e4a801fc3', '1234567890', 'male', 'e-23, sec 63', 'Noida', '201301', 'UP', 'INDIA', 'Dancing,Sketching', 'B.Tech', 'singhpassport.jpg', '2018-01-09 18:30:00', '2018-01-09 18:30:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student_acedemic_details`
--
ALTER TABLE `student_acedemic_details`
  ADD PRIMARY KEY (`sad_id`),
  ADD KEY `student_id` (`sad_student_id`);

--
-- Indexes for table `student_details`
--
ALTER TABLE `student_details`
  ADD PRIMARY KEY (`sd_student_id`),
  ADD UNIQUE KEY `email` (`sd_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student_acedemic_details`
--
ALTER TABLE `student_acedemic_details`
  MODIFY `sad_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `student_details`
--
ALTER TABLE `student_details`
  MODIFY `sd_student_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_acedemic_details`
--
ALTER TABLE `student_acedemic_details`
  ADD CONSTRAINT `student_acedemic_details_ibfk_1` FOREIGN KEY (`sad_student_id`) REFERENCES `student_details` (`sd_student_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
