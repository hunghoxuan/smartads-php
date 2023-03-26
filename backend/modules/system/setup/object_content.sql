SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `object_content`;
CREATE TABLE `object_content` (
    `id` bigint(20)  NOT NULL AUTO_INCREMENT ,
    `object_id` varchar(100)  NOT NULL  ,
    `object_type` varchar(100)  NULL  ,
    `image` varchar(300)  NULL  ,
    `name` varchar(255)  NULL  ,
    `description` varchar(2000)  NULL  ,
    `content` text  NULL  ,
    `sort_order` tinyint(11)  NULL  ,
    `is_active` tinyint(1)  NULL  ,
    `created_date` datetime  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


