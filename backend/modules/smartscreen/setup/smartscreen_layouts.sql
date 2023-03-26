SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `smartscreen_layouts`;
CREATE TABLE `smartscreen_layouts` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `name` varchar(255)  NOT NULL  ,
    `description` text  NULL  ,
    `sort_order` int(11)  NULL  ,
    `is_active` tinyint(4)  NULL  ,
    `is_default` tinyint(4)  NULL  ,
    `demo_html` text  NULL  ,
    `created_date` int(11)  NOT NULL  ,
    `modified_date` int(11)  NULL  ,
    `appilication_id` varchar(100) DEFAULT stechqms NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


