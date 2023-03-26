SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `app_membership`;
CREATE TABLE `app_membership` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `user_id` int(11)  NOT NULL  ,
    `service` varchar(100)  NULL   COMMENT 'data:business,library,ecommerce,content',
    `type` varchar(255)  NOT NULL   COMMENT 'data:vip,premium,pro',
    `expiry` int(11)  NOT NULL  ,
    `is_active` tinyint(1)  NOT NULL  ,
    `created_date` datetime  NULL  ,
    `modified_date` datetime  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


