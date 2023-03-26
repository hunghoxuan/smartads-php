SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `qms_branch_devices`;
CREATE TABLE `qms_branch_devices` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `branch_id` int(11)  NOT NULL  ,
    `dev_address` varchar(30)  NOT NULL  ,
    `dev_type` varchar(30)  NOT NULL  ,
    `dev_status` tinyint(1) DEFAULT -1 NOT NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


