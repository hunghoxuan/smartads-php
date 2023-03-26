SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `app_token`;
CREATE TABLE `app_token` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `user_id` int(11)  NOT NULL  ,
    `token` varchar(100)  NULL  ,
    `time` varchar(100)  NULL  ,
    `is_expired` tinyint(1)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


