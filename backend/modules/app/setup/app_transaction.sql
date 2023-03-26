SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `app_transaction`;
CREATE TABLE `app_transaction` (
    `id` bigint(20)  NOT NULL AUTO_INCREMENT ,
    `transaction_id` varchar(255)  NOT NULL  ,
    `user_id` varchar(100)  NOT NULL  ,
    `receiver_user_id` varchar(100)  NOT NULL   COMMENT 'lookup:@app_user',
    `object_id` varchar(100)  NULL  ,
    `object_type` varchar(100)  NULL  ,
    `amount` decimal(20,2)  NOT NULL  ,
    `currency` varchar(100)  NULL  ,
    `payment_method` varchar(100)  NOT NULL   COMMENT 'data:POINT,CREDIT,CASH,BANK,PAYPAL,WU',
    `note` varchar(2000)  NULL  ,
    `time` varchar(20)  NOT NULL  ,
    `action` varchar(255)  NULL   COMMENT 'data:SYSTEM_ADJUST,CANCELLATION_ORDER_FEE,EXCHANGE_POINT,REDEEM_POINT,TRANSFER_POINT,TRIP_PAYMENT,PASSENGER_SHARE_BONUS,DRIVER_SHARE_BONUS',
    `type` varchar(100)  NULL   COMMENT 'data:PLUS,MINUS',
    `status` varchar(100)  NOT NULL   COMMENT 'data:PENDING=0,APPROVED=1,REJECTED=-1',
    `created_date` datetime  NULL  ,
    `created_user` varchar(100)  NULL  ,
    `modified_date` datetime  NULL  ,
    `modified_user` varchar(100)  NULL  ,
    `application_id` varchar(100)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


