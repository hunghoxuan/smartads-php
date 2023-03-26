-- phpMyAdmin SQL Dump
-- version 4.7.6
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th5 15, 2018 lúc 01:41 PM
-- Phiên bản máy phục vụ: 5.6.35
-- Phiên bản PHP: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `stech_smartads`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `app_file`
--

CREATE TABLE `app_file` (
  `id` int(11) NOT NULL,
  `file_name` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  `ime` varchar(500) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `download_time` datetime DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `application_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `app_file`
--

INSERT INTO `app_file` (`id`, `file_name`, `file_size`, `user_id`, `ime`, `status`, `download_time`, `created_date`, `application_id`) VALUES
(2, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15262208730_video.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '0000-00-00 00:00:00', '2018-05-15 02:48:00', 'stechqms'),
(3, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media_gioi_thieu_ve_108_smartscreen_content_10_url10_20180427_150414.mp4', 14429310, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '0000-00-00 00:00:00', '2018-05-15 09:34:04', 'stechqms'),
(4, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media_gioi_thieu_benh_vien_108_smartscreen_content_11_url11_20180427_150449.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '0000-00-00 00:00:00', '2018-05-15 11:42:29', 'stechqms');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `app_file`
--
ALTER TABLE `app_file`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `app_file`
--
ALTER TABLE `app_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
