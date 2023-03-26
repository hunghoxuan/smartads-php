SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `qms_branch_services`;
CREATE TABLE `qms_branch_services` (
    `branch_id` int(11)  NOT NULL  ,
    `service_id` int(11)  NOT NULL  ,
    PRIMARY KEY (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


