-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th10 03, 2018 lúc 10:52 AM
-- Phiên bản máy phục vụ: 5.7.22
-- Phiên bản PHP: 7.1.17

SET FOREIGN_KEY_CHECKS=0;
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
CREATE DATABASE IF NOT EXISTS `stech_smartads` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `stech_smartads`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `app_file`
--

DROP TABLE IF EXISTS `app_file`;
CREATE TABLE IF NOT EXISTS `app_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  `ime` varchar(500) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `download_time` datetime DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `application_id` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `app_file`
--

INSERT INTO `app_file` (`id`, `file_name`, `file_size`, `user_id`, `ime`, `status`, `download_time`, `created_date`, `application_id`) VALUES
(1, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media-gioi-thieu-benh-vien-108-1gb_smartscreen_content_11_url__date_20180517180547_id_11.mp4', 205124534, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-29 14:11:15', NULL, 'stechqms'),
(2, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media_gioi_thieu_benh_vien_108_smartscreen_content_11_url11_20180427_150449.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-05 11:26:58', NULL, 'stechqms'),
(3, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15262208730_video.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-05 11:26:50', NULL, 'stechqms'),
(4, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15262208730_video.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'processing', '2018-05-24 14:11:03', NULL, 'stechqms'),
(5, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media-gioi-thieu-benh-vien-108-1gb_smartscreen_content_11_url__date_20180517180547_id_11.mp4', 205124534, '', 'D4676204D1B57CFB5E1C081666CF7BB6', 'done', '2018-06-29 15:03:14', NULL, 'stechqms'),
(6, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15262208730_video.mp4', 22863340, '', 'D4676204D1B57CFB5E1C081666CF7BB6', 'processing', '2018-05-24 14:51:47', NULL, 'stechqms'),
(7, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media-gioi-thieu-benh-vien-108-1gb_smartscreen_content_11_url__date_20180517180547_id_11.mp4', 205124534, '', '9BB94C9F8212428CA77AF540584A32C2', 'done', '2018-06-22 16:32:06', NULL, 'stechqms'),
(8, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media_gioi_thieu_benh_vien_108_smartscreen_content_11_url11_20180427_150449.mp4', 22863340, '', 'D4676204D1B57CFB5E1C081666CF7BB6', 'done', '2018-06-29 15:18:36', NULL, 'stechqms'),
(9, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media_gioi_thieu_benh_vien_108_smartscreen_content_11_url11_20180427_150449.mp4', 22863340, '', '9BB94C9F8212428CA77AF540584A32C2', 'done', '2018-06-07 12:14:45', NULL, 'stechqms'),
(10, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media_gioi_thieu_benh_vien_108_smartscreen_content_11_url11_20180427_150449.mp4', 22863340, '', 'AF536D636A2AD7236F08F7E0F1CD29A4', 'done', '2018-05-31 17:00:29', NULL, 'stechqms'),
(11, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15262208730_video.mp4', 22863340, '', 'AF536D636A2AD7236F08F7E0F1CD29A4', 'done', '2018-05-31 17:00:49', NULL, 'stechqms'),
(12, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15262208730_video.mp4', 22863340, '', '9BB94C9F8212428CA77AF540584A32C2', 'processing', '2018-06-07 12:14:27', '2018-06-07 12:14:27', 'stechqms'),
(13, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15289608400_video.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-14 15:25:02', '2018-06-14 14:21:38', 'stechqms'),
(14, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15289586561_video.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-14 15:25:13', '2018-06-14 14:21:46', 'stechqms'),
(15, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15289726480_video.jpg', 159371, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-15 15:32:00', '2018-06-14 17:38:38', 'stechqms'),
(16, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media-hoat-dong-ve-nguon-cua-benh-vien-108_smartscreen_content_19_url_date_20180615110627.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'processing', '2018-06-22 15:35:40', '2018-06-15 12:00:53', 'stechqms'),
(17, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15290553160_video.png', 250354, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-29 13:59:07', '2018-06-15 16:36:01', 'stechqms'),
(18, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15290553161_video.png', 250354, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-29 13:59:03', '2018-06-15 16:36:02', 'stechqms'),
(19, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15290554740_video.mp4', 205124534, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-07-02 11:21:42', '2018-06-15 16:54:27', 'stechqms'),
(20, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15290553412_video.png', 238613, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-29 13:56:11', '2018-06-18 11:12:27', 'stechqms'),
(21, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15290553160_video.png', 250354, '', '7C8505626F218B00C7F7E5C96ADDE9C8', 'done', '2018-06-18 13:36:45', '2018-06-18 13:34:41', 'stechqms'),
(22, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media-gioi-thieu-benh-vien-108-1gb_smartscreen_content_11_url__date_20180517180547_id_11.mp4', 205124534, '', '7C8505626F218B00C7F7E5C96ADDE9C8', 'done', '2018-06-18 13:38:21', '2018-06-18 13:36:45', 'stechqms'),
(23, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15293176270_video.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-29 13:56:03', '2018-06-19 11:01:24', 'stechqms'),
(24, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15293176561_video.mp4', 205124534, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-29 13:58:41', '2018-06-19 15:46:01', 'stechqms'),
(25, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15293912831_video.mp4', 205124534, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-29 16:11:30', '2018-06-20 16:54:21', 'stechqms'),
(26, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15293912830_video.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-06-29 16:18:14', '2018-06-20 16:54:33', 'stechqms'),
(27, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15290553160_video.png', 250354, '', '9BB94C9F8212428CA77AF540584A32C2', 'done', '2018-06-22 16:15:06', '2018-06-21 14:10:19', 'stechqms'),
(28, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15290553161_video.png', 250354, '', '9BB94C9F8212428CA77AF540584A32C2', 'done', '2018-06-22 16:19:35', '2018-06-21 14:10:22', 'stechqms'),
(29, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15290553412_video.png', 238613, '', '9BB94C9F8212428CA77AF540584A32C2', 'done', '2018-06-22 16:19:35', '2018-06-21 14:12:35', 'stechqms'),
(30, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15290553412_video.png', 238613, '', '9BB94C9F8212428CA77AF540584A32C2', 'processing', '2018-06-21 14:12:35', '2018-06-21 14:12:35', 'stechqms'),
(31, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15293176270_video.mp4', 22863340, '', '9BB94C9F8212428CA77AF540584A32C2', 'done', '2018-06-22 16:36:11', '2018-06-21 14:30:10', 'stechqms'),
(32, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15293176561_video.mp4', 205124534, '', '9BB94C9F8212428CA77AF540584A32C2', 'done', '2018-06-22 16:44:14', '2018-06-21 17:03:05', 'stechqms'),
(33, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media-hoat-dong-ve-nguon-cua-benh-vien-108_smartscreen_content_19_url_date_20180615110627.mp4', 22863340, '', '9BB94C9F8212428CA77AF540584A32C2', 'done', '2018-06-29 15:57:39', '2018-06-21 17:06:42', 'stechqms'),
(34, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15290554740_video.mp4', 205124534, '', '9BB94C9F8212428CA77AF540584A32C2', 'done', '2018-06-29 16:01:57', '2018-06-21 17:23:23', 'stechqms'),
(35, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media-hoat-dong-ve-nguon-cua-benh-vien-108_smartscreen_content_19_url_date_20180615110627.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'processing', '2018-06-22 15:35:41', '2018-06-22 15:35:41', 'stechqms'),
(36, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media-hoat-dong-ve-nguon-cua-benh-vien-108_smartscreen_content_19_url_date_20180615110627.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'processing', '2018-06-22 15:35:41', '2018-06-22 15:35:43', 'stechqms'),
(37, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media-hoat-dong-ve-nguon-cua-benh-vien-108_smartscreen_content_19_url_date_20180615110627.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'processing', '2018-06-22 15:35:41', '2018-06-22 15:35:42', 'stechqms'),
(38, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media-hoat-dong-ve-nguon-cua-benh-vien-108_smartscreen_content_19_url_date_20180615110627.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'processing', '2018-06-22 15:35:43', '2018-06-22 15:35:44', 'stechqms'),
(39, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-content/media-hoat-dong-ve-nguon-cua-benh-vien-108_smartscreen_content_19_url_date_20180615110627.mp4', 22863340, '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 'done', '2018-07-02 11:20:26', '2018-06-22 15:35:46', 'stechqms'),
(40, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15293912830_video.mp4', 22863340, '', 'D4676204D1B57CFB5E1C081666CF7BB6', 'done', '2018-06-29 16:54:10', '2018-06-29 16:05:02', 'stechqms'),
(41, '/Volumes/DATA/PHP/smartads-stech-php/applications/stechqms/upload/smartscreen-file/15302621080_video.mp4', 21863739, '', 'D4676204D1B57CFB5E1C081666CF7BB6', 'processing', '2018-06-29 16:55:44', '2018-06-29 16:07:58', 'stechqms');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `app_version`
--

DROP TABLE IF EXISTS `app_version`;
CREATE TABLE IF NOT EXISTS `app_version` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `package_version` int(255) DEFAULT NULL,
  `package_name` varchar(255) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `platform_info` varchar(1000) DEFAULT NULL,
  `file` varchar(300) NOT NULL,
  `count_views` int(11) DEFAULT NULL,
  `count_downloads` int(11) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL,
  `is_default` int(11) NOT NULL,
  `history` text,
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `modified_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `app_version`
--

