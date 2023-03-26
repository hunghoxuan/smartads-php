SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `object_activity`;
CREATE TABLE `object_activity` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `object_id` varchar(100)  NOT NULL  ,
    `object_type` varchar(100)  NOT NULL  ,
    `type` varchar(100)  NOT NULL   COMMENT 'data:like,share,favourite,rate',
    `user_id` int(11)  NOT NULL   COMMENT 'lookup:@app_user',
    `user_type` varchar(100)  NULL   COMMENT 'data:app_user,user',
    `created_date` datetime  NULL  ,
    `modified_date` datetime  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


