SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `qms_branch_station`;
CREATE TABLE `qms_branch_station` (
    `branch_id` int(11)  NOT NULL  ,
    `station_id` int(11)  NOT NULL  ,
    `employee_id` int(11)  NOT NULL  ,
    `scu_addr` varchar(30)  NOT NULL  ,
    `scu_status` varchar(30)  NOT NULL  ,
    `sdu_addr` varchar(30)  NOT NULL  ,
    `sdu_status` varchar(30)  NOT NULL  ,
    `vcu_addr` varchar(30)  NOT NULL  ,
    `vcu_status` varchar(30)  NOT NULL  ,
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