INSERT INTO `app_version` (`id`, `name`, `description`, `package_version`, `package_name`, `platform`, `platform_info`, `file`, `count_views`, `count_downloads`, `is_active`, `is_default`, `history`, `created_date`, `created_user`, `modified_date`, `modified_user`, `application_id`) VALUES
(1, 'version 1.0.1 build 2', 'first release 20180712', 2, 'com.stech.smartads', 'android', '', 'version-1-0-1-build-2_app_version_1_file_date_20181002151030.apk', NULL, NULL, 1, 1, '', NULL, '', '2018-10-02 15:05:30', '8', 'stechqms');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_group`
--

DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE IF NOT EXISTS `auth_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_permission`
--

DROP TABLE IF EXISTS `auth_permission`;
CREATE TABLE IF NOT EXISTS `auth_permission` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) NOT NULL,
  `object_type` varchar(100) NOT NULL,
  `object2_id` bigint(20) NOT NULL,
  `object2_type` varchar(100) NOT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `sort_order` int(5) NOT NULL,
  `created_date` date DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_role`
--

DROP TABLE IF EXISTS `auth_role`;
CREATE TABLE IF NOT EXISTS `auth_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `media_file`
--

DROP TABLE IF EXISTS `media_file`;
CREATE TABLE IF NOT EXISTS `media_file` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(300) DEFAULT NULL,
  `file` varchar(555) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_size` varchar(255) DEFAULT NULL,
  `file_duration` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `sort_order` tinyint(5) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `media_file`
--

