SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `smartscreen_frame`;
CREATE TABLE `smartscreen_frame` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `name` varchar(255)  NOT NULL  ,
    `backgroundColor` varchar(10)  NULL  ,
    `layout_id` int(11)  NULL  ,
    `percentWidth` int(11)  NOT NULL  ,
    `percentHeight` int(11)  NOT NULL  ,
    `marginTop` int(11)  NULL  ,
    `marginLeft` int(11)  NULL  ,
    `contentLayout` varchar(255)  NULL  ,
    `created_date` int(11)  NOT NULL  ,
    `modified_date` int(11)  NULL  ,
    `application_id` varchar(255) DEFAULT stechqms NULL  ,
    `file` varchar(255)  NULL  ,
    `content` text  NULL  ,
    `content_id` int(11)  NULL  ,
    `font_size` varchar(255)  NULL  ,
    `font_color` varchar(255)  NULL  ,
    `alignment` varchar(255)  NULL  ,
    `sort_order` int(11)  NULL  ,
    `is_active` int(11) DEFAULT 1 NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


