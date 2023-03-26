SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `object_request`;
CREATE TABLE `object_request` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `object_id` int(11)  NOT NULL  ,
    `object_type` varchar(100)  NULL  ,
    `name` varchar(255)  NULL  ,
    `email` varchar(255)  NULL  ,
    `type` varchar(100)  NULL   COMMENT 'data:vip,moderator,unlock',
    `is_active` tinyint(1)  NOT NULL  ,
    `user_id` int(11)  NOT NULL   COMMENT 'lookup:@app_user',
    `user_type` varchar(100)  NULL   COMMENT 'data:app_user,user',
    `user_role` varchar(100)  NULL  ,
    `created_date` datetime  NULL  ,
    `modified_date` datetime  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


