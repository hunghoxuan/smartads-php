SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `object_banner`;
CREATE TABLE `object_banner` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `object_id` int(11)  NULL  ,
    `object_type` varchar(100)  NULL  ,
    `image` varchar(300)  NULL  ,
    `title` varchar(255)  NOT NULL  ,
    `link_url` varchar(1000)  NULL  ,
    `platform` varchar(100)  NULL   COMMENT 'data:android,ios,web,all',
    `position` varchar(100)  NULL   COMMENT 'data:dashboard,top,bottom,left,right,center,middle',
    `type` varchar(100)  NULL   COMMENT 'data:banner,block,vertical,horizontal,full-screen',
    `sort_order` int(11)  NULL  ,
    `is_active` tinyint(1)  NOT NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


