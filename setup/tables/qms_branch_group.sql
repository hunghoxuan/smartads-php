SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `qms_branch_group`;
CREATE TABLE `qms_branch_group` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `name` varchar(150)  NOT NULL  ,
    `telephone` varchar(25)  NOT NULL  ,
    `sort_order` int(11)  NULL  ,
    `is_active` tinyint(4)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


