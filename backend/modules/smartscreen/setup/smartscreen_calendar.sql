SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `smartscreen_calendar`;
CREATE TABLE `smartscreen_calendar` (
    `id` bigint(20)  NOT NULL AUTO_INCREMENT ,
    `code` varchar(100)  NULL  ,
    `name` varchar(255)  NOT NULL  ,
    `description` varchar(2000)  NULL  ,
    `date` date  NULL  ,
    `time` varchar(255)  NULL  ,
    `device_id` varchar(500)  NULL  ,
    `location` varchar(255)  NULL  ,
    `type` varchar(100)  NULL  ,
    `owner_name` varchar(255)  NULL  ,
    `created_date` date  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `modified_date` date  NULL  ,
    `modified_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


