SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `survey`;
CREATE TABLE `survey` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `name` varchar(255)  NOT NULL  ,
    `description` varchar(2000)  NULL  ,
    `date_start` datetime  NULL  ,
    `date_end` datetime  NULL  ,
    `is_active` tinyint(1)  NULL  ,
    `type` varchar(100)  NULL  ,
    `status` varchar(100)  NULL  ,
    `created_date` datetime  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


