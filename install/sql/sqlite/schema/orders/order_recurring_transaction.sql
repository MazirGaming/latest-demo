DROP TABLE IF EXISTS `order_recurring_transaction`;

CREATE TABLE `order_recurring_transaction` (
`order_recurring_transaction_id` INT  NOT NULL ,
`order_recurring_id` INT  NOT NULL,
`reference` TEXT NOT NULL,
`type` INT  NOT NULL,
`quantity` decimal(10,4) NOT NULL,
`date_added` datetime NOT NULL,
PRIMARY KEY (`order_recurring_transaction_id`)
);





