SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `app_file`;
CREATE TABLE `app_file` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `file_name` varchar(500)  NOT NULL  ,
    `file_size` int(11)  NULL  ,
    `user_id` varchar(100)  NULL  ,
    `ime` varchar(500)  NULL  ,
    `status` varchar(100)  NULL  ,
    `download_time` datetime  NULL  ,
    `created_date` datetime  NULL  ,
    `application_id` varchar(100)  NOT NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