INSERT INTO `media_file` (`id`, `name`, `image`, `file`, `file_path`, `description`, `file_type`, `file_size`, `file_duration`, `is_active`, `sort_order`, `created_date`, `created_user`, `application_id`) VALUES
(7, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'Test Hihi', NULL, 'music_artist_1_file_19.png', NULL, '', 'image/png', NULL, NULL, 1, 0, '2016-09-26 00:00:00', '6', ''),
(21, 'hihi', NULL, 'cms_testimonial_1_file_21.png', NULL, '', 'image/png', NULL, NULL, 1, 0, '2016-09-26 00:00:00', '6', ''),
(22, 'DDDDDD', NULL, 'music_artist_1_file_22.png', NULL, '', 'image/png', NULL, NULL, 1, 1, '2016-09-27 00:00:00', '6', ''),
(24, 'jjjjjjjjjj', NULL, 'music_artist_2_file_24.png', NULL, '', 'image/png', NULL, NULL, 1, 0, '2016-09-27 00:00:00', '6', ''),
(25, 'DDDDDDDD', NULL, 'music_artist_2_file_25.png', NULL, '', 'image/png', NULL, NULL, 1, 1, '2016-09-27 00:00:00', '6', ''),
(26, 'fsdfdsfafas', NULL, 'music_song_1_file_26.png', NULL, '', 'image/png', NULL, NULL, 1, 0, '2016-09-28 00:00:00', '6', ''),
(30, '2', NULL, 'www.vnexpress.net', NULL, '', 'image/png', NULL, NULL, 1, 2, '2016-10-01 00:00:00', '6', ''),
(31, '3', NULL, 'vnexpress.net', NULL, '', '', NULL, NULL, 1, 3, '2016-10-01 00:00:00', '6', ''),
(32, '4', NULL, 'music_artist_1_file_32.png', NULL, '', 'image/png', NULL, NULL, 1, 4, '2016-10-01 00:00:00', '6', ''),
(33, 'Company profile', NULL, 'crm_client_1_file_33.pdf', NULL, '', 'application/pdf', NULL, NULL, 1, 0, '2016-10-06 00:00:00', '6', 'education'),
(34, 'Test', NULL, 'cms_blogs_1_file_34.png', NULL, '', 'image/png', '602113', '111', 1, 0, '2016-12-05 00:00:00', '6', 'education'),
(35, 'Ok', NULL, 'cms_blogs_1_file_35.png', NULL, '', 'image/png', '', '3333', 1, 1, '2016-12-05 00:00:00', '6', 'education'),
(36, 'ss', NULL, 'ddd', NULL, '', '', '', 'ss', 1, 0, '2017-01-18 00:00:00', '6', 'default'),
(37, 'fasf', NULL, 'fdsfsd', NULL, '', '', '', 'fsd', 1, 1, '2017-01-18 00:00:00', '6', 'default'),
(38, 'Sample AVI', '', 'sample-avi_media_file38_file.avi', '', 'TEst fsdfsd', '', '', '', 1, NULL, '2017-04-30 20:19:33', '6', 'stech'),
(39, 'Test File Media', 'test_file_media_media_file__image.png', 'test_file_media_media_file__file.png', '3', 'ABC', '332', '2333', '23332', 1, NULL, '2017-09-29 12:53:03', '8', 'stechqms');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `object_file`
--

DROP TABLE IF EXISTS `object_file`;
CREATE TABLE IF NOT EXISTS `object_file` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `object_type` varchar(100) NOT NULL,
  `file` varchar(555) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_kind` varchar(100) DEFAULT '',
  `file_size` varchar(255) DEFAULT NULL,
  `file_duration` varchar(100) DEFAULT '',
  `is_active` tinyint(1) DEFAULT NULL,
  `sort_order` tinyint(5) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `object_file`
--

INSERT INTO `object_file` (`id`, `object_id`, `object_type`, `file`, `title`, `description`, `file_type`, `file_kind`, `file_size`, `file_duration`, `is_active`, `sort_order`, `created_date`, `created_user`, `application_id`) VALUES
(8, 8, 'smartscreen_content', 'smartscreen_content_8_file_8.png', 'smartscreen_content_8_file_8.png', 'ok', 'Image', 'number', '25317', '2', 1, 0, '2017-11-10 00:00:00', '9', 'stechqms'),
(9, 8, 'smartscreen_content', 'smartscreen_content_8_file_8.png', 'smartscreen_content_8_file_8.png', 'ok', 'Image', 'number', '', '2', 1, 1, '2017-11-10 00:00:00', '9', 'stechqms'),
(15, 8, 'smartscreen_content', 'smartscreen_content_8_file_15.png', '', 'ok', 'Image', 'number', '31807', '2', 1, 2, '2017-11-14 00:00:00', '9', 'stechqms'),
(16, 69, 'smartscreen_layouts', 'smartscreen_layouts_69_file_.docx', 'Smartscreen Layouts 69 File  Docx', '', 'File', '', '966701', '', 1, 0, '2018-01-09 00:00:00', '9', 'stechqms'),
(17, 69, 'smartscreen_layouts', 'smartscreen_layouts_69_file_.jpg', 'Smartscreen Layouts 69 File  Jpg', '', 'Image', '', '121408', '', 1, 1, '2018-01-09 00:00:00', '9', 'stechqms'),
(18, 3, 'smartscreen_queue', 'smartscreen_queue_3_file_.doc', 'Hop dong moi', '', 'File', '', '78848', '', 1, 0, '2017-12-25 00:00:00', '9', 'stechqms');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `smartscreen_calendar`
--

DROP TABLE IF EXISTS `smartscreen_calendar`;
CREATE TABLE IF NOT EXISTS `smartscreen_calendar` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `content` varchar(2000) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `device_id` varchar(500) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `modified_date` date DEFAULT NULL,
  `modified_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `smartscreen_calendar`
--

