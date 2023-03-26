SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `object_category`;
CREATE TABLE `object_category` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `parent_id` int(11)  NULL  ,
    `thumbnail` varchar(300)  NULL  ,
    `image` varchar(255)  NULL  ,
    `name` varchar(255)  NOT NULL  ,
    `description` text  NULL  ,
    `content` text  NULL  ,
    `translations` text  NULL  ,
    `properties` text  NULL  ,
    `sort_order` int(5)  NULL  ,
    `is_active` tinyint(1)  NULL  ,
    `is_top` tinyint(1)  NULL  ,
    `is_hot` tinyint(1)  NULL  ,
    `object_type` varchar(50)  NULL  ,
    `created_date` datetime  NULL  ,
    `modified_date` datetime  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


