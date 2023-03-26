SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `object_file`;
CREATE TABLE `object_file` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `object_id` int(11)  NOT NULL  ,
    `object_type` varchar(20)  NOT NULL   COMMENT 'DROPDOWN:gallery|fashion_model',
    `thumbnail` varchar(255)  NULL  ,
    `name` varchar(255)  NULL  ,
    `description` varchar(2000)  NULL  ,
    `type` varchar(20)  NOT NULL   COMMENT 'DROPDOWN:video|image|audio|misc',
    `file` varchar(255)  NOT NULL  ,
    `file_type` varchar(100)  NULL  ,
    `status` varchar(20)  NULL   COMMENT 'DROPDOWN:adult|normal',
    `is_active` tinyint(1)  NULL  ,
    `file_size` varchar(255)  NULL  ,
    `file_duration` varchar(255)  NULL  ,
    `sort_order` tinyint(5)  NULL  ,
    `created_date` varchar(20)  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `modified_date` varchar(20)  NULL  ,
    `modified_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