INSERT INTO `smartscreen_calendar` (`id`, `code`, `name`, `description`, `content`, `date`, `time`, `device_id`, `location`, `type`, `owner_name`, `created_date`, `created_user`, `modified_date`, `modified_user`, `application_id`) VALUES
(1, '001', 'HO XUAN HUNG', '36t', 'Phòng A - Tai mũi họng', NULL, '', '[\"\"]', '', 'patient', '', '2017-12-26', '9', '2017-12-26', '9', 'stechqms'),
(2, '001', 'PHAM NGOC HUY', '30t', 'Phòng B - Mổ nội soi', NULL, '09:00 AM', '[\"\"]', 'Phòng nội soi', 'patient', 'Mr. Cường', '2017-12-26', '9', '2017-12-26', '9', 'stechqms'),
(3, '002', 'HY QUỐC CƯỜNG', '30t', 'Chăm sóc sức khỏe nâng cao', NULL, 'SÁNG', '[\"\"]', 'Phòng khám bệnh theo yêu cầu', 'patient', 'BS. ĐÀO', '2017-12-26', '9', '2017-12-26', '9', 'stechqms'),
(4, '002', 'Mr. Trần Anh Dũng', 'Khám da liễu', 'Khám Da liễu', NULL, '10:30 AM - 12:00 AM', '[\"\"]', 'Phòng Da liễu', 'doctor', 'HO XUAN HUNG', '2017-12-26', '9', '2017-12-26', '9', 'stechqms'),
(5, '00032', 'Mr Tuấn', 'Khám nội trú', 'Khám nội trú', NULL, '', '[\"\"]', 'Bệnh nhân VIP', 'doctor', 'Quốc Cường, 27t', '2018-01-09', '9', '2018-01-09', '9', 'stechqms'),
(6, '007', 'ĐINH LA THIÊN', '65t', 'Khám Răng hàm mặt', NULL, 'Cả ngày', '[\"\"]', 'Khám chữa bệnh theo yêu cầu', 'patient', 'Mr. Trọng', '2017-12-26', '9', '2017-12-26', '9', 'stechqms'),
(7, '008', 'NGUYỄN TIẾN DŨNG', '65t', 'Khám bệnh theo yêu cầu', NULL, '3h Chiều', '[\"\"]', 'Khám bệnh theo yêu cầu', 'patient', 'Mr. Trọng', '2017-12-26', '9', '2017-12-26', '9', 'stechqms'),
(8, '00011', 'Mr Chi DUng', 'Mổ van tim', 'Mổ van tim', NULL, '', '[\"\"]', 'Viện tim mạch', 'doctor', 'Quốc Cường, 27t', '2018-01-09', '9', '2018-01-09', '9', 'stechqms'),
(9, '00011', 'Mr Trường', 'Chuẩn đoán hình ảnh', '', NULL, '', '[\"\"]', 'Phòng nội soi', 'doctor', 'Hung Ho, 35t', '2018-01-09', '9', '2018-01-09', '9', 'stechqms');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `smartscreen_channels`
--

DROP TABLE IF EXISTS `smartscreen_channels`;
CREATE TABLE IF NOT EXISTS `smartscreen_channels` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `image` varchar(300) DEFAULT NULL,
  `content_id` text,
  `layout_id` varchar(100) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT NULL,
  `is_default` tinyint(4) DEFAULT NULL,
  `devices` text,
  `created_date` date DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `smartscreen_channels`
--

INSERT INTO `smartscreen_channels` (`id`, `name`, `description`, `image`, `content_id`, `layout_id`, `is_active`, `is_default`, `devices`, `created_date`, `created_user`, `application_id`) VALUES
(1, 'HIS', '', '', '[18,\"\"]', '54', 1, NULL, '', '2018-01-15', '9', 'stechqms'),
(2, 'Media + HIS', '', '', '[18,\"\"]', '54', 1, NULL, '', '2018-01-15', '9', 'stechqms');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `smartscreen_content`
--

