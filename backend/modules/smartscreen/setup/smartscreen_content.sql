SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `smartscreen_content`;
CREATE TABLE `smartscreen_content` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `title` varchar(255)  NOT NULL  ,
    `url` varchar(255)  NULL  ,
    `description` text  NULL  ,
    `type` varchar(255)  NOT NULL  ,
    `kind` varchar(100)  NULL  ,
    `duration` int(12)  NULL  ,
    `expire_date` varchar(20)  NULL  ,
    `sort_order` int(11)  NULL  ,
    `owner_id` varchar(100)  NULL  ,
    `is_active` tinyint(4) DEFAULT 1 NULL  ,
    `is_default` tinyint(4)  NULL  ,
    `created_date` int(11)  NOT NULL  ,
    `modified_date` int(11)  NULL  ,
    `application_id` varchar(100) DEFAULT stechqms NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


