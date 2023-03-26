SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `object_link`;
CREATE TABLE `object_link` (
    `id` bigint(20)  NOT NULL AUTO_INCREMENT ,
    `object_id` int(12)  NOT NULL  ,
    `object_type` varchar(100)  NOT NULL  ,
    `name` varchar(255)  NOT NULL  ,
    `link_url` varchar(1000)  NULL  ,
    `type` varchar(100)  NULL   COMMENT 'data:tag,news,paper',
    `is_active` tinyint(1)  NULL  ,
    `created_date` datetime  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    `image` varchar(300)  NULL  ,
    `description` varchar(2000)  NULL  ,
    `label` varchar(255)  NULL  ,
    `target` varchar(100)  NULL  ,
    `sort_order` int(11)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


