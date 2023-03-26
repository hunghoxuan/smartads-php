SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `app_log`;
CREATE TABLE `app_log` (
    `id` bigint(20)  NOT NULL AUTO_INCREMENT ,
    `user_id` varchar(100)  NOT NULL   COMMENT 'lookup:@app_user',
    `action` varchar(100)  NULL   COMMENT 'data:register,login,purchase,feedback',
    `note` text  NULL  ,
    `tracking_time` varchar(255)  NULL  ,
    `status` varchar(100)  NULL   COMMENT 'data:success,fail,block',
    `created_date` datetime  NULL  ,
    `modified_date` datetime  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


