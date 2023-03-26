SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `qms_services`;
CREATE TABLE `qms_services` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `name` varchar(150)  NOT NULL  ,
    `description` text  NOT NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


