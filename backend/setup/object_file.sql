SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `object_file`;
CREATE TABLE `object_file` (
    `id` bigint(20)  NOT NULL AUTO_INCREMENT ,
    `object_id` int(11)  NOT NULL  ,
    `object_type` varchar(100)  NOT NULL  ,
    `file` varchar(555)  NULL  ,
    `title` varchar(255)  NOT NULL  ,
    `description` varchar(2000)  NULL  ,
    `file_type` varchar(100)  NULL  ,
    `file_size` varchar(255)  NULL  ,
    `file_duration` varchar(255)  NULL  ,
    `is_active` tinyint(1)  NULL  ,
    `sort_order` tinyint(5)  NULL  ,
    `created_date` datetime  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


