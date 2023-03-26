SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `qms_transactions`;
CREATE TABLE `qms_transactions` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `ticket_number` varchar(20)  NOT NULL  ,
    `service_id` int(11)  NOT NULL  ,
    `station_id` int(11)  NOT NULL  ,
    `time_in` int(11)  NOT NULL  ,
    `time_served` int(11)  NOT NULL  ,
    `time_out` int(11)  NOT NULL  ,
    `vote_score` int(11)  NOT NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


