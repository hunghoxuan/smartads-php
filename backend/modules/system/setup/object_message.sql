SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `object_message`;
CREATE TABLE `object_message` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `object_id` varchar(100)  NOT NULL   COMMENT 'lookup:@app_user',
    `object_type` varchar(100)  NULL  ,
    `title` varchar(255)  NULL  ,
    `message` varchar(4000)  NOT NULL  ,
    `method` varchar(100)  NULL   COMMENT 'data:push,email,sms',
    `send_date` datetime  NULL  ,
    `sender_id` int(11)  NULL  ,
    `sender_type` varchar(100)  NULL  ,
    `type` varchar(100)  NULL   COMMENT 'data:notify,warning,birthday,promotion,remind',
    `status` varchar(100)  NULL   COMMENT 'data:pending,sent',
    `is_active` tinyint(1)  NULL  ,
    `created_date` datetime  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


