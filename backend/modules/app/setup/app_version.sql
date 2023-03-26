SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `app_version`;
CREATE TABLE `app_version` (
    `id` bigint(20)  NOT NULL AUTO_INCREMENT ,
    `name` varchar(255)  NOT NULL  ,
    `description` varchar(2000)  NULL  ,
    `package_version` int(255)  NULL  ,
    `package_name` varchar(255)  NULL  ,
    `platform` varchar(255)  NULL  ,
    `platform_info` varchar(1000)  NULL  ,
    `file` varchar(300)  NOT NULL  ,
    `count_views` int(11)  NULL  ,
    `count_downloads` int(11)  NULL  ,
    `is_active` tinyint(4)  NOT NULL  ,
    `is_default` int(11)  NOT NULL  ,
    `history` text  NULL  ,
    `created_date` datetime  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `modified_date` datetime  NULL  ,
    `modified_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