DROP TABLE IF EXISTS `smartscreen_content`;
CREATE TABLE IF NOT EXISTS `smartscreen_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT '',
  `description` text,
  `type` varchar(255) NOT NULL DEFAULT '',
  `kind` varchar(100) DEFAULT '',
  `duration` int(12) DEFAULT '0',
  `expire_date` varchar(20) DEFAULT '',
  `sort_order` int(11) DEFAULT NULL,
  `owner_id` varchar(100) DEFAULT '',
  `is_active` tinyint(4) DEFAULT '1',
  `is_default` tinyint(4) DEFAULT NULL,
  `created_date` int(11) NOT NULL,
  `modified_date` int(11) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT 'stechqms',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `smartscreen_content`
--

INSERT INTO `smartscreen_content` (`id`, `title`, `url`, `description`, `type`, `kind`, `duration`, `expire_date`, `sort_order`, `owner_id`, `is_active`, `is_default`, `created_date`, `modified_date`, `application_id`) VALUES
(1, 'Header: WELCOME TEXT', '', 'BỆNH VIỆN TRUNG ƯƠNG QUÂN ĐỘI 108 ', 'text', 'number', 2, '', 0, '', 1, NULL, 1507601659, 2017, 'stechqms'),
(3, 'Footer: Tin tức', 'advertise_1_smartscreen_content_3_url3_20171215_141218.png', 'Tháng 6-2018 Khoa khám bệnh sẽ triển khai tiếp đón và khám chữa bệnh cho bệnh nhân thu một phần viện phí (dịch vụ) cả ngày thứ 7. ', 'text', 'number', 2, '', 2, '', 1, NULL, 1507601659, 2017, 'stechqms'),
(4, 'Footer: Giới thiệu sản phẩm', '', 'Chương trình hiển thị nội dung thông minh - Smart Media. Phiên bản được ứng dụng tại BỆNH VIỆN TRUNG ƯƠNG QUÂN ĐỘI 108', 'text', 'number', 2, '', 3, '', 1, NULL, 1507601659, 2017, 'stechqms'),
(10, 'Giới thiệu quá trình xây dựng BỆNH VIỆN 108 (Flycam)', 'media-gioi-thieu-ve-108_smartscreen_content_10_url_date_20180523110521.mp4', '<p>Giới thiệu qu&aacute; tr&igrave;nh x&acirc;y dựng BỆNH VIỆN 108 3</p>\r\n', 'video', '', 0, '', NULL, '', 1, NULL, 2017, 2018, 'stechqms'),
(11, 'Media Hoạt động về nguồn BV 108', 'media-hoat-dong-ve-nguon-bv-108_smartscreen_content_11_url__date_20180505110506_id_11.mp4', 'Media Giới thiệu bênh viện 108 ', 'video', '', 0, '', NULL, '', 1, NULL, 2017, 2018, 'stechqms'),
(12, 'HTML - GIỚI THIỆU CHUNG', '', '<p><strong>Bệnh viện Trung ương Qu&acirc;n đội 108</strong></p>\r\n\r\n<p>th&agrave;nh lập ng&agrave;y 01/4/1951, l&agrave; Bệnh viện Đa khoa, Chuy&ecirc;n khoa s&acirc;u, Tuyến cuối của ng&agrave;nh Qu&acirc;n y v&agrave; l&agrave; Bệnh viện hạng đặc biệt của Quốc gia với chức năng nhiệm vụ: Kh&aacute;m, cấp cứu thu dung điều trị cho c&aacute;c đối tượng bệnh nh&acirc;n: qu&acirc;n nh&acirc;n tại chức, bảo hiểm qu&acirc;n v&agrave; nh&acirc;n d&acirc;n thuộc diện thu một phần viện ph&iacute;.</p>\r\n', 'html', '', 0, '', NULL, '', 1, NULL, 2017, 2017, 'stechqms'),
(14, 'Danh sách khách hàng (32\', phòng chờ)', '', '', 'queue_room', '', 0, '', NULL, '', 1, NULL, 2017, 2018, 'stechqms'),
(15, 'Danh sách khách hàng (Màn 55\', sảnh lớn)', '', '', 'queue_hall', '', 0, '', NULL, '', 1, NULL, 2018, 2018, 'stechqms'),
(16, 'Lịch làm việc và tiếp khách (Màn 32\' ở phòng chờ)', '', '', 'calendar_patient', '', 0, '', NULL, '', 1, NULL, 2018, 2018, 'stechqms'),
(17, 'Lịch làm việc (32\', phòng nhân viên)', '', '', 'calendar_doctor', '', 0, '', NULL, '', 1, NULL, 2018, 2018, 'stechqms'),
(18, 'Danh sách Bệnh nhân', '', '', 'queue_simple', '', 0, '', NULL, '', 1, 1, 2018, 2018, 'stechqms'),
(20, 'Media Giới thiệu BV 108', 'media-gioi-thieu-bv-108_smartscreen_content_20_url_date_20180523110504.mp4', '', 'video', '', 0, '', NULL, '', 1, 0, 2018, 2018, 'stechqms'),
(21, 'Album ảnh hoạt động BV 108', 'album-anh-hoat-dong-bv-108_smartscreen_content__url__date_20180516110544_id_.jpeg', '<p>Bệnh viện 108</p>\r\n', 'gallery', '', 0, '', NULL, '', 1, NULL, 2018, 2018, 'stechqms');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `smartscreen_file`
--

DROP TABLE IF EXISTS `smartscreen_file`;
CREATE TABLE IF NOT EXISTS `smartscreen_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `object_type` varchar(100) DEFAULT NULL,
  `command` varchar(255) DEFAULT NULL,
  `file` varchar(555) DEFAULT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `file_kind` varchar(100) DEFAULT '',
  `file_size` varchar(255) DEFAULT NULL,
  `file_duration` varchar(100) DEFAULT '',
  `is_active` tinyint(1) DEFAULT NULL,
  `sort_order` tinyint(5) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT 'stechqms',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `smartscreen_file`
--

INSERT INTO `smartscreen_file` (`id`, `object_id`, `object_type`, `command`, `file`, `description`, `file_kind`, `file_size`, `file_duration`, `is_active`, `sort_order`, `created_date`, `created_user`, `application_id`) VALUES
(1, 21, NULL, NULL, '15264627160_video.JPG', '', '', NULL, '', 1, NULL, '2018-05-16 11:48:44', '8', 'stechqms'),
(2, 21, NULL, NULL, '15264627161_video.jpg', '', '', NULL, '', 1, NULL, '2018-05-16 11:48:45', '8', 'stechqms'),
(3, 21, NULL, NULL, '15264568262_video.png', '', '', NULL, '', 1, NULL, '2018-05-16 11:48:45', '8', 'stechqms'),
(4, 21, NULL, NULL, '15270629973_video.png', '', '', NULL, '', 1, NULL, '2018-05-16 11:48:45', '8', 'stechqms');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `smartscreen_frame`
--

DROP TABLE IF EXISTS `smartscreen_frame`;
CREATE TABLE IF NOT EXISTS `smartscreen_frame` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `backgroundColor` varchar(10) DEFAULT '',
  `layout_id` int(11) DEFAULT NULL,
  `percentWidth` int(11) NOT NULL,
  `percentHeight` int(11) NOT NULL,
  `marginTop` int(11) DEFAULT '0',
  `marginLeft` int(11) DEFAULT '0',
  `contentLayout` varchar(255) DEFAULT '',
  `created_date` int(11) NOT NULL,
  `modified_date` int(11) DEFAULT NULL,
  `application_id` varchar(255) DEFAULT 'stechqms',
  `file` varchar(255) DEFAULT NULL,
  `content` text,
  `content_id` int(11) DEFAULT NULL,
  `font_size` varchar(255) DEFAULT NULL,
  `font_color` varchar(255) DEFAULT NULL,
  `alignment` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `smartscreen_frame`
