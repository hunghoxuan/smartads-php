SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `settings_api`;
CREATE TABLE `settings_api` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `code` varchar(255)  NOT NULL  ,
    `name` varchar(255)  NOT NULL  ,
    `type` varchar(100)  NULL  ,
    `data` text  NULL  ,
    `data_html` text  NULL  ,
    `data_link` varchar(2000)  NULL  ,
    `data_array` text  NULL  ,
    `data_array_columns` text  NULL  ,
    `permissions` text  NULL  ,
    `is_active` tinyint(4)  NULL  ,
    `modified_date` datetime  NULL  ,
    `modified_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


