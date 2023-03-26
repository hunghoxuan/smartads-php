SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `tools_copy`;
CREATE TABLE `tools_copy` (
    `id` int(10) unsigned  NOT NULL AUTO_INCREMENT ,
    `name` varchar(100)  NULL  ,
    `folders` text  NULL  ,
    `files` text  NULL  ,
    `description` varchar(255)  NULL  ,
    `created_date` datetime  NULL  ,
    `modified_date` datetime  NULL  ,
    `created_user` int(11)  NULL  ,
    `application_id` varchar(255)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


