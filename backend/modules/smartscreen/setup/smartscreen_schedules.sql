SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `smartscreen_schedules`;
CREATE TABLE `smartscreen_schedules` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `device_id` varchar(100)  NULL  ,
    `layout_id` varchar(100)  NULL  ,
    `content_id` varchar(100)  NULL  ,
    `frame_id` varchar(100)  NULL  ,
    `start_time` varchar(100)  NULL  ,
    `date` varchar(20)  NULL  ,
    `type` varchar(10)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


