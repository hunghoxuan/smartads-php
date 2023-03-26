SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `smartscreen_station`;
CREATE TABLE `smartscreen_station` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `name` varchar(255)  NOT NULL  ,
    `description` varchar(2000)  NULL  ,
    `ime` varchar(100)  NOT NULL  ,
    `status` tinyint(4) DEFAULT 1 NULL  ,
    `last_activity` varchar(2000)  NULL  ,
    `last_update` int(11)  NULL  ,
    `ScreenName` varchar(255)  NULL  ,
    `MACAddress` varchar(255)  NULL  ,
    `LicenseKey` varchar(255)  NULL  ,
    `branch_id` varchar(100)  NULL   COMMENT 'lookup:@qms_branch',
    `channel_id` varchar(100)  NULL   COMMENT 'lookup:@smartscreen_channels',
    `dept_id` varchar(100)  NULL  ,
    `room_id` varchar(100)  NULL  ,
    `disk_storage` float  NULL  ,
    `created_date` date  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


