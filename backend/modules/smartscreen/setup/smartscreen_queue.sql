SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `smartscreen_queue`;
CREATE TABLE `smartscreen_queue` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `code` varchar(255)  NULL  ,
    `name` varchar(255)  NOT NULL  ,
    `ticket` varchar(255)  NULL  ,
    `counter` varchar(255)  NULL  ,
    `service` varchar(255)  NULL  ,
    `service_id` varchar(100)  NULL   COMMENT 'lookup:@qms_services',
    `status` varchar(100)  NULL  ,
    `note` varchar(2000)  NULL  ,
    `device_id` varchar(2000)  NULL  ,
    `is_active` tinyint(1)  NULL  ,
    `sort_order` int(11)  NULL  ,
    `created_date` date  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    `description` varchar(255)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


