SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `survey_result`;
CREATE TABLE `survey_result` (
    `id` int(11)  NOT NULL AUTO_INCREMENT ,
    `survey_id` int(11)  NULL   COMMENT '@survey',
    `question_id` int(11)  NULL   COMMENT '@survey_question',
    `customer_id` int(11)  NULL  ,
    `customer_info` int(255)  NULL  ,
    `transaction_id` varchar(255)  NULL  ,
    `comment` varchar(2000)  NULL  ,
    `answer` varchar(255)  NOT NULL  ,
    `branch_id` varchar(255)  NULL  ,
    `employee_id` varchar(255)  NULL  ,
    `created_date` timestamp  NULL  ,
    `application_id` varchar(255)  NULL  ,
    `ime` varchar(255)  NULL  ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


