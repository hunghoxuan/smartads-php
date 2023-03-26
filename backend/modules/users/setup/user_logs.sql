SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `user_logs`;
CREATE TABLE `user_logs` (
    `id` bigint(20)  NOT NULL AUTO_INCREMENT ,
    `log_date` varchar(18)  NULL  ,
    `user_id` varchar(100)  NOT NULL   COMMENT 'lookup:@app_user',
    `action` varchar(255)  NULL  ,
    `object_type` varchar(255)  NULL  ,
    `object_id` int(11)  NULL  ,
    `link_url` varchar(255)  NULL  ,
    `ip_address` varchar(255)  NULL  ,
    `duration` varchar(255)  NULL  ,
    `note` text  NULL  ,
    `status` varchar(255)  NULL  ,
    `created_date` timestamp  NULL  ,
    `modified_date` timestamp  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


