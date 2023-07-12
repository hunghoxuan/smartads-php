-- -------------------------------------------------------------
-- TablePlus 5.1.0(468)
--
-- https://tableplus.com/
--
-- Database: stech_smartads_msb
-- Generation Time: 2023-07-12 17:00:17.1570
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `app_file`;
CREATE TABLE `app_file` (
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

DROP TABLE IF EXISTS `app_version`;
CREATE TABLE `app_version` (
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE `auth_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `auth_permission`;
CREATE TABLE `auth_permission` (
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
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `auth_role`;
CREATE TABLE `auth_role` (
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

DROP TABLE IF EXISTS `media_file`;
CREATE TABLE `media_file` (
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

DROP TABLE IF EXISTS `object_file`;
CREATE TABLE `object_file` (
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

DROP TABLE IF EXISTS `smartscreen_calendar`;
CREATE TABLE `smartscreen_calendar` (
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

DROP TABLE IF EXISTS `smartscreen_channels`;
CREATE TABLE `smartscreen_channels` (
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `smartscreen_content`;
CREATE TABLE `smartscreen_content` (
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
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `smartscreen_file`;
CREATE TABLE `smartscreen_file` (
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
) ENGINE=InnoDB AUTO_INCREMENT=504 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `smartscreen_frame`;
CREATE TABLE `smartscreen_frame` (
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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `smartscreen_layouts`;
CREATE TABLE `smartscreen_layouts` (
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
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `smartscreen_layouts_frame`;
CREATE TABLE `smartscreen_layouts_frame` (
  `layout_id` int(11) NOT NULL,
  `frame_id` int(11) NOT NULL,
  `sort_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`layout_id`,`frame_id`),
  CONSTRAINT `relation_layout_has_many_frame` FOREIGN KEY (`layout_id`) REFERENCES `smartscreen_layouts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `smartscreen_queue`;
CREATE TABLE `smartscreen_queue` (
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

DROP TABLE IF EXISTS `smartscreen_schedules`;
CREATE TABLE `smartscreen_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` varchar(1000) DEFAULT '',
  `layout_id` varchar(500) DEFAULT '',
  `content_id` varchar(1000) DEFAULT '',
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
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=996 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `smartscreen_scripts`;
CREATE TABLE `smartscreen_scripts` (
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

DROP TABLE IF EXISTS `smartscreen_station`;
CREATE TABLE `smartscreen_station` (
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
) ENGINE=InnoDB AUTO_INCREMENT=270 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `smartscreen_tasks`;
CREATE TABLE `smartscreen_tasks` (
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

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(155) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` tinyint(2) DEFAULT '0' COMMENT '1: male, 2: female; 3: not specified',
  `birthday` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identity_card` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passport` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `metadata` text COLLATE utf8_unicode_ci,
  `is_verified` tinyint(10) DEFAULT '0' COMMENT '1:email,2:phone,3:both',
  `username` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role` int(2) DEFAULT '10' COMMENT 'data:10:USER,20:MODERATOR,30:ADMIN',
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT 'data:DISABLED=0,ACTIVE=10',
  `referral_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `referral_sent` int(11) DEFAULT NULL,
  `referral_signup` int(11) DEFAULT NULL,
  `referral_earned` float(11,0) DEFAULT NULL,
  `referral_by` int(11) DEFAULT NULL,
  `discount` tinyint(2) DEFAULT NULL,
  `is_online` bit(1) DEFAULT b'0',
  `attributes` text COLLATE utf8_unicode_ci,
  `last_login` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `app_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;