SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `object_calendar`;
CREATE TABLE `object_calendar` (
    `id` int(10) unsigned  NOT NULL AUTO_INCREMENT ,
    `object_id` int(11)  NULL  ,
    `object_type` varchar(100)  NULL  ,
    `color` varchar(100)  NULL  ,
    `title` varchar(255)  NULL  ,
    `start_date` varchar(48)  NULL  ,
    `end_date` varchar(48)  NULL  ,
    `all_day` varchar(100)  NULL  ,
    `status` varchar(100)  NULL  ,
    `link_url` varchar(255)  NULL  ,
    `type` varchar(100)  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `created_date` datetime  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


