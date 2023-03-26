SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `app_device`;
CREATE TABLE `app_device` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `user_id` int(11)  NULL   COMMENT 'lookup:@app_user',
    `imei` varchar(255)  NOT NULL  ,
    `token` varchar(255)  NOT NULL  ,
    `type` varchar(100)  NOT NULL   COMMENT 'data:android,ios',
    `is_active` tinyint(1)  NOT NULL  ,
    `created_date` datetime  NULL  ,
    `modified_date` datetime  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


