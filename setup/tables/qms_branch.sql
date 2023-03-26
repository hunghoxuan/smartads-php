SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `qms_branch`;
CREATE TABLE `qms_branch` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `branch_group_id` int(11)  NOT NULL  ,
    `code` varchar(255)  NULL  ,
    `name` varchar(150)  NOT NULL  ,
    `address` varchar(200)  NOT NULL  ,
    `service_number` int(11)  NOT NULL  ,
    `station_number` int(11)  NOT NULL  ,
    `service_number_qms` int(11)  NOT NULL  ,
    `station_number_qms` int(11)  NOT NULL  ,
    `qman_name` varchar(150)  NOT NULL  ,
    `qman_id` int(11)  NOT NULL  ,
    `qman_phone` varchar(25)  NOT NULL  ,
    `qman_mobile` varchar(25)  NOT NULL  ,
    `telephone` varchar(25)  NOT NULL  ,
    `is_active` tinyint(1)  NULL  ,
    `sort_order` int(10)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


