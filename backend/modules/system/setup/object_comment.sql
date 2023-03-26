SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `object_comment`;
CREATE TABLE `object_comment` (
    `id` bigint(20)  NOT NULL AUTO_INCREMENT ,
    `object_id` varchar(255)  NOT NULL  ,
    `object_type` varchar(100)  NOT NULL  ,
    `parent_id` bigint(20)  NULL  ,
    `title` varchar(255)  NULL  ,
    `content` varchar(2000)  NULL  ,
    `user_id` int(11)  NOT NULL  ,
    `user_type` varchar(100)  NULL   COMMENT 'data:app_user,user',
    `is_active` tinyint(1)  NULL  ,
    `created_date` datetime  NULL  ,
    `modified_date` datetime  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

