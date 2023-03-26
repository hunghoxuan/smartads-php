SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `survey_question`;
CREATE TABLE `survey_question` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `name` varchar(255)  NOT NULL  ,
    `image` varchar(300)  NULL  ,
    `content` text  NOT NULL  ,
    `type` varchar(100)  NULL  ,
    `allow_comment` tinyint(1)  NULL  ,
    `timeout` int(11)  NULL  ,
    `hint` text  NULL  ,
    `answers` varchar(2000)  NULL  ,
    `sort_order` tinyint(1)  NULL  ,
    `is_active` tinyint(1)  NULL  ,
    `created_date` datetime  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


