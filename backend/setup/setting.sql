SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `metaKey` varchar(255)  NULL  ,
    `metaValue` text  NULL  ,
    `group` varchar(255)  NULL  ,
    `is_active` tinyint(1)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


