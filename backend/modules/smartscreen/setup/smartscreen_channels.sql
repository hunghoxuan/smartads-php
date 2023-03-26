SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `smartscreen_channels`;
CREATE TABLE `smartscreen_channels` (
    `id` bigint(20)  NOT NULL AUTO_INCREMENT ,
    `name` varchar(255)  NOT NULL  ,
    `description` varchar(2000)  NULL  ,
    `image` varchar(300)  NULL  ,
    `content` text  NULL  ,
    `is_active` tinyint(4)  NULL  ,
    `is_default` tinyint(4)  NULL  ,
    `devices` text  NULL  ,
    `created_date` date  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