--

INSERT INTO `smartscreen_frame` (`id`, `name`, `backgroundColor`, `layout_id`, `percentWidth`, `percentHeight`, `marginTop`, `marginLeft`, `contentLayout`, `created_date`, `modified_date`, `application_id`, `file`, `content`, `content_id`, `font_size`, `font_color`, `alignment`, `sort_order`, `is_active`) VALUES
(2, 'Sidebar', '#ff9900', 1, 30, 80, 10, 70, 'text', 1507601659, 2018, 'stechqms', NULL, NULL, NULL, NULL, NULL, NULL, 7, 1),
(3, 'Main', '#3c78d8', 1, 100, 80, 10, 0, '', 1507601659, 2018, 'stechqms', '', '', NULL, NULL, '', NULL, 6, 1),
(4, 'Footer', '#1c4587', 1, 100, 10, 90, 0, '', 1507601659, 2017, 'stechqms', '', '', NULL, NULL, '', NULL, 5, 1),
(10, 'Header', '#274e13', NULL, 100, 10, 0, 0, '', 2017, 2017, 'stechqms', '', '', NULL, NULL, '', NULL, 2, 1),
(11, 'Full Screen', '#3c78d8', 1, 100, 100, 0, 0, '', 1507601659, 2018, 'stechqms', '', '', NULL, NULL, '', NULL, 6, 1),
(12, 'Main with left sidebar', '', NULL, 70, 80, 10, NULL, 'main', 2018, 2018, 'stechqms', '', '', NULL, NULL, '', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `smartscreen_layouts`
--

DROP TABLE IF EXISTS `smartscreen_layouts`;
CREATE TABLE IF NOT EXISTS `smartscreen_layouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `sort_order` int(11) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT NULL,
  `is_default` tinyint(4) DEFAULT NULL,
  `demo_html` text,
  `created_date` int(11) NOT NULL,
  `modified_date` int(11) DEFAULT NULL,
  `appilication_id` varchar(100) DEFAULT 'stechqms',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `smartscreen_layouts`
--

INSERT INTO `smartscreen_layouts` (`id`, `name`, `description`, `sort_order`, `is_active`, `is_default`, `demo_html`, `created_date`, `modified_date`, `appilication_id`) VALUES
(53, 'Giao diện 3 Hàng', '', NULL, 1, NULL, NULL, 1509551075, 1525080491, ''),
(54, 'Giao diện toàn màn hình', '', NULL, 1, 1, NULL, 1509551112, 1522309081, ''),
(69, '4 part', '', NULL, 1, 0, NULL, 1510029191, 1526461609, '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `smartscreen_layouts_frame`
--

DROP TABLE IF EXISTS `smartscreen_layouts_frame`;
CREATE TABLE IF NOT EXISTS `smartscreen_layouts_frame` (
  `layout_id` int(11) NOT NULL,
  `frame_id` int(11) NOT NULL,
  `sort_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`layout_id`,`frame_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `smartscreen_layouts_frame`
--

INSERT INTO `smartscreen_layouts_frame` (`layout_id`, `frame_id`, `sort_order`) VALUES
(53, 3, 1),
(53, 4, 2),
(53, 10, 0),
(54, 11, 0),
(69, 2, 0),
(69, 4, 2),
(69, 10, 3),
(69, 12, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `smartscreen_queue`
--

DROP TABLE IF EXISTS `smartscreen_queue`;
CREATE TABLE IF NOT EXISTS `smartscreen_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `ticket` varchar(255) DEFAULT NULL,
  `counter` varchar(255) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `service_id` varchar(100) DEFAULT NULL COMMENT 'lookup:@qms_services',
  `status` varchar(100) DEFAULT NULL,
  `note` varchar(2000) DEFAULT NULL,
  `device_id` varchar(2000) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `smartscreen_queue`
--

INSERT INTO `smartscreen_queue` (`id`, `code`, `name`, `ticket`, `counter`, `service`, `service_id`, `status`, `note`, `device_id`, `is_active`, `sort_order`, `created_date`, `created_user`, `application_id`, `description`) VALUES
(1, '0003', 'Nguyen Duc Tho', '1025', '01', 'Khám bệnh nâng cao', '', 'processing', '', '', 1, 2, '2017-12-21', '9', 'stechqms', '18 tuổi'),
(2, '0002', 'PHAM NGOC HUY', '1024', '03', 'Nội soi', '', 'new', '', '', 1, 1, '2017-12-21', '9', 'stechqms', '30 tuổi'),
(3, '0001', 'Nguyen Thi Hanh', '3333', '03', 'Khám chữa bệnh theo yêu cầu', '6', 'new', '', '', 1, NULL, '2017-12-24', '9', 'stechqms', '34 tuổi'),
(4, '0007', 'NGUYỄN KHƯƠNG TUẤN', '1249', '01', 'Tai mũi họng', '', 'new', '', '', 1, NULL, '2018-01-09', '9', 'stechqms', '37t'),
(5, '0002', 'Nguyễn Văn A', '4431', '02', 'Răng hàm mặt', '', 'new', '', '', 1, NULL, '2018-01-09', '9', 'stechqms', '34t'),
(6, '0007', 'SƠN TÙNG', '0081', '03', 'Khám bệnh tổng hợp', '', 'new', '', '', 1, NULL, '2018-01-09', '9', 'stechqms', '27t'),
(7, '0011', 'HƯƠNG TRÀM', '0082', '01', 'Xét nghiệm máu', '', 'processing', '', '', 1, NULL, '2018-01-09', '9', 'stechqms', '27t'),
(8, '', 'Nguyễn Văn Binh', '2003', '5', '', '', 'new', 'Khám tai mũi họng', '', NULL, NULL, '2018-05-16', '8', 'stechqms', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `smartscreen_schedules`
--

DROP TABLE IF EXISTS `smartscreen_schedules`;
CREATE TABLE IF NOT EXISTS `smartscreen_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` varchar(100) DEFAULT '',
  `layout_id` varchar(100) DEFAULT '',
  `content_id` varchar(100) DEFAULT '',
  `frame_id` varchar(100) DEFAULT '',
  `start_time` varchar(100) DEFAULT '',
  `date` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `days` varchar(500) DEFAULT NULL,
  `type` varchar(100) DEFAULT '',
  `channel_id` varchar(100) DEFAULT NULL,
  `loop` int(5) DEFAULT NULL,
  `duration` int(10) DEFAULT '0',
  `application_id` varchar(100) DEFAULT 'stechqms',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=473 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `smartscreen_schedules`
--

INSERT INTO `smartscreen_schedules` (`id`, `device_id`, `layout_id`, `content_id`, `frame_id`, `start_time`, `date`, `date_end`, `days`, `type`, `channel_id`, `loop`, `duration`, `application_id`) VALUES
(472, '...', '54', '[18,\"\"]', '', '', NULL, NULL, '', 'advance', '', 0, 480, 'stechqms');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `smartscreen_scripts`
--

DROP TABLE IF EXISTS `smartscreen_scripts`;
CREATE TABLE IF NOT EXISTS `smartscreen_scripts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Logo` varchar(300) DEFAULT NULL,
  `TopBanner` varchar(300) DEFAULT NULL,
  `BotBanner` varchar(300) DEFAULT NULL,
  `ClipHeader` varchar(300) DEFAULT NULL,
  `ClipFooter` varchar(300) DEFAULT NULL,
  `ScrollText` text,
  `Clipnum` int(11) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip1` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip2` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip3` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip4` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip5` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip6` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip7` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip8` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip9` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip10` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip11` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip12` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip13` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `Clip14` varchar(300) DEFAULT NULL COMMENT 'group:CLIP',
  `CommandNumber` int(11) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line1` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line2` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line3` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line4` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line5` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line6` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line7` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line8` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line9` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line10` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line11` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line12` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line13` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line14` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line15` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `Line16` varchar(2000) DEFAULT NULL COMMENT 'group:COMMAND',
  `scripts_content` text CHARACTER SET utf8 COMMENT 'group:Scripts',
  `scripts_file` varchar(300) CHARACTER SET utf8 DEFAULT NULL COMMENT 'group:Form',
  `ReleaseDate` datetime DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `smartscreen_station`
--

DROP TABLE IF EXISTS `smartscreen_station`;
CREATE TABLE IF NOT EXISTS `smartscreen_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `ime` varchar(100) NOT NULL,
  `status` tinyint(4) DEFAULT '1',
  `last_activity` varchar(2000) DEFAULT NULL,
  `last_update` int(11) DEFAULT NULL,
  `ScreenName` varchar(255) DEFAULT NULL,
  `MACAddress` varchar(255) DEFAULT NULL,
  `LicenseKey` varchar(255) DEFAULT NULL,
  `branch_id` varchar(100) DEFAULT NULL COMMENT 'lookup:@qms_branch',
  `channel_id` varchar(100) DEFAULT NULL COMMENT 'lookup:@smartscreen_channels',
  `dept_id` varchar(100) DEFAULT NULL,
  `room_id` varchar(100) DEFAULT NULL,
  `disk_storage` varchar(255) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng `smartscreen_station`
--

INSERT INTO `smartscreen_station` (`id`, `name`, `description`, `ime`, `status`, `last_activity`, `last_update`, `ScreenName`, `MACAddress`, `LicenseKey`, `branch_id`, `channel_id`, `dept_id`, `room_id`, `disk_storage`, `created_date`, `application_id`) VALUES
(82, '03-E-LC2', '', '5101D89425D0C1B02B63642E3D9FF6CA', 1, '472', 1538538526, NULL, NULL, '', NULL, '', 'C1.2', '6', '3882995712', '2018-07-09', 'stechqms'),
(205, '03-E-LC1', '', '182F5A34B7A6981472ECAC2256A77465', 1, '472', 1538538599, NULL, NULL, '', NULL, '', 'C1.2', '7', '4428619776', '2018-07-10', 'stechqms'),
(225, '03-E-LC14', '', '0CAF9652C7996C813BB115FC5C7535DC', 1, '472', 1538538300, NULL, NULL, '', NULL, '', 'C7', '10', '4607827968', '2018-10-01', 'stechqms'),
(233, '03-E-LC5', '', '0CF3338D87B0CD05A2FDB0F893D00574', 1, '472', 1538538249, NULL, NULL, '', NULL, '', 'C1.2', '3', '4669014016', '2018-10-01', 'stechqms'),
(243, '03-E-LCD10', '', '2308BE962589418C71C5B1B579761ACE', 1, '472', 1538538365, NULL, NULL, '', NULL, '', 'C1.2', '1', '4748378112', '2018-10-01', 'stechqms'),
(245, '03-E-LC4', '', 'BAC6E9F8F4ECC4FDD8FC0F5A8847E807', 1, '472', 1538538739, NULL, NULL, '', NULL, '', 'C1.2', '4', '4548050944', '2018-10-01', 'stechqms'),
(246, '03-E-LC15', '', 'BC72AACC02DE80A68EEDE5DF74D9997B', 1, '472', 1538538243, NULL, NULL, '', NULL, '', 'C7', '16', '4455137280', '2018-10-01', 'stechqms'),
(247, '03-E-LCD11', '', 'B22C5DDE0DA61B513A7161DFAA6CC970', 1, '472', 1538538114, NULL, NULL, '', NULL, '', 'C1.2', '2', '4780728320', '2018-10-01', 'stechqms'),
(248, 'tablet', '', 'BB7AEE472903C273B6EFC8CFA217E7A7', 1, '472', 1538471985, '', '', '', '', '1', 'C1.2', '4', '14081581056', '2018-10-02', 'stechqms'),
(249, '03-E-LC3', '', 'AB99ED96C564D744C222AB0B55170592', 1, '472', 1538538430, NULL, NULL, '', NULL, '', 'C1.2', '5', '4468895744', '2018-10-02', 'stechqms');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `smartscreen_tasks`
--

DROP TABLE IF EXISTS `smartscreen_tasks`;
CREATE TABLE IF NOT EXISTS `smartscreen_tasks` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `note` varchar(2000) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `recurring_type` varchar(100) DEFAULT NULL,
  `recurring_values` varchar(500) DEFAULT NULL,
  `start_time` varchar(22) DEFAULT NULL,
  `end_time` varchar(22) DEFAULT NULL,
  `task_id` varchar(100) NOT NULL,
  `task_note` varchar(2000) NOT NULL,
  `owner_id` varchar(100) DEFAULT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `device_id` varchar(2000) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_user` varchar(100) DEFAULT NULL,
  `modified_date` date DEFAULT NULL,
  `modified_user` varchar(100) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `smartscreen_tasks`
--

INSERT INTO `smartscreen_tasks` (`id`, `code`, `name`, `note`, `start_date`, `end_date`, `recurring_type`, `recurring_values`, `start_time`, `end_time`, `task_id`, `task_note`, `owner_id`, `owner_name`, `type`, `status`, `device_id`, `created_date`, `created_user`, `modified_date`, `modified_user`, `application_id`) VALUES
(1, '001', 'Hồ Xuân Hùng', '36t', '2017-12-26', '2017-12-29', 'daily', '', '', '', 'Uống thuốc', 'Sáng: \r\n- Aspinrin: 3 viên (sau ăn)', '', 'Bs. Nguyễn Khương Tuấn', '', '', '', '2017-12-26', '9', NULL, '', 'stechqms');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `overview` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identity_card` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `organization` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `department` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `lat` int(11) DEFAULT NULL,
  `long` int(11) DEFAULT NULL,
  `rate` float DEFAULT NULL,
  `rate_count` int(11) DEFAULT NULL,
  `card_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_exp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_cvv` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balance` decimal(10,0) DEFAULT NULL,
  `point` int(11) DEFAULT NULL,
  `role` int(2) DEFAULT NULL COMMENT 'data:10:USER,20:MODERATOR,30:ADMIN',
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT 'data:DISABLED=0,ACTIVE=10',
  `is_online` tinyint(1) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `application_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `code`, `name`, `username`, `image`, `overview`, `content`, `auth_key`, `password_hash`, `password_reset_token`, `birth_date`, `birth_place`, `gender`, `identity_card`, `email`, `phone`, `skype`, `address`, `country`, `state`, `city`, `post_code`, `organization`, `department`, `position`, `start_date`, `end_date`, `lat`, `long`, `rate`, `rate_count`, `card_number`, `card_name`, `card_exp`, `card_cvv`, `balance`, `point`, `role`, `type`, `status`, `is_online`, `last_login`, `last_logout`, `created_at`, `updated_at`, `application_id`) VALUES
(8, '', 'admin', 'admin', '', '', '', '09RAwbjBI6GC7GHcgDHbHdhpDCa2e5HK', '$2y$13$JlWPgFy2wf0Vn4WGn0VQ2.HqGStgHjnQ./D/orA4lb4dmS7rG4ZIW', 'wSe3C0W6hWGSOOCNwgqxGtOrIjEyxMYv_1500398158', NULL, '', '', '', 'admin@gmail.com', '', '', '', NULL, NULL, '', NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30, '', 1, 1, '2018-10-02 11:03:10', '2018-05-05 11:53:50', 2018, 2018, ''),
(9, '', 'admin_stechqms', 'admin_stechqms', '', '', '', 'ACfChRPup7ZsYuEkjn54mDtBbzKMCeLT', '$2y$13$zmW0EVLuoeWneYnAD21k/.p9q/pWuda8DHRn07zlt5mhtBtjMXm3S', 'Hjbl631_IpsHSrJ0Qu7-Twsc-1Kk3XKL_1509937607', NULL, '', '', '', 'admin_stechqms@gmail.com', '', '', '', NULL, NULL, '', NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30, '', 10, 1, '2018-04-27 11:09:17', '2018-03-30 09:26:14', 2018, 2018, 'stechqms'),
(10, '', 'admin_stech', 'admin_stech', '', '', '', 'ZedIjM1IJxs17DswkGiyLx5Vd4X0OVaE', '$2y$13$p8wbuSVFU.7MxX0h3pdqcum.Rcb5UYPMsFelLNWYBlTFgKk.lNoSa', 'sxHMZx6AzOms8nUbqt6igVpfhvzz4C2j_1513241560', NULL, '', '', '', 'admin_stech@gmail.com', '', '', '', NULL, NULL, '', NULL, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30, '', 10, NULL, NULL, NULL, 2017, 2017, 'stech');

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `smartscreen_layouts_frame`
--
ALTER TABLE `smartscreen_layouts_frame`
  ADD CONSTRAINT `relation_layout_has_many_frame` FOREIGN KEY (`layout_id`) REFERENCES `smartscreen_layouts` (`id`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
