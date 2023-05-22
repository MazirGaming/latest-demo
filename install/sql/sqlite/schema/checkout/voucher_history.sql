DROP TABLE IF EXISTS `voucher_history`;

CREATE TABLE `voucher_history` (
`voucher_history_id` INT  NOT NULL ,
`voucher_id` INT  NOT NULL,
`order_id` INT  NOT NULL,
`quantity` decimal(15,4) NOT NULL,
`date_added` datetime NOT NULL,
PRIMARY KEY (`voucher_history_id`)
);





