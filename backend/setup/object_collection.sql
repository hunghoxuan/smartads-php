SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `object_collection`;
CREATE TABLE `object_collection` (
    `id` int(11) unsigned  NOT NULL AUTO_INCREMENT ,
    `name` varchar(255)  NOT NULL  ,
    `description` varchar(1000)  NULL  ,
    `object_type` varchar(100)  NOT NULL  ,
    `is_active` tinyint(1)  NOT NULL  ,
    `sort_order` int(11)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


