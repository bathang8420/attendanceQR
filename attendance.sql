-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2022 at 10:38 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_user_name` varchar(100) NOT NULL,
  `admin_password` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `admin_user_name`, `admin_password`) VALUES
(1, 'admin', '$2y$10$D74Zy1qMkATvmGRoVeq7hed4ajWof2aqDGnEaD3yPHABA.p.e7f4u');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attendance_qr`
--

CREATE TABLE `tbl_attendance_qr` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `qr_id` varchar(255) NOT NULL,
  `scan_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_attendance_qr`
--

INSERT INTO `tbl_attendance_qr` (`id`, `student_id`, `qr_id`, `scan_time`) VALUES
(10, 20183834, 'e63ddcaf4a0042c2430303ead151b63c', '2021-12-28 16:42:05'),
(11, 20183834, 'f30c3ced57be29e0afa549b3bac31836', '2022-01-04 08:31:12'),
(12, 20183849, 'd4aa3014dc41e533e22a38a804db648f', '2022-01-11 08:28:50'),
(13, 20183834, '6f1840e0dc8f9b44106e39ed62250510', '2021-12-30 06:51:00'),
(14, 20183834, '1ca6d151c629008a3d298b7447ee5302', '2022-01-06 06:51:30'),
(15, 20183834, '848f970dad30ea512fb08874036d327b', '2022-01-03 15:06:15'),
(16, 20183834, '93e7bc78a86cd1bc9879bbfc5a24731e', '2022-01-10 15:30:42'),
(17, 20183757, '93e7bc78a86cd1bc9879bbfc5a24731e', '2022-01-10 15:31:01'),
(18, 20183834, '45f28f936148c4f66da2c249687a4fbc', '2021-12-21 08:31:16'),
(19, 20183834, 'b6983ed7ba5b26b0fd11d547705ba2b4', '2022-01-17 15:42:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_class`
--

CREATE TABLE `tbl_class` (
  `class_id` int(11) NOT NULL,
  `course_id` varchar(11) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_class`
--

INSERT INTO `tbl_class` (`class_id`, `course_id`, `teacher_id`) VALUES
(128717, 'IT4651', 5),
(128723, 'IT4409', 4),
(128747, 'IT4735', 2),
(128844, 'IT4785', 4),
(129654, 'IT4735', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_class_student`
--

CREATE TABLE `tbl_class_student` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_class_student`
--

INSERT INTO `tbl_class_student` (`id`, `class_id`, `student_id`) VALUES
(6, 128717, 20183679),
(7, 128717, 20183730),
(8, 128717, 20183834),
(9, 128723, 20183834),
(10, 128844, 20183796),
(11, 128844, 20183733),
(12, 128723, 20183849),
(13, 128844, 20183834),
(15, 129654, 20183757),
(16, 129654, 20183834),
(17, 129654, 20183679),
(18, 129654, 20183730),
(19, 128747, 20183730);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_course`
--

CREATE TABLE `tbl_course` (
  `course_id` varchar(11) NOT NULL,
  `course_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_course`
--

INSERT INTO `tbl_course` (`course_id`, `course_name`) VALUES
('IT4409', 'Công nghệ Web và dịch vụ trực tuyến'),
('IT4651', 'Thiết kế và triển khai mạng IP'),
('IT4735', 'IoT và ứng dụng'),
('IT4785', 'Phát triển ứng dụng cho thiết bị di động');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_qr`
--

CREATE TABLE `tbl_qr` (
  `qr_id` char(255) NOT NULL,
  `class_id` int(11) NOT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_qr`
--

INSERT INTO `tbl_qr` (`qr_id`, `class_id`, `date_created`) VALUES
('1ca6d151c629008a3d298b7447ee5302', 128844, '2022-01-06 06:51:07'),
('45f28f936148c4f66da2c249687a4fbc', 128723, '2021-12-21 08:30:56'),
('6f1840e0dc8f9b44106e39ed62250510', 128844, '2021-12-30 06:50:02'),
('848f970dad30ea512fb08874036d327b', 129654, '2022-01-03 15:05:21'),
('93e7bc78a86cd1bc9879bbfc5a24731e', 129654, '2022-01-10 15:30:12'),
('a349805916a6d32f8ec02510822590f0', 128747, '2022-01-17 15:16:03'),
('b6983ed7ba5b26b0fd11d547705ba2b4', 129654, '2022-01-17 15:42:21'),
('c4836fff271eccb0b08f2a1be1e25229', 128844, '2022-01-16 18:46:41'),
('d4aa3014dc41e533e22a38a804db648f', 128723, '2022-01-11 08:28:40'),
('e63ddcaf4a0042c2430303ead151b63c', 128723, '2021-12-28 16:41:41'),
('f30c3ced57be29e0afa549b3bac31836', 128723, '2022-01-04 08:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_schedule`
--

CREATE TABLE `tbl_schedule` (
  `schedule_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `dow` varchar(20) NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `location` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_schedule`
--

INSERT INTO `tbl_schedule` (`schedule_id`, `class_id`, `date_from`, `date_to`, `dow`, `time_from`, `time_to`, `location`) VALUES
(4, 128717, '2021-09-27', '2022-01-31', '1', '06:45:00', '10:05:00', 'TC-302'),
(5, 128723, '2021-09-27', '2022-01-31', '2', '08:25:00', '11:45:00', 'TC-304'),
(6, 128747, '2021-09-27', '2022-01-31', '5', '09:20:00', '11:45:00', 'TC-307'),
(7, 128844, '2021-09-27', '2022-01-31', '4', '06:45:00', '10:05:00', 'TC-307'),
(8, 129654, '2022-01-01', '2022-01-31', '1', '15:05:00', '17:30:00', 'TC-307');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_student`
--

CREATE TABLE `tbl_student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_dob` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_student`
--

INSERT INTO `tbl_student` (`student_id`, `student_name`, `student_dob`) VALUES
(20183679, 'Lê Minh Anh', '2000-12-28'),
(20183691, 'Vũ Trần Đức Anh', '2000-08-16'),
(20183724, 'Nguyễn Đình Hải Dương', '2020-11-11'),
(20183730, 'Nguyễn Trọng Hải', '2000-10-15'),
(20183733, 'Nguyễn Hồng Hạnh', '2000-02-18'),
(20183757, 'Lê Hà Hưng', '2000-03-11'),
(20183796, 'Ngô Đức Minh', '2000-07-06'),
(20183810, 'Doãn Minh Phụng', '2000-02-04'),
(20183834, 'Trịnh Bá Thắng', '2000-04-08'),
(20183843, 'Nguyễn Thị Trang', '2000-01-22'),
(20183849, 'Nguyễn Đình Tuấn', '2000-05-23'),
(20183861, 'Lê Thị Yên', '2000-11-12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teacher`
--

CREATE TABLE `tbl_teacher` (
  `teacher_id` int(11) NOT NULL,
  `teacher_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher_emailid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher_password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher_doj` date NOT NULL,
  `teacher_image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_teacher`
--

INSERT INTO `tbl_teacher` (`teacher_id`, `teacher_name`, `teacher_address`, `teacher_emailid`, `teacher_password`, `teacher_doj`, `teacher_image`) VALUES
(2, 'Teacher Demo4', 'Số 4 Đại Cồ Việt, Hai Bà Trưng, Hà Nội', 'teacher.demo4@gmail.com', '$2y$10$TT00uya5Id6/g8xBRSpB2.r7bmMdhXBVoS2an/iCq2S7CwVZ.8Gvy', '2019-05-30', '61d01207cf66c.jpg'),
(3, 'Teacher Demo2', 'Số 3 Đại Cồ Việt, Hai Bà Trưng, Hà Nội', 'teacher.demo2@gmail.com', '$2y$10$jmgJN1xvteg6XqBnHvT7UerviGNJOSnF8KFzBHnCky0FJWa74Nvmu', '2017-12-31', '5ce53488d50ec.jpg'),
(4, 'Teacher Demo', 'Số 2 Đại Cồ Việt, Hai Bà Trưng, Hà Nội', 'teacher.demo@gmail.com', '$2y$10$Vb9t4CvkJwm41KXgPehuLOFcM7o5Qdm1RFxSBxzh9cvBcc21AUAiW', '2019-05-01', '5cdd2f35be8fa.jpg'),
(5, 'Teacher Demo3', 'Số 1 Đại Cồ Việt, Hai Bà Trưng, Hà Nội', 'teacher.demo3@gmail.com', '$2y$10$SVxX4/7lf3pDs1vrpuJexOG7Ue1e1jqIntGmXip3JzxkB753uxBiO', '2020-05-28', '61d011a8ba870.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_attendance_qr`
--
ALTER TABLE `tbl_attendance_qr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_class`
--
ALTER TABLE `tbl_class`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `tbl_class_student`
--
ALTER TABLE `tbl_class_student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_course`
--
ALTER TABLE `tbl_course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `tbl_qr`
--
ALTER TABLE `tbl_qr`
  ADD PRIMARY KEY (`qr_id`);

--
-- Indexes for table `tbl_schedule`
--
ALTER TABLE `tbl_schedule`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `tbl_student`
--
ALTER TABLE `tbl_student`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `tbl_teacher`
--
ALTER TABLE `tbl_teacher`
  ADD PRIMARY KEY (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_attendance_qr`
--
ALTER TABLE `tbl_attendance_qr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_class_student`
--
ALTER TABLE `tbl_class_student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_schedule`
--
ALTER TABLE `tbl_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_teacher`
--
ALTER TABLE `tbl_teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
